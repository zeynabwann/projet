<?php
namespace App\Repository;
use App\Entity\Dette;
use App\enum\StatusDette;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class DetteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Dette::class);
    }

public function findDetteByClient(int $idClient, string $status = "Impaye",  int $limit = 2, int $page = 1): Paginator
    {
        $query = $this->createQueryBuilder('d');
        $query->where('d.client = :idClient');
        $query->setParameter('idClient', $idClient);
        if ($status == StatusDette::Impaye->value) {
            $query->andWhere('d.montant != d.montantVerser');
        }
        if ($status == StatusDette::Paye->value) {
            $query->andWhere('d.montant = d.montantVerser');
        }
        $query->orderBy('d.createAt', 'DESC')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery();
        return new Paginator($query);
    }
}