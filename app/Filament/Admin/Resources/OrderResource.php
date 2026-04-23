<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\OrderResource\Pages;
use App\Mail\OrderShippedMail;
use App\Models\Order;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static \UnitEnum|string|null $navigationGroup = 'Vendas';
    protected static ?string $navigationLabel = 'Pedidos';
    protected static ?string $modelLabel = 'Pedido';
    protected static ?string $pluralModelLabel = 'Pedidos';
    protected static ?int $navigationSort = 1;

    public static function getNavigationIcon(): \BackedEnum|string|null
    {
        return Heroicon::OutlinedShoppingCart;
    }

    public static function getNavigationBadge(): ?string
    {
        $count = Order::where('status', 'pending')->count();
        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([

            Section::make('Status do Pedido')
                ->description('Altere o status e o código de rastreio')
                ->icon('heroicon-m-clipboard-document-list')
                ->schema([
                    Grid::make(3)->schema([
                        Forms\Components\TextInput::make('order_number')
                            ->label('Número do Pedido')
                            ->disabled(),
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'pending'    => '⏳ Aguardando Pagamento',
                                'processing' => '🔄 Em Processamento',
                                'paid'       => '✅ Pago',
                                'shipped'    => '🚚 Enviado',
                                'delivered'  => '🏠 Entregue',
                                'cancelled'  => '❌ Cancelado',
                                'refunded'   => '↩️ Reembolsado',
                            ])
                            ->required()
                            ->native(false),
                        Forms\Components\TextInput::make('tracking_code')
                            ->label('Código de Rastreio')
                            ->placeholder('AA000000000BR'),
                    ]),
                ]),

            Section::make('Cliente')
                ->description('Dados do comprador')
                ->icon('heroicon-m-user')
                ->collapsible()
                ->schema([
                    Grid::make(3)->schema([
                        TextEntry::make('user.name')->label('Nome'),
                        TextEntry::make('user.email')->label('Email'),
                        TextEntry::make('user.phone')->label('Telefone')->placeholder('—'),
                    ]),
                ]),

            Section::make('Endereço de Entrega')
                ->description('Local de envio do pedido')
                ->icon('heroicon-m-map-pin')
                ->collapsible()
                ->schema([
                    Grid::make(4)->schema([
                        TextEntry::make('ship_cep')->label('CEP')
                            ->state(fn ($record) => $record?->shipping_address['cep'] ?? '—'),
                        TextEntry::make('ship_street')->label('Logradouro')
                            ->state(fn ($record) => trim(($record?->shipping_address['street'] ?? '—') . ', ' . ($record?->shipping_address['number'] ?? ''))),
                        TextEntry::make('ship_neighborhood')->label('Bairro')
                            ->state(fn ($record) => $record?->shipping_address['neighborhood'] ?? '—'),
                        TextEntry::make('ship_city')->label('Cidade / UF')
                            ->state(fn ($record) => ($record?->shipping_address['city'] ?? '—') . ' — ' . ($record?->shipping_address['state'] ?? '')),
                    ]),
                ]),

            Section::make('Itens do Pedido')
                ->description('Produtos incluídos neste pedido')
                ->icon('heroicon-m-shopping-bag')
                ->collapsible()
                ->schema([
                    Forms\Components\Repeater::make('items')
                        ->relationship()
                        ->label('')
                        ->schema([
                            Forms\Components\TextInput::make('product_name')
                                ->label('Produto')->disabled()->columnSpan(3),
                            Forms\Components\TextInput::make('size')->label('Tam.')->disabled(),
                            Forms\Components\TextInput::make('color')->label('Cor')->disabled(),
                            Forms\Components\TextInput::make('quantity')->label('Qtd')->disabled(),
                            Forms\Components\TextInput::make('unit_price')->label('Unit. R$')->disabled(),
                            Forms\Components\TextInput::make('total_price')->label('Total R$')->disabled(),
                        ])
                        ->columns(8)
                        ->addable(false)
                        ->deletable(false)
                        ->reorderable(false),
                ]),

            Section::make('Pagamento & Valores')
                ->description('Resumo financeiro')
                ->icon('heroicon-m-banknotes')
                ->collapsible()
                ->schema([
                    Grid::make(4)->schema([
                        Forms\Components\TextInput::make('subtotal')->label('Subtotal')->prefix('R$')->disabled(),
                        Forms\Components\TextInput::make('discount')->label('Desconto')->prefix('R$')->disabled(),
                        Forms\Components\TextInput::make('shipping')->label('Frete')->prefix('R$')->disabled(),
                        Forms\Components\TextInput::make('total')->label('Total')->prefix('R$')->disabled(),
                    ]),
                    Grid::make(3)->schema([
                        TextEntry::make('payment_method')->label('Método')
                            ->state(fn ($record) => match($record?->payment_method) {
                                'stripe'      => '💳 Cartão (Stripe)',
                                'mercadopago' => '🛒 MercadoPago',
                                'pix'         => '⚡ PIX',
                                default       => $record?->payment_method ?? '—',
                            }),
                        TextEntry::make('payment_status')->label('Status do Pagamento')
                            ->state(fn ($record) => match($record?->payment_status) {
                                'paid'    => '✅ Pago',
                                'pending' => '⏳ Aguardando',
                                'failed'  => '❌ Falhou',
                                default   => $record?->payment_status ?? '—',
                            }),
                        TextEntry::make('coupon_code')->label('Cupom Utilizado')->placeholder('Nenhum'),
                    ]),
                ]),

            Section::make('Observações Internas')
                ->icon('heroicon-m-pencil-square')
                ->collapsible()
                ->collapsed()
                ->schema([
                    Forms\Components\Textarea::make('notes')->label('')->rows(4),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->label('Pedido')
                    ->searchable()->sortable()->weight('bold')->copyable()
                    ->description(fn ($record) => $record->created_at->format('d/m/Y H:i')),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Cliente')
                    ->searchable()->sortable()
                    ->description(fn ($record) => $record->user?->email),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state) => match($state) {
                        'pending'    => 'warning',
                        'processing' => 'info',
                        'paid'       => 'success',
                        'shipped'    => 'primary',
                        'delivered'  => 'success',
                        'cancelled'  => 'danger',
                        default      => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match($state) {
                        'pending'    => '⏳ Aguardando',
                        'processing' => '🔄 Processando',
                        'paid'       => '✅ Pago',
                        'shipped'    => '🚚 Enviado',
                        'delivered'  => '🏠 Entregue',
                        'cancelled'  => '❌ Cancelado',
                        'refunded'   => '↩️ Reembolsado',
                        default      => $state,
                    }),
                Tables\Columns\TextColumn::make('total')
                    ->label('Total')
                    ->money('BRL')->sortable()->weight('bold'),
                Tables\Columns\TextColumn::make('payment_method')
                    ->label('Pagamento')
                    ->badge()->color('gray')
                    ->formatStateUsing(fn ($state) => match($state) {
                        'stripe'      => '💳 Cartão',
                        'mercadopago' => '🛒 MercadoPago',
                        'pix'         => '⚡ PIX',
                        default       => $state,
                    }),
                Tables\Columns\TextColumn::make('tracking_code')
                    ->label('Rastreio')
                    ->copyable()
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending'    => '⏳ Aguardando',
                        'processing' => '🔄 Processando',
                        'paid'       => '✅ Pago',
                        'shipped'    => '🚚 Enviado',
                        'delivered'  => '🏠 Entregue',
                        'cancelled'  => '❌ Cancelado',
                    ]),
                Tables\Filters\SelectFilter::make('payment_method')
                    ->label('Pagamento')
                    ->options([
                        'stripe'      => 'Cartão',
                        'mercadopago' => 'MercadoPago',
                        'pix'         => 'PIX',
                    ]),
            ])
            ->actions([
                Action::make('markPaid')
                    ->label('Pago')
                    ->icon('heroicon-m-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Confirmar pagamento manual')
                    ->modalDescription('Marcar este pedido como pago manualmente.')
                    ->visible(fn ($record) => \in_array($record->status, ['pending', 'processing']))
                    ->action(function ($record) {
                        $record->update(['status' => 'paid', 'payment_status' => 'paid', 'paid_at' => now()]);
                        Notification::make()->title('Pedido marcado como pago!')->success()->send();
                    }),

                Action::make('markShipped')
                    ->label('Enviar')
                    ->icon('heroicon-m-truck')
                    ->color('info')
                    ->visible(fn ($record) => $record->status === 'paid')
                    ->form([
                        Forms\Components\TextInput::make('tracking_code')
                            ->label('Código de Rastreio (Correios)')
                            ->placeholder('AA000000000BR')
                            ->required(),
                    ])
                    ->action(function ($record, array $data) {
                        $record->update([
                            'status'        => 'shipped',
                            'tracking_code' => $data['tracking_code'],
                            'shipped_at'    => now(),
                        ]);
                        try {
                            $email = $record->billing_address['email'] ?? $record->user?->email;
                            if ($email) {
                                Mail::to($email)->send(new OrderShippedMail($record->load('items')));
                            }
                        } catch (\Exception $e) {
                            Log::error('Erro ao enviar e-mail de envio: ' . $e->getMessage());
                        }
                        Notification::make()->title('Pedido enviado!')->success()->send();
                    }),

                Action::make('markDelivered')
                    ->label('Entregue')
                    ->icon('heroicon-m-home')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => $record->status === 'shipped')
                    ->action(function ($record) {
                        $record->update(['status' => 'delivered', 'delivered_at' => now()]);
                        Notification::make()->title('Pedido entregue!')->success()->send();
                    }),

                Action::make('cancel')
                    ->label('Cancelar')
                    ->icon('heroicon-m-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Cancelar pedido?')
                    ->modalDescription('Esta ação não pode ser desfeita.')
                    ->visible(fn ($record) => !\in_array($record->status, ['delivered', 'cancelled', 'refunded']))
                    ->action(function ($record) {
                        $record->update(['status' => 'cancelled']);
                        Notification::make()->title('Pedido cancelado.')->danger()->send();
                    }),

                EditAction::make()->label('')->tooltip('Editar detalhes'),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'edit'  => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
