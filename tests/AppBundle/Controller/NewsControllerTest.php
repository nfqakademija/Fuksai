<?php

namespace Fuksai\tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class NewsControllerTest
 * @package Fuksai\tests\AppBundle\Controller
 */
class NewsControllerTest extends WebTestCase
{
    public function testNewsAction()
    {
        $client = static::createClient();

        // Crawler for news
        $crawler = $client->request('GET', '/news');
        // Assert that the response is successful
        $this->assertTrue($client->getResponse()->isSuccessful());
        // Assert that there are exactly 10 logo tag with the class "logo-responsive" on the page
        $this->assertCount(10, $crawler->filter('logo.logo-responsive'));
    }

    public function testPlanetArticlesAction()
    {
        $client = static::createClient();

        // Crawler for Mercury articles
        $crawler = $client->request('GET', '/planetArticles/Mercury');
        // Assert that the response is successful
        $this->assertTrue($client->getResponse()->isSuccessful());
        // Assert that there is at least one logo tag with the class "logo-responsive" on the page
        $this->assertGreaterThan(
            0,
            $crawler->filter('logo.logo-responsive')->count()
        );

        // Crawler for Venus articles
        $crawler = $client->request('GET', '/planetArticles/Venus');
        // Assert that the response is successful
        $this->assertTrue($client->getResponse()->isSuccessful());
        // Assert that there is at least one logo tag with the class "logo-responsive" on the page
        $this->assertGreaterThan(
            0,
            $crawler->filter('logo.logo-responsive')->count()
        );

        // Crawler for Earth articles
        $crawler = $client->request('GET', '/planetArticles/Earth');
        // Assert that the response is successful
        $this->assertTrue($client->getResponse()->isSuccessful());
        // Assert that there is at least one logo tag with the class "logo-responsive" on the page
        $this->assertGreaterThan(
            0,
            $crawler->filter('logo.logo-responsive')->count()
        );

        // Crawler for Mars articles
        $crawler = $client->request('GET', '/planetArticles/Mars');
        // Assert that the response is successful
        $this->assertTrue($client->getResponse()->isSuccessful());
        // Assert that there is at least one logo tag with the class "logo-responsive" on the page
        $this->assertGreaterThan(
            0,
            $crawler->filter('logo.logo-responsive')->count()
        );

        // Crawler for Jupiter articles
        $crawler = $client->request('GET', '/planetArticles/Jupiter');
        // Assert that the response is successful
        $this->assertTrue($client->getResponse()->isSuccessful());
        // Assert that there is at least one logo tag with the class "logo-responsive" on the page
        $this->assertGreaterThan(
            0,
            $crawler->filter('logo.logo-responsive')->count()
        );

        // Crawler for Saturn articles
        $crawler = $client->request('GET', '/planetArticles/Saturn');
        // Assert that the response is successful
        $this->assertTrue($client->getResponse()->isSuccessful());
        // Assert that there is at least one logo tag with the class "logo-responsive" on the page
        $this->assertGreaterThan(
            0,
            $crawler->filter('logo.logo-responsive')->count()
        );

        // Crawler for Uranus articles
        $crawler = $client->request('GET', '/planetArticles/Uranus');
        // Assert that the response is successful
        $this->assertTrue($client->getResponse()->isSuccessful());
        // Assert that there is at least one logo tag with the class "logo-responsive" on the page
        $this->assertGreaterThan(
            0,
            $crawler->filter('logo.logo-responsive')->count()
        );

        // Crawler for Neptune articles
        $crawler = $client->request('GET', '/planetArticles/Neptune');
        // Assert that the response is successful
        $this->assertTrue($client->getResponse()->isSuccessful());
        // Assert that there is at least one logo tag with the class "logo-responsive" on the page
        $this->assertGreaterThan(
            0,
            $crawler->filter('logo.logo-responsive')->count()
        );

        // Crawler for Pluto articles
        $crawler = $client->request('GET', '/planetArticles/Pluto');
        // Assert that the response is successful
        $this->assertTrue($client->getResponse()->isSuccessful());
        // Assert that there is at least one logo tag with the class "logo-responsive" on the page
        $this->assertGreaterThan(
            0,
            $crawler->filter('logo.logo-responsive')->count()
        );
    }

    public function testArticleAction()
    {
        $client = static::createClient();

        // Crawler for 1 article
        $crawler = $client->request('GET', '/news/1');
        // Assert that the response is successful
        $this->assertTrue($client->getResponse()->isSuccessful());
        // Assert that there is exactly 1 h6 tag on the page
        $this->assertCount(1, $crawler->filter('h6'));
    }
}
