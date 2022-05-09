<?php

namespace App\Repository;

use App\Entity\Answer;
use App\Entity\OptionChoice;
use App\Entity\Question;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Answer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Answer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Answer[]    findAll()
 * @method Answer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnswerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Answer::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Answer $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Answer $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function getOptionChoose(OptionChoice $optionChoice, Question $question)
    {
        return $this->createQueryBuilder('a')
            ->leftJoin('a.optionChoice', 'opt')
            ->leftJoin('opt.question', 'que')
            ->andWhere('opt.id = :optionChoice')
            ->andWhere('que.id = :question')
            ->setParameter('optionChoice', $optionChoice)
            ->setParameter('question', $question)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    public function getOneByOptionChoose(OptionChoice $optionChoice)
    {
        return $this->createQueryBuilder('a')
            ->leftJoin('a.optionChoice', 'opt')
            ->andWhere('opt.id = :optionChoice')
            ->setParameter('optionChoice', $optionChoice)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    public function getOneByQuestion(Question $question)
    {
        return $this->createQueryBuilder('a')
            ->leftJoin('a.optionChoice', 'opt')
            ->leftJoin('opt.question', 'que')
            ->andWhere('que.id = :question')
            ->setParameter('question', $question)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }
}
