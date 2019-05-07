<?php

namespace App\Tests\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class PackControllerTest extends WebTestCase
{
    public function testPack()
    {
        $client = static::createClient();
        $client->request('GET', '/listePack');

        $crawler = $client->request('GET', '/listePack');
        
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Nom', $crawler->filter('th')->text());
    }
}