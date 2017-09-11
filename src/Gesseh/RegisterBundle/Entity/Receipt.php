<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <gesseh@medlibre.fr>
 * @copyright: Copyright 2017 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\RegisterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Gesseh\RegisterBundle\Entity\Receipt
 *
 * @ORM\Table(name="receipt")
 * @ORM\Entity(repositoryClass="Gesseh\RegisterBundle\Entity\ReceiptRepository")
 * @Vich\Uploadable
 */
class Receipt
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @var integer $id
     */
    private $id;

    /**
     * @ORM\Column(name="begin", type="date")
     *
     * @var date $begin
     */
    private $begin;

    /**
     * @ORM\Column(name="end", type="date")
     *
     * @var date $end
     */
    private $end;

    /**
     * @ORM\ManyToOne(targetEntity="\Gesseh\UserBundle\Entity\Student", inversedBy="receipts", cascade={"persist"})
     * @ORM\JoinColumn(name="student_id", referencedColumnName="id")
     *
     * @var Student $student
     */
    private $student;

    /**
     * @ORM\Column(name="position", type="string", length=50, nullable=false)
     * @Assert\NotBlank()
     *
     * @var string $position
     */
    private $position;

    /**
     * @Vich\UploadableField(mapping="receipt_sign", fieldNameProperty="imageName")
     *
     * @var File $image
     */
    private $image;

    /**
     * @ORM\Column(name="sign", type="string", length=255, nullable=true)
     * @Assert\Image()
     *
     * @var string $imageName
     */
    private $imageName;

    /**
     * @ORM\Column(name="updated_at", type="datetime")
     *
     * @var \DateTime $updatedAt
     */
    private $updatedAt;


    public function __toString()
    {
        return $this->begin->format('Y') . ' - ' . $this->end->format('Y');
    }

    /**
     * Set image file
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
     *
     * @return Receipt
     */
    public function setImage(File $image = null)
    {
        $this->image = $image;

        if ($image)
            $this->updatedAt = new \DateTimeImmutable();
    }

    /**
     * Get image file
     *
     * @return File|null
     */
    public function getImage()
    {
        return $this->image;
    }

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
     * Set begin
     *
     * @param \DateTime $begin
     *
     * @return Receipt
     */
    public function setBegin($begin)
    {
        $this->begin = $begin;

        return $this;
    }

    /**
     * Get begin
     *
     * @return \DateTime
     */
    public function getBegin()
    {
        return $this->begin;
    }

    /**
     * Set end
     *
     * @param \DateTime $end
     *
     * @return Receipt
     */
    public function setEnd($end)
    {
        $this->end = $end;

        return $this;
    }

    /**
     * Get end
     *
     * @return \DateTime
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * Set position
     *
     * @param string $position
     *
     * @return Receipt
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return string
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set imageName
     *
     * @param string $imageName
     *
     * @return Receipt
     */
    public function setImageName($imageName)
    {
        $this->imageName = $imageName;

        return $this;
    }

    /**
     * Get imageName
     *
     * @return string
     */
    public function getImageName()
    {
        return $this->imageName;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Receipt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set student
     *
     * @param \Gesseh\UserBundle\Entity\Student $student
     *
     * @return Receipt
     */
    public function setStudent(\Gesseh\UserBundle\Entity\Student $student = null)
    {
        $this->student = $student;

        return $this;
    }

    /**
     * Get student
     *
     * @return \Gesseh\UserBundle\Entity\Student
     */
    public function getStudent()
    {
        return $this->student;
    }
}
