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
        $queryTerm = 'astronomy';

        // astronomical news got using API
        $astronomicalNews = $this->getArticles($queryTerm);

        $planetsNames = $this->getPlanetsNames();

        $this->createNewArticles($astronomicalNews, $planetsNames);

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
     * @param $queryTerm
     * @return array
     */
    private function getArticles($queryTerm)
    {
        // The New York Times api key
        $api_key = '0c3bb1800a1b4895ac8ae744d010d5ad';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $query = array(
            "api-key" => $api_key,
            "q" => $queryTerm,
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

        // check if we got any result, otherwise return empty array
        if (isset($result['response']['docs'])) {
            return $result['response']['docs'];
        }

        return [];
    }

    /**
     * @param $astronomicalNews
     * @param $planetsNames
     */
    private function createNewArticles($astronomicalNews, $planetsNames)
    {
        $repository = 'AppBundle:Article';

        // go through all got astronomical news, check if article exists in DB and create one if it does not exist
        foreach ($astronomicalNews as $astronomicalArticle) {
            // if article has no multimedia or author, then check next article in an array
            if ($astronomicalArticle['multimedia'] == null || $astronomicalArticle['byline'] == null) {
                continue;
            }

            if (!$this->checkArticleExistence($astronomicalArticle, $repository)) {
                $newArticle = $this->createArticle($astronomicalArticle, $planetsNames);
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

        $newArticle->setAuthor($article['byline']['original']);
        $newArticle->setArticleId($article['_id']);
        $newArticle->setTitle($article['headline']['main']);
        $newArticle->setDescription($article['snippet']);
        $newArticle->setUrl($article['web_url']);
        $newArticle->setUrlToImage("https://static01.nyt.com/" . $article['multimedia'][1]['url']);
        $newArticle->setPublishStringDate($article['pub_date']);

        // go through all planet names and check if found planet name in title or description
        // then set found planet name to new article otherwise set empty string
        foreach ($planetsNames as $planetName) {
            if (preg_match('/\b'.$planetName.'\b/i', $article[''.
                'headline']['main']) || preg_match('/\b'. $planetName .
                    '\b/i', $article['snippet'])) {
                $newArticle->setPlanet($planetName);
            } else {
                $newArticle->setPlanet("");
            }
        }
        return $newArticle;
    }

    private function insertNewArticleToDB($newArticle)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $em->persist($newArticle);
        $em->flush();
        dump($newArticle);
        exit;
    }

    /**
     * @param $newArticle
     * @param $repository
     * @return bool
     */
    private function checkArticleExistence($newArticle, $repository)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();

        // article got by article id
        $oldArticle = $em->getRepository($repository)
            ->findOneByarticle_id($newArticle['_id']);

        if (!empty($oldArticle)) {
            return true;
        }

        return false;
    }
}
