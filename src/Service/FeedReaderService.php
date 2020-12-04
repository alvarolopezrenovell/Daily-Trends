<?php


namespace App\Service;


use App\Entity\Feed;
use App\Factory\FeedFactory;
use App\Repository\FeedRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DomCrawler\Crawler;

class FeedReaderService
{

    private $em;

    public const EL_PAIS = 'https://elpais.com/';
    public const EL_MUNDO = 'https://www.elmundo.es/';

    public const FEEDS = [
      self::EL_PAIS,
      self::EL_MUNDO,
    ];

    public const LIMIT_FEEDS = 5;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function createFeeds() {
        /** @var FeedRepository $feedRepo */
        $feedRepo = $this->em->getRepository(Feed::class);

        foreach (self::FEEDS as $url) {
            $feeds = $this->getFeedsByUrl($url);
            foreach ($feeds as $feed) {
                // Check if exists by url (source)
                $exists = $feedRepo->findOneBy(['source' => $feed->getSource()]);
                if ($exists === null) {
                    $this->em->persist($feed);
                }
            }
        }

        $this->em->flush();
    }

    /**
     * @param $url
     * @return array|Feed[]
     */
    public function getFeedsByUrl($url) {
        $feeds = [];

        $crawler = new Crawler(file_get_contents($url));

        $filters = $this->getFilters($url);

        // Search all feeds
        $links = $crawler->filter($filters['feed_section'])->each(function (Crawler $node, $i) use ($url, $filters) {
            $href = $node->filter($filters['feed_link'])->attr('href');
            if (strpos($href, $url) !== 0) {
                $href = $url.$href;
            }
            return $href;
        });

        // Get only self::LIMIT_FEEDS
        $links = array_splice($links, 0, self::LIMIT_FEEDS);

        foreach ($links as $source) {

            $crawler = new Crawler(file_get_contents($source));

            try {
                $feed = $this->createFeedByCrawler($crawler, $url, $source);
            } catch (\Exception $e) {
                $feed = null;
            }

            if ($feed) {
                $feeds[] = $feed;
            }
        }

        return $feeds;
    }

    /**
     * @param $crawler
     * @param $url
     * @param $source
     * @return Feed|null
     */
    public function createFeedByCrawler($crawler, $url, $source) {
        $feed = null;

        switch ($url) {
            case self::EL_PAIS:

                $title = $crawler->filter('article h1')->text();

                if (strpos($source, 'verne')) {
                    try {
                        $image = $crawler->filter('article figure.foto meta')->attr('content');
                    } catch (\Exception $e) {
                        // The article has video but no image
                        $image = '';
                    }
                    $body = $crawler->filter('article #cuerpo_noticia p')->each(function (Crawler $node, $i) {
                        return $node->text();
                    });
                    $publisher = $crawler->filter('article .autor')->text();
                    $publishedAt = $crawler->filter('article time a')->text();
                } else {
                    try {
                        $image = $crawler->filter('article figure img')->attr('src');
                    } catch (\Exception $e) {
                        // The article has video but no image
                        $image = '';
                    }
                    $body = $crawler->filter('article .article_body p')->each(function (Crawler $node, $i) {
                        return $node->text();
                    });
                    $publisher = $crawler->filter('article .a_by > .a_auts > .a_aut > a.a_aut_n')->text();
                    $publishedAt = $crawler->filter('article .a_by .a_ti')->text();
                }

                $body = $this->processBody($body);
                $publishedAt = $this->processDateTime($publishedAt);

                $feed = FeedFactory::create($title, $body, $image, $source, $publisher, $publishedAt);

                break;
            case self::EL_MUNDO:

                $title = $crawler->filter('article h1')->text();
                try {
                    $image = $crawler->filter('article figure picture img')->attr('src');
                } catch (\Exception $e) {
                    // The article has video but no image
                    $image = '';
                }
                $body = $crawler->filter('article .ue-c-article__body p')->each(function (Crawler $node, $i) {
                    return $node->text();
                });
                $publisher = $crawler->filter('article .ue-c-article__byline-name')->text();
                $publishedAt = $crawler->filter('article time')->attr('datetime');

                $body = $this->processBody($body);
                $publishedAt = $this->processDateTime($publishedAt);

                $feed = FeedFactory::create($title, $body, $image, $source, $publisher, $publishedAt);

                break;
        }

        return $feed;
    }

    /**
     * @param $body
     * @return string
     */
    public function processBody($body) {
        $body = join(PHP_EOL.PHP_EOL, $body);
        $body = strip_tags($body, '<br><p><ul><li><b><i><u><a>');
        return $body;
    }

    /**
     * @param $dateTime
     * @return \DateTime|null
     */
    public function processDateTime($dateTime) {

        $esp_months = [
          'ene' => '01',
          'feb' => '02',
          'mar' => '03',
          'may' => '04',
          'abr' => '05',
          'jun' => '06',
          'jul' => '07',
          'ago' => '08',
          'sep' => '09',
          'oct' => '10',
          'nov' => '11',
          'dic' => '12',
          'dec' => '12',
        ];

        $dateTime = strtolower($dateTime);
        $dateTime = str_replace(' cet', '', $dateTime);
        $dateTime = str_replace(' ', '-', $dateTime);
        $dateTime = str_replace('---', ' ', $dateTime);

        $dateTime = strtr( $dateTime, $esp_months);

        try {
            $dateTime = new \DateTime($dateTime);
        } catch (\Exception $e) {
            $dateTime = null;
        }

        return $dateTime;
    }

    /**
     * @param $url
     * @return string[]
     */
    public function getFilters($url) {
        $filters = [
          'feed_section' => '',
          'feed_link' => '',
        ];

        switch ($url) {
            case self::EL_PAIS:
                $filters['feed_section'] = 'article';
                $filters['feed_link'] = 'article h2 > a';
                break;
            case self::EL_MUNDO:
                $filters['feed_section'] = 'article';
                $filters['feed_link'] = 'article header .ue-c-cover-content__link';
                break;
        }

        return $filters;
    }

}