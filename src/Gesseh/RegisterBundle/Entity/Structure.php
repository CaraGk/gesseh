<?php

/**
 * This file is part of PIGASS project
 *
 * @author: Pierre-François ANGRAND <pigass@medlibre.fr>
 * @copyright: Copyright 2016-2018 Pierre-François Angrand
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
 * App\Entity\Structure
 *
 * @ORM\Table(name="structure")
 * @ORM\Entity(repositoryClass="Gesseh\RegisterBundle\Entity\StructureRepository")
 * @UniqueEntity(fields={"name"}, message="Une structure ayant ce nom-là existe déjà.")
 * @Vich\Uploadable
 */
class Structure
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
     * @ORM\Column(name="name", type="string", length=100, unique=true)
     * @Assert\NotBlank(message="Donnez un nom à la structure")
     *
     * @var string $name
     */
    private $name;

    /**
     * @ORM\Column(name="fullname", type="string", length=255, nullable=true)
     *
     * @var string $fullname
     */
    private $fullname;

    /**
     * @ORM\Column(name="slug", type="string", length=100, unique=true)
     *
     * @var string $slug
     */
    private $slug;

    /**
     * @ORM\Column(name="email", type="string", length=150, nullable=true)
     * @Assert\Email
     *
     * @var string $email
     */
    private $email;

    /**
     * @ORM\Column(name="address", type="array")
     *
     * @var array $address
     */
    private $address;

    /**
     * @ORM\Column(name="url", type="string", length=255, nullable=true)
     * @Assert\Url
     *
     * @var string $url
     */
    private $url;

    /**
     * @ORM\Column(name="phone", type="string", length=15, nullable=true)
     * @Assert\Length(min=10, max=13)
     *
     * @var integer $phone
     */
    private $phone;

    /**
     * @ORM\Column(name="area", type="string", length=100, nullable=true)
     *
     * @var string $area
     */
    private $area;

    /**
     * @ORM\Column(name="areamap", type="array", nullable=true)
     *
     * @var array $areamap
     */
    private $areamap;

    /**
     * @ORM\Column(name="logo", type="string", length=255, nullable=true)
     *
     * @var string $logoName
     */
    private $logoName;

    /**
     * @Vich\UploadableField(mapping="structure_logo", fileNameProperty="logoName")
     * @Assert\File(
     *     maxSize = "1000k",
     *     mimeTypes = {"image/jpeg", "image/gif", "image/png"},
     *     mimeTypesMessage = "Le fichier doit être une image (PNG, JPEG, GIF)"
     * )
     *
     * @var File $logoFile
     */
    private $logoFile;
    /**
     * @ORM\OneToMany(targetEntity="\Gesseh\ParameterBundle\Entity\Parameter", mappedBy="structure")
     *
     * @var EntityCollection $parameters
     */
    private $parameters;

    /**
     * @ORM\OneToMany(targetEntity="\Gesseh\RegisterBundle\Entity\Gateway", mappedBy="structure")
     *
     * @var EntityCollection $gateways
     */
    private $gateways;

    /**
     * @ORM\Column(name="activated", type="boolean", nullable=true)
     *
     * @var boolean $activated
     */
    private $activated;

    /**
     * @ORM\OneToMany(targetEntity="\Gesseh\RegisterBundle\Entity\Receipt", mappedBy="structure")
     *
     * @var EntityCollection $receipts
     */
    private $receipts;

    /**
     * @ORM\OneToMany(targetEntity="\Gesseh\RegisterBundle\Entity\Fee", mappedBy="structure")
     *
     * @var EntityCollection $fees
     */
    private $fees;

    /**
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     *
     * @var \DateTime $updatedAt
     */
    private $updatedAt;

    public function __construct()
    {
    }

    public function __toString()
    {
      return $this->name;
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
     * Set name
     *
     * @param string $name
     * @return Structure
     */
    public function setName($name)
    {
        $this->name = $name;
        $this->slug = $this->slugify($name);

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Slugify name
     *
     * @param string $name
     * @return string
     */
    private function slugify($text)
    {
        $text = preg_replace('/\W+/', '_', $text);
        $text = strtolower(trim($text, '-'));

        return $text;
    }


    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Structure
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Add parameter
     *
     * @param \Gesseh\RegisterBundle\Entity\Parameter $parameter
     *
     * @return Structure
     */
    public function addParameter(\Gesseh\ParameterBundle\Entity\Parameter $parameter)
    {
        $this->parameters[] = $parameter;

        return $this;
    }

    /**
     * Remove parameter
     *
     * @param \Gesseh\RegisterBundle\Parameter $parameter
     */
    public function removeParameter(\Gesseh\ParameterBundle\Entity\Parameter $parameter)
    {
        $this->parameters->removeElement($parameter);
    }

    /**
     * Get parameters
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * Add gateway
     *
     * @param \Gesseh\RegisterBundle\Entity\Gateway $gateway
     *
     * @return Structure
     */
    public function addGateway(\Gesseh\RegisterBundle\Entity\Gateway $gateway)
    {
        $this->gateways[] = $gateway;

        return $this;
    }

    /**
     * Remove gateway
     *
     * @param \Gesseh\RegisterBundle\Entity\Gateway $gateway
     */
    public function removeGateway(\Gesseh\RegisterBundle\Entity\Gateway $gateway)
    {
        $this->gateways->removeElement($gateway);
    }

    /**
     * Get gateways
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGateways()
    {
        return $this->gateways;
    }

    /**
     * Set address
     *
     * @param array $address
     *
     * @return Structure
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return array
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Get printable address
     *
     * @return string
     */
    public function getPrintableAddress($html = false)
    {
        $address = $this->address['number'] . ' ' . $this->address['type'] . ' ' . $this->address['street'];
        if ($complement = $this->address['complement']) {
            $address .= ($html?'<br />':', ') . $complement;
        }
        $address .= ($html?'<br />':', ') . $this->address['code'] . ', ' . $this->address['city'] . ', ' . $this->address['country'];

        return $address;
    }

    /**
     * Set area
     *
     * @param string $area
     *
     * @return Structure
     */
    public function setArea($area)
    {
        $this->area = $area;

        return $this;
    }

    /**
     * Get area
     *
     * @return string
     */
    public function getArea()
    {
        return $this->area;
    }

    /**
     * Set logoName
     *
     * @param string $logoName
     *
     * @return Structure
     */
    public function setLogoName(string $logoName = null)
    {
        $this->logoName = $logoName;

        return $this;
    }

    /**
     * Get logoName
     *
     * @return string
     */
    public function getLogoName()
    {
        return $this->logoName;
    }

    /**
     * Set activated
     *
     * @param boolean $activated
     *
     * @return Structure
     */
    public function setActivated($activated)
    {
        $this->activated = $activated;

        return $this;
    }

    /**
     * Is activated?
     *
     * @return boolean
     */
    public function isActivated()
    {
        return $this->activated;
    }

    /**
     * Set fullname
     *
     * @param string $fullname
     *
     * @return Structure
     */
    public function setFullname($fullname)
    {
        $this->fullname = $fullname;

        return $this;
    }

    /**
     * Get fullname
     *
     * @return string
     */
    public function getFullname()
    {
        return $this->fullname;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Structure
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Get activated
     *
     * @return boolean
     */
    public function getActivated()
    {
        return $this->activated;
    }

    /**
     * Add receipt
     *
     * @param \Gesseh\RegisterBundle\Entity\Receipt $receipt
     *
     * @return Structure
     */
    public function addReceipt(\Gesseh\RegisterBundle\Entity\Receipt $receipt)
    {
        $this->receipts[] = $receipt;

        return $this;
    }

    /**
     * Remove receipt
     *
     * @param \Gesseh\RegisterBundle\Entity\Receipt $receipt
     */
    public function removeReceipt(\Gesseh\RegisterBundle\Entity\Receipt $receipt)
    {
        $this->receipts->removeElement($receipt);
    }

    /**
     * Get receipts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReceipts()
    {
        return $this->receipts;
    }

    /**
     * Add fee
     *
     * @param \Gesseh\RegisterBundle\Entity\Fee $fee
     *
     * @return Structure
     */
    public function addFee(\Gesseh\RegisterBundle\Entity\Fee $fee)
    {
        $this->fees[] = $fee;

        return $this;
    }

    /**
     * Remove fee
     *
     * @param \Gesseh\RegisterBundle\Entity\Fee $fee
     */
    public function removeFee(\Gesseh\RegisterBundle\Entity\Fee $fee)
    {
        $this->fees->removeElement($fee);
    }

    /**
     * Get fees
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFees()
    {
        return $this->fees;
    }

    /**
     * Set areamap
     *
     * @param array $areamap
     *
     * @return Structure
     */
    public function setAreamap($areamap)
    {
        $this->areamap = $areamap;

        return $this;
    }

    /**
     * Get areamap
     *
     * @return array
     */
    public function getAreamap()
    {
        return $this->areamap;
    }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return Structure
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set phone
     *
     * @param integer $phone
     *
     * @return Structure
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return integer
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Get Role
     *
     * @return string
     */
    public function getRole()
    {
        return 'ROLE_STRUCTURE_' . strtoupper($this->slug);
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Structure
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
     * Set logoFile
     *
     * @param File $logoFile
     * @return Receipt
     */
    public function setLogoFile(File $logoFile = null)
    {
        $this->logoFile = $logoFile;

        if ($logoFile)
            $this->updatedAt = new \DateTimeImmutable();

        return $this;
    }

    /**
     * Get logoFile
     *
     * @return File
     */
    public function getLogoFile()
    {
        return $this->logoFile;
    }
}
