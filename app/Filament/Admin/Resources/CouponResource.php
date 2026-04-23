<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\CouponResource\Pages;
use App\Models\Coupon;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;

class CouponResource extends Resource
{
    protected static ?string $model = Coupon::class;
    protected static \UnitEnum|string|null $navigationGroup = 'Marketing';
    protected static ?string $navigationLabel = 'Cupons';
    protected static ?string $modelLabel = 'Cupom';
    protected static ?string $pluralModelLabel = 'Cupons';
    protected static ?int $navigationSort = 1;

    public static function getNavigationIcon(): \BackedEnum|string|null
    {
        return Heroicon::OutlinedTicket;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([

            Section::make('Identificação do Cupom')
                ->icon('heroicon-m-ticket')
                ->description('Código e descrição que o cliente vai usar')
                ->schema([
                    Grid::make(2)->schema([
                        Forms\Components\TextInput::make('code')
                            ->label('Código do Cupom')
                            ->required()
                            ->maxLength(50)
                            ->placeholder('Ex: TOTTI10')
                            ->live()
                            ->afterStateUpdated(fn ($state, Set $set) => $set('code', strtoupper((string) $state)))
                            ->helperText('Convertido para maiúsculas automaticamente'),
                        Forms\Components\TextInput::make('description')
                            ->label('Descrição Interna')
                            ->maxLength(255)
                            ->placeholder('Ex: Cupom de boas-vindas 10%'),
                    ]),
                ]),

            Section::make('Tipo & Valor do Desconto')
                ->icon('heroicon-m-percent-badge')
                ->description('Configure o desconto que será aplicado')
                ->schema([
                    Grid::make(3)->schema([
                        Forms\Components\Select::make('type')
                            ->label('Tipo de Desconto')
                            ->options([
                                'percentage' => '% Porcentagem',
                                'fixed'      => 'R$ Valor Fixo',
                            ])
                            ->required()
                            ->default('percentage')
                            ->native(false),
                        Forms\Components\TextInput::make('value')
                            ->label('Valor')
                            ->required()
                            ->numeric()
                            ->step(0.01)
                            ->helperText('10 para 10% ou 20.00 para R$20'),
                        Forms\Components\TextInput::make('minimum_order')
                            ->label('Pedido Mínimo (R$)')
                            ->numeric()
                            ->prefix('R$')
                            ->step(0.01)
                            ->helperText('Deixe vazio para sem mínimo'),
                    ]),
                    Grid::make(2)->schema([
                        Forms\Components\TextInput::make('maximum_discount')
                            ->label('Desconto Máximo (R$)')
                            ->numeric()
                            ->prefix('R$')
                            ->helperText('Teto do desconto em porcentagem'),
                        Forms\Components\Toggle::make('free_shipping')
                            ->label('Incluir Frete Grátis')
                            ->helperText('Além do desconto, zera o frete'),
                    ]),
                ]),

            Section::make('Limites de Uso')
                ->icon('heroicon-m-users')
                ->description('Quantas vezes o cupom pode ser usado')
                ->schema([
                    Grid::make(2)->schema([
                        Forms\Components\TextInput::make('usage_limit')
                            ->label('Limite Total de Usos')
                            ->numeric()
                            ->helperText('Deixe vazio para ilimitado'),
                        Forms\Components\TextInput::make('usage_limit_per_user')
                            ->label('Usos por Usuário')
                            ->numeric()
                            ->default(1),
                    ]),
                ]),

            Section::make('Validade')
                ->icon('heroicon-m-calendar')
                ->description('Período em que o cupom estará disponível')
                ->schema([
                    Grid::make(2)->schema([
                        Forms\Components\DateTimePicker::make('starts_at')
                            ->label('Válido a partir de')
                            ->displayFormat('d/m/Y H:i')
                            ->native(false),
                        Forms\Components\DateTimePicker::make('expires_at')
                            ->label('Expira em')
                            ->displayFormat('d/m/Y H:i')
                            ->native(false),
                    ]),
                    Forms\Components\Toggle::make('is_active')
                        ->label('Cupom Ativo')
                        ->default(true)
                        ->helperText('Desative para pausar o cupom sem excluir'),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Código')
                    ->searchable()->badge()->color('primary')
                    ->weight('bold')->copyable()->copyMessage('Código copiado!'),
                Tables\Columns\TextColumn::make('description')
                    ->label('Descrição')->limit(30),
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipo')->badge()
                    ->color(fn ($state) => $state === 'percentage' ? 'info' : 'success')
                    ->formatStateUsing(fn ($state) => $state === 'percentage' ? '% Porcentagem' : 'R$ Fixo'),
                Tables\Columns\TextColumn::make('value')
                    ->label('Desconto')
                    ->formatStateUsing(fn ($state, $record) => $record->type === 'percentage'
                        ? "{$state}%"
                        : 'R$ ' . number_format((float) $state, 2, ',', '.')),
                Tables\Columns\TextColumn::make('minimum_order')
                    ->label('Mínimo')
                    ->money('BRL')
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('times_used')
                    ->label('Usos')
                    ->badge()
                    ->color(fn ($state, $record) => $record->usage_limit && $state >= $record->usage_limit ? 'danger' : 'gray')
                    ->formatStateUsing(fn ($state, $record) => $record->usage_limit
                        ? "{$state} / {$record->usage_limit}"
                        : (string) $state),
                Tables\Columns\IconColumn::make('free_shipping')->label('Frete Grátis')->boolean(),
                Tables\Columns\IconColumn::make('is_active')->label('Ativo')->boolean(),
                Tables\Columns\TextColumn::make('expires_at')
                    ->label('Expira em')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->placeholder('Sem expiração'),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')->label('Ativo'),
                Tables\Filters\TernaryFilter::make('free_shipping')->label('Frete Grátis'),
                Tables\Filters\SelectFilter::make('type')
                    ->label('Tipo')
                    ->options(['percentage' => 'Porcentagem', 'fixed' => 'Valor Fixo']),
            ])
            ->actions([EditAction::make(), DeleteAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListCoupons::route('/'),
            'create' => Pages\CreateCoupon::route('/create'),
            'edit'   => Pages\EditCoupon::route('/{record}/edit'),
        ];
    }
}
