<?php
/**
 * Created by PhpStorm.
 * User: bartosz
 */

namespace App\Tests\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    /**
     * @covers \App\Entity\User::__construct
     * @covers \App\Entity\User::getId
     * @covers \App\Entity\User::getUsername
     * @covers \App\Entity\User::getPassword
     * @covers \App\Entity\User::getRoles
     * @covers \App\Entity\User::setPassword
     * @covers \App\Entity\User::getSalt
     */
    public function testConstructGettersAndSetters()
    {

        $user = new User('561ef864-3b5f-4293-a411-4478f56d9178', 'test-user', 'secret-password');
        $this->assertSame('561ef864-3b5f-4293-a411-4478f56d9178', $user->getId());
        $this->assertSame('test-user', $user->getUsername());
        $this->assertSame('secret-password', $user->getPassword());
        $this->assertSame([User::ROLE_USER], $user->getRoles());
        $user->setPassword('new-password');
        $this->assertSame('new-password', $user->getPassword());

        $this->assertNull($user->getSalt());
    }

}
