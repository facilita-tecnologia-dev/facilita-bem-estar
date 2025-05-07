<?php

use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\DB;

beforeEach(function () {
    $this->actingAs(User::first());
    session(['company' => Company::first()]);
});


it('show page and form to create user', function(){
    $response = $this->get(route('user.create'));

    $response->assertOk();
    $response->assertViewIs('private.users.create');
    $response->assertSeeText('Colaborador | Criar');
});

/* --------------------------------------------- */
it('name should be required', function(){
    $response = $this->post(route('user.store'), [
        'name' => '',
        'cpf' => '222.333.444-05',
        'birth_date' => '2009-05-06',
        'gender' => 'Masculino',
        'department' => 'Financeiro',
        'occupation' => 'Financeiro I',
        'admission' => '2025-04-29',
        'role' => 'Gestor Interno'
    ]);
    
    $response->assertSessionHasErrors(['name' => __('validation.required', ['attribute' => 'name'])]);
});

it('name should have a minimum of 5 characters', function(){
    $response = $this->post(route('user.store'), [
        'name' => 'a',
        'cpf' => '123.456.789-09',
        'birth_date' => '2009-05-06',
        'gender' => 'Masculino',
        'department' => 'Financeiro',
        'occupation' => 'Financeiro I',
        'admission' => '2025-04-29',
        'role' => 'Gestor Interno'
    ]);

    $response->assertSessionHasErrors(['name' => __('validation.min.string', ['attribute' => 'name', 'min' => 5])]);
});

it('name should have a maximum of 255 characters', function(){
    $response = $this->post(route('user.store'), [
        'name' => str_repeat('*', 256),
        'cpf' => '123.456.789-09',
        'birth_date' => '2009-05-06',
        'gender' => 'Masculino',
        'department' => 'Financeiro',
        'occupation' => 'Financeiro I',
        'admission' => '2025-04-29',
        'role' => 'Gestor Interno'
    ]);

    $response->assertSessionHasErrors(['name' => __('validation.max.string', ['attribute' => 'name', 'max' => 255])]);
});
/* --------------------------------------------- */
it('cpf should be required', function(){
    $response = $this->post(route('user.store'), [
        'name' => 'Joe Doe',
        'cpf' => '',
        'birth_date' => '2009-05-06',
        'gender' => 'Masculino',
        'department' => 'Financeiro',
        'occupation' => 'Financeiro I',
        'admission' => '2025-04-29',
        'role' => 'Gestor Interno'
    ]);

    $response->assertSessionHasErrors(['cpf' => __('validation.required', ['attribute' => 'cpf'])]);
});

it('cpf should be unique on users table', function(){
    $response = $this->post(route('user.store'), [
        'name' => 'Joe Doe',
        'cpf' => '222.333.444-05',
        'birth_date' => '2009-05-06',
        'gender' => 'Masculino',
        'department' => 'Financeiro',
        'occupation' => 'Financeiro I',
        'admission' => '2025-04-29',
        'role' => 'Gestor Interno'
    ]);

    $response->assertSessionHasErrors(['cpf' => __('validation.unique', ['attribute' => 'cpf', 'unique' => 'users'])]);
});

it('cpf should be a valid formatted CPF', function(){
    $response = $this->post(route('user.store'), [
        'name' => 'Joe Doe',
        'cpf' => '12345678909',
        'birth_date' => '2009-05-06',
        'gender' => 'Masculino',
        'department' => 'Financeiro',
        'occupation' => 'Financeiro I',
        'admission' => '2025-04-29',
        'role' => 'Gestor Interno'
    ]);

    $response->assertSessionHasErrors(['cpf' => "O CPF deve estar no formato 000.000.000-00."]);
});

