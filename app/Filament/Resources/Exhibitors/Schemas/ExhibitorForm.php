<?php

namespace App\Filament\Resources\Exhibitors\Schemas;

use App\Enums\Branch;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;

class ExhibitorForm
{
    public static function configure(Schema $schema): Schema
    {
        $addressSchema = [
            TextInput::make('name')
                ->label('Bezeichnung')
                ->columnSpanFull(),

            TextInput::make('street')
                ->label('Straße')
                ->columnSpanFull(),

            TextInput::make('zipcode')
                ->numeric()
                ->label('PLZ')
                ->columnSpan(4),

            TextInput::make('city')
                ->label('Ort')
                ->columnSpan(8),
        ];

        $contactPersonSchema = [
            TextInput::make('title')
                ->label('Titel')
                ->columnSpan(2),

            TextInput::make('first_name')
                ->label('Vorname')
                ->columnSpan(5),

            TextInput::make('last_name')
                ->label('Nachname')
                ->columnSpan(5),

            TextInput::make('phone')
                ->label('Telefon')
                ->tel()
                ->columnSpan(6),

            TextInput::make('email')
                ->label('Email')
                ->email()
                ->columnSpan(6),
        ];

        return $schema
            ->columns(1)
            ->components([
                Tabs::make()->vertical()->contained(false)->tabs([
                    Tabs\Tab::make('Stammdaten')->schema([
                        Section::make('Stammdaten')->columns(2)->schema([
                            TextInput::make('display_name')
                                ->label('Firmenname')
                                ->columnSpanFull()
                                ->required(),

                            TextInput::make('display_name_affix')
                                ->label('Zusatz')
                                ->columnSpanFull(),

                            Select::make('branch')
                                ->label('Branche')
                                ->options(Branch::class),

                            TextInput::make('website')
                                ->label('Webseite')
                                ->url()
                                ->placeholder('https://'),
                        ]),

                        Section::make('Adresse')
                            ->columns(12)
                            ->relationship('address')
                            ->schema($addressSchema),

                        Section::make('Ansprechpartner')
                            ->columns(12)
                            ->relationship('contactPerson')
                            ->schema($contactPersonSchema),
                    ]),

                    Tabs\Tab::make('Rechnung')->schema([
                        Section::make('Rechnungsadresse')
                            ->compact(true)
                            ->columns(12)
                            ->relationship('billingAddress')
                            ->schema($addressSchema),

                        Section::make('Ansprechpartner')
                            ->compact(true)
                            ->columns(12)
                            ->relationship('billingContactPerson')
                            ->schema($contactPersonSchema),
                    ]),

                    Tabs\Tab::make('Logo')->schema([
                        Section::make('Logo')
                            ->schema([
                                FileUpload::make('logo_file')
                                    ->label('Logo')
                                    ->acceptedFileTypes(['image/*']),
                            ]),
                    ]),
                ]),
            ]);
    }
}
