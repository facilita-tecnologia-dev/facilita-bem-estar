<?php

use App\Models\Company;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    $this->actingAs(User::first());
    session(['company' => Company::first()]);
});

it('show page and form to import users', function () {
    $response = $this->get(route('user.import'));

    $response->assertOk();
    $response->assertViewIs('private.users.import');
});

it('file should be required', function () {
    $response = $this->post(route('user.import'), [
        'import_users' => '',
    ]);

    $response->assertSessionHasErrors(['import_users' => __('validation.required', ['attribute' => 'import users'])]);
});

it('users shoud be imported', function () {
    Storage::fake('local');

    $path = base_path('tests/Files/teste-tabela-funcionarios.xlsx');

    $file = new UploadedFile(
        $path,
        'teste-tabela-funcionarios.xlsx',
        'application/vnd.ms-excel',
        null,
        true // marca como "test file"
    );

    $response = $this->post(route('user.import'), [
        'import_users' => $file,
    ]);

    $this->assertDatabaseHas('users', [
        'cpf' => '123.456.789-01',
    ]);
});

it('file shoud be valid (.xlsx)', function () {
    Storage::fake('local');

    $file = UploadedFile::fake()->create('funcionarios.txt', 100);

    $response = $this->post(route('user.import'), [
        'import_users' => $file,
    ]);

    $response->assertSessionHasErrors(['import_users' => __('validation.mimetypes', ['attribute' => 'import users', 'values' => 'xlsx'])]);
});
