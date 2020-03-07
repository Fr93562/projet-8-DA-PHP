<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * tests du securityController
 */
class SecurityControllerTest extends WebTestCase
{

    /**
     * vérifie l'existence du endpoint de login en testant la réponse http de la requête
     */
    public function testLoginActionEndpoint()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame(1, $crawler->filter('html:contains("Se connecter")')->count());
    }

    /**
     * teste le endpoint de login si aucune information est transmise au formulaire
     */
    public function testloginActionFormNoValue()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $form = $crawler->selectButton('Se connecter')->form();
        $crawler = $client->submit($form);

        $client->followRedirect();

        $this->assertSame(200, $client->getResponse()->getStatusCode()); // attendu d'être redirigé avec le code status 200
        $this->assertSame('login', $request = $client->getRequest()->attributes->get('_route'));
    }

    /**
     * teste le comportement de login si le password / user sont invalides
     */
    public function testloginActionFormErrorValue()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $form = $crawler->selectButton('Se connecter')->form(['_username' => 'Student1', '_password' => 'Student1']);

        $crawler = $client->submit($form);
        $client->followRedirect();

        $this->assertSame(200, $client->getResponse()->getStatusCode()); // attendu d'être redirigé avec le code status 200
        $this->assertSame('login', $request = $client->getRequest()->attributes->get('_route'));
    }

    /**
     * teste le comportement de login si le password / user sont valides
     */
    public function testloginAction()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $form = $crawler->selectButton('Se connecter')->form(['_username' => 'test', '_password' => 'test']);

        $crawler = $client->submit($form);
        $client->followRedirect();

        $this->assertSame(200, $client->getResponse()->getStatusCode()); // attendu d'être redirigé avec le code status 200
        $this->assertSame('homepage', $request = $client->getRequest()->attributes->get('_route'));
    }

    /**
     * teste la déconnexion d'un user
     */
    public function testlogout()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'test',
            'PHP_AUTH_PW'   => 'test',
        ]);

        $crawler = $client->request('GET', '/logout');
        $client->followRedirect();

        $this->assertSame(200, $client->getResponse()->getStatusCode()); // attendu d'être redirigé avec le code status 302 car redirection vers page d'accueil
        $this->assertSame('homepage', $request = $client->getRequest()->attributes->get('_route')); // attendu d'être redirigé avec le code status 302 car redirection vers page d'accueil
    }
}