it('cpf should be a valid CPF', function(){
    $response = $this->post(route('user.store'), [
        'name' => 'Joe Doe',
        'cpf' => '245.765.987-01',
        'birth_date' => '2009-05-06',
        'gender' => 'Masculino',
        'department' => 'Financeiro',
        'occupation' => 'Financeiro I',
        'admission' => '2025-04-29',
        'role' => 'Gestor Interno'
    ]);

    $response->assertSessionHasErrors(['cpf' => "O CPF informado é inválido."]);
});
/* --------------------------------------------- */
it('birth date should be required', function(){
    $response = $this->post(route('user.store'), [
        'name' => 'Joe Doe',
        'cpf' => '123.456.789-09',
        'birth_date' => '',
        'gender' => 'Masculino',
        'department' => 'Financeiro',
        'occupation' => 'Financeiro I',
        'admission' => '2025-04-29',
        'role' => 'Gestor Interno'
    ]);

    $response->assertSessionHasErrors(['birth_date' => __('validation.required', ['attribute' => 'birth date'])]);
});

it('birth date should be before or equal 16 years ago', function(){
    $response = $this->post(route('user.store'), [
        'name' => 'Joe Doe',
        'cpf' => '123.456.789-09',
        'birth_date' => '2029-05-06',
        'gender' => 'Masculino',
        'department' => 'Financeiro',
        'occupation' => 'Financeiro I',
        'admission' => '2025-04-29',
        'role' => 'Gestor Interno'
    ]);

    $response->assertSessionHasErrors(['birth_date' => __('validation.before_or_equal', ['attribute' => 'birth date', 'date' => now()->subYears(16)->format('Y-m-d')])]);
});

it('birth date should be after 100 years ago', function(){
    $response = $this->post(route('user.store'), [
        'name' => 'Joe Doe',
        'cpf' => '123.456.789-09',
        'birth_date' => '1909-05-06',
        'gender' => 'Masculino',
        'department' => 'Financeiro',
        'occupation' => 'Financeiro I',
        'admission' => '2025-04-29',
        'role' => 'Gestor Interno'
    ]);

    $response->assertSessionHasErrors(['birth_date' => __('validation.after', ['attribute' => 'birth date', 'date' => now()->subCenturies(1)->format('Y-m-d')])]);
});
/* --------------------------------------------- */
it('admission should be required', function(){
    $response = $this->post(route('user.store'), [
        'name' => 'Joe Doe',
        'cpf' => '123.456.789-09',
        'birth_date' => '2009-04-23',
        'gender' => 'Masculino',
        'department' => 'Financeiro',
        'occupation' => 'Financeiro I',
        'admission' => '',
        'role' => 'Gestor Interno'
    ]);

    $response->assertSessionHasErrors(['admission' => __('validation.required', ['attribute' => 'admission'])]);
});

it('admission should be before or equal today', function(){
    $response = $this->post(route('user.store'), [
        'name' => 'Joe Doe',
        'cpf' => '123.456.789-09',
        'birth_date' => '2009-04-23',
        'gender' => 'Masculino',
        'department' => 'Financeiro',
        'occupation' => 'Financeiro I',
        'admission' => '2029-04-23',
        'role' => 'Gestor Interno'
    ]);
    
    $response->assertSessionHasErrors(['admission' => __('validation.before_or_equal', ['attribute' => 'admission', 'date' => today()->format('Y-m-d')])]);
});

it('admission should be after 100 years ago', function(){
    $response = $this->post(route('user.store'), [
        'name' => 'Joe Doe',
        'cpf' => '123.456.789-09',
        'birth_date' => '2009-04-23',
        'gender' => 'Masculino',
        'department' => 'Financeiro',
        'occupation' => 'Financeiro I',
        'admission' => '1905-04-29',
        'role' => 'Gestor Interno'
    ]);

    $response->assertSessionHasErrors(['admission' => __('validation.after', ['attribute' => 'admission', 'date' => now()->subCenturies(1)->format('Y-m-d')])]);
});
/* --------------------------------------------- */
it('gender should be required', function(){
    $response = $this->post(route('user.store'), [
        'name' => 'Joe Doe',
        'cpf' => '123.456.789-09',
        'birth_date' => '2009-04-23',
        'gender' => '',
        'department' => 'Financeiro',
        'occupation' => 'Financeiro I',
        'admission' => '2009-04-12',
        'role' => 'Gestor Interno'
    ]);

    $response->assertSessionHasErrors(['gender' => __('validation.required', ['attribute' => 'gender'])]);
});

