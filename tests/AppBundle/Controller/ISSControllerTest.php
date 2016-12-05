<?php

namespace Fuksai\tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class ISSControllerTest
 * @package Fuksai\tests\AppBundle\Controller
 */
class ISSControllerTest extends WebTestCase
{
    public function testShowISS()
    {
        $client = static::createClient();

        // Crawler for space station
        $crawler = $client->request('GET', '/space_station');
        // Assert that the response is successful
        $this->assertTrue($client->getResponse()->isSuccessful());
        // Assert that there is exactly 1 iframe tag on the page
        $this->assertCount(1, $crawler->filter('iframe'));
    }
}
