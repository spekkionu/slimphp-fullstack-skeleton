<?php

namespace Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

use Golem\Auth\Authenticatable;

class Functional extends \Codeception\Module
{
    /**
     * Navigates to route
     *
     * @param string $name
     * @param array  $data
     * @param array  $queryParams
     * @param bool   $absolute
     *
     * @return mixed
     */
    public function amOnRoute($name, array $data = [], array $queryParams = [], $absolute = false)
    {
        $page = route($name, $data, $queryParams, $absolute);

        return $this->getModule('\Herloct\Codeception\Module\Slim')->amOnPage($page);
    }

    /**
     * @param Authenticatable $user
     */
    public function amLoggedInAs(Authenticatable $user)
    {
        auth()->login($user);
    }

    /**
     * Log out user
     */
    public function amNotLoggedIn()
    {
        auth()->logout();
    }

    /**
     * Asserts that user is logged in
     */
    public function seeIAmLoggedIn()
    {
        $this->assertTrue(auth()->loggedIn());
    }

    /**
     * Asserts that I am logged in as a specific user
     *
     * @param Authenticatable|string|int $user
     */
    public function seeIAmLoggedInAs($user)
    {
        $id = $user;
        if ($user instanceof Authenticatable) {
            $id = $user->getAuthId();
        }
        $auth = auth();
        $this->assertTrue($auth->loggedIn());
        $this->assertEquals($id, $auth->getUserId());
    }

    /**
     * Asserts that user is not logged in
     */
    public function seeIAmNotLoggedIn()
    {
        $this->assertFalse(auth()->loggedIn());
    }
}
