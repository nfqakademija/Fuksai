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
        $this->assertCount(2, $crawler->filter('header'));
        // Assert that there is exactly 1 span tag with the class "icon-prev" on the page
        $this->assertCount(1, $crawler->filter('span.icon-prev'));
        // Assert that there is exactly 1 span tag with the class "icon-next" on the page
        $this->assertCount(1, $crawler->filter('span.icon-next'));
    }
}
