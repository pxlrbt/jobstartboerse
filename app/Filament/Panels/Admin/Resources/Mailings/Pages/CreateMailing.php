<?php

namespace App\Filament\Panels\Admin\Resources\Mailings\Pages;

use App\Actions\RenderMailContent;
use App\Filament\Components\MailEditor;
use App\Filament\Components\MailPreview;
use App\Filament\Panels\Admin\Resources\Mailings\MailingResource;
use App\Mail\GenericMail;
use App\Models\Exhibitor;
use App\Models\JobFair;
use App\Models\Mailing;
use App\Models\MailTemplate;
use Filament\Actions\Action;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\RichEditor\RichContentRenderer;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Wizard\Step;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\HtmlString;
use Livewire\Features\SupportRedirects\Redirector;

class CreateMailing extends CreateRecord
{
    use CreateRecord\Concerns\HasWizard;

    protected static string $resource = MailingResource::class;

    public bool $wasTestSent = false;

    /**
     * @var array<string, string>
     */
    protected $listeners = ['refresh' => '$refresh'];

    protected function getCreateFormAction(): Action
    {
        return Action::make('create')
            ->label('Versenden')
            ->action('sendMailing')
            ->disabled(! $this->wasTestSent);
    }

    public function sendMailing(): Redirector
    {
        $data = $this->form->getState();

        $subject = $data['subject'];
        $content = $data['content'];
        $recipients = Exhibitor::findMany($data['exhibitors']);

        $mailing = Mailing::create([
            'subject' => $subject,
            'message' => $content,
        ]);

        foreach ($recipients as $recipient) {
            if (blank($recipient->contactPerson?->email)) {
                continue;
            }

            $mailing->exhibitors()->attach($recipient->id, [
                'name' => $recipient->contactPerson->full_name,
                'email' => $recipient->contactPerson->email,
            ]);

            Mail::to(
                $recipient->contactPerson->email,
                $recipient->contactPerson->full_name,
            )->send(new GenericMail(
                $subject,
                (new RenderMailContent)($content, $recipient)
            ));
        }

        Notification::make()
            ->info()
            ->title('Mailing wird gesendet')
            ->body('Ihr Mailing wird jetzt an die Aussteller versendet.')
            ->send();

        return redirect()->to(MailingResource::getUrl());
    }

    /**
     * @return array<Step>
     */
    protected function getSteps(): array
    {
        return [
            Step::make('Auswahl')->components([
                Section::make()->collapsible(false)->components([
                    CheckboxList::make('job_fairs')
                        ->label('Veranstaltungen')
                        ->columns(3)
                        ->options(fn () => JobFair::query()
                            ->orderBy('created_at', 'desc')
                            ->pluck('display_name', 'id')
                        ),
                ]),

                // CheckboxList::make('surveys')
                //     ->label('Umfragen')
                //     ->columns(3)
                //     ->options(fn () => JobFair::query()
                //         ->orderBy('created_at', 'desc')
                //         ->pluck('display_name', 'id')
                //     ),
            ])->afterValidation(function (CreateMailing $livewire) {
                $jobFairIds = $livewire->data['job_fairs'];

                $livewire->data['exhibitors'] = Exhibitor::query()
                    ->whereHas('jobFairs', fn ($query) => $query->whereIn('job_fairs.id', $jobFairIds))
                    ->pluck('id')
                    ->toArray();
            }),

            Step::make('Empfänger')->components(function (CreateMailing $livewire) {
                $jobFairIds = $livewire->data['job_fairs'];

                $exhibitors = Exhibitor::query()
                    ->whereHas('jobFairs', fn ($query) => $query->whereIn('job_fairs.id', $jobFairIds))
                    ->with('contactPerson')
                    ->get();

                $options = $exhibitors->mapWithKeys(fn ($exhibitor) => [
                    $exhibitor->id => "{$exhibitor->contactPerson?->full_name} ({$exhibitor->contactPerson?->email})",
                ])->toArray();

                if (count($options) <= 0) {
                    return [
                        Section::make()->collapsible(false)->components([
                            new HtmlString('Keine Empfänger.'),
                        ]),
                    ];
                }

                return [
                    Section::make(count($options).' Empfänger')->collapsible(false)->components([
                        CheckboxList::make('exhibitors')
                            ->hiddenLabel()
                            ->columns(2)
                            ->in(fn () => $exhibitors->pluck('id')->toArray())
                            ->options(fn () => $options),
                    ]),
                ];
            }),

            Step::make('Nachricht')->components([
                Section::make()->collapsible(false)->components([
                    Select::make('template')
                        ->label('Template')
                        ->live()
                        ->options(fn () => MailTemplate::query()
                            ->orderBy('display_name')
                            ->pluck('display_name', 'id')
                        )
                        ->afterStateUpdated(function (Set $set, $state) {
                            $template = MailTemplate::find($state);
                            $set('subject', $template->display_name);
                            $set('content', $template->content);
                        }),

                    TextInput::make('subject')
                        ->label('Betreff')
                        ->required(),

                    MailEditor::make(),
                ]),
            ]),

            Step::make('Übersicht')->components([
                Section::make()->collapsible(false)->components([

                    TextEntry::make('subject')
                        ->label('Betreff')
                        ->inlineLabel()
                        ->getStateUsing(fn ($livewire) => $livewire->data['subject']),

                    TextEntry::make('recipients')
                        ->label('Empfänger')
                        ->inlineLabel()
                        ->getStateUsing(fn ($livewire) => count(data_get($livewire, 'data.exhibitors', []))),

                    MailPreview::make('preview')
                        ->label('Vorschau')
                        ->state(fn ($livewire) => data_get($livewire, 'data.content')),

                    TextInput::make('test_recipient')
                        ->label('E-Mail-Adresse für Test')
                        ->email()
                        ->required(),

                    Actions::make([
                        Action::make('send_test_mail')
                            ->label('Test-Email senden')
                            ->action(function (CreateMailing $livewire) {
                                $data = $this->form->getState();
                                $email = $data['test_recipient'];

                                Mail::to($email)->send(new GenericMail(
                                    $data['subject'],
                                    RichContentRenderer::make($data['content'])->toHtml()
                                ));

                                Notification::make()
                                    ->title('Testmail gesendet')
                                    ->info()
                                    ->body('Eine Testmail wurde an Ihre Adresse gesendet. Bitte prüfen Sie die Mail bevor Sie das Mailing versenden.')
                                    ->send();

                                $this->wasTestSent = true;
                                $livewire->dispatch('refresh');
                            }),
                    ])->alignEnd(),
                ]),
            ]),
        ];
    }
}
