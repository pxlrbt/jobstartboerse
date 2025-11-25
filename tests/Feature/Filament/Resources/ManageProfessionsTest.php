<?php

use App\Filament\Panels\Admin\Resources\Professions\Pages\ManageProfessions;
use App\Models\Profession;
use Livewire\Livewire;

it('creates professions with slug', function () {
    $this->login();

    Livewire::test(ManageProfessions::class)
        ->callAction('create', [
            'display_name' => 'Testing Professions',
        ]);

    expect(Profession::first())
        ->display_name->toBe('Testing Professions')
        ->slug->toBe('testing-professions');
});
