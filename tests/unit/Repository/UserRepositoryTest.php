<?php

namespace Test\Unit\Repository;

use App\Model\User;
use App\Repository\UserRepository;
use Test\Traits\RunsMigrations;

class UserRepositoryTest extends \PHPUnit_Framework_TestCase
{
    use RunsMigrations;

    /**
     * @var UserRepository
     */
    private $repository;

    public function setUp()
    {
        $this->repository = new UserRepository();
    }


    public function test_finding_by_id()
    {
        $user  = factory(User::class)->create(['email' => 'steve@example.com']);
        $found = $this->repository->findUserById($user->id);
        $this->assertEquals($user->id, $found->id);
    }

    /**
     * @expectedException     \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function test_failing_to_find_by_id()
    {
        $user = factory(User::class)->create(['email' => 'steve@example.com']);
        $this->repository->findUserById($user->id + 10);
    }

    public function test_finding_by_email()
    {
        $user  = factory(User::class)->create(['email' => 'steve@example.com']);
        $found = $this->repository->findUserByEmail($user->email);
        $this->assertEquals($user->id, $found->id);

        $found = $this->repository->findUserByEmail('bob@example.com');
        $this->assertNull($found);
    }

    public function test_email_address_unique_check()
    {
        $user  = factory(User::class)->create(['email' => 'steve@example.com']);
        $email = 'bob@example.com';
        $this->assertFalse($this->repository->emailAddressExists($email));
        $this->assertTrue($this->repository->emailAddressExists($user->email));
        $this->assertFalse($this->repository->emailAddressExists($user->email, $user->id));
    }

    public function test_password_verification()
    {
        $user = factory(User::class)->create(['password' => password_hash('password', PASSWORD_DEFAULT)]);
        $this->assertTrue($this->repository->passwordHashMatches($user->id, 'password'));
        $this->assertFalse($this->repository->passwordHashMatches($user->id, 'not-password'));
        $this->assertFalse($this->repository->passwordHashMatches($user->id + 10, 'password'));
    }
}
