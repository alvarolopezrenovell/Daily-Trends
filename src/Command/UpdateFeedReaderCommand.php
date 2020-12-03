<?php

namespace App\Command;

use App\Service\FeedReaderService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateFeedReaderCommand extends Command
{

    /** @var FeedReaderService */
    private $feedReader;

    public function __construct(FeedReaderService $feedReader)
    {
        parent::__construct();
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
        try {
            $this->feedReader->createFeeds();
            return 1;
        } catch (\Exception $e) {
            return 0;
        }
    }
}
