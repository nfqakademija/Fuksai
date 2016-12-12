<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class SubscribeControllerTest
 * @package Tests\AppBundle\Controller
 */
class SubscribeControllerTest extends WebTestCase
{
    public function testIndexAction()
    {
        $client = static::createClient();

        // Crawler for subscribe page
        $client->request('GET', '/subscribe');
        // Assert that the response is successful
        $this->assertTrue($client->getResponse()->isSuccessful());
    }
}
