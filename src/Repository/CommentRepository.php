<?php

namespace App\Repository;

use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    /**
     * @return Comment[] Returns an array of Comment objects
     */

    public function findAllCommentsPublished($id)
    {
        return $this->createQueryBuilder('commentPublished')
            ->andWhere('commentPublished.lesson = :id')
            ->andWhere('commentPublished.state = :published')
            ->setParameter('id', $id)
            ->setParameter('published', "published")
            ->orderBy('commentPublished.createdAt', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @return Comment[] Returns an array of Comment objects
     */

    public function findAllComments($id)
    {
        return $this->createQueryBuilder('comment')
            ->andWhere('comment.lesson = :id')
            ->andWhere('comment.state <> :rejected')
            ->setParameter('id', $id)
            ->setParameter('rejected', "rejected")
            ->orderBy('comment.createdAt', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @return Comment[] Returns an array of Comment objects
     */

    public function countAllCommentsOfLessonPublished($id)
    {
        return $this->createQueryBuilder('n')
            ->select('count(n.id)')
            ->andWhere('n.lesson = :id')
            ->andWhere('n.state = :published')
            ->setParameter('id', $id)
            ->setParameter('published', "published")
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }

    /**
     * @return Comment[] Returns an array of Comment objects
     */

    public function countAllCommentsOfLesson($id)
    {
        return $this->createQueryBuilder('n')
            ->select('count(n.id)')
            ->andWhere('n.lesson = :id')
            ->andWhere('n.state = :published')
            ->orWhere('n.state = :submitted')
            ->setParameter('id', $id)
            ->setParameter('published', "published")
            ->setParameter('submitted', "submitted")
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }

    // /**
    //  * @return Comment[] Returns an array of Comment objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Comment
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
