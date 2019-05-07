<?php

namespace App\Tests\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class HomeControllerTest extends WebTestCase
{
    public function testHomepage()
    {
        $client = static::createClient();
        $client->request('GET', '/home');

        $crawler = $client->request('GET', '/home');
        
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Parce', $crawler->filter('h2')->text());
    }
}