<?php

namespace App\Filament\Panels\Admin\Resources\Exhibitors\Schemas;

use App\Enums\Branch;
use Filament\Facades\Filament;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;

class ExhibitorForm
{
    /**
     * @return array<Component>
     */
    protected static function getAddressSchema(): array
    {
        return [
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
    }

    /**
     * @return array<Component>
     */
    protected static function getContactPersonSchema(): array
    {
        return [
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
    }

    public static function configure(Schema $schema): Schema
    {
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
                            ->schema(self::getAddressSchema()),

                        Section::make('Ansprechpartner')
                            ->columns(12)
                            ->relationship('contactPerson')
                            ->schema(self::getContactPersonSchema()),
                    ]),

                    Tabs\Tab::make('Rechnung')->schema([
                        Section::make('Rechnungsadresse')
                            ->columns(12)
                            ->relationship('billingAddress')
                            ->schema(self::getAddressSchema()),

                        Section::make('Ansprechpartner')
                            ->columns(12)
                            ->relationship('billingContactPerson')
                            ->schema(self::getContactPersonSchema()),
                    ]),

                    Tabs\Tab::make('Logo')->schema([
                        Section::make('Logo')
                            ->schema([
                                FileUpload::make('logo_file')
                                    ->label('Logo')
                                    ->acceptedFileTypes(['image/*']),
                            ]),
                    ]),

                    Tabs\Tab::make('Beschreibung')->schema([
                        Section::make('Beschreibung')
                            ->schema([
                                RichEditor::make('description')
                                    ->hiddenLabel()
                                    ->toolbarButtons(['bold', 'italic', 'underline']),
                            ]),
                    ]),

                    Tabs\Tab::make('Notiz')
                        ->visible(fn () => Filament::getCurrentPanel()->getId() === 'admin')
                        ->schema([
                            Section::make('Interne Notiz')
                                ->schema([
                                    RichEditor::make('internal_note')
                                        ->hiddenLabel()
                                        ->toolbarButtons(['bold', 'italic', 'underline']),
                                ]),
                        ]),
                ]),
            ]);
    }
}
