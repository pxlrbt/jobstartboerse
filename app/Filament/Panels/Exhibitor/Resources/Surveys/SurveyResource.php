<?php

namespace App\Filament\Panels\Exhibitor\Resources\Surveys;

use App\Filament\Panels\Exhibitor\Resources\Surveys\Pages\ListSurveys;
use App\Filament\Panels\Exhibitor\Resources\Surveys\Pages\ParticipateSurvey;
use App\Filament\Panels\Exhibitor\Resources\Surveys\Tables\SurveysTable;
use App\Models\Survey;
use BackedEnum;
use Filafly\Icons\Phosphor\Enums\Phosphor;
use Filament\Resources\RelationManagers\RelationManagerConfiguration;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SurveyResource extends Resource
{
    protected static ?string $model = Survey::class;

    protected static string|BackedEnum|null $navigationIcon = Phosphor::QuestionDuotone;

    protected static ?string $recordTitleAttribute = 'display_name';

    protected static ?string $modelLabel = 'Umfrage';

    protected static ?string $pluralModelLabel = 'Umfragen';

    public static function table(Table $table): Table
    {
        return SurveysTable::configure($table);
    }

    /**
     * @return array<RelationManagerConfiguration>
     */
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function getPages(): array
    {
        return [
            'index' => ListSurveys::route('/'),
            'view' => ParticipateSurvey::route('/{record}'),
        ];
    }

    /**
     * @return Builder<Survey>
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereDoesntHave(
                'submissions',
                fn (Builder $query) => $query->where('exhibitor_id', auth()->user()->exhibitor_id)
            )
            ->whereHas(
                'jobFairs',
                fn (Builder $query) => $query->whereIn('job_fair_id', auth()->user()->exhibitor->jobFairs()->pluck('job_fairs.id'))
            );
    }
}
