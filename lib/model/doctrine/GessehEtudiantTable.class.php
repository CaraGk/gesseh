<?php


class GessehEtudiantTable extends Doctrine_Table
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('GessehEtudiant');
    }

    public function retrieveActiveEtudiant(Doctrine_Query $q)
    {
      $rootAlias = $q->getRootAlias();
      $q->leftjoin($rootAlias.'.GessehPromo c')
        ->where('c.ordre < ?', '6')
	->orderBy('c.ordre asc, '.$rootAlias.'.nom asc, '.$rootAlias.'.prenom asc');

      return $q;
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
      $promo = Doctrine_query::create()
        ->from('GessehPromo a')
	->where('a.ordre = ?', '1')
	->limit(1)
	->fetchOne();

      $data = new sfExcelReader($fichier);

//      echo $data->dump(true, true);
      for($i = 1 ; $i <= $data->rowcount($sheet_index=0) ; $i++)
      {
        $etudiant = new GessehEtudiant();
	$etudiant->setId($data->val($i, csSettings::get('excelrownumber_promo_identifiant')));
	$etudiant->setNom($data->val($i, csSettings::get('excelrownumber_promo_nom')));
	$etudiant->setPrenom($data->val($i, csSettings::get('excelrownumber_promo_prenom')));
	$date = explode('/', $data->val($i, csSettings::get('excelrownumber_promo_naissance')));
	$etudiant->setNaissance('19'.$date[2].'-'.$date[1].'-'.$date[0]);
	$etudiant->setPromoId($promo->getId());
	$etudiant->save();

	$user = new sfGuardUser();
	$user->setUsername($etudiant->getId());
	$user->setPassword($date[0].$date[1].'19'.$date[2]);
	$user->save();
	$user->addGroupByName('etudiant');
	$user->addPermissionByName('etudiant');
      }

      return $data->rowcount($sheet_index=0);
    }

}
