<?php

namespace App\Filament\Panels\Admin\Resources\Exhibitors\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SchoolRegistrationsRelationManager extends RelationManager
{
    protected static string $relationship = 'schoolRegistrations';

    protected static ?string $modelLabel = 'Schulanmeldung';

    protected static ?string $pluralModelLabel = 'Schulanmeldungen';

    protected static ?string $title = 'Schulanmeldungen';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Fieldset::make()->columnSpanFull()->contained(false)->schema([
                    TextInput::make('school_name')
                        ->label('Schule')
                        ->columnSpanFull()
                        ->required(),

                    TextInput::make('school_type')
                        ->label('Schulart')
                        ->columnSpanFull()
                        ->required(),

                    TextInput::make('school_zipcode')
                        ->label('PLZ')
                        ->required()
                        ->numeric()
                        ->maxLength(5),

                    TextInput::make('school_city')
                        ->label('Ort')
                        ->required(),
                ]),

                Fieldset::make()->columnSpanFull()->contained(false)->schema([
                    TextInput::make('teacher')
                        ->label('Lehrer')
                        ->columnSpanFull()
                        ->required(),

                    TextInput::make('teacher_email')
                        ->label('E-Mail')
                        ->email()
                        ->required(),

                    TextInput::make('teacher_phone')
                        ->label('Telefon')
                        ->tel(),
                ]),

                Fieldset::make()->columnSpanFull()->contained(false)->schema([
                    Repeater::make('classes')
                        ->label('Schulklassen')
                        ->relationship('classes')
                        ->columnSpanFull()
                        ->minItems(1)
                        ->addActionLabel('Klasse hinzufügen')
                        ->table([
                            Repeater\TableColumn::make('Klasse')->alignLeft(),
                            Repeater\TableColumn::make('Zeitpunkt')->alignLeft(),
                            Repeater\TableColumn::make('Anzahl')->alignLeft(),
                        ])
                        ->schema([
                            TextInput::make('name')->required(),
                            TextInput::make('time')->required(),
                            TextInput::make('students_count')->required()->numeric(),
                        ]),
                ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('school_name')
            ->columns([
                TextColumn::make('school_name')
                    ->label('Schule')
                    ->searchable(),

                TextColumn::make('teacher')
                    ->label('Lehrer')
                    ->searchable(),

                TextColumn::make('classes_count')
                    ->label('Klassen')
                    ->counts('classes')
                    ->badge(),

                TextColumn::make('classes_sum_students_count')
                    ->label('Schüler')
                    ->sum('classes', 'students_count')
                    ->badge(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
                ForceDeleteAction::make(),
                RestoreAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ])
            ->modifyQueryUsing(fn (Builder $query) => $query
                ->withoutGlobalScopes([
                    SoftDeletingScope::class,
                ]));
    }
}
