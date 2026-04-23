<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ProductResource\Pages;
use App\Models\Category;
use App\Models\Product;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;
    protected static \UnitEnum|string|null $navigationGroup = 'Catálogo';
    protected static ?string $navigationLabel = 'Produtos';
    protected static ?string $modelLabel = 'Produto';
    protected static ?string $pluralModelLabel = 'Produtos';
    protected static ?int $navigationSort = 1;

    public static function getNavigationIcon(): \BackedEnum|string|null
    {
        return Heroicon::OutlinedShoppingBag;
    }

    public static function getNavigationBadge(): ?string
    {
        $lowStock = Product::where('is_active', true)->where('stock', '<=', 5)->count();
        return $lowStock > 0 ? (string) $lowStock : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([

            Grid::make(2)->columnSpanFull()->schema([

                // Coluna esquerda — todos os campos de texto
                Grid::make(1)->columnSpan(1)->schema([

                    Section::make('Identificação')
                        ->icon('heroicon-m-information-circle')
                        ->columns(2)
                        ->schema([
                            Forms\Components\TextInput::make('name')
                                ->label('Nome do Produto')
                                ->required()
                                ->maxLength(255)
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn (string $state, Set $set) => $set('slug', Str::slug($state)))
                                ->columnSpanFull(),
                            Forms\Components\Hidden::make('slug'),
                            Forms\Components\Select::make('gender')
                                ->label('Gênero')
                                ->options(['masculine' => 'Masculino', 'feminine' => 'Feminino', 'unisex' => 'Unissex'])
                                ->default('unisex')
                                ->native(false),
                            Forms\Components\Select::make('category_id')
                                ->label('Categoria')
                                ->options(Category::whereNull('parent_id')->pluck('name', 'id'))
                                ->searchable()
                                ->preload()
                                ->required()
                                ->live()
                                ->afterStateUpdated(fn (Set $set) => $set('subcategory_id', null)),
                            Forms\Components\Select::make('subcategory_id')
                                ->label('Subcategoria')
                                ->options(fn ($get) => Category::where('parent_id', $get('category_id'))->pluck('name', 'id'))
                                ->searchable()
                                ->preload()
                                ->placeholder('Selecione uma categoria primeiro')
                                ->disabled(fn ($get) => !$get('category_id')),
                            Forms\Components\TextInput::make('team')
                                ->label('Time / Seleção')
                                ->placeholder('Ex: Flamengo, Brasil, AS Roma')
                                ->maxLength(100),
                            Forms\Components\TextInput::make('sku')
                                ->label('SKU')
                                ->maxLength(100)
                                ->placeholder('TL-XXXXXXXX')
                                ->columnSpanFull(),
                            Forms\Components\Textarea::make('short_description')
                                ->label('Descrição Curta')
                                ->maxLength(500)
                                ->rows(2)
                                ->helperText('Aparece na listagem. Máximo 500 caracteres.')
                                ->columnSpanFull(),
                            Forms\Components\RichEditor::make('description')
                                ->label('Descrição Completa')
                                ->columnSpanFull(),
                        ]),

                    Section::make('Preço & Estoque')
                        ->icon('heroicon-m-banknotes')
                        ->columns(3)
                        ->schema([
                            Forms\Components\TextInput::make('price')
                                ->label('Preço (R$)')
                                ->required()
                                ->numeric()
                                ->prefix('R$')
                                ->step(0.01),
                            Forms\Components\TextInput::make('sale_price')
                                ->label('Preço Promocional')
                                ->numeric()
                                ->prefix('R$')
                                ->step(0.01)
                                ->helperText('Deixe vazio para sem promoção'),
                            Forms\Components\TextInput::make('stock')
                                ->label('Estoque')
                                ->numeric()
                                ->default(0)
                                ->suffix('un.'),
                            Forms\Components\Toggle::make('is_active')
                                ->label('Ativo')
                                ->default(true),
                            Forms\Components\Toggle::make('is_featured')
                                ->label('Destaque'),
                            Forms\Components\Toggle::make('is_new')
                                ->label('Novo')
                                ->default(true),
                        ]),

                    Section::make('Tamanhos')
                        ->icon('heroicon-m-rectangle-stack')
                        ->schema([
                            Forms\Components\CheckboxList::make('sizes')
                                ->label('')
                                ->options(['PP' => 'PP', 'P' => 'P', 'M' => 'M', 'G' => 'G', 'GG' => 'GG', 'XG' => 'XG', 'XXG' => 'XXG'])
                                ->columns(7),
                        ]),

                    Section::make('Cores & Composição')
                        ->icon('heroicon-m-swatch')
                        ->columns(2)
                        ->schema([
                            Forms\Components\Repeater::make('colors')
                                ->label('Cores disponíveis')
                                ->schema([
                                    Forms\Components\TextInput::make('name')
                                        ->label('Nome da Cor')
                                        ->required()
                                        ->placeholder('Ex: Vermelho e Preto, Branco, Azul e Dourado'),
                                ])
                                ->columns(1)
                                ->defaultItems(1)
                                ->addActionLabel('+ Adicionar cor'),
                            Forms\Components\KeyValue::make('composition')
                                ->label('Composição do tecido')
                                ->keyLabel('Material')
                                ->valueLabel('Percentual')
                                ->addActionLabel('+ Adicionar material'),
                        ]),

                ]),

                // Coluna direita — apenas imagens
                Section::make('Imagens')
                    ->icon('heroicon-m-photo')
                    ->columnSpan(1)
                    ->schema([
                        Forms\Components\FileUpload::make('main_image')
                            ->label('Imagem Principal')
                            ->image()
                            ->imageEditor()
                            ->directory('products')
                            ->helperText('Aparece na listagem e na página do produto'),
                        Forms\Components\FileUpload::make('images')
                            ->label('Galeria (adicionais)')
                            ->image()
                            ->multiple()
                            ->reorderable()
                            ->directory('products/gallery'),
                    ]),

            ]),

        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('main_image')
                    ->label('')
                    ->disk('public')
                    ->square()
                    ->imageSize(56),
                Tables\Columns\TextColumn::make('name')
                    ->label('Produto')
                    ->searchable()->sortable()->weight('bold')
                    ->description(fn ($record) => $record->team ? "🏆 {$record->team}" : null),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Categoria')
                    ->badge()->color('primary')->sortable(),
                Tables\Columns\TextColumn::make('subcategory.name')
                    ->label('Subcategoria')
                    ->badge()->color('gray')
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('price')
                    ->label('Preço')
                    ->money('BRL')->sortable()
                    ->description(fn ($record) => $record->sale_price ? 'Promo: R$ ' . number_format($record->sale_price, 2, ',', '.') : null),
                Tables\Columns\TextColumn::make('stock')
                    ->label('Estoque')
                    ->badge()
                    ->color(fn ($state) => $state > 10 ? 'success' : ($state > 0 ? 'warning' : 'danger'))
                    ->formatStateUsing(fn ($state) => $state . ' un.')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Ativo')
                    ->boolean()->sortable(),
                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Destaque')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->relationship('category', 'name')
                    ->label('Categoria'),
                Tables\Filters\TernaryFilter::make('is_active')->label('Ativo'),
                Tables\Filters\TernaryFilter::make('is_featured')->label('Destaque'),
                Tables\Filters\TernaryFilter::make('is_new')->label('Novo'),
                Tables\Filters\Filter::make('low_stock')
                    ->label('Estoque Baixo (≤ 5)')
                    ->query(fn ($query) => $query->where('stock', '<=', 5)->where('stock', '>', 0)),
                Tables\Filters\Filter::make('out_of_stock')
                    ->label('Sem Estoque')
                    ->query(fn ($query) => $query->where('stock', 0)),
                Tables\Filters\Filter::make('on_sale')
                    ->label('Em Promoção')
                    ->query(fn ($query) => $query->whereNotNull('sale_price')),
            ])
            ->actions([
                Action::make('toggleActive')
                    ->label(fn ($record) => $record->is_active ? 'Desativar' : 'Ativar')
                    ->icon(fn ($record) => $record->is_active ? 'heroicon-m-eye-slash' : 'heroicon-m-eye')
                    ->color(fn ($record) => $record->is_active ? 'gray' : 'success')
                    ->action(function ($record) {
                        $record->update(['is_active' => !$record->is_active]);
                        Notification::make()
                            ->title($record->is_active ? 'Produto ativado' : 'Produto desativado')
                            ->success()
                            ->send();
                    }),
                Action::make('toggleFeatured')
                    ->label(fn ($record) => $record->is_featured ? 'Remover destaque' : 'Destacar')
                    ->icon(fn ($record) => $record->is_featured ? 'heroicon-m-star' : 'heroicon-o-star')
                    ->color(fn ($record) => $record->is_featured ? 'warning' : 'gray')
                    ->action(function ($record) {
                        $record->update(['is_featured' => !$record->is_featured]);
                        Notification::make()->title('Destaque atualizado')->success()->send();
                    }),
                EditAction::make()->label('')->tooltip('Editar'),
                DeleteAction::make()->label('')->tooltip('Excluir'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    BulkAction::make('activate')
                        ->label('Ativar Selecionados')
                        ->icon('heroicon-m-check-circle')
                        ->color('success')
                        ->action(fn (Collection $records) => $records->each->update(['is_active' => true]))
                        ->deselectRecordsAfterCompletion(),
                    BulkAction::make('deactivate')
                        ->label('Desativar Selecionados')
                        ->icon('heroicon-m-eye-slash')
                        ->color('gray')
                        ->action(fn (Collection $records) => $records->each->update(['is_active' => false]))
                        ->deselectRecordsAfterCompletion(),
                    BulkAction::make('feature')
                        ->label('Marcar como Destaque')
                        ->icon('heroicon-m-star')
                        ->color('warning')
                        ->action(fn (Collection $records) => $records->each->update(['is_featured' => true]))
                        ->deselectRecordsAfterCompletion(),
                    BulkAction::make('unfeature')
                        ->label('Remover Destaque')
                        ->icon('heroicon-o-star')
                        ->color('gray')
                        ->action(fn (Collection $records) => $records->each->update(['is_featured' => false]))
                        ->deselectRecordsAfterCompletion(),
                    DeleteBulkAction::make()->label('Excluir Selecionados'),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit'   => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
