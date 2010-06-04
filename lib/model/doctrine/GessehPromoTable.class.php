<?php


class GessehPromoTable extends Doctrine_Table
{
    public static function getInstance()
    {
        return Doctrine_Core::getTable('GessehPromo');
    }

    public function getChoices()
    {
      $promos = Doctrine_Core::getTable('GessehPromo')
        ->createQuery('a')
	->execute();

      $choices_promo = array('0' => '');
      foreach($promos as $promo)
        $choices_promo = array_merge($choices_promo, array($promo->getId() => $promo->getTitre()));

      return $choices_promo;
    }
}

?>
