<?php
/**
 * Created by PhpStorm.
 * User: arnas
 * Date: 16.11.14
 * Time: 18.51
 */

namespace AppBundle\Command;

use AppBundle\Entity\Planet;
use AppBundle\Entity\Article;
use AppBundle\Entity\PlanetArticle;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use AppBundle\Repository\PlanetRepository;
use AppBundle\Repository\ArticleRepository;
use AppBundle\Repository\PlanetArticleRepository;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class ImportNewsCommand
 * @package Fuksai\src\AppBundle\Command
 */
class ImportNewsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:import:news')
            ->setDescription('Import astronomy news.')
            ->setHelp('This command finds and imports astronomy news in the website.');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $astronomyNews = $this->getArticles();

        $planetsNames = $this->getPlanetsNames();

        $this->createNewArticles($astronomyNews, $planetsNames);

        $output->writeln('All astronomy news were inserted!');
    }

    /**
     * @return array
     */
    private function getPlanetsNames()
    {
        $planets = $this->getContainer()
            ->get('doctrine')
            ->getRepository('AppBundle:Planet')
            ->createQueryBuilder('planet')
            ->select('planet.name')
            ->getQuery()
            ->execute();

        $planetsNames = [];

        foreach ($planets as $planet) {
            $planetsNames[] = $planet['name'];
        }

        return $planetsNames;
    }


    private function getArticles()
    {
        $links = $this->getArticlesLinks();

        $articles = [];

        foreach ($links as $link) {

            $articles = $this->getArticle($link->getUri());

        }

        return $articles;
    }

    /**
     * @return \Symfony\Component\DomCrawler\Link[]
     */
    private function getArticlesLinks()
    {
        // string url converted to html
        $html = file_get_contents('https://astronomynow.com/category/news/');

        $crawler = new Crawler($html, 'https');

        // array of the links to the astronomy articles
        $links = $crawler->filter('article > div > header > h3 > a')->links();

        return $links;
    }

    /**
     * @param $link
     * @return array
     */
    private function getArticle($link)
    {
        // string url converted to html
        $html = file_get_contents($link);

        $article = [];

        $crawler = new Crawler($html, 'https');

        $article['url'] = $link;
        $article['title'] = $crawler->filter('header > h1')->text();
        $article['author'] = $crawler->filter('header > p > span > a.fn')->text();
        $article['publishDate'] = $crawler->filter('header > p > span > a')->text();

        $article['urlToImage'] = $crawler->selectImage($crawler->filter(''.
           'article img')->eq(0)->attr('alt'))->image()->getUri();

        $article['description'] = $crawler->filter('div.entry-content')->text();

        $imageCaptions = $crawler->filter('figcaption')->each(function (Crawler $node) {
            return $node->text();
        });

        // remove image captions of the description from the article
        foreach ($imageCaptions as $imageCaption) {
            $article['description'] = str_replace($imageCaption, "", $article['description']);
        }


        return $article;
    }

    /**
     * @param $astronomyNews
     * @param $planetsNames
     */
    private function createNewArticles($astronomyNews, $planetsNames)
    {
        $repository = 'AppBundle:Article';

        // go through all got astronomical news, check if article exists in DB and create one if it does not exist
        foreach ($astronomyNews as $astronomyArticle) {

            if (!$this->checkArticleExistence($astronomyArticle, $repository)) {
                $newArticle = $this->createArticle($astronomyArticle, $planetsNames);
                $this->insertNewArticleToDB($newArticle);
            }
        }
    }

    /**
     * @param $article
     * @param $planetsNames
     * @return Article
     */
    private function createArticle($article, $planetsNames)
    {
        $newArticle = new Article();

        $newArticle->setAuthor($article['author']);
        $newArticle->setTitle($article['title']);
        $newArticle->setDescription($article['description']);
        $newArticle->setUrl($article['url']);
        $newArticle->setUrlToImage($article['urlToImage']);
        $newArticle->setPublishStringDate($article['publishDate']);

        // go through all planet names and check if found planet name in title or description
        // then set found planet name to new article otherwise set empty string
        foreach ($planetsNames as $planetName) {
            if (preg_match('/\b'.$planetName.'\b/i', $article[''.
                'title']) || preg_match('/\b'. $planetName .
                    '\b/i', $article['description'])) {
                $newArticle->setPlanet($planetName);
            } else {
                $newArticle->setPlanet("");
            }
        }
        return $newArticle;
    }

    /**
     * @param $newArticle
     */
    private function insertNewArticleToDB($newArticle)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $em->persist($newArticle);
        $em->flush();
    }

    /**
     * @param $newArticle
     * @param $repository
     * @return bool
     */
    private function checkArticleExistence($newArticle, $repository)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();

        // article got by article title
        $oldArticle = $em->getRepository($repository)
            ->findOneBytitle($newArticle['title']);

        if (!empty($oldArticle)) {
            return true;
        }

        return false;
    }
}
