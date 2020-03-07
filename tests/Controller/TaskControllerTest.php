<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * tests du defaultController
 */
class TaskControllerTest extends WebTestCase
{

    /**
     * vérifie l'existence du endpoint de tasklist undone
     */
    public function testTaskUndoneEndpoint()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/tasks');

        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame(1, $crawler->filter('html:contains("To Do List app")')->count());
    }

    /**
     * vérifie l'existence du endpoint de tasklist done
     */
    public function testTaskDoneEndpoint()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/tasksfinished');

        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame(1, $crawler->filter('html:contains("To Do List app")')->count());
    }

    /**
     * vérifie l'accès de create task pour un user anonyme
     */
    public function testCreateAnonyme()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/tasks/create');
        $client->followRedirect();

        $this->assertSame("login", $request = $client->getRequest()->attributes->get('_route')); // récupère le nom de la route pour la comparer à l'attendu
    }

    /**
     * vérifie l'accès de create task pour un user authentifié
     */
    public function testCreateAuthentified()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'test',
            'PHP_AUTH_PW'   => 'test',
        ]);
        $crawler = $client->request('GET', '/tasks/create');

        $this->assertSame("task_create", $request = $client->getRequest()->attributes->get('_route')); // récupère le nom de la route pour la comparer à l'attendu
        $this->assertSame(1, $crawler->filter('html:contains("To Do List app")')->count());
    }

    /**
     * vérifie le formulaire création de task avec la soumission
     */
    public function testCreateFormIsSubmited()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'test',
            'PHP_AUTH_PW'   => 'test',
        ]);
        $crawler = $client->request('GET', '/tasks/create');

        $form = $crawler->selectButton('Ajouter')->form(['task[title]' => 'titre testé',
                                                        'task[content]' => 'contenu testé'
                                                        ]);

        $crawler = $client->submit($form);
        $client->followRedirect();

        //$this->assertSame( "titre testé" , $request = $client->getRequest()->get('task[title]'));
        $this->assertSame('task_list', $request = $client->getRequest()->attributes->get('_route'));
    }

    /**
     * vérifie l'accès de update task pour un user anonyme
     */
    public function testUpdateeAnonyme()
    {
        $client = static::createClient();
        $client->request('GET', '/tasks/1/edit');
        $client->followRedirect();

        $this->assertSame("login", $request = $client->getRequest()->attributes->get('_route')); // récupère le nom de la route pour la comparer à l'attendu
    }

    /**
     * vérifie l'accès de update task pour un user authentifié
     */
    public function testUpdateAuthentified()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'test',
            'PHP_AUTH_PW'   => 'test',
        ]);
        $crawler = $client->request('GET', '/tasks/1/edit');

        $this->assertSame("task_edit", $request = $client->getRequest()->attributes->get('_route')); // récupère le nom de la route pour la comparer à l'attendu
        $this->assertSame(1, $crawler->filter('html:contains("To Do List app")')->count());
    }

    /**
     * vérifie l'accès de toggle task pour un user anonyme
     */
    public function testToggleeAnonyme()
    {
        $client = static::createClient();
        $client->request('GET', '/tasks/1/toggle');
        $client->followRedirect();

        $this->assertSame("login", $request = $client->getRequest()->attributes->get('_route')); // récupère le nom de la route pour la comparer à l'attendu
    }

    /**
     * vérifie l'accès de toggle task pour un user authentifié
     * l'user authentifié n'est pas le créateur de la task
     */
    public function testToggleeAuthentified()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'test',
            'PHP_AUTH_PW'   => 'test',
        ]);
        $client->request('GET', '/tasks/1/toggle');

        $this->assertSame("task_toggle", $request = $client->getRequest()->attributes->get('_route')); // récupère le nom de la route pour la comparer à l'attendu
    }

    /**
     * vérifie l'accès de toggle task pour un user authentifié
     * l'user authentifié n'est pas le créateur de la task
     */
    public function testToggleeAuthentifiedCreator()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'test',
            'PHP_AUTH_PW'   => 'test',
        ]);
        $client->request('GET', '/tasks/3/toggle');
        $client->followRedirect();

        $this->assertSame("task_list", $request = $client->getRequest()->attributes->get('_route')); // récupère le nom de la route pour la comparer à l'attendu
    }

    /**
     * vérifie l'accès de delete task pour un user anonyme
     */
    public function testDeleteAnonyme()
    {
        $client = static::createClient();
        $client->request('GET', '/tasks/1/delete');
        $client->followRedirect();

        $this->assertSame("login", $request = $client->getRequest()->attributes->get('_route')); // récupère le nom de la route pour la comparer à l'attendu
    }

    /**
     * vérifie l'accès de delete task pour un user authentifié
     */
    public function testDeleteAuthentified()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'test',
            'PHP_AUTH_PW'   => 'test',
        ]);
        $client->request('GET', '/tasks/1/delete');

        $this->assertSame("task_delete", $request = $client->getRequest()->attributes->get('_route')); // récupère le nom de la route pour la comparer à l'attendu
    }
}
