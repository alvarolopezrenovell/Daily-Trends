<?php


namespace App\Factory;


use App\Entity\Feed;

class FeedFactory
{

    /**
     * @param $title
     * @param $body
     * @param $image
     * @param $source
     * @param $publisher
     * @param $publishedAt
     * @return Feed
     */
    public static function create(string $title, string $body, ?string $image, string $source, string $publisher, ?\DateTime $publishedAt) {
        $feed = new Feed();

        $feed->setTitle($title);
        $feed->setBody($body);
        $feed->setImage($image);
        $feed->setSource($source);
        $feed->setPublisher($publisher);
        $feed->setPublishedAt($publishedAt);

        return $feed;
    }

}