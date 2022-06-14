<?php

namespace App\Repository;

use App\Entity\Scene;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Scene>
 *
 * @method Scene|null find($id, $lockMode = null, $lockVersion = null)
 * @method Scene|null findOneBy(array $criteria, array $orderBy = null)
 * @method Scene[]    findAll()
 * @method Scene[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SceneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Scene::class);
    }

    public function add(Scene $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Scene $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getScenesByChapter($chapter): array
    {
        return $this->createQueryBuilder('s')
                    ->andWhere('s.chapter = :chapter')
                    ->setParameter('chapter', $chapter)
                    ->getQuery()
                    ->getResult();
    }

    public function getSceneByLabel($label): array
    {
        return $this->createQueryBuilder('s')
                    ->andWhere('s.label = :label')
                    ->setParameter('label', $label)
                    ->getQuery()
                    ->getResult();
                }

    public function getLikeSceneByLabel($saisie)
    {
        return $this->createQueryBuilder('s')
                    ->andWhere('s.label LIKE :label')
                    ->setParameter('label', "%$saisie%")
                    ->getQuery()
                    ->getResult();
    }

//    /**
//     * @return Scene[] Returns an array of Scene objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Scene
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
