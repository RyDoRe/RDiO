<?php

use App\User;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class AuthTest extends TestCase
{
    use DatabaseMigrations;

    public function test_user_can_login_with_correct_credentials()
    {
        $password = 'Hackme!1234';
        $user = factory(User::class)->create([
            'password' => app('hash')->make($password)
        ]);

        $response = $this->post('auth/login', [
            'email' => $user->email,
            'password' => $password
        ]);

        $response->seeJson([
            'name' => $user->name,
            'email' => $user->email
        ]);
    }

    public function test_user_cannot_login_with_incorrect_password()
    {
        $password = 'Hackme!1234';
        $user = factory(User::class)->create([
            'password' => app('hash')->make($password)
        ]);

        $response = $this->post('auth/login', [
            'email' => $user->email,
            'password' => 'invalid-password'
        ]);

        $response->seeJson([
            'error' => 'Email or password is wrong.'
        ]);
    }

    public function test_user_can_register_with_correct_inputs()
    {
        $user = factory(User::class)->make();
        $password = 'Hackme!1234';

        $response = $this->post('auth/register', [
            'name' => $user->name,
            'email' => $user->email,
            'password' => $password,
            'password_confirmation' => $password
        ]);


        $this->assertEquals(201, $this->response->status());
        $response->seeJsonStructure([
            'user',
            'message'
        ]);

        $this->seeInDatabase('users', ['name' => $user->name, 'email' => $user->email]);
    }

    public function test_user_cannot_register_with_already_taken_email()
    {
        $password = 'Hackme!1234';
        $user = factory(User::class)->create([
            'password' => app('hash')->make($password)
        ]);

        $response = $this->post('auth/register', [
            'name' => $user->name,
            'email' => $user->email,
            'password' => $password,
            'password_confirmation' => $password
        ]);

        $this->assertEquals(422, $this->response->status());
        $response->seeJson([
            'email' => [
                'The email has already been taken.'
            ]
        ]);
    }

    public function test_user_cannot_register_with_password_size_smaller_than_8()
    {
        $password = 'Hack!12';
        $this->wrongPasswordHelper($password, 'The password must be at least 8 characters.');
    }

    public function test_user_cannot_register_with_password_without_capital_letter()
    {
        $password = 'hackme!1234';
        $this->wrongPasswordHelper($password);
    }

    public function test_user_cannot_register_with_password_without_small_letter()
    {
        $password = 'HACKME!1234';
        $this->wrongPasswordHelper($password);
    }

    public function test_user_cannot_register_with_password_without_number()
    {
        $password = 'Hackmee!';
        $this->wrongPasswordHelper($password);
    }

    public function test_user_cannot_register_with_password_without_special_character()
    {
        $password = 'Hackme1234';
        $this->wrongPasswordHelper($password);
    }

    private function wrongPasswordHelper($password, $errorMessage = 'The password format is invalid.')
    {
        $user = factory(User::class)->make();

        $response = $this->post('auth/register', [
            'name' => $user->name,
            'email' => $user->email,
            'password' => $password,
            'password_confirmation' => $password
        ]);

        $this->assertEquals(422, $this->response->status());
        $response->seeJson([
            'password' => [
                $errorMessage
            ]
        ]);
    }
}
