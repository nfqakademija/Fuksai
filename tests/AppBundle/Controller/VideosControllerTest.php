<?php

namespace Fuksai\tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class VideosControllerTest
 * @package Fuksai\tests\AppBundle\Controller
 */
class VideosControllerTest extends WebTestCase
{
    public function testViewingAllVideosAction()
    {
        $client = static::createClient();

        // Crawler for first page of all videos
        $crawler = $client->request('GET', '/videos/1');
        // Assert that the response is successful
        $this->assertTrue($client->getResponse()->isSuccessful());
        // Assert that there is at least one div tag with the class "youtube-player" on the page
        $this->assertGreaterThan(
            0,
            $crawler->filter('div.youtube-player')->count()
        );
    }

    public function testPlanetsAction()
    {
        $client = static::createClient();

        // Crawler for first page of Mercury videos
        $crawler = $client->request('GET', '/videos/Mercury/1');
        // Assert that the response is successful
        $this->assertTrue($client->getResponse()->isSuccessful());
        // Assert that there is at least one div tag with the class "youtube-player" on the page
        $this->assertGreaterThan(
            0,
            $crawler->filter('div.youtube-player')->count()
        );

        // Crawler for first page of Venus videos
        $crawler = $client->request('GET', '/videos/Venus/1');
        // Assert that the response is successful
        $this->assertTrue($client->getResponse()->isSuccessful());
        // Assert that there is at least one div tag with the class "youtube-player" on the page
        $this->assertGreaterThan(
            0,
            $crawler->filter('div.youtube-player')->count()
        );

        // Crawler for first page of Earth videos
        $crawler = $client->request('GET', '/videos/Earth/1');
        // Assert that the response is successful
        $this->assertTrue($client->getResponse()->isSuccessful());
        // Assert that there is at least one div tag with the class "youtube-player" on the page
        $this->assertGreaterThan(
            0,
            $crawler->filter('div.youtube-player')->count()
        );

        // Crawler for first page of Mars videos
        $crawler = $client->request('GET', '/videos/Mars/1');
        // Assert that the response is successful
        $this->assertTrue($client->getResponse()->isSuccessful());
        // Assert that there is at least one div tag with the class "youtube-player" on the page
        $this->assertGreaterThan(
            0,
            $crawler->filter('div.youtube-player')->count()
        );

        // Crawler for first page of Jupiter videos
        $crawler = $client->request('GET', '/videos/Jupiter/1');
        // Assert that the response is successful
        $this->assertTrue($client->getResponse()->isSuccessful());
        // Assert that there is at least one div tag with the class "youtube-player" on the page
        $this->assertGreaterThan(
            0,
            $crawler->filter('div.youtube-player')->count()
        );

        // Crawler for first page of Saturn videos
        $crawler = $client->request('GET', '/videos/Saturn/1');
        // Assert that the response is successful
        $this->assertTrue($client->getResponse()->isSuccessful());
        // Assert that there is at least one div tag with the class "youtube-player" on the page
        $this->assertGreaterThan(
            0,
            $crawler->filter('div.youtube-player')->count()
        );

        // Crawler for first page of Uranus videos
        $crawler = $client->request('GET', '/videos/Uranus/1');
        // Assert that the response is successful
        $this->assertTrue($client->getResponse()->isSuccessful());
        // Assert that there is at least one div tag with the class "youtube-player" on the page
        $this->assertGreaterThan(
            0,
            $crawler->filter('div.youtube-player')->count()
        );

        // Crawler for first page of Neptune videos
        $crawler = $client->request('GET', '/videos/Neptune/1');
        // Assert that the response is successful
        $this->assertTrue($client->getResponse()->isSuccessful());
        // Assert that there is at least one div tag with the class "youtube-player" on the page
        $this->assertGreaterThan(
            0,
            $crawler->filter('div.youtube-player')->count()
        );

        // Crawler for first page of Pluto videos
        $crawler = $client->request('GET', '/videos/Pluto/1');
        // Assert that the response is successful
        $this->assertTrue($client->getResponse()->isSuccessful());
        // Assert that there is at least one div tag with the class "youtube-player" on the page
        $this->assertGreaterThan(
            0,
            $crawler->filter('div.youtube-player')->count()
        );
    }

    public function testChannelAction()
    {
        $client = static::createClient();

        // Crawler for first page of channel CrashCourse videos
        $crawler = $client->request('GET', 'videos/channel/CrashCourse/1');
        // Assert that the response is successful
        $this->assertTrue($client->getResponse()->isSuccessful());
        // Assert that there is at least one div tag with the class "youtube-player" on the page
        $this->assertGreaterThan(
            0,
            $crawler->filter('div.youtube-player')->count()
        );

        // Crawler for first page of channel SciShow videos
        $crawler = $client->request('GET', 'videos/channel/SciShow/1');
        // Assert that the response is successful
        $this->assertTrue($client->getResponse()->isSuccessful());
        // Assert that there is at least one div tag with the class "youtube-player" on the page
        $this->assertGreaterThan(
            0,
            $crawler->filter('div.youtube-player')->count()
        );

        // Crawler for first page of channel NasaTelevision videos
        $crawler = $client->request('GET', 'videos/channel/NasaTelevision/1');
        // Assert that the response is successful
        $this->assertTrue($client->getResponse()->isSuccessful());
        // Assert that there is at least one div tag with the class "youtube-player" on the page
        $this->assertGreaterThan(
            0,
            $crawler->filter('div.youtube-player')->count()
        );
    }
}
