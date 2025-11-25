<?php

namespace App\Filament\Panels\Admin\Resources\JobFairs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class JobFairsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('display_name')
                    ->label('Börse'),

                TextColumn::make('date')
                    ->label('Datum')
                    ->date()
                    ->getStateUsing(fn ($record) => $record->dates->first()->date)
                    ->sortable(query: function (Builder $query, $direction) {
                        $query->join('job_fair_dates', 'job_fairs.id', '=', 'job_fair_dates.job_fair_id')
                            ->select('job_fairs.*')
                            ->groupBy('job_fairs.id')
                            ->orderBy(DB::raw('MIN(job_fair_dates.date)'), $direction);
                    }),

                IconColumn::make('is_public')
                    ->label('Veröffentlicht')
                    ->boolean(),

                TextColumn::make('exhibitors_count')
                    ->label('Aussteller')
                    ->sortable()
                    ->badge()
                    ->counts('exhibitors'),

                TextColumn::make('school_registrations_count')
                    ->label('Schulanmeldungen')
                    ->sortable()
                    ->badge()
                    ->counts('schoolRegistrations'),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
