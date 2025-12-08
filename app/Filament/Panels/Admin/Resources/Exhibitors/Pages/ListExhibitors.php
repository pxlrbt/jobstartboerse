<?php

namespace App\Filament\Panels\Admin\Resources\Exhibitors\Pages;

use App\Filament\Panels\Admin\Resources\Exhibitors\ExhibitorResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use pxlrbt\FilamentExcel\Actions\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class ListExhibitors extends ListRecords
{
    protected static string $resource = ExhibitorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ExportAction::make()
                ->color('gray')
                ->exports([
                    ExcelExport::make()
                        ->fromForm()
                        ->except(['logo_file']),
                ]),

            CreateAction::make(),
        ];
    }
}
