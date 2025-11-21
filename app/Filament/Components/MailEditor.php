<?php

namespace App\Filament\Components;

use Filament\Forms\Components\RichEditor;

class MailEditor
{
    public static function make(): RichEditor
    {
        return RichEditor::make('content')
            ->label('Mail')
            ->toolbarButtons([
                ['bold', 'italic', 'underline'],
                ['orderedList', 'bulletList', 'link'],
                ['mergeTags'],
            ])
            ->mergeTags([
                'titel' => 'Titel',
                'vorname' => 'Vorname',
                'nachname' => 'Nachname',
                'zugangsdaten' => 'Zugangsdaten',
                'ausstellername' => 'Ausstellername',
            ])
            ->required();
    }
}
