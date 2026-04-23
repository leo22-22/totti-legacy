<?php

namespace App\Filament\Admin\Resources\SubcategoryResource\Pages;

use App\Filament\Admin\Resources\SubcategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSubcategory extends EditRecord
{
    protected static string $resource = SubcategoryResource::class;

    protected function getHeaderActions(): array { return [Actions\DeleteAction::make()]; }
}
