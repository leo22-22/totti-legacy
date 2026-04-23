<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\CategoryResource\Pages;
use App\Models\Category;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;
    protected static \UnitEnum|string|null $navigationGroup = 'Catálogo';
    protected static ?string $navigationLabel = 'Categorias';
    protected static ?string $modelLabel = 'Categoria';
    protected static ?string $pluralModelLabel = 'Categorias';
    protected static ?int $navigationSort = 2;

    public static function getNavigationIcon(): \BackedEnum|string|null
    {
        return Heroicon::OutlinedTag;
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->whereNull('parent_id');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\TextInput::make('name')
                ->label('Nome')
                ->required()
                ->live(onBlur: true)
                ->afterStateUpdated(fn (string $state, Set $set) => $set('slug', Str::slug($state))),
            Forms\Components\Hidden::make('slug'),
            Forms\Components\Textarea::make('description')->label('Descrição')->rows(3),
            Forms\Components\FileUpload::make('image')->label('Imagem')->image()->directory('categories'),
            Grid::make(2)->schema([
                Forms\Components\Toggle::make('is_active')->label('Ativa')->default(true),
                Forms\Components\TextInput::make('sort_order')->label('Ordem')->numeric()->default(0),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')->label('')->square()->imageSize(50),
                Tables\Columns\TextColumn::make('name')
                    ->label('Categoria')
                    ->searchable()->sortable()->weight('bold'),
                Tables\Columns\TextColumn::make('children_count')
                    ->label('Subcategorias')
                    ->counts('children')
                    ->badge()->color('info'),
                Tables\Columns\TextColumn::make('products_count')
                    ->label('Produtos')
                    ->counts('products')
                    ->badge()->color('primary'),
                Tables\Columns\IconColumn::make('is_active')->label('Ativa')->boolean(),
                Tables\Columns\TextColumn::make('sort_order')->label('Ordem')->sortable(),
            ])
            ->actions([EditAction::make(), DeleteAction::make()])
            ->reorderable('sort_order');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit'   => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
