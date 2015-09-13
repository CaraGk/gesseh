<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <gesseh@medlibre.fr>
 * @copyright: Copyright 2013 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\MigrateOldDbBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Gesseh\MigrateOldDbBundle\Entity\sf_guard_user
 *
 * @ORM\Table(name="sf_guard_user")
 * @ORM\Entity
 */
class sfGuardUser
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
     * @var string $first_name
     *
     * @ORM\Column(name="first_name", type="string", length=255)
     */
    private $first_name;

    /**
     * @var string $last_name
     *
     * @ORM\Column(name="last_name", type="string", length=255)
     */
    private $last_name;

    /**
     * @var string $email_address
     *
     * @ORM\Column(name="email_address", type="string", length=255)
     */
    private $email_address;

    /**
     * @var string $username
     *
     * @ORM\Column(name="username", type="string", length=128)
     */
    private $username;

    /**
     * @var string $algorithm
     *
     * @ORM\Column(name="algorithm", type="string", length=128)
     */
    private $algorithm;

    /**
     * @var string $salt
     *
     * @ORM\Column(name="salt", type="string", length=128)
     */
    private $salt;

    /**
     * @var string $password
     *
     * @ORM\Column(name="password", type="string", length=128)
     */
    private $password;

    /**
     * @var smallint $is_active
     *
     * @ORM\Column(name="is_active", type="smallint")
     */
    private $is_active;

    /**
     * @var smallint $is_super_admin
     *
     * @ORM\Column(name="is_super_admin", type="smallint")
     */
    private $is_super_admin;

    /**
     * @var datetime $last_login
     *
     * @ORM\Column(name="last_login", type="datetime")
     */
    private $last_login;

    /**
     * @var datetime $created_at
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $created_at;

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
     * Set first_name
     *
     * @param string $firstName
     */
    public function setFirstName($firstName)
    {
        $this->first_name = $firstName;
    }

    /**
     * Get first_name
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * Set last_name
     *
     * @param string $lastName
     */
    public function setLastName($lastName)
    {
        $this->last_name = $lastName;
    }

    /**
     * Get last_name
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->last_name;
    }

    /**
     * Set email_address
     *
     * @param string $emailAddress
     */
    public function setEmailAddress($emailAddress)
    {
        $this->email_address = $emailAddress;
    }

    /**
     * Get email_address
     *
     * @return string
     */
    public function getEmailAddress()
    {
        return $this->email_address;
    }

    /**
     * Set username
     *
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set algorithm
     *
     * @param string $algorithm
     */
    public function setAlgorithm($algorithm)
    {
        $this->algorithm = $algorithm;
    }

    /**
     * Get algorithm
     *
     * @return string
     */
    public function getAlgorithm()
    {
        return $this->algorithm;
    }

    /**
     * Set salt
     *
     * @param string $salt
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    }

    /**
     * Get salt
     *
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set password
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set is_active
     *
     * @param smallint $isActive
     */
    public function setIsActive($isActive)
    {
        $this->is_active = $isActive;
    }

    /**
     * Get is_active
     *
     * @return smallint
     */
    public function getIsActive()
    {
        return $this->is_active;
    }

    /**
     * Set is_super_admin
     *
     * @param smallint $isSuperAdmin
     */
    public function setIsSuperAdmin($isSuperAdmin)
    {
        $this->is_super_admin = $isSuperAdmin;
    }

    /**
     * Get is_super_admin
     *
     * @return smallint
     */
    public function getIsSuperAdmin()
    {
        return $this->is_super_admin;
    }

    /**
     * Set last_login
     *
     * @param datetime $lastLogin
     */
    public function setLastLogin($lastLogin)
    {
        $this->last_login = $lastLogin;
    }

    /**
     * Get last_login
     *
     * @return datetime
     */
    public function getLastLogin()
    {
        return $this->last_login;
    }

    /**
     * Set created_at
     *
     * @param datetime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->created_at = $createdAt;
    }

    /**
     * Get created_at
     *
     * @return datetime
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Set updated_at
     *
     * @param datetime $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updated_at = $updatedAt;
    }

    /**
     * Get updated_at
     *
     * @return datetime
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }
}
