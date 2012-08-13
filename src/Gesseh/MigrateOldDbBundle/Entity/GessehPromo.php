<?php

namespace Gesseh\MigrateOldDbBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Gesseh\MigrateOldDbBundle\Entity\GessehPromo
 *
 * @ORM\Table(name="gesseh_promo")
 * @ORM\Entity
 */
class GessehPromo
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
     * @var string $titre
     *
     * @ORM\Column(name="titre", type="string", length=100)
     */
    private $titre;

    /**
     * @var smallint $ordre
     *
     * @ORM\Column(name="ordre", type="smallint")
     */
    private $ordre;

    /**
     * @var smallint $active
     *
     * @ORM\Column(name="active", type="smallint")
     */
    private $active;

    /**
     * @ORM\ManyToOne(targetEntity="GessehFormEval")
     * @ORM\JoinColumn(name="form", referencedColumnName="id")
     */
    private $form;


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
     * Set titre
     *
     * @param string $titre
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;
    }

    /**
     * Get titre
     *
     * @return string
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set ordre
     *
     * @param smallint $ordre
     */
    public function setOrdre($ordre)
    {
        $this->ordre = $ordre;
    }

    /**
     * Get ordre
     *
     * @return smallint
     */
    public function getOrdre()
    {
        return $this->ordre;
    }

    /**
     * Set active
     *
     * @param smallint $active
     */
    public function setActive($active)
    {
        $this->active = $active;
    }

    /**
     * Get active
     *
     * @return smallint
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set form
     *
     * @param GessehEvalForm $form
     */
    public function setForm($form)
    {
        $this->form = $form;
    }

    /**
     * Get form
     *
     * @return GessehEvalForm
     */
    public function getForm()
    {
        return $this->form;
    }
}
