<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testHomepageEndpoint() {

        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }

}