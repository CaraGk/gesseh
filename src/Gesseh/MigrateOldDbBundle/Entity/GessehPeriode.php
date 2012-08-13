<?php

namespace Gesseh\MigrateOldDbBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Gesseh\MigrateOldDbBundle\Entity\GessehPeriode
 *
 * @ORM\Table(name="gesseh_periode")
 * @ORM\Entity
 */
class GessehPeriode
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var date $debut
     *
     * @ORM\Column(name="debut", type="date")
     */
    private $debut;

    /**
     * @var date $fin
     *
     * @ORM\Column(name="fin", type="date")
     */
    private $fin;

    /**
     * @var date $debut_simul
     *
     * @ORM\Column(name="debut_simul", type="date")
     */
    private $debut_simul;

    /**
     * @var date $fin_simul
     *
     * @ORM\Column(name="fin_simul", type="date")
     */
    private $fin_simul;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set debut
     *
     * @param date $debut
     */
    public function setDebut($debut)
    {
        $this->debut = $debut;
    }

    /**
     * Get debut
     *
     * @return date
     */
    public function getDebut()
    {
        return $this->debut;
    }

    /**
     * Set fin
     *
     * @param date $fin
     */
    public function setFin($fin)
    {
        $this->fin = $fin;
    }

    /**
     * Get fin
     *
     * @return date
     */
    public function getFin()
    {
        return $this->fin;
    }

    /**
     * Set debut_simul
     *
     * @param date $debutSimul
     */
    public function setDebutSimul($debutSimul)
    {
        $this->debut_simul = $debutSimul;
    }

    /**
     * Get debut_simul
     *
     * @return date
     */
    public function getDebutSimul()
    {
        return $this->debut_simul;
    }

    /**
     * Set fin_simul
     *
     * @param date $finSimul
     */
    public function setFinSimul($finSimul)
    {
        $this->fin_simul = $finSimul;
    }

    /**
     * Get fin_simul
     *
     * @return date
     */
    public function getFinSimul()
    {
        return $this->fin_simul;
    }
}
