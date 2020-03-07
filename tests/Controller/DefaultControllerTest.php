<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * tests du defaultController
 */
class DefaultControllerTest extends WebTestCase
{

    /**
     * vérifie l'existence du endpoint/contenu html de homePage en testant la réponse http de la requête
     */
    public function testHomepageEndpoint()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');       // homePage est accessible via get et /

        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame(1, $crawler->filter('html:contains("Bienvenue sur Todo List, l\'application vous permettant de gérer l\'ensemble de vos tâches sans effort !")')->count());
    }
}
