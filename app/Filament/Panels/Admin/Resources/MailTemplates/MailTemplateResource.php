<?php

namespace App\Filament\Panels\Admin\Resources\MailTemplates;

use App\Filament\Components\MailEditor;
use App\Filament\Enums\NavigationGroup;
use App\Filament\Panels\Admin\Resources\Mailings\MailingResource;
use App\Filament\Panels\Admin\Resources\MailTemplates\Pages\CreateMailTemplate;
use App\Filament\Panels\Admin\Resources\MailTemplates\Pages\EditMailTemplate;
use App\Filament\Panels\Admin\Resources\MailTemplates\Pages\ListMailTemplates;
use App\Models\MailTemplate;
use BackedEnum;
use Filafly\Icons\Phosphor\Enums\Phosphor;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class MailTemplateResource extends Resource
{
    protected static ?string $model = MailTemplate::class;

    protected static ?string $slug = 'mail-templates';

    protected static ?string $modelLabel = 'Vorlage';

    protected static ?string $pluralModelLabel = 'Vorlagen';

    protected static string|BackedEnum|null $navigationIcon = Phosphor::EnvelopeSimpleDuotone;

    protected static string|UnitEnum|null $navigationGroup = NavigationGroup::Functions;

    protected static ?string $navigationParentItem = 'Mailings';

    public static function getNavigationParentItem(): ?string
    {
        return MailingResource::getPluralModelLabel();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                TextInput::make('display_name')
                    ->label('Name')
                    ->required(),

                MailEditor::make(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('display_name'),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
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
            'index' => ListMailTemplates::route('/'),
            'create' => CreateMailTemplate::route('/create'),
            'edit' => EditMailTemplate::route('/{record}/edit'),
        ];
    }

    /**
     * @return Builder<MailTemplate>
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
