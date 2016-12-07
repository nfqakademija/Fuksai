<?php

namespace Fuksai\tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class PictureControllerTest
 * @package Fuksai\tests\AppBundle\Controller
 */
class PictureControllerTest extends WebTestCase
{
    public function testPictureAction()
    {
        $client = static::createClient();

        // Crawler for first astronomy picture of the day
        $crawler = $client->request('GET', '/astronomy-picture-of-the-day/0');
        // Assert that the response is successful
        $this->assertTrue($client->getResponse()->isSuccessful());
        // Assert that there is exactly 1 logo tag with the class "logo-responsive" on the page
        $this->assertCount(1, $crawler->filter('logo.logo-responsive'));
    }

    public function testAllPicturesAction()
    {
        $client = static::createClient();

        // Crawler for first astronomy pictures of the day
        $crawler = $client->request('GET', '/astronomy-pictures-of-the-day');
        // Assert that the response is successful
        $this->assertTrue($client->getResponse()->isSuccessful());
        // Assert that there is at least one ul tag on the page
        $this->assertGreaterThan(
            0,
            $crawler->filter('ul')->count()
        );
    }
}
