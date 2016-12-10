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

        // Crawler for first page of the news
        $crawler = $client->request('GET', '/news/page/1');
        // Assert that the response is successful
        $this->assertTrue($client->getResponse()->isSuccessful());
        // Assert that there are exactly 10 img tag with the class "img-responsive" on the page
        $this->assertCount(10, $crawler->filter('img.img-responsive'));
    }

    public function testPlanetArticlesAction()
    {
        $client = static::createClient();

        // Crawler for first page of Mercury articles
        $crawler = $client->request('GET', '/planetArticles/Mercury/page/1');
        // Assert that the response is successful
        $this->assertTrue($client->getResponse()->isSuccessful());
        // Assert that there is at least one img tag with the class "img-responsive" on the page
        $this->assertGreaterThan(
            0,
            $crawler->filter('img.img-responsive')->count()
        );

        // Crawler for first page of Venus articles
        $crawler = $client->request('GET', '/planetArticles/Venus/page/1');
        // Assert that the response is successful
        $this->assertTrue($client->getResponse()->isSuccessful());
        // Assert that there is at least one img tag with the class "img-responsive" on the page
        $this->assertGreaterThan(
            0,
            $crawler->filter('img.img-responsive')->count()
        );

        // Crawler for first page of Earth articles
        $crawler = $client->request('GET', '/planetArticles/Earth/page/1');
        // Assert that the response is successful
        $this->assertTrue($client->getResponse()->isSuccessful());
        // Assert that there is at least one img tag with the class "img-responsive" on the page
        $this->assertGreaterThan(
            0,
            $crawler->filter('img.img-responsive')->count()
        );

        // Crawler for first page of Mars articles
        $crawler = $client->request('GET', '/planetArticles/Mars/page/1');
        // Assert that the response is successful
        $this->assertTrue($client->getResponse()->isSuccessful());
        // Assert that there is at least one img tag with the class "img-responsive" on the page
        $this->assertGreaterThan(
            0,
            $crawler->filter('img.img-responsive')->count()
        );

        // Crawler for first page of Jupiter articles
        $crawler = $client->request('GET', '/planetArticles/Jupiter/page/1');
        // Assert that the response is successful
        $this->assertTrue($client->getResponse()->isSuccessful());
        // Assert that there is at least one img tag with the class "img-responsive" on the page
        $this->assertGreaterThan(
            0,
            $crawler->filter('img.img-responsive')->count()
        );

        // Crawler for first page of Saturn articles
        $crawler = $client->request('GET', '/planetArticles/Saturn/page/1');
        // Assert that the response is successful
        $this->assertTrue($client->getResponse()->isSuccessful());
        // Assert that there is at least one img tag with the class "img-responsive" on the page
        $this->assertGreaterThan(
            0,
            $crawler->filter('img.img-responsive')->count()
        );

        // Crawler for first page of Uranus articles
        $crawler = $client->request('GET', '/planetArticles/Uranus/page/1');
        // Assert that the response is successful
        $this->assertTrue($client->getResponse()->isSuccessful());
        // Assert that there is at least one img tag with the class "img-responsive" on the page
        $this->assertGreaterThan(
            0,
            $crawler->filter('img.img-responsive')->count()
        );

        // Crawler for first page of Neptune articles
        $crawler = $client->request('GET', '/planetArticles/Neptune/page/1');
        // Assert that the response is successful
        $this->assertTrue($client->getResponse()->isSuccessful());
        // Assert that there is at least one img tag with the class "img-responsive" on the page
        $this->assertGreaterThan(
            0,
            $crawler->filter('img.img-responsive')->count()
        );

        // Crawler for first page of Pluto articles
        $crawler = $client->request('GET', '/planetArticles/Pluto/page/1');
        // Assert that the response is successful
        $this->assertTrue($client->getResponse()->isSuccessful());
        // Assert that there is at least one img tag with the class "img-responsive" on the page
        $this->assertGreaterThan(
            0,
            $crawler->filter('img.img-responsive')->count()
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
