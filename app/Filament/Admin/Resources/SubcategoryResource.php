<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\SubcategoryResource\Pages;
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

class SubcategoryResource extends Resource
{
    protected static ?string $model = Category::class;
    protected static \UnitEnum|string|null $navigationGroup = 'Catálogo';
    protected static ?string $navigationLabel = 'Subcategorias';
    protected static ?string $modelLabel = 'Subcategoria';
    protected static ?string $pluralModelLabel = 'Subcategorias';
    protected static ?int $navigationSort = 3;
    protected static ?string $slug = 'subcategorias';

    public static function getNavigationIcon(): \BackedEnum|string|null
    {
        return Heroicon::OutlinedFolderOpen;
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->whereNotNull('parent_id');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\Select::make('parent_id')
                ->label('Categoria')
                ->options(Category::whereNull('parent_id')->pluck('name', 'id'))
                ->required()
                ->searchable()
                ->preload()
                ->helperText('Selecione a categoria a qual esta subcategoria pertence'),
            Forms\Components\TextInput::make('name')
                ->label('Nome')
                ->required()
                ->live(onBlur: true)
                ->afterStateUpdated(fn (string $state, Set $set) => $set('slug', Str::slug($state))),
            Forms\Components\Hidden::make('slug'),
            Forms\Components\Textarea::make('description')->label('Descrição')->rows(2),
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
                Tables\Columns\TextColumn::make('parent.name')
                    ->label('Categoria')
                    ->badge()->color('primary')->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Subcategoria')
                    ->searchable()->sortable()->weight('bold'),
                Tables\Columns\TextColumn::make('products_count')
                    ->label('Produtos')
                    ->counts('products')
                    ->badge()->color('gray'),
                Tables\Columns\IconColumn::make('is_active')->label('Ativa')->boolean(),
                Tables\Columns\TextColumn::make('sort_order')->label('Ordem')->sortable(),
            ])
            ->defaultSort('parent_id')
            ->actions([EditAction::make(), DeleteAction::make()])
            ->filters([
                Tables\Filters\SelectFilter::make('parent_id')
                    ->label('Categoria')
                    ->options(Category::whereNull('parent_id')->pluck('name', 'id')),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListSubcategories::route('/'),
            'create' => Pages\CreateSubcategory::route('/create'),
            'edit'   => Pages\EditSubcategory::route('/{record}/edit'),
        ];
    }
}
