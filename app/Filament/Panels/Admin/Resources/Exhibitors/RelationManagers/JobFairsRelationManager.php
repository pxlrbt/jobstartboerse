<?php

namespace App\Filament\Panels\Admin\Resources\Exhibitors\RelationManagers;

use App\Filament\Panels\Admin\Resources\JobFairs\JobFairResource;
use Filament\Actions\Action;
use Filament\Actions\AttachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\HtmlString;

class JobFairsRelationManager extends RelationManager
{
    protected static string $relationship = 'jobFairs';

    protected static ?string $title = 'Börsen';

    protected static ?string $modelLabel = 'Börse';

    protected static ?string $pluralModelLabel = 'Börsen';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                TextInput::make('stall_number')
                    ->label('Standnummer'),

                Toggle::make('needs_power')
                    ->label('Strom'),

                Textarea::make('internal_note')
                    ->label('Notiz')
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('display_name')
            ->columns([
                TextColumn::make('display_name')
                    ->label('Börse')
                    ->action(
                        Action::make('go_to_job_fair')
                            ->action(fn ($record) => redirect(JobFairResource::getUrl('edit', ['record' => $record])))
                    )
                    ->extraAttributes(['class' => 'hover:underline'])
                    ->searchable(),

                TextColumn::make('stall_number')
                    ->label('Standnummer'),

                IconColumn::make('needs_power')
                    ->label('Strom')
                    ->boolean(),

                IconColumn::make('internal_note')
                    ->label('Notiz')
                    ->icon(fn ($state) => filled($state) ? Heroicon::Envelope : null)
                    ->action(
                        Action::make('show_note')
                            ->action(null)
                            ->modalWidth(Width::Medium)
                            ->modalHeading('Interne Notiz')
                            ->modalContent(fn ($record) => new HtmlString(nl2br($record->internal_note)))
                            ->modalSubmitAction(false)
                            ->modalCancelActionLabel('Schließen')
                    ),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->headerActions([
                AttachAction::make()
                    ->schema(fn (AttachAction $action) => [
                        $action->getRecordSelect(),

                        TextInput::make('stall_number')
                            ->label('Standnummer'),

                        Toggle::make('pivot_needs_power')
                            ->label('Strom'),

                        Textarea::make('internal_note')
                            ->label('Notiz')
                            ->columnSpanFull(),
                    ])
                    ->preloadRecordSelect()
                    ->multiple(),
            ])
            ->recordActions([
                EditAction::make()
                    ->modalWidth(Width::Large)
                    ->iconButton(),
                DetachAction::make()->iconButton(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DetachBulkAction::make(),
                ]),
            ])
            ->modifyQueryUsing(fn (Builder $query) => $query
                ->withoutGlobalScopes([
                    SoftDeletingScope::class,
                ]));
    }
}
