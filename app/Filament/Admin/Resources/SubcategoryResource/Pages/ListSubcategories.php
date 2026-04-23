<?php

namespace App\Filament\Admin\Resources\SubcategoryResource\Pages;

use App\Filament\Admin\Resources\SubcategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSubcategories extends ListRecords
{
    protected static string $resource = SubcategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()->label('Nova Subcategoria')];
    }
}
