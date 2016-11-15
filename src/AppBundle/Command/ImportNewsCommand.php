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
            ->setDescription('Import news.')
            ->setHelp('This command finds and imports news in the website.');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        // all news got using API
        $news = $this->getAllNews();

        // array of astronomical keywords
        $keywords = [];
        $keywords[0] = "earth";
        $keywords[1] = "sun";
        $keywords[2] = "moon";
        $keywords[3] = "satellite";
        $keywords[4] = "meteor";
        $keywords[5] = "meteorite";
        $keywords[6] = "meteoroid";
        $keywords[7] = "moon";
        $keywords[8] = "planet";
        $keywords[9] = "astronomy";

        // astronomical news got using the keywords
        $astronomicalNews = $this->getAstronomicalNews($news, $keywords);

        /* if found zero astronomical articles then insert other articles to the
        database, otherwise insert astronomical articles */
        if ($astronomicalNews == []) {

            foreach ($news as $article) {

                $newArticle = $this->checkIfArticleExistAndCreateOne($article);
                $this->insertNewArticleToDB($newArticle);

                $output->writeln('Inserting "' . $article['title'] . '" article...');

            }
        } else {

            foreach ($astronomicalNews as $article) {

                $newArticle = $this->checkIfArticleExistAndCreateOne($article);
                $this->insertNewArticleToDB($newArticle);

                $output->writeln('Inserting "' . $article['title'] . '" article...');

            }

        }

        // all planets names got from our DB
        $planetsNames = $this->getPlanetsNames();

        // insert articles related to planet name to database
        foreach ($planetsNames as $planetName) {

            // article related to given planet name
            $article = $this->getArticle($news, $planetName);

            if ($article == null) {
                $output->writeln('Could not find article related to: '. $planetName);
                continue;
            }

            $newArticle = $this->checkIfPlanetArticleExistAndCreateOne($planetName, $article);
            $this->insertNewPlanetArticleToDB($newArticle);

            $output->writeln('Inserting "' . $newArticle['title'] . '" article... related to:' . $planetName);
        }

        $output->writeln('All news were inserted!');
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

    /**
     * @return null|array
     */
    private function getAllNews()
    {

        //$response = $this->getData("https://newsapi.org/v1/articles?source=new-scientist&sortBy".
        //    "=top&apiKey=c869ce160f3d4013b365336a2f9fa3f3");

        $response = $this->getData("https://newsapi.org/v1/articles?source=new-scientist&sortBy=".
            "top&apiKey=c869ce160f3d4013b365336a2f9fa3f3");

        if (isset($response['articles'])) {

            return $response['articles'];
        }



        return null;

    }

    /**
     * @param $news
     * @param $keywords
     * @return array
     */
    private function getAstronomicalNews($news, $keywords)
    {
        $astronomicalNews = [];

        foreach ($news as $article) {

            foreach ($keywords as $keyword) {

                // if found keyword in article's title or description then add that article to astronomicalNews array
                if (preg_match('/\b'.$keyword.'\b/i', $article[''.
                    'title']) || preg_match('/\b'. $keyword .'\b/i', $article['description'])) {
                    $astronomicalNews[] = $article;

                    // if found keyword in article then break out from foreach to checck a new article
                    break;
                }

            }


        }

        return $astronomicalNews;
    }

    /**
     * @param $astronomicalNews
     * @param $planetName
     * @return null|array
     */
    private function getArticle($astronomicalNews, $planetName)
    {

        foreach ($astronomicalNews as $article) {

            // if found planet name in article's title or description then return that article
            if (preg_match('/\b'.$planetName.'\b/i', $article[''.
                'title']) || preg_match('/\b'. $planetName .'\b/i', $article['description'])) {

                return $article;
            }


        }

        return null;

    }

    /**
     * @param $url
     * @return mixed
     */
    private function getData($url)
    {
        $json = file_get_contents($url);
        $data = json_decode($json, true);

        return $data;
    }

    /**
     * @param $article
     * @return Article
     */
    private function createArticle($article)
    {
        $newArticle = new Article();

        $newArticle->setAuthor($article['author']);
        $newArticle->setTitle($article['title']);
        $newArticle->setDescription($article['description']);
        $newArticle->setUrl($article['url']);
        $newArticle->setUrlToImage($article['urlToImage']);
        $newArticle->setPublishedAt($article['publishedAt']);

        return $newArticle;
    }

    /**
     * @param Article $newArticle
     */
    private function insertNewArticleToDB(Article $newArticle)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $em->persist($newArticle);
        $em->flush();

    }

    /**
     * @param $name
     * @param $planetArticle
     * @return PlanetArticle
     */
    private function createPlanetArticle($name, $planetArticle)
    {
        $newPlanetArticle = new PlanetArticle();

        $newPlanetArticle->setAuthor($planetArticle['author']);
        $newPlanetArticle->setTitle($planetArticle['title']);
        $newPlanetArticle->setDescription($planetArticle['description']);
        $newPlanetArticle->setUrl($planetArticle['url']);
        $newPlanetArticle->setUrlToImage($planetArticle['urlToImage']);
        $newPlanetArticle->setPublishedAt($planetArticle['publishedAt']);
        $newPlanetArticle->setType($name);

        return $newPlanetArticle;
    }

    /**
     * @param PlanetArticle $newPlanetArticle
     */
    private function insertNewPlanetArticleToDB(PlanetArticle $newPlanetArticle)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $em->persist($newPlanetArticle);
        $em->flush();

    }

    /**
     * @param $newArticle
     * @return Article
     */
    private function checkIfArticleExistAndCreateOne($newArticle)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();

        // article got by name
        $oldArticle = $em->getRepository('AppBundle:Article')
            ->findOneByTitle($newArticle['title']);

        // if old article is not empty then set new values to the columns from new article
        if (!empty($oldArticle)) {
            $oldArticle->setAuthor($newArticle['author']);
            $oldArticle->setTitle($newArticle['title']);
            $oldArticle->setDescription($newArticle['description']);
            $oldArticle->setUrl($newArticle['url']);
            $oldArticle->setUrlToImage($newArticle['urlToImage']);
            $oldArticle->setPublishedAt($newArticle['publishedAt']);

            return $oldArticle;
        }

        // otherwise create new article
        return $this->createArticle($newArticle);
    }

    /**
     * @param $name
     * @param $newArticle
     * @return PlanetArticle
     */
    private function checkIfPlanetArticleExistAndCreateOne($name, $newArticle)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();

        // article got by name
        $oldArticle = $em->getRepository('AppBundle:PlanetArticle')
            ->findOneByType($name);

        // if old article is not empty then set new values to the columns from new article
        if (!empty($oldArticle)) {
            $oldArticle->setAuthor($newArticle['author']);
            $oldArticle->setTitle($newArticle['title']);
            $oldArticle->setDescription($newArticle['description']);
            $oldArticle->setUrl($newArticle['url']);
            $oldArticle->setUrlToImage($newArticle['urlToImage']);
            $oldArticle->setPublishedAt($newArticle['publishedAt']);

            return $oldArticle;
        }

        // otherwise create new article
        return $this->createPlanetArticle($name, $newArticle);
    }

}
