<?php

namespace Tests\AppBundle\Entity;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\User;

/**
 * tests de l'entity User
 */
class UserTest extends WebTestCase
{

    /**
     * vérifie l'instanciation de l'objet user
     */
    public function testInstanciationUser()
    {
        $user = new User();

        $this->assertSame("", $user->getUsername()); // attendu que user crée soit vide
    }

    /**
     * vérifie si role user est correctement récupéré (lié à l'array)
     */
    public function testRoleUser()
    {
        $user = new User();
        $user->setRoles(array("ROLE_USER"));

        $this->assertSame(array("ROLE_USER"), $user->getRoles()); // attendu que user crée remonte bien ROLE_USER
    }

    /**
     * vérifie si role admin possède ses deux roles
     */
    public function testRoleAdmin()
    {
        $user = new User();
        $user->setRoles(array("ROLE_ADMIN"));

        $this->assertSame(array("ROLE_ADMIN", "ROLE_USER"), $user->getRoles()); // attendu que user  crée remonte bien ROLE_USER et ROLE_ADMIN
    }
}
