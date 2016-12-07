<?php

namespace AppBundle\Command;

use AppBundle\Entity\Planet;
use AppBundle\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
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
            ->setHelp('This command finds astronomy news and imports them to the website.');
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
    private function getPlanetsNames(): array
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

    /**
     * @return array
     */
    private function getArticles(): array
    {
        // source of astronomy website news page
        $source = $this->getSourceOfWebsite('https://astronomynow.com/category/news/');

        $links = $this->getArticlesLinks($source);
        $images = $this->getArticlesImages($source);

        $articles = [];

        // article's image index
        $i = 0;

        foreach ($links as $link) {
            $articles[] = $this->getArticle($link->getUri(), $images[$i++]);
        }

        return $articles;
    }

    /**
     * @param string $source
     * @return \Symfony\Component\DomCrawler\Link[]
     */
    private function getArticlesLinks(string $source)
    {
        $crawler = new Crawler($source, 'https');

        // array of the links to the astronomy articles
        $links = $crawler->filter('article > div > header > h3 > a')->links();

        return $links;
    }

    /**
     * @param string $source
     * @return array
     */
    private function getArticlesImages(string $source)
    {
        $crawler = new Crawler($source, 'https');

        // array of the astronomy articles images
        $images = $crawler->filter('div.mh-loop-thumb > a > logo')->each(function (Crawler $node) {
            return $node->attr('src');
        });

        return $images;
    }

    /**
     * @param string $link
     * @param string $image
     * @return array
     */
    private function getArticle(string $link, string $image): array
    {
        $source = $this->getSourceOfWebsite($link);

        $article = [];

        $crawler = new Crawler($source, 'https');

        $article['url'] = $link;
        $article['urlToImage'] = $image;
        $article['title'] = $crawler->filter('header > h1')->text();
        $article['author'] = $crawler->filter('header > p > span > a.fn')->text();
        $article['publishDate'] = $crawler->filter('header > p > span > a')->text();
        $article['description'] = $this->getDescriptionWithoutImageCaptions($crawler);

        return $article;
    }

    /**
     * @param string $url
     * @return string
     */
    private function getSourceOfWebsite(string $url): string
    {
        return file_get_contents($url);
    }

    /**
     * @param Crawler $crawler
     * @return string
     */
    private function getDescriptionWithoutImageCaptions(Crawler $crawler): string
    {
        $articleDescription = $crawler->filter('div.entry-content')->text();

        $imageCaptions = $crawler->filter('figcaption')->each(function (Crawler $node) {
            return $node->text();
        });

        // remove image captions from the article description
        foreach ($imageCaptions as $imageCaption) {
            $articleDescription = str_replace($imageCaption, "", $articleDescription);
        }
        return $articleDescription;
    }

    /**
     * @param $astronomyNews
     * @param $planetsNames
     */
    private function createNewArticles(array $astronomyNews, array $planetsNames)
    {
        // go through all got astronomical news, check if article exists in DB and create one if it does not exist
        foreach ($astronomyNews as $astronomyArticle) {
            if (!$this->checkArticleExistence($astronomyArticle)) {
                $newArticle = $this->createArticle($astronomyArticle, $planetsNames);
                $this->insertNewArticleToDB($newArticle);
            }
        }
    }

    /**
     * @param array $article
     * @param array $planetsNames
     * @return Article
     */
    private function createArticle(array $article, array $planetsNames): Article
    {
        $newArticle = new Article();

        $newArticle->setAuthor($article['author']);
        $newArticle->setTitle($article['title']);
        $newArticle->setDescription($article['description']);
        $newArticle->setUrl($article['url']);
        $newArticle->setUrlToImage($article['urlToImage']);
        $newArticle->setPublishDateString($article['publishDate']);

        $planetName = $this->checkPlanetsNamesInArticle($article, $planetsNames);
        if (!empty($planetName)) {
            $newArticle->setPlanet($planetName);
        }

        return $newArticle;
    }

    /**
     * @param array $article
     * @param array $planetsNames
     * @return string
     */
    private function checkPlanetsNamesInArticle(array $article, array $planetsNames): string
    {
        // if found planet name in title or description then return planet name, otherwise empty string
        foreach ($planetsNames as $planetName) {
            if (preg_match('/\b'.$planetName.'\b/i', $article[''.
                'title']) || preg_match('/\b'. $planetName .
                    '\b/i', $article['description'])) {
                return $planetName;
            }
        }
        return '';
    }

    /**
     * @param Article $newArticle
     */
    private function insertNewArticleToDB(Article $newArticle)
    {
        $em = $this->getEntityManager();
        $em->persist($newArticle);
        $em->flush();
    }

    /**
     * @return mixed
     */
    private function getEntityManager()
    {
        return $this
            ->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    /**
     * @param array $newArticle
     * @return bool
     */
    private function checkArticleExistence(array $newArticle): bool
    {
        $em = $this->getContainer()->get('doctrine')->getManager();

        // article got by article title
        $oldArticle = $em->getRepository('AppBundle:Article')
            ->findOneBy(
                array(
                    'title' => $newArticle['title'],
                )
            );

        if (!empty($oldArticle)) {
            return true;
        }

        return false;
    }
}
