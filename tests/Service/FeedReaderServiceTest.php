<?php


namespace App\Tests\Service;

use App\Entity\Feed;
use App\Service\FeedReaderService;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FeedReaderServiceTest extends WebTestCase
{

    /**
     * @param $class
     * @return object|null
     */
    private function getService($class) {
        self::bootKernel();
        $container = self::$container;
        return $container->get($class);
    }

    public function testFilters() {
        /** @var FeedReaderService $feedReader */
        $feedReader = $this->getService(FeedReaderService::class);

        $filters = $feedReader->getFilters(FeedReaderService::EL_MUNDO);
        $this->assertCount(2, $filters);
        $this->assertTrue($filters['feed_section'] !== '');
        $this->assertTrue($filters['feed_link'] !== '');

        $filters = $feedReader->getFilters(FeedReaderService::EL_PAIS);
        $this->assertCount(2, $filters);
        $this->assertTrue($filters['feed_section'] !== '');
        $this->assertTrue($filters['feed_link'] !== '');
    }

    public function testDate() {

        /** @var FeedReaderService $feedReader */
        $feedReader = $this->getService(FeedReaderService::class);

        $dateTime = $feedReader->processDateTime('10-05-2020 20:30 CET');
        $this->assertTrue($dateTime instanceof \DateTime);

        $dateTime = $feedReader->processDateTime('10-05-2020');
        $this->assertTrue($dateTime instanceof \DateTime);

        $dateTime = $feedReader->processDateTime('prueba');
        $this->assertNull($dateTime);
    }

    public function testFeeds() {
        /** @var FeedReaderService $feedReader */
        $feedReader = $this->getService(FeedReaderService::class);

        $feeds = $feedReader->getFeedsByUrl(FeedReaderService::EL_PAIS);
        $this->assertTrue(count($feeds) > 0);
        foreach ($feeds as $feed) {
            $this->assertTrue($feed instanceof Feed);
        }

        $feeds = $feedReader->getFeedsByUrl(FeedReaderService::EL_MUNDO);
        $this->assertTrue(count($feeds) > 0);
        foreach ($feeds as $feed) {
            $this->assertTrue($feed instanceof Feed);
        }
    }

}