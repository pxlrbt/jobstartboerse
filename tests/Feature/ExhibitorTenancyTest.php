<?php

use App\Enums\Role;
use App\Models\Exhibitor;
use App\Models\User;

it('allows user to belong to multiple exhibitors', function () {
    $user = User::factory()->create(['role' => Role::Exhibitor]);
    $exhibitor1 = Exhibitor::factory()->create();
    $exhibitor2 = Exhibitor::factory()->create();

    $user->exhibitors()->attach([$exhibitor1->id, $exhibitor2->id]);

    expect($user->exhibitors)->toHaveCount(3);
    expect($user->exhibitors->pluck('id'))->toContain($exhibitor1->id, $exhibitor2->id);
});

it('implements getTenants method correctly', function () {
    $user = User::factory()->create(['role' => Role::Exhibitor]);
    $exhibitor = Exhibitor::factory()->create();

    $user->exhibitors()->sync([$exhibitor->id]);

    $panel = filament()->getPanel('exhibitor');
    $tenants = $user->getTenants($panel);

    expect($tenants)->toHaveCount(1);
    expect($tenants->first()->id)->toBe($exhibitor->id);
});

it('verifies canAccessTenant returns true for accessible exhibitors', function () {
    $user = User::factory()->create(['role' => Role::Exhibitor]);
    $exhibitor = Exhibitor::factory()->create();

    $user->exhibitors()->sync([$exhibitor->id]);

    expect($user->canAccessTenant($exhibitor))->toBeTrue();
});

it('verifies canAccessTenant returns false for inaccessible exhibitors', function () {
    $user = User::factory()->create(['role' => Role::Exhibitor]);
    $exhibitor = Exhibitor::factory()->create();
    $otherExhibitor = Exhibitor::factory()->create();

    $user->exhibitors()->sync([$exhibitor->id]);

    expect($user->canAccessTenant($otherExhibitor))->toBeFalse();
});

it('can access exhibitor panel with tenant context', function () {
    $user = User::factory()->create(['role' => Role::Exhibitor]);
    $exhibitor = Exhibitor::factory()->create();

    $user->exhibitors()->sync([$exhibitor->id]);

    $response = $this->actingAs($user)->get("/aussteller/{$exhibitor->id}");

    $response->assertSuccessful();
});

it('cannot access other exhibitor tenant', function () {
    $user = User::factory()->create(['role' => Role::Exhibitor]);
    $exhibitor = Exhibitor::factory()->create();
    $otherExhibitor = Exhibitor::factory()->create();

    $user->exhibitors()->sync([$exhibitor->id]);

    $response = $this->actingAs($user)->get("/aussteller/{$otherExhibitor->id}");

    $response->assertNotFound();
});
