<?php


class GessehEtudiantTable extends Doctrine_Table
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('GessehEtudiant');
    }

    public function save(Doctrine_Connection $conn)
    {
      if (!$this->getTokenMail())
      {
        if ($this->getEmail() != $this->getActiveUserEmail())
	{
	  $this->setTokenMail(sha1($this->getValue('email').rand(11111, 99999)));
	  $this->sendMailToken($this->getEmail(), $this->getToken());
	}
      }

      return parent::save($conn);
    }

    public function sendMailToken($mail, $token)
    {
      $message = sfContext::getInstance()->getMailer()->compose(
        array('tmp@angrand.fr' => 'Administration Paris-Ouest'),
	  $email,
	  '[Paris-Ouest] Validation de votre nouvelle adresse mail',
	  <<<EOF
Bonjour,

Vous venez de changer votre adresse mail sur le gestionnaire d'évaluation de la faculté. Pour confirmer le changement d'adresse e-mail, nous vous prions de bien vouloir cliquer sur le lien suivant :

{$sf_request->getRelativeUrlRoot()}/etudiant/mail/{sfContext::getInstance()->getUser()->getUsername()}/{$token}

Merci.

L'administration de la faculté de médecine Paris-Ile-de-France-Ouest.

Ce message a été généré automatiquement, merci de ne pas y répondre.
EOF
      );
      $this->getMailer()->send($message);
    }

    public function getActiveUserEmail()
    {
      $q = Doctrine_Query::create()
        ->from('GessehEtudiant a')
	->select('a.email')
	->where('a.nom = ?', sfContext::getInstance()->getUser()->getUsername())
	->fetchOne();
      return $q;
    }

    public function validTokenMail($user, $token)
    {
      $q = Doctrine_Query::create()
        ->from('GessehEtudiant a')
	->select('a.token_mail')
	->where('a.id = ?', $user)
	->fetchOne();

      if($q == $token)
      {
        Doctrine_Query::create()
	  ->update('GessehEtudiant a')
	  ->set('a.token_mail', '?', null)
	  ->execute();
	return true;
      }
      else
        return false;
    }

    public static function changePromo($promo_depart, $promo_arrivee)
    {
      $q = Doctrine_Query::create()
        ->update('GessehEtudiant a')
	->set('promo_id', '?', $promo_arrivee)
	->where('promo_id = ?', $promo_depart);
      return $q->execute();
    }

    public static function importPromo($fichier)
    {
    }
}
