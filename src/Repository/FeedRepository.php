<?php


namespace App\Repository;


use App\Entity\Feed;
use Doctrine\ORM\EntityRepository;

class FeedRepository extends EntityRepository
{

    /**
     * @return array|Feed[]
     */
    public function getFeedsByCurrentDate() {

        $dql = "SELECT f "
          ."FROM App:Feed f "
          ."WHERE f.publishedAt >= CURRENT_DATE() AND f.publishedAt <= CURRENT_DATE()+1";

        $query = $this->getEntityManager()->createQuery($dql);

        return $query->getResult();
    }

}