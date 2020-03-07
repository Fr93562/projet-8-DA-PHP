<?php

namespace Tests\AppBundle\Other;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * tests de la navigation
 */
class NavigateTest extends WebTestCase
{

    /**
     * teste le lien du header
     */
    public function testLinkHome()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');       // homePage est accessible via get et /

        $link = $crawler->selectLink('To Do List app')->link();
        $crawler = $client->click($link);

        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame(1, $crawler->filter('html:contains("Bienvenue sur Todo List, l\'application vous permettant de gérer l\'ensemble de vos tâches sans effort !")')->count());
    }

    /**
     * teste les liens vers connexion et inscription
     */
    public function testLinkUser()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');       // homePage est accessible via get et /

        $link = $crawler->selectLink('Créer un compte')->link();
        $crawler = $client->click($link);

        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame("user_create", $request = $client->getRequest()->attributes->get('_route')); // récupère le nom de la route pour la comparer à l'attendu

        $link = $crawler->selectLink('Se connecter')->link();
        $crawler = $client->click($link);

        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame("login", $request = $client->getRequest()->attributes->get('_route')); // récupère le nom de la route pour la comparer à l'attendu
    }

    /**
     * teste les liens vers tasks done et undone
     */
    public function testLinkTaskList()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');       // homePage est accessible via get et /

        $link = $crawler->selectLink('Consulter la liste des tâches à faire')->link();
        $crawler = $client->click($link);

        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame("task_list", $request = $client->getRequest()->attributes->get('_route')); // récupère le nom de la route pour la comparer à l'attendu
    }

    /**
     * teste les liens vers tasks done et undone
     */
    public function testLinkTaskListUndone()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');       // homePage est accessible via get et /

        $link = $crawler->selectLink('Consulter la liste des tâches terminées')->link();
        $crawler = $client->click($link);

        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame("tasksfinished", $request = $client->getRequest()->attributes->get('_route')); // récupère le nom de la route pour la comparer à l'attendu
    }
}
