<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

class OrderRepository extends EntityRepository
{
    const PERIOD_TODAY = 'today';
    const PERIOD_LAST_7_DAYS = 'last7days';

    /**
     * @param string|null $period
     * @param string|null $term
     * @return \Doctrine\ORM\Query
     */
    public function search($period = null, $term = null)
    {
        $emConfig = $this->getEntityManager()->getConfiguration();
        $emConfig->addCustomDatetimeFunction('DATE', 'DoctrineExtensions\Query\Mysql\Date');

        $qb = $this->createQueryBuilder('o')
            ->innerJoin('o.user', 'u')
            ->innerJoin('o.product', 'p');

        if ($period !== null) {
            switch ($period) {
                case self::PERIOD_TODAY:
                    $qb->andWhere('DATE(o.createdAt) = CURRENT_DATE()');
                    break;
                case self::PERIOD_LAST_7_DAYS:
                    $date = new \DateTime();
                    $date->sub(new \DateInterval('P7D'));

                    $qb->andWhere('DATE(o.createdAt) >= :date')
                        ->setParameter('date', $date->format('Y-m-d'));
                    break;
                default:
                    throw new \InvalidArgumentException('Invalid period passed');
            }
        }

        if ($term !== null) {
            if (is_string($term) == false) {
                throw new \InvalidArgumentException('Invalid term passed');
            }

            $qb->andWhere('p.name LIKE :term OR u.name LIKE :term')
                ->setParameter('term', '%' . $term . '%');
        }

        return $qb->getQuery();
    }
}
