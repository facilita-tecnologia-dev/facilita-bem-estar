<?php

use App\Models\Company;
use App\Models\User;

beforeEach(function () {
    $this->actingAs(User::first());
    session(['company' => Company::first()]);
});

it('user should be deleted', function () {
    $user = User::factory()->create();
    $this->assertDatabaseHas('users', [
        'name' => $user->name,
        'cpf' => $user->cpf,
    ]);

    $response = $this->delete(route('user.destroy', $user));

    $this->assertDatabaseMissing('users', [
        'cpf' => $user->cpf,
    ]);

});
