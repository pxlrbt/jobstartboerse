<?php

namespace App\Filament\Panels\Admin\Resources\Mailings;

use App\Filament\Enums\NavigationGroup;
use App\Filament\Panels\Admin\Resources\Mailings\Pages\CreateMailing;
use App\Filament\Panels\Admin\Resources\Mailings\Pages\ListMailings;
use App\Filament\Panels\Admin\Resources\Mailings\Pages\ViewMailing;
use App\Models\Mailing;
use BackedEnum;
use Filafly\Icons\Phosphor\Enums\Phosphor;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class MailingResource extends Resource
{
    protected static ?string $model = Mailing::class;

    protected static ?string $slug = 'mailings';

    protected static ?string $modelLabel = 'Mailing';

    protected static ?string $pluralModelLabel = 'Mailings';

    protected static string|BackedEnum|null $navigationIcon = Phosphor::EnvelopeDuotone;

    protected static string|UnitEnum|null $navigationGroup = NavigationGroup::Functions;

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'DESC')
            ->columns([
                TextColumn::make('subject')
                    ->label('Betreff')
                    ->searchable(),

                TextColumn::make('exhibitors_count')
                    ->label('Empfänger')
                    ->badge()
                    ->counts('exhibitors')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Versendet am')
                    ->date()
                    ->sortable(),

            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
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

    public static function getPages(): array
    {
        return [
            'index' => ListMailings::route('/'),
            'create' => CreateMailing::route('/create'),
            'view' => ViewMailing::route('/{record}/view'),
        ];
    }

    /**
     * @return Builder<Mailing>
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
