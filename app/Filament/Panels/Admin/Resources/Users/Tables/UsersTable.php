<?php

namespace App\Filament\Panels\Admin\Resources\Users\Tables;

use App\Enums\Role;
use App\Models\User;
use Filafly\Icons\Phosphor\Enums\Phosphor;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Support\Facades\Password;
use STS\FilamentImpersonate\Actions\Impersonate;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('exhibitors.display_name')
                    ->label('Aussteller')
                    ->searchable(),

                TextColumn::make('role')
                    ->label('Rolle')
                    ->badge()
                    ->searchable(),

                TextColumn::make('name')
                    ->label('Name')
                    ->searchable(),

                TextColumn::make('email')
                    ->label('E-Mail')
                    ->searchable(),
            ])
            ->filters([
                SelectFilter::make('role')
                    ->label('Rolle')
                    ->options(Role::toOptions()),
            ])
            ->recordActions([
                EditAction::make(),

                Action::make('send_credentials')
                    ->label('Zugangsdaten versenden')
                    ->icon(Phosphor::FingerprintDuotone)
                    ->requiresConfirmation()
                    ->action(function (User $user) {
                        $status = Password::broker(Filament::getAuthPasswordBroker())->sendResetLink(
                            ['email' => $user->email],
                            function (CanResetPassword $user, string $token): void {
                                $notification = app(ResetPasswordNotification::class, ['token' => $token]);
                                $notification->url = Filament::getResetPasswordUrl($token, $user);

                                $user->notify($notification);
                            },
                        );

                        if ($status !== Password::RESET_LINK_SENT) {
                            Notification::make()
                                ->title(__($status))
                                ->danger()
                                ->send();

                            return;
                        }

                        Notification::make()
                            ->title(__($status))
                            ->body(($status === Password::RESET_LINK_SENT) ? 'Ein Link zum Zurücksetzen der Zugangsdaten wurde verschickt' : null)
                            ->success()
                            ->send();
                    }),

                Impersonate::make()
                    ->redirectTo(Filament::getPanel('exhibitor')->getUrl()),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
