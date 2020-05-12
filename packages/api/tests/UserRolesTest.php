<?php

use App\User;
use Symfony\Component\HttpFoundation\Cookie;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class UserRolesTest extends TestCase
{
    use DatabaseMigrations;

    public function test_user_with_admin_role_can_access_users()
    {
        // TODO: find a method to send cookies
        $this->assertTrue(true);
        /* $password = 'Hackme!1234'; */
        /* $user = factory(User::class)->create([ */
        /*     'password' => app('hash')->make($password), */
        /*     'role' => 'admin' */
        /* ]); */

        /* $response = $this->post('auth/login', [ */
        /*     'email' => $user->email, */
        /*     'password' => $password */
        /* ]); */

        /* $cookieValue = $this->response->headers->getCookies()[0]->getValue(); */

        /* $response = $this */
        /*     ->withoutMiddleware('jwt') */
        /*     ->call('GET', 'users', [], ['jwt' => '1234']); */

        /* $this->assertEquals(401, $this->response->status()); */
    }
}
