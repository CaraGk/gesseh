<?php

namespace Gesseh\SimulationBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * SimulationRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SimulationRepository extends EntityRepository
{
  public function getAll()
  {
    $query = $this->createQueryBuilder('t')
                  ->join('t.student', 's')
//                  ->join('t.department', 'd')
//                  ->join('d.hospital', 'h')
                  ->addSelect('s')
//                  ->addSelect('d')
                  ->orderBy('t.id', 'asc');

    return $query->getQuery();
  }

  public function getByUsername($user)
  {
    $query = $this->createQueryBuilder('t')
                  ->join('t.student', 's')
                  ->join('s.user', 'u')
                  ->where('u.username = :user')
                    ->setParameter('user', $user);

    return $query->getQuery()->getSingleResult();
  }

  public function setSimulationTable($students, $em)
  {
    $count = 1;

    foreach($students as $student) {
      $simulation = new Simulation();
      $simulation->setId($count);
      $simulation->setStudent($student);
      $simulation->setActive(true);
      $simulation->setDepartment(null);
      $em->persist($simulation);
      $count++;
    }

    $em->flush();
    return --$count;
  }

  public function doSimulation($department_table, \Doctrine\ORM\EntityManager $em)
  {
    $query = $this->createQueryBuilder('t')
                  ->join('t.student', 's')
                  ->join('t.wishes', 'w')
                  ->join('w.department', 'd')
                  ->join('s.user', 'u')
                  ->join('s.grade', 'p')
                  ->where('u.enabled = true')
                  ->addOrderBy('p.rank', 'desc')
                  ->addOrderBy('s.graduate', 'asc')
                  ->addOrderBy('s.ranking', 'asc')
                  ->addOrderBy('w.rank', 'asc');

    $sims = $query->getQuery()->getResult();

    foreach($sims as $sim) {
      $student = $sim->getStudent();
      $sim->setDepartment(null);
      $sim->setExtra(null);
      foreach($sim->getWishes() as $wish) {
        if(null === $sim->getDepartment() and $department_table[$wish->getDepartment()->getId()] > 0) {
          $department_table[$wish->getDepartment()->getId()]--;
          $sim->setDepartment($wish->getDepartment());
          $sim->setExtra($department_table[$wish->getDepartment()->getId()]);
        }
      }
      $em->persist($sim);
    }

    $em->flush();
  }

/*  public function deleteAll()
  {
    $dql = 'DELETE FROM GessehSimulationBundle:Simulation s';
    $this->getEntityManager()->createQuery($dql)->getResult();
  }
*/
}
