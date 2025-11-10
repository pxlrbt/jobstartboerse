<?php

namespace App\Filament\Exhibitor\RelationManagers;

use App\Models\Degree;
use App\Models\Exhibitor;
use App\Models\JobFair;
use App\Models\Profession;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\DB;

class JobStartRelationManager extends RelationManager
{
    protected static string $relationship = 'degrees';

    protected static ?string $title = 'Job-Start-Lounge';

    protected static ?string $modelLabel = 'Job-Start-Lounge';

    protected static ?string $pluralModelLabel = 'Job-Start-Lounge';

    public ?JobFair $jobFair = null;

    public ?Exhibitor $exhibitor = null;

    public function mount(): void
    {
        $this->exhibitor = auth()->user()->exhibitor;
    }

    protected function getTableHeading(): string|Htmlable|null
    {
        return $this->jobFair->display_name;
    }

    public function getTableRecordKey(Model|array $record): string
    {
        return match ($record->classname) {
            'degrees' => Degree::class.':'.$record->id,
            'profession' => Profession::class.':'.$record->id,
        };
    }

    public function getTableRecord(?string $key): Model|array|null
    {
        [$class, $id] = explode(':', $key);

        return $class::find($id);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Degree::query()
                    ->select(['id', 'display_name'])
                    ->selectRaw('"degrees" as classname')
                    ->selectSub(function ($query) {
                        $query->from('lounge_participations')
                            ->whereColumn('lounge_participations.model_id', 'degrees.id')
                            ->where('lounge_participations.job_fair_id', $this->jobFair->id)
                            ->where('lounge_participations.exhibitor_id', $this->exhibitor->id)
                            ->where('lounge_participations.model_type', 'degree')
                            ->selectRaw('COUNT(*) > 0');
                    }, 'status')
                    ->union(
                        Profession::query()
                            ->select(['id', 'display_name'])
                            ->selectRaw('"profession" as classname')
                            ->selectSub(function ($query) {
                                $query->from('lounge_participations')
                                    ->whereColumn('lounge_participations.model_id', 'professions.id')
                                    ->where('lounge_participations.job_fair_id', $this->jobFair->id)
                                    ->where('lounge_participations.exhibitor_id', $this->exhibitor->id)
                                    ->where('lounge_participations.model_type', 'profession')
                                    ->selectRaw('COUNT(*) > 0');
                            }, 'status')
                    )
            )
            ->defaultKeySort(false)
            ->recordTitleAttribute('display_name')
            ->columns([
                TextColumn::make('display_name')
                    ->label('Name')
                    ->searchable(),

                TextColumn::make('classname')
                    ->label('Beruf/Studiengang')
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'degrees' => 'Studiengang',
                        'profession' => 'Beruf',
                    })
                    ->badge()
                    ->searchable(),

                ToggleColumn::make('status')
                    ->label('Teilnehmen')
                    ->updateStateUsing(function ($record, $state) {
                        if ($state) {
                            DB::table('lounge_participations')->insert([
                                'exhibitor_id' => $this->exhibitor->id,
                                'job_fair_id' => $this->jobFair->id,
                                'model_type' => Relation::getMorphAlias($record::class),
                                'model_id' => $record->id,
                                'status' => $state,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                            // add Jobstart
                        } else {
                            DB::table('lounge_participations')
                                ->where([
                                    'exhibitor_id' => $this->exhibitor->id,
                                    'job_fair_id' => $this->jobFair->id,
                                    'model_type' => Relation::getMorphAlias($record::class),
                                    'model_id' => $record->id,
                                ])
                                ->delete();
                            // REmove JobLoung
                        }
                    }),
            ]);
    }
}
