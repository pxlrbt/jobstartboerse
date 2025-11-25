<?php

namespace App\Filament\Panels\Admin\Resources\Mailings\Pages;

use App\Filament\Components\MailPreview;
use App\Filament\Panels\Admin\Resources\Mailings\MailingResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;

class ViewMailing extends ViewRecord
{
    protected static string $resource = MailingResource::class;

    public function infolist(Schema $schema): Schema
    {
        return $schema->columns(1)->components([
            Tabs::make()->tabs([
                Tabs\Tab::make('Infos')->components([

                    TextEntry::make('subject')
                        ->label('Subject'),

                    TextEntry::make('created_at')
                        ->date('d.m.Y, H:i')
                        ->label('Gesendet'),

                    MailPreview::make('message'),

                ]),

                Tabs\Tab::make('Empfänger')->components([
                    RepeatableEntry::make('exhibitors')
                        ->hiddenLabel()
                        ->table([
                            RepeatableEntry\TableColumn::make('Aussteller'),
                            RepeatableEntry\TableColumn::make('Empfänger'),
                            RepeatableEntry\TableColumn::make('E-Mail-Adresse'),
                        ])
                        ->schema([
                            TextEntry::make('display_name'),
                            TextEntry::make('pivot.name'),
                            TextEntry::make('pivot.email'),
                        ]),
                ]),
            ]),
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
