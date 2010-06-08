<?php


class GessehEtudiantTable extends Doctrine_Table
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('GessehEtudiant');
    }

    public function validTokenMail($user, $mailtmp)
    {
      $q = Doctrine_Query::create()
        ->from('GessehEtudiant a')
	->select('a.token_mail')
	->where('a.id = ?', $user)
	->fetchOne();

      if ($mailtmp === sha1($user.$q->getTokenMail()))
      {
        $q2 = Doctrine_Query::create()
	  ->update('GessehEtudiant a')
	  ->set('a.token_mail', '?', '')
	  ->set('a.email', '?', $q->getTokenMail())
	  ->where('a.id = ?', $user);
	return $q2->execute();
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
