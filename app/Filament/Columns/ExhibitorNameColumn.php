<?php

namespace App\Filament\Columns;

use App\Filament\Panels\Admin\Resources\Exhibitors\ExhibitorResource;
use App\Models\Exhibitor;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\HtmlString;

class ExhibitorNameColumn
{
    public static function make(): TextColumn
    {
        return TextColumn::make('display_name')
            ->label('Name')
            ->formatStateUsing(function (Exhibitor $record) {
                return new HtmlString(<<<HTML
                    <div style="line-height: 1.2">
                        {$record->display_name}<br>
                        <small>{$record->display_name_affix}</small>
                    </div>
                HTML);
            })
            ->action(
                Action::make('go_to_exhibitor')
                    ->action(fn ($record) => redirect(ExhibitorResource::getUrl('edit', ['record' => $record])))
            )
            ->extraAttributes(['class' => 'hover:underline'])
            ->searchable()
            ->sortable();
    }
}
