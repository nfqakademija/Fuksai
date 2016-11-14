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
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use AppBundle\Repository\PlanetRepository;
use AppBundle\Repository\ArticleRepository;
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

        $keyword = "planet";

        // astronomical news got using a keyword
        $astronomicalNews = $this->getAstronomicalNews($news, $keyword);

        /* if found zero astronomical articles then insert other articles to the
        database, otherwise insert astronomical articles */
        if ($astronomicalNews == []) {

            foreach ($news as $article) {

                $newArticle = $this->createArticle("", $article);
                $this->insertNewArticleToDB($newArticle);

                $output->writeln('Inserting "' . $article['title'] . '" article...');

            }
        } else {

            // all planets names got from our DB
            $planetsNames = $this->getPlanetsNames();

            foreach ($planetsNames as $planetName) {

                // article related to given planet name
                $article = $this->getArticle($astronomicalNews, $planetName);

                if ($article == null) {
                    $output->writeln('Could not find article related to: '. $planetName);
                    continue;
                }

                $newArticle = $this->checkIfArticleExistAndCreateOne($planetName, $article);
                $this->insertNewArticleToDB($newArticle);

                $output->writeln('Inserting "' . $newArticle['title'] . '" article... related to:' . $planetName);
            }

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
     * @param $keyword
     * @return array
     */
    private function getAstronomicalNews($news, $keyword)
    {
        $astronomicalNews = [];

        foreach ($news as $article) {

            // if found keyword in article's title or description then add that article to astronomicalNews array
            if (strpos($article['title'], $keyword) !== false || strpos($article['description'], $keyword) !== false) {
                $astronomicalNews[] = $article;
            }

        }

        return $astronomicalNews;
    }

    /**
     * @param $astronomicalNews
     * @param $planetName
     * @return null
     */
    private function getArticle($astronomicalNews, $planetName)
    {

        foreach ($astronomicalNews as $article) {

            // if found planet name in article's title or description then return that article
            if (strpos($article['title'], $planetName) !== false || strpos($article[''.
                'description'], $planetName) !== false) {
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
     * @param $name
     * @param $article
     * @return Article
     */
    private function createArticle($name, $article)
    {
        $newArticle = new Article();

        $newArticle->setAuthor($article['author']);
        $newArticle->setTitle($article['title']);
        $newArticle->setDescription($article['description']);
        $newArticle->setUrl($article['url']);
        $newArticle->setUrlToImage($article['urlToImage']);
        $newArticle->setPublishedAt($article['publishedAt']);
        $newArticle->setType($name);

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
     * @param $newArticle
     * @return Article
     */
    private function checkIfArticleExistAndCreateOne($name, $newArticle)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();

        // article got by name
        $oldArticle = $em->getRepository('AppBundle:Article')
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
        return $this->createArticle($name, $newArticle);
    }
}
