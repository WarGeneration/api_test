<?php


namespace App\Service;


use App\Entity\Classroom;
use Doctrine\ORM\EntityManagerInterface;

class ClassroomService
{
    /** @var EntityManagerInterface */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function save(Classroom $classroom): Classroom
    {
        $this->em->persist($classroom);
        $this->em->flush();

        return $classroom;
    }

    public function changeActive(Classroom $classroom): Classroom
    {
        return $this->save($classroom);
    }

    public function delete(Classroom $classroom): void
    {
        $this->em->remove($classroom);
        $this->em->flush();
    }
}