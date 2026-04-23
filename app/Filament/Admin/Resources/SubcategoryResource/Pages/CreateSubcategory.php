<?php

namespace App\Filament\Admin\Resources\SubcategoryResource\Pages;

use App\Filament\Admin\Resources\SubcategoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSubcategory extends CreateRecord
{
    protected static string $resource = SubcategoryResource::class;

    protected function getRedirectUrl(): string { return $this->getResource()::getUrl('index'); }
}
