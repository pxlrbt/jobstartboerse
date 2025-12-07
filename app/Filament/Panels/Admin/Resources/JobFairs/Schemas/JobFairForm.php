<?php

namespace App\Filament\Panels\Admin\Resources\JobFairs\Schemas;

use App\Models\Location;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;

class JobFairForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make()->vertical()->contained(false)->columnSpanFull()->tabs([
                    Tabs\Tab::make('Stammdaten')->columns(3)->schema([
                        Section::make('Stammdaten')->schema([
                            Repeater::make('dates')
                                ->label('Veranstaltungstage')
                                ->minItems(1)
                                ->defaultItems(2)
                                ->reorderable(false)
                                ->relationship('dates')
                                ->table([
                                    Repeater\TableColumn::make('Datum')->alignLeft(),
                                    Repeater\TableColumn::make('Startzeit')->alignLeft(),
                                    Repeater\TableColumn::make('Endzeit')->alignLeft(),
                                ])
                                ->schema([
                                    DatePicker::make('date')
                                        ->label('Datum')
                                        ->required(),
                                    TextInput::make('starts_at')
                                        ->label('Startzeit')
                                        ->type('time')
                                        ->required(),
                                    TextInput::make('ends_at')
                                        ->label('Endzeit')
                                        ->type('time')
                                        ->required(),
                                ])
                                ->addActionLabel('Tag hinzufügen'),

                            Select::make('location')
                                ->label('Veranstaltungsorte')
                                ->required()
                                ->multiple()
                                ->preload()
                                ->relationship('locations', 'name')
                                ->getOptionLabelFromRecordUsing(fn (Location $record) => $record->displayName)
                                ->createOptionModalHeading('Ort erstellen')
                                ->createOptionForm([
                                    Grid::make()->columns(3)->schema([
                                        TextInput::make('name')
                                            ->label('Name')
                                            ->required()
                                            ->columnSpanFull(),

                                        TextInput::make('street')
                                            ->label('Straße')
                                            ->columnSpanFull(),

                                        TextInput::make('zipcode')
                                            ->numeric()
                                            ->maxLength(5)
                                            ->label('PLZ'),

                                        TextInput::make('city')
                                            ->label('Ort')
                                            ->columnSpan(2),
                                    ]),
                                ]),

                            RichEditor::make('description')
                                ->label('Beschreibung')
                                ->toolbarButtons([
                                    [
                                        'bold',
                                        'italic',
                                        'underline',
                                    ],
                                    [
                                        'link',
                                        'bulletList',
                                        'orderedList',
                                    ],
                                ]),
                        ])->columnSpan(2),

                        Section::make([
                            Toggle::make('is_public')
                                ->label('Öffentlich'),

                            Toggle::make('are_exhibitors_public')
                                ->label('Aussteller veröffentlichen'),

                        ])->columnSpan(1),
                    ]),

                    Tabs\Tab::make('Hallenplan')->schema([
                        Section::make('Hallenplan')->schema([
                            TextInput::make('floor_plan_link')
                                ->label('Externer Plan')
                                ->url()
                                ->placeholder('https://'),

                            FileUpload::make('floor_plan_file')
                                ->label('Upload')
                                ->downloadable(),
                        ]),
                    ]),

                    Tabs\Tab::make('Anhänge')->schema([
                        Section::make('Anhänge')->schema([
                            Repeater::make('attachments')
                                ->label('Anhänge')
                                ->columns(3)
                                ->defaultItems(0)
                                ->addActionLabel('Anhang hinzufügen')
                                ->schema([
                                    FileUpload::make('file')
                                        ->label('Datei')
                                        ->downloadable()
                                        ->required(),

                                    TextInput::make('display_name')
                                        ->label('Titel')
                                        ->required(),

                                    Radio::make('category')
                                        ->label('Titel')
                                        ->required()
                                        ->options([
                                            1 => 'Besucher',
                                            2 => 'Aussteller',
                                        ]),
                                ]),
                        ]),
                    ]),

                    Tabs\Tab::make('Job-Start-Lounge')->schema([
                        Section::make('Job-Start-Lounge')->schema([
                            DatePicker::make('lounge_registration_ends_at')
                                ->label('Anmeldung endet am'),

                            FileUpload::make('lounge_files_students')
                                ->label('Infos Schüler')
                                ->multiple()
                                ->downloadable()
                                ->acceptedFileTypes(['application/pdf']),

                            FileUpload::make('lounge_files_exhibitors')
                                ->label('Infos Aussteller')
                                ->multiple()
                                ->downloadable()
                                ->acceptedFileTypes(['application/pdf']),
                        ]),
                    ]),
                ]),
            ]);
    }
}
