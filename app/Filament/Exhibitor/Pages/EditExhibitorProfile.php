<?php

namespace App\Filament\Exhibitor\Pages;

use App\Filament\Enums\NavigationGroup;
use App\Filament\Resources\Exhibitors\Schemas\ExhibitorForm;
use App\Models\Exhibitor;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Js;
use UnitEnum;

/**
 * @property-read Schema $form
 */
class EditExhibitorProfile extends Page
{
    use InteractsWithForms;

    public Exhibitor $record;

    public array $data = [];

    public ?string $previousUrl = null;

    protected Width|string|null $maxContentWidth = Width::ScreenLarge;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::UserCircle;

    protected static string|UnitEnum|null $navigationGroup = NavigationGroup::Profiles;

    protected static ?string $navigationLabel = 'Aussteller-Profil';

    protected static ?string $title = 'Aussteller-Profil';

    public function mount(): void
    {
        $this->record = auth()->user()->exhibitor;

        $this->form->fill($this->record->toArray());
        $this->form->record($this->record);
        $this->form->loadStateFromRelationships();

        $this->previousUrl = url()->previous();
    }

    public function form(Schema $schema): Schema
    {
        return ExhibitorForm::configure($schema)
            ->statePath('data')
            ->model($this->record);
    }

    public function save(): void
    {
        $data = $this->form->getState();
        $this->record->save($data);

        $this->getSavedNotification()?->send();

    }

    public function content(Schema $schema): Schema
    {
        return $schema->components([
            Form::make([EmbeddedSchema::make('form')])
                ->id('form')
                ->livewireSubmitHandler('save')
                ->footer([
                    $this->getFormActionsContentComponent(),
                ]),
        ]);
    }

    public function getFormActionsContentComponent(): Component
    {
        return Actions::make($this->getFormActions())
            ->alignment(Alignment::End)
            ->fullWidth(false)
            ->sticky(false)
            ->key('form-actions');
    }

    /**
     * @return array<Action | ActionGroup>
     */
    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction(),
            $this->getCancelFormAction(),
        ];
    }

    protected function getSaveFormAction(): Action
    {
        $hasFormWrapper = true; // $this->hasFormWrapper();

        return Action::make('save')
            ->label(__('filament-panels::resources/pages/edit-record.form.actions.save.label'))
            ->submit('save')
            ->keyBindings(['mod+s']);
    }

    protected function getSubmitFormAction(): Action
    {
        return $this->getSaveFormAction();
    }

    protected function getCancelFormAction(): Action
    {
        $url = $this->previousUrl;

        return Action::make('cancel')
            ->label(__('filament-panels::resources/pages/edit-record.form.actions.cancel.label'))
            ->alpineClickHandler(
                'document.referrer ? window.history.back() : (window.location.href = '.Js::from($url).')'
            )
            ->color('gray');
    }

    protected function getSavedNotification(): ?Notification
    {
        $title = $this->getSavedNotificationTitle();

        if (blank($title)) {
            return null;
        }

        return Notification::make()
            ->success()
            ->title($title);
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return __('filament-panels::resources/pages/edit-record.notifications.saved.title');
    }
}
