<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * tests du defaultController
 */
class UserControllerTest extends WebTestCase
{
    /**
     * vérifie l'accès de la liste user pour un user anonyme
     */
    public function testUsersAnonyme()
    {
        $client = static::createClient();
        $client->request('GET', '/users');
        $client->followRedirect();

        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame("login", $request = $client->getRequest()->attributes->get('_route')); // récupère le nom de la route pour la comparer à l'attendu
    }

    /**
     * vérifie l'accès de la liste pour un user authentifié en tant que ROLE_USER
     * l'user authentifié n'est pas le créateur de la task
     */
    public function testUserRoleUser()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'testUser',
            'PHP_AUTH_PW'   => 'testUser',
        ]);
        $client->request('GET', '/users');

        $this->assertSame(403, $client->getResponse()->getStatusCode());
        $this->assertSame("user_list", $request = $client->getRequest()->attributes->get('_route')); // récupère le nom de la route pour la comparer à l'attendu
    }

    /**
     * vérifie l'accès de la liste task pour un user authentifié en tant que ADMIN_USER
     * l'user authentifié n'est pas le créateur de la task
     */
    public function testUserRoleAdmin()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'test',
            'PHP_AUTH_PW'   => 'test',
        ]);
        $crawler = $client->request('GET', '/users');

        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame("user_list", $request = $client->getRequest()->attributes->get('_route')); // récupère le nom de la route pour la comparer à l'attendu
        $this->assertSame(1, $crawler->filter('html:contains("To Do List app")')->count());
    }

    /**
     * vérifie l'accès de create user
     */
    public function testCreateUserEndpoint()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/users/create');

        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame("user_create", $request = $client->getRequest()->attributes->get('_route')); // récupère le nom de la route pour la comparer à l'attendu
        $this->assertSame(1, $crawler->filter('html:contains("To Do List app")')->count());
    }

    /**
     * teste le formulaire de création de user avec le ROLE_USER
     */
    public function testCreateUserIsSubmited()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/users/create');

        $form = $crawler->selectButton('Ajouter')->form(['user[username]' => 'usertesté',
                                                        'user[role]' => 'ROLE_USER',
                                                        'user[password]' => 'passwordtesté',
                                                        'user[email]' => 'emailtesté@hotmail.fr'
                                                        ]);

        $crawler = $client->submit($form);

        //$this->assertSame( "titre testé" , $request = $client->getRequest()->get('task[title]'));
        $this->assertSame('user_create', $request = $client->getRequest()->attributes->get('_route'));
    }

    /**
     * teste le formulaire de création de user avec le ROLE_ADMIN
     */
    public function testCreateUserIsSubmitedAdmin()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/users/create');

        $form = $crawler->selectButton('Ajouter')->form(['user[username]' => 'admintesté',
                                                        'user[role]' => 'ROLE_ADMIN',
                                                        'user[password]' => 'passwordadmintesté',
                                                        'user[email]' => 'emailadmin@hotmail.fr'
                                                        ]);

        $crawler = $client->submit($form);

        //$this->assertSame( "titre testé" , $request = $client->getRequest()->get('task[title]'));
        $this->assertSame('user_create', $request = $client->getRequest()->attributes->get('_route'));
    }

    /**
     * vérifie l'accès de update user pour un user anonyme
     */
    public function testUserEditAnonyme()
    {
        $client = static::createClient();
        $client->request('GET', '/users/1/edit');
        $client->followRedirect();

        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame("login", $request = $client->getRequest()->attributes->get('_route')); // récupère le nom de la route pour la comparer à l'attendu
    }

    /**
     * vérifie l'accès de update user pour un user authentifié
     */
    public function testUserEditAuthentified()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'testUser',
            'PHP_AUTH_PW'   => 'testUser',
        ]);
        $crawler = $client->request('GET', '/users/1/edit');

        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame("user_edit", $request = $client->getRequest()->attributes->get('_route')); // récupère le nom de la route pour la comparer à l'attendu
        $this->assertSame(1, $crawler->filter('html:contains("To Do List app")')->count());
    }
}
