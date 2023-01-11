<?php

namespace App\Repository;

use App\Entity\Notes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\Boolean;

/**
 * @extends ServiceEntityRepository<Notes>
 *
 * @method Notes|null find($id, $lockMode = null, $lockVersion = null)
 * @method Notes|null findOneBy(array $criteria, array $orderBy = null)
 * @method Notes[]    findAll()
 * @method Notes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NotesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Notes::class);
    }

    public function findAllNotesArePublic(bool $isPublic, $user): array {
        // return $this->getEntityManager()->createQuery('
        //     SELECT notes.id, notes.title, notes.datePosted, notes.description, notes.isPublic, notes.isActive
        //     FROM App:Notes notes
        //     WHERE notes.isPublic = true
        //     andWhere notes.user != :user
        //     ')->getResult();
        return $this->createQueryBuilder('notes')
            ->where( 'notes.isPublic =:isPublic and notes.user <>:user' )
            ->setParameter('isPublic', $isPublic)
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
        
    }

    public function deleteOneNoteById($id ) {
        // $em = $doctrine->getManager();
        // return $this->getEntityManager()->createQuery(
        //     'SELECT *
        //     FROM App\Entity\Notes notes
        //     WHERE notes.id = :id
        //     '
        // )->getResult();
        
        // $condition = $noteRow->isDeleted;
        // $noteRow->setIsDeleted(true);
        
    }

    public function save(Notes $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Notes $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

   

//    /**
//     * @return Notes[] Returns an array of Notes objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('n.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Notes
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
