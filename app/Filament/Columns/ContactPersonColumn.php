<?php

namespace App\Filament\Columns;

use App\Models\Exhibitor;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\HtmlString;

class ContactPersonColumn
{
    public static function make(): TextColumn
    {
        return TextColumn::make('contactPerson.last_name')
            ->label('Ansprechpartner')
            ->formatStateUsing(function (Exhibitor $record) {
                return new HtmlString(<<<HTML
                            <div style="line-height: 1.2">
                                {$record->contactPerson->last_name}<br>
                                <small>
                                    <a href="mailto:{$record->contactPerson->email}">
                                        {$record->contactPerson->email}
                                    </a>
                                </small>
                            </div>
                        HTML);
            })
            ->searchable()
            ->sortable();
    }
}
