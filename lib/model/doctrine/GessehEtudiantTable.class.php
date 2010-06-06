<?php


class GessehEtudiantTable extends Doctrine_Table
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('GessehEtudiant');
    }

    public function save(Doctrine_Connection $conn)
    {
      if ($this->getEmail() != $this->getTokenMail() and $this->getTokenMail())
        $this->sendMailValidation($this->getTokenMail());
      else
        $this->setTokenMail('');

      return parent::save($conn);
    }

    public function sendMailValidation($email)
    {
      $token = sha1(sfContext::getInstance()->getUser()->getUsername().$email);
      $message = sfContext::getInstance()->getMailer()->compose(
        array('tmp@angrand.fr' => 'Administration Paris-Ouest'),
	  $email,
	  '[Paris-Ouest] Validation de votre nouvelle adresse mail',
	  <<<EOF
Bonjour,

Vous venez de changer votre adresse mail sur le gestionnaire d'évaluations de la faculté. Pour confirmer le changement d'adresse e-mail, nous vous prions de bien vouloir cliquer sur le lien suivant :

{$sf_request->getRelativeUrlRoot()}/etudiant/mail/{sfContext::getInstance()->getUser()->getUsername()}/{$token}

Merci.

L'administration de la faculté de médecine Paris-Ile-de-France-Ouest.

Ce message a été généré automatiquement, merci de ne pas y répondre.
EOF
      );
      $this->getMailer()->send($message);
    }

    public function validTokenMail($user, $token)
    {
      $q = Doctrine_Query::create()
        ->from('GessehEtudiant a')
	->select('a.token_mail')
	->where('a.id = ?', $user)
	->fetchOne();

      if ($token == sha1($user.$q))
      {
        Doctrine_Query::create()
	  ->update('GessehEtudiant a')
	  ->set('a.token_mail', '?', null)
	  ->set('a.email', '?', $q)
	  ->execute();
	return true;
      }
      else
        return false;
    }

    public function checkValidMail($user)
    {
      $q = Doctrine_Query::create()
        ->from('GessehEtudiant a')
	->select('a.email, a.token_mail')
	->where('a.id = ?', $user)
	->limit(1)
	->fetchOne();
      
      if (!$q->getEmail() or $q->getTokenMail())
        return false;
      else
        return true;
    }

    public static function changePromo($promo_depart, $promo_arrivee)
    {
      $q = Doctrine_Query::create()
        ->update('GessehEtudiant a')
	->set('promo_id', '?', $promo_arrivee)
	->where('promo_id = ?', $promo_depart);
      return $q->execute();
    }

    public static function importFichier($fichier)
    {
    }
}