it('gender should be a valid gender', function(){
    $response = $this->post(route('user.store'), [
        'name' => 'Joe Doe',
        'cpf' => '123.456.789-09',
        'birth_date' => '2009-04-23',
        'gender' => 'aaaa',
        'department' => 'Financeiro',
        'occupation' => 'Financeiro I',
        'admission' => '2009-04-12',
        'role' => 'Gestor Interno'
    ]);

    $response->assertSessionHasErrors(['gender' => __('validation.enum', ['attribute' => 'gender'])]);
});
/* --------------------------------------------- */
it('department should be required', function(){
    $response = $this->post(route('user.store'), [
        'name' => 'Joe Doe',
        'cpf' => '123.456.789-09',
        'birth_date' => '2009-04-23',
        'gender' => 'Masculino',
        'department' => '',
        'occupation' => 'Financeiro I',
        'admission' => '2009-04-12',
        'role' => 'Gestor Interno'
    ]);

    $response->assertSessionHasErrors(['department' => __('validation.required', ['attribute' => 'department'])]);
});

it('department should have a maximum of 255 characters', function(){
    $response = $this->post(route('user.store'), [
        'name' => 'Joe Doe',
        'cpf' => '123.456.789-09',
        'birth_date' => '2009-04-23',
        'gender' => 'Masculino',
        'department' => str_repeat('*', 256),
        'occupation' => 'Financeiro I',
        'admission' => '2009-04-12',
        'role' => 'Gestor Interno'
    ]);

    $response->assertSessionHasErrors(['department' => __('validation.max.string', ['attribute' => 'department', 'max' => 255])]);
});
/* --------------------------------------------- */
it('occupation should be required', function(){
    $response = $this->post(route('user.store'), [
        'name' => 'Joe Doe',
        'cpf' => '123.456.789-09',
        'birth_date' => '2009-04-23',
        'gender' => 'Masculino',
        'department' => 'Financeiro',
        'occupation' => '',
        'admission' => '2009-04-12',
        'role' => 'Gestor Interno'
    ]);

    $response->assertSessionHasErrors(['occupation' => __('validation.required', ['attribute' => 'occupation'])]);
});

it('occupation should have a maximum of 255 characters', function(){
    $response = $this->post(route('user.store'), [
        'name' => 'Joe Doe',
        'cpf' => '123.456.789-09',
        'birth_date' => '2009-04-23',
        'gender' => 'Masculino',
        'department' => 'Financeiro',
        'occupation' => str_repeat('*', 256),
        'admission' => '2009-04-12',
        'role' => 'Gestor Interno'
    ]);

    $response->assertSessionHasErrors(['occupation' => __('validation.max.string', ['attribute' => 'occupation', 'max' => 255])]);
});
/* --------------------------------------------- */
it('role should be required', function(){
    $response = $this->post(route('user.store'), [
        'name' => 'Joe Doe',
        'cpf' => '123.456.789-09',
        'birth_date' => '2009-04-23',
        'gender' => 'Masculino',
        'department' => 'Financeiro',
        'occupation' => 'Financeiro I',
        'admission' => '2009-04-12',
        'role' => ''
    ]);

    $response->assertSessionHasErrors(['role' => __('validation.required', ['attribute' => 'role'])]);
});

it('role should be a valid role', function(){
    $response = $this->post(route('user.store'), [
        'name' => 'Joe Doe',
        'cpf' => '123.456.789-09',
        'birth_date' => '2009-04-23',
        'gender' => 'Masculino',
        'department' => 'Financeiro',
        'occupation' => 'Financeiro I',
        'admission' => '2009-04-12',
        'role' => 'aaaa'
    ]);

    $response->assertSessionHasErrors(['role' => __('validation.enum', ['attribute' => 'role'])]);
});