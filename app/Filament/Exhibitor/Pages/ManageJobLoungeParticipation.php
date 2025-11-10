<?php

namespace App\Filament\Exhibitor\Pages;

use App\Filament\Enums\NavigationGroup;
use App\Filament\Exhibitor\RelationManagers\JobStartRelationManager;
use App\Models\Exhibitor;
use App\Models\JobFair;
use BackedEnum;
use Filafly\Icons\Phosphor\Enums\Phosphor;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Livewire;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\Width;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Js;
use UnitEnum;

/**
 * @property-read Schema $form
 */
class ManageJobLoungeParticipation extends Page
{
    use InteractsWithForms;

    protected static ?string $title = 'Job-Start-Lounge';

    protected static string|BackedEnum|null $navigationIcon = Phosphor::CalendarDuotone;

    protected static string|UnitEnum|null $navigationGroup = NavigationGroup::Data;

    protected Width|string|null $maxContentWidth = Width::ScreenLarge;

    public Exhibitor $record;

    public array $data = [];

    public ?string $previousUrl = null;

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
        return $schema->components([
            Section::make()
                ->heading('Ansprechpartner')
                ->columns(2)
                ->components([
                    TextInput::make('name')
                        ->columnSpan(2)
                        ->label('Name')
                        ->required(),

                    TextInput::make('phone')
                        ->label('Telefon')
                        ->required(),

                    TextInput::make('email')
                        ->label('E-Mail')
                        ->email()
                        ->required(),

                ]),
        ])
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
            new HtmlString(<<<'HTML'
                <div class="max-w-3xl text-base">
                    <p>Wählen Sie nachfolgend alle Ausbildungsberufe und dualen Studienangebote, die für die entsprechende Veranstaltung bei der "Job-Start-Lounge" angezeigt werden sollen.</p>
                </div>
            HTML),

            Form::make([EmbeddedSchema::make('form')])
                ->id('form')
                ->livewireSubmitHandler('save')
                ->footer([
                    $this->getFormActionsContentComponent(),
                ]),

            $this->getJobLoungeRelationManagers(),
        ]);
    }

    public function getJobLoungeRelationManagers(): Component
    {
        $jobFairs = JobFair::query()
            ->whereNotNull('job_fairs.lounge_registration_ends_at')
            ->where('job_fairs.lounge_registration_ends_at', '>=', today())
            ->get();

        if ($jobFairs->isEmpty()) {
            return Section::make()->collapsible(false)->schema([
                new HtmlString(<<<'HTML'
                    <div style="text-align: center;">
                        Aktuell gibt es keine Job-Start-Lounges, an denen Sie teilnehmen können.
                    </div>
                HTML),
            ]);
        }

        $manager = JobStartRelationManager::make();
        $record = auth()->user()->exhibitor;

        return
            Tabs::make()->contained(false)->tabs(
                $jobFairs->map(fn (JobFair $jobFair) => Tabs\Tab::make($jobFair->display_name)->schema([
                    Livewire::make(
                        $manager->relationManager,
                        [
                            'ownerRecord' => $record,
                            'jobFair' => $jobFair,
                            'pageClass' => self::class,
                        ]
                    ),
                ]))->toArray(),
            );
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
