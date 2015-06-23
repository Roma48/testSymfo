<?php

namespace CTO\AppBundle\Command;

use Carbon\Carbon;
use CTO\AppBundle\Entity\Car;
use CTO\AppBundle\Entity\Model;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class ParseandLoadCarsCollectionCommand extends ContainerAwareCommand
{
    const AUTO_SITE_URL = "http://www.autonet.ru/auto/ttx";

    protected function configure()
    {
        $this
            ->setName('cto:cars:parse')
            ->setDescription('Parse and load car brand collection and models this cars');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $startTime = Carbon::now();
        $output->writeln("");
        $output->writeln("Loading ...");
        $client = new Client();
        $response = $client->get(self::AUTO_SITE_URL);
        $crawler = new Crawler($response->getBody()->getContents(), self::AUTO_SITE_URL);
        $filtered = $crawler->filter('div.brands-block.bt-null ul li a')->each(function (Crawler $node, $i) {

            return ['marka' => $node->text(), "link" => $node->link()->getUri()];
        });

        $result = [];
        foreach ($filtered as $item) {
            $response = $client->get($item['link']);
            $crawler = new Crawler($response->getBody()->getContents(), $item['link']);
            $resultModel = $crawler->filter('div.filter-models.bt-null ul li a')->each(function (Crawler $node, $i) {

                return ['model' => $node->text(), "link" => $node->link()->getUri()];
            });
            $result[] = [
                "marka" => $item['marka'],
                "marka_url" => $item['link'],
                "models" => $resultModel
            ];
        }

        /** @var EntityManager $em */
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $brandsCount = 0;
        $modelsCount = 0;

        foreach ($result as $item) {
            $brand = new Car();
            $brand->setName($item['marka']);
            $em->persist($brand);

            foreach ($item['models'] as $modelItem) {
                $model = new Model();
                $model->setName($modelItem['model']);

                $em->persist($model);

                $brand->addModel($model);
                $modelsCount++;
            }
            $brandsCount++;
        }

        $em->flush();

        $finishTime = Carbon::now();

        $output->writeln("");
        $output->writeln(" Car brands loaded: ".$brandsCount);
        $output->writeln(" Car models loaded: ".$modelsCount);
        $output->writeln(" Elapsed time: ".$finishTime->diffForHumans($startTime)." start.");
        $output->writeln("Done.");
        $output->writeln("");
    }
}
