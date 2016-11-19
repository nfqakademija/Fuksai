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
            ->setHelp('This command finds and imports astronomical news in the website.');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        // astronomical news got using API
        $astronomicalNews = $this->getArticles();

        // go through all got astronomical news, check if article exists in DB and create one if it does not exist
        foreach ($astronomicalNews as $astronomicalArticle) {
            // if article has no multimedia or author, then check next article in an array
            if ($astronomicalArticle['multimedia'] == null || $astronomicalArticle['byline'] == null) {
                continue;
            }

            if ($this->checkArticleExistence($astronomicalArticle)) {
                $output->writeln('There is the same article -'.
                    '"' . $astronomicalArticle['headline']['main'] . '" in database...');
            } else {
                $newArticle = $this->createArticle($astronomicalArticle);
                $this->insertNewArticleToDB($newArticle);
                $output->writeln('Inserting "' . $newArticle->getTitle() . '" article...');
            }
        }

        // all planets names got from our DB
        $planetsNames = $this->getPlanetsNames();

        // insert astronomical articles related to planet name to database
        foreach ($planetsNames as $planetName) {
            // article related to given planet name
            $planetArticles = $this->getPlanetsArticles($planetName);

            if ($planetArticles == null) {
                $output->writeln('Could not find articles related to: '. $planetName);
                continue;
            }

            // go through all got planet articles, check if article exists in DB and create one if it does not exist
            foreach ($planetArticles as $planetArticle) {
                // if article has no multimedia or author, then check next article in an array
                if ($planetArticle['multimedia'] == null || $planetArticle['byline'] == null) {
                    continue;
                }

                if ($this->checkPlanetArticleExistence($planetArticle)) {
                    $output->writeln('There is the same planet "'.$planetName.'" article -'.
                        '"' . $planetArticle['headline']['main'] . '" in database...');
                } else {
                    $newPlanetArticle = $this->createPlanetArticle($planetName, $planetArticle);
                    $this->insertNewArticleToDB($newPlanetArticle);
                    $output->writeln('Inserting "' . $newPlanetArticle->getTitle() . '" planet article'.
                        ' related to: '. $planetName .'...');
                }
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
    private function getArticles()
    {
        // The New York Times api key
        $api_key = '0c3bb1800a1b4895ac8ae744d010d5ad';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $query = array(
            "api-key" => $api_key,
            "q" => "astronomy",
            "fq" => "news_desk:(\"Science\")",
            "sort" => "newest",
            "fl" => "_id,headline,snippet,lead_paragraph,web_url,pub_date,byline,multimedia"
        );

        // get astronomical news
        curl_setopt(
            $curl,
            CURLOPT_URL,
            "https://api.nytimes.com/svc/search/v2/articlesearch.json" . "?" . http_build_query($query)
        );

        // result in array from json
        $result = json_decode(curl_exec($curl), true);

        // check if we got any result, otherwise return null
        if (isset($result['response']['docs'])) {
            return $result['response']['docs'];
        }

        return null;
    }

    /**
     * @param $planetName
     * @return null|array
     */
    private function getPlanetsArticles($planetName)
    {
        // The New York Times api key
        $api_key = '0c3bb1800a1b4895ac8ae744d010d5ad';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $query = array(
            "api-key" => $api_key,
            "q" => $planetName,
            "fq" => "news_desk:(\"Science\")",
            "sort" => "newest",
            "fl" => "_id,headline,snippet,lead_paragraph,web_url,pub_date,byline,multimedia"
        );

        // get astronomical news
        curl_setopt(
            $curl,
            CURLOPT_URL,
            "https://api.nytimes.com/svc/search/v2/articlesearch.json" . "?" . http_build_query($query)
        );

        // result in array from json
        $result = json_decode(curl_exec($curl), true);

        // check if we got any result, otherwise return null
        if (isset($result['response']['docs'])) {
            return $result['response']['docs'];
        }

        return null;
    }

    /**
     * @param $article
     * @return Article
     */
    private function createArticle($article)
    {
        $newArticle = new Article();

        $newArticle->setAuthor($article['byline']['original']);
        $newArticle->setArticleId($article['_id']);
        $newArticle->setTitle($article['headline']['main']);
        $newArticle->setDescription($article['snippet']);
        $newArticle->setUrl($article['web_url']);
        $newArticle->setUrlToImage("https://static01.nyt.com/" . $article['multimedia'][1]['url']);

        // date string converted to a specific format
        $pub_date = substr($article['pub_date'], 0, -10);

        // DateTime object converted from string
        $pub_date = date_create_from_format('Y-m-d', $pub_date);

        $newArticle->setPublishDate($pub_date);

        return $newArticle;
    }

    private function insertNewArticleToDB($newArticle)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $em->persist($newArticle);
        $em->flush();
    }

    /**
     * @param String $name
     * @param $planetArticle
     * @return PlanetArticle
     */
    private function createPlanetArticle(String $name, $planetArticle)
    {
        $newPlanetArticle = new PlanetArticle();

        $newPlanetArticle->setAuthor($planetArticle['byline']['original']);
        $newPlanetArticle->setArticleId($planetArticle['_id']);
        $newPlanetArticle->setTitle($planetArticle['headline']['main']);
        $newPlanetArticle->setDescription($planetArticle['snippet']);
        $newPlanetArticle->setUrl($planetArticle['web_url']);
        $newPlanetArticle->setUrlToImage("https://static01.nyt.com/" . $planetArticle['multimedia'][1]['url']);

        // date string converted to a specific format
        $pub_date = substr($planetArticle['pub_date'], 0, -10);

        // DateTime object converted from string
        $pub_date = date_create_from_format('Y-m-d', $pub_date);

        $newPlanetArticle->setPublishDate($pub_date);
        $newPlanetArticle->setPlanet($name);

        return $newPlanetArticle;
    }

    /**
     * @param $newArticle
     * @return bool
     */
    private function checkArticleExistence($newArticle)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();

        // article got by article id
        $oldArticle = $em->getRepository('AppBundle:Article')
            ->findOneByarticle_id($newArticle['_id']);

        if (!empty($oldArticle)) {
            return true;
        }

        return false;
    }

    /**
     * @param $newArticle
     * @return bool
     */
    private function checkPlanetArticleExistence($newArticle)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();

        // article got by article id
        $oldArticle = $em->getRepository('AppBundle:PlanetArticle')
            ->findOneByarticle_id($newArticle['_id']);

        if (!empty($oldArticle)) {
            return true;
        }

        return false;
    }
}
