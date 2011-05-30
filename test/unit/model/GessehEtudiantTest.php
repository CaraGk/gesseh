<?php

include(dirname(__FILE__).'/../../bootstrap/Doctrine.php');

$t = new lime_test(1);

$t->comment('->validTokenMail()');
$etudiant = Doctrine_Core::getTable('GessehEtudiant')->createQuery()->fetchOne();
$t->ok($etudiant->validTokenMail($etudiant->getId(), sha1($etudiant->getId().$etudiant->getTokenMail())), '->validTokenMail() retourne true si le token est correct');

?>
