<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <gesseh@medlibre.fr>
 * @copyright: Copyright 2013-2017 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Gesseh\UserBundle\Entity\User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="Gesseh\UserBundle\Entity\UserRepository")
 * @UniqueEntity(
 *      fields={"emailCanonical", "email", "username", "usernameCanonical"},
 *      errorPath="email",
 *      message="Cette adresse e-mail est déjà utilisée.")
 */
class User extends BaseUser
{
  /**
   * @var integer $id
   *
   * @ORM\Column(name="id", type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;

  public function __construct()
  {
    parent::__construct();
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
   * Set email
   *
   * @return User
   */
  public function setEmail($email)
  {
      $this->email = $email;
      $this->username = $email;

      return $this;
  }

  /**
   * Set emailCanonical
   *
   * @return User
   */
  public function setEmailCanonical($emailCanonical)
  {
    $this->emailCanonical = $emailCanonical;
    $this->usenameCanonical = $emailCanonical;

    return $this;
  }

    public function generatePassword($length = 8)
    {
        $characters = array ('a','z','e','r','t','y','u','p','q','s','d','f','g','h','j','k','m','w','x','c','v','b','n','2','3','4','5','6','7','8','9','A','Z','E','R','T','Y','U','P','S','D','F','G','H','J','K','L','M','W','X','C','V','B','N');
        $password = '';

        for ($i = 0 ; $i < $length ; $i++) {
            $rand = array_rand($characters);
            $password .= $characters[$rand];
        }

        $this->setPlainPassword($password);

        return $password;
    }
}
