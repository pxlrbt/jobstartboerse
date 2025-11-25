<?php

namespace App\Filament\Panels\Admin\Resources\Surveys;

use App\Enums\SurveyQuestionType;
use App\Filament\Enums\NavigationGroup;
use App\Filament\Panels\Admin\Resources\Surveys\Pages\CreateSurvey;
use App\Filament\Panels\Admin\Resources\Surveys\Pages\EditSurvey;
use App\Filament\Panels\Admin\Resources\Surveys\Pages\ListSurveys;
use App\Filament\Panels\Admin\Resources\Surveys\Pages\ViewSurveyResults;
use App\Models\Survey;
use BackedEnum;
use Filafly\Icons\Phosphor\Enums\Phosphor;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\PageRegistration;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class SurveyResource extends Resource
{
    protected static ?string $model = Survey::class;

    protected static ?string $slug = 'surveys';

    protected static string|BackedEnum|null $navigationIcon = Phosphor::QuestionDuotone;

    protected static string|UnitEnum|null $navigationGroup = NavigationGroup::Functions;

    protected static ?string $modelLabel = 'Umfrage';

    protected static ?string $pluralModelLabel = 'Umfragen';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Tabs::make()->contained(false)->tabs([
                    Tabs\Tab::make('Daten')->components([
                        Section::make()->collapsible(false)->columns(2)->components([
                            TextInput::make('display_name')
                                ->label('Name')
                                ->columnSpanFull()
                                ->required(),

                            DatePicker::make('starts_at')
                                ->label('Von')
                                ->required(),

                            DatePicker::make('ends_at')
                                ->label('Bis')
                                ->required(),

                            CheckboxList::make('jobFairs')
                                ->label('Börsen')
                                ->relationship('jobFairs', 'display_name')
                                ->minItems(1)
                                ->columnSpanFull()
                                ->columns(3),
                        ]),
                    ]),

                    Tabs\Tab::make('Fragen')->components([
                        Repeater::make('questions')
                            ->hiddenLabel()
                            ->relationship('questions')
                            ->collapsed()
                            ->collapsible()
                            ->orderColumn('order')
                            ->addActionLabel('Frage hinzufügen')
                            ->itemLabel(fn (array $state) => $state['display_name'])
                            ->columnSpanFull()
                            ->columns(2)
                            ->components([
                                TextInput::make('display_name')
                                    ->label('Label')
                                    ->live()
                                    ->required(),

                                Select::make('type')
                                    ->label('Typ')
                                    ->disabled(function (EditSurvey $livewire) {
                                        /** @var Survey $record */
                                        $record = $livewire->getRecord();

                                        return $record->submissions()->exists();
                                    })
                                    ->enum(SurveyQuestionType::class)
                                    ->options(SurveyQuestionType::class)

                                    ->live()
                                    ->partiallyRenderComponentsAfterStateUpdated(['options'])
                                    ->required(),

                                Repeater::make('options')
                                    ->label('Optionen')
                                    ->columnSpanFull()
                                    ->visible(fn (Get $get) => $get('type')?->hasOptions())
                                    ->minItems(1)
                                    ->defaultItems(1)
                                    ->addActionLabel('Option hinzufügen')
                                    ->simple(
                                        TextInput::make('option')->label('Option')
                                    ),
                            ]),
                    ]),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('display_name')
                    ->label('Name'),

                TextColumn::make('starts_at')
                    ->label('Von')
                    ->date(),

                TextColumn::make('ends_at')
                    ->label('Bis')
                    ->date(),

                TextColumn::make('submissions_count')
                    ->label('Teilnehmer')
                    ->counts('submissions'),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('view_results')
                    ->label('Ergebnisse')
                    ->color('secondary')
                    ->icon(Phosphor::ChartScatterDuotone)
                    ->url(fn (Survey $record) => SurveyResource::getUrl('results', ['record' => $record->id])),

                DeleteAction::make(),
                RestoreAction::make(),
                ForceDeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                ]),
            ]);
    }

    /**
     * @return array<string, PageRegistration>
     */
    public static function getPages(): array
    {
        return [
            'index' => ListSurveys::route('/'),
            'create' => CreateSurvey::route('/create'),
            'edit' => EditSurvey::route('/{record}/edit'),
            'results' => ViewSurveyResults::route('/{record}'),
        ];
    }

    /**
     * @return Builder<Survey>
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
