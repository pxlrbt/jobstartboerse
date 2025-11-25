<?php

use App\Filament\Panels\Admin\Resources\Degrees\Pages\ManageDegrees;
use App\Models\Degree;
use Livewire\Livewire;

it('creates degrees with slug', function () {
    $this->login();

    Livewire::test(ManageDegrees::class)
        ->callAction('create', [
            'display_name' => 'Testing Degrees',
        ]);

    expect(Degree::first())
        ->display_name->toBe('Testing Degrees')
        ->slug->toBe('testing-degrees');
});
