<?php

namespace App\Command;

use App\Entity\Feed;
use App\Repository\FeedRepository;
use App\Service\FeedReaderService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateFeedReaderCommand extends Command
{
    /** @var EntityManager $em */
    private $em;

    /** @var FeedReaderService */
    private $feedReader;

    public function __construct(EntityManagerInterface $entityManager, FeedReaderService $feedReader)
    {
        parent::__construct();
        $this->em = $entityManager;
        $this->feedReader = $feedReader;
    }

    protected function configure()
    {
        $this
          ->setName('dailytrends:update-feed-reader')
          ->setDescription('Command to read the current news and save it in the database');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $urls = [
          FeedReaderService::EL_MUNDO,
          FeedReaderService::EL_PAIS,
        ];

        /** @var FeedRepository $feedRepo */
        $feedRepo = $this->em->getRepository(Feed::class);

        foreach ($urls as $url) {

            $feeds = $this->feedReader->getFeedsByUrl($url);

            foreach ($feeds as $feed) {
                // Check if exists by url (source)
                $exists = $feedRepo->findOneBy(['source' => $feed->getSource()]);
                if ($exists === null) {
                    $this->em->persist($feed);
                }
            }

        }

        $this->em->flush();


        return 1;
    }
}
