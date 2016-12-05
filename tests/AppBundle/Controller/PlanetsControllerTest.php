<?php

namespace Fuksai\tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class PlanetsControllerTest
 * @package Fuksai\tests\AppBundle\Controller
 */
class PlanetsControllerTest extends WebTestCase
{
    public function testPlanetAction()
    {
        $client = static::createClient();

        // Crawler for Mercury planet
        $crawler = $client->request('GET', '/planets/Mercury');
        // Assert that the response is successful
        $this->assertTrue($client->getResponse()->isSuccessful());
        // Assert that there is at least one img tag with the class "img-responsive" on the page
        $this->assertGreaterThan(
            0,
            $crawler->filter('img.img-responsive')->count()
        );

        // Crawler for Venus planet
        $crawler = $client->request('GET', '/planets/Venus');
        // Assert that the response is successful
        $this->assertTrue($client->getResponse()->isSuccessful());
        // Assert that there is at least one img tag with the class "img-responsive" on the page
        $this->assertGreaterThan(
            0,
            $crawler->filter('img.img-responsive')->count()
        );

        // Crawler for Earth planet
        $crawler = $client->request('GET', '/planets/Earth');
        // Assert that the response is successful
        $this->assertTrue($client->getResponse()->isSuccessful());
        // Assert that there is at least one img tag with the class "img-responsive" on the page
        $this->assertGreaterThan(
            0,
            $crawler->filter('img.img-responsive')->count()
        );

        // Crawler for Mars planet
        $crawler = $client->request('GET', '/planets/Mars');
        // Assert that the response is successful
        $this->assertTrue($client->getResponse()->isSuccessful());
        // Assert that there is at least one img tag with the class "img-responsive" on the page
        $this->assertGreaterThan(
            0,
            $crawler->filter('img.img-responsive')->count()
        );

        // Crawler for Jupiter planet
        $crawler = $client->request('GET', '/planets/Jupiter');
        // Assert that the response is successful
        $this->assertTrue($client->getResponse()->isSuccessful());
        // Assert that there is at least one img tag with the class "img-responsive" on the page
        $this->assertGreaterThan(
            0,
            $crawler->filter('img.img-responsive')->count()
        );

        // Crawler for Saturn planet
        $crawler = $client->request('GET', '/planets/Saturn');
        // Assert that the response is successful
        $this->assertTrue($client->getResponse()->isSuccessful());
        // Assert that there is at least one img tag with the class "img-responsive" on the page
        $this->assertGreaterThan(
            0,
            $crawler->filter('img.img-responsive')->count()
        );

        // Crawler for Uranus planet
        $crawler = $client->request('GET', '/planets/Uranus');
        // Assert that the response is successful
        $this->assertTrue($client->getResponse()->isSuccessful());
        // Assert that there is at least one img tag with the class "img-responsive" on the page
        $this->assertGreaterThan(
            0,
            $crawler->filter('img.img-responsive')->count()
        );

        // Crawler for Neptune planet
        $crawler = $client->request('GET', '/planets/Neptune');
        // Assert that the response is successful
        $this->assertTrue($client->getResponse()->isSuccessful());
        // Assert that there is at least one img tag with the class "img-responsive" on the page
        $this->assertGreaterThan(
            0,
            $crawler->filter('img.img-responsive')->count()
        );

        // Crawler for Pluto planet
        $crawler = $client->request('GET', '/planets/Pluto');
        // Assert that the response is successful
        $this->assertTrue($client->getResponse()->isSuccessful());
        // Assert that there is at least one img tag with the class "img-responsive" on the page
        $this->assertGreaterThan(
            0,
            $crawler->filter('img.img-responsive')->count()
        );
    }

    public function testPlanetList()
    {
        $client = static::createClient();

        $client->request('GET', '/planet/list');

        // Assert that the response is successful
        $this->assertTrue($client->getResponse()->isSuccessful());
    }
}
