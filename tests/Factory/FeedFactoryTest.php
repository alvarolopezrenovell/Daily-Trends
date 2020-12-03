<?php


namespace App\Tests\Factory;


use App\Factory\FeedFactory;
use PHPUnit\Framework\TestCase;

class FeedFactoryTest extends TestCase
{

    public function testFactory()
    {
        $datetime = new \DateTime();

        $feed = FeedFactory::create('title', 'body', 'image', 'source', 'publisher', $datetime);
        $this->assertEquals('title', $feed->getTitle());
        $this->assertEquals('body', $feed->getBody());
        $this->assertEquals('image', $feed->getImage());
        $this->assertEquals('source', $feed->getSource());
        $this->assertEquals('publisher', $feed->getPublisher());
        $this->assertEquals('publisher', $feed->getPublisher());
        $this->assertEquals($datetime, $feed->getPublishedAt());

        $feed = FeedFactory::create('title', 'body', null, 'source', 'publisher', $datetime);
        $this->assertEquals('title', $feed->getTitle());
        $this->assertEquals('body', $feed->getBody());
        $this->assertEquals(null, $feed->getImage());
        $this->assertEquals('source', $feed->getSource());
        $this->assertEquals('publisher', $feed->getPublisher());
        $this->assertEquals($datetime, $feed->getPublishedAt());

        $feed = FeedFactory::create('title', 'body', 'image', 'source', 'publisher', null);
        $this->assertEquals('title', $feed->getTitle());
        $this->assertEquals('body', $feed->getBody());
        $this->assertEquals('image', $feed->getImage());
        $this->assertEquals('source', $feed->getSource());
        $this->assertEquals('publisher', $feed->getPublisher());
        $this->assertEquals(null, $feed->getPublishedAt());
    }

}