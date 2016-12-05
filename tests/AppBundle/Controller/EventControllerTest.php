<?php

namespace Fuksai\tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class EventControllerTest
 * @package Fuksai\tests\AppBundle\Controller
 */
class EventControllerTest extends WebTestCase
{
    public function testEventAction()
    {
        $client = static::createClient();

        // Crawler for events
        $crawler = $client->request('GET', '/events');
        // Assert that the response is successful
        $this->assertTrue($client->getResponse()->isSuccessful());
        // Assert that there are exactly 2 div tags with the class "container"
        $this->assertCount(2, $crawler->filter('div.container'));
    }
}
