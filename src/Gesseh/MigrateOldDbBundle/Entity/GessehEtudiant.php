<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <caragk@angrand.fr>
 * @copyright: Copyright 2013 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\MigrateOldDbBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Gesseh\MigrateOldDbBundle\Entity\GessehEtudiant
 *
 * @ORM\Table(name="gesseh_etudiant")
 * @ORM\Entity
 */
class GessehEtudiant
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
     * @ORM\ManyToOne(targetEntity="GessehPromo")
     * @ORM\JoinColumn(name="promo_id", referencedColumnName="id")
     */
    private $promo_id;

    /**
     * @var smallint $annee_promo
     *
     * @ORM\Column(name="annee_promo", type="smallint")
     */
    private $annee_promo;

    /**
     * @var integer $classement
     *
     * @ORM\Column(name="classement", type="integer")
     */
    private $classement;

    /**
     * @var string $tel
     *
     * @ORM\Column(name="tel", type="string", length=14)
     */
    private $tel;

    /**
     * @var date $naissance
     *
     * @ORM\Column(name="naissance", type="date")
     */
    private $naissance;

    /**
     * @var smallint $anonyme
     *
     * @ORM\Column(name="anonyme", type="smallint")
     */
    private $anonyme;

    /**
     * @ORM\OneToOne(targetEntity="sfGuardUser")
     * @ORM\JoinColumn(name="utilisateur", referencedColumnName="id")
     */
    private $utilisateur;

    /**
     * @var datetime $updated_at
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updated_at;


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
     * Set promo_id
     *
     * @param GessehPromo $promoId
     */
    public function setPromoId($promoId)
    {
        $this->promo_id = $promoId;
    }

    /**
     * Get promo_id
     *
     * @return GessehPromo
     */
    public function getPromoId()
    {
        return $this->promo_id;
    }

    /**
     * Set annee_promo
     *
     * @param smallint $anneePromo
     */
    public function setAnneePromo($anneePromo)
    {
        $this->annee_promo = $anneePromo;
    }

    /**
     * Get annee_promo
     *
     * @return smallint
     */
    public function getAnneePromo()
    {
        return $this->annee_promo;
    }

    /**
     * Set classement
     *
     * @param integer $classement
     */
    public function setClassement($classement)
    {
        $this->classement = $classement;
    }

    /**
     * Get classement
     *
     * @return integer
     */
    public function getClassement()
    {
        return $this->classement;
    }

    /**
     * Set tel
     *
     * @param string $tel
     */
    public function setTel($tel)
    {
        $this->tel = $tel;
    }

    /**
     * Get tel
     *
     * @return string
     */
    public function getTel()
    {
        return $this->tel;
    }

    /**
     * Set naissance
     *
     * @param date $naissance
     */
    public function setNaissance($naissance)
    {
        $this->naissance = $naissance;
    }

    /**
     * Get naissance
     *
     * @return date
     */
    public function getNaissance()
    {
        return $this->naissance;
    }

    /**
     * Set anonyme
     *
     * @param smallint $anonyme
     */
    public function setAnonyme($anonyme)
    {
        $this->anonyme = $anonyme;
    }

    /**
     * Get anonyme
     *
     * @return smallint
     */
    public function getAnonyme()
    {
        return $this->anonyme;
    }

    /**
     * Set utilisateur
     *
     * @param sfGuardUser $utilisateur
     */
    public function setUtilisateur($utilisateur)
    {
        $this->utilisateur = $utilisateur;
    }

    /**
     * Get utilisateur
     *
     * @return sfGuardUser
     */
    public function getUtilisateur()
    {
        return $this->utilisateur;
    }
}
