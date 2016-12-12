<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class DefaultControllerTest
 * @package Tests\AppBundle\Controller
 */
class DefaultControllerTest extends WebTestCase
{
    public function testIndexAction()
    {
        $client = static::createClient();

        // Crawler for homepage
        $crawler = $client->request('GET', '/');
        // Assert that the response is successful
        $this->assertTrue($client->getResponse()->isSuccessful());
        // Assert that there are exactly 2 header tags on the page
        $this->assertCount(1, $crawler->filter('header'));
        // Assert that the response content contains a string - "Our solar system"
        $this->assertContains('Our solar system', $client->getResponse()->getContent());
        // Assert that the response content contains a string - "Sky above us"
        $this->assertContains('Sky above us', $client->getResponse()->getContent());
        // Assert that the response content contains a string - "NEWS"
        $this->assertContains('NEWS', $client->getResponse()->getContent());
        // Assert that the response content contains a string - "APOD"
        $this->assertContains('APOD', $client->getResponse()->getContent());
        // Assert that the response content contains a string - "VIDEOS"
        $this->assertContains('VIDEOS', $client->getResponse()->getContent());
        // Assert that the response content contains a string - "ISS"
        $this->assertContains('ISS', $client->getResponse()->getContent());
        // Assert that the response content contains a string - "PLANETS"
        $this->assertContains('PLANETS', $client->getResponse()->getContent());
        // Assert that the response content contains a string - "EVENTS"
        $this->assertContains('EVENTS', $client->getResponse()->getContent());
    }
}
