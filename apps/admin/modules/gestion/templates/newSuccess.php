<h1>Edit Gesseh etudiant</h1>

Ce module va mettre à jour les promos des étudiants dans l'ordre qui suit :
<ol>
  <li>Les "DCEM 4" se retrouvent "hors promo"</li>
  <li>Les "DCEM 3" se retrouvent "DCEM 4"</li>
  <li>Les "DCEM 2" se retrouvent "DCEM 3"</li>
  <li>Les "DCEM 1" se retrouvent "DCEM 2"</li>
  <li>Les "PCEM 2" se retrouvent "DCEM 1"</li>
</ol>
Il vous restera ensuite à <a href="<?php url_for('gestion/new'); ?>">importer une nouvelle promo de PCEM 2</a>, <a href="<?php url_for('admEtudiant/index'); ?>">modifier manuellement les étudiants doublants</a> et à <a href="<?php url_for('admEtudiant/new'); ?>">ajouter les étudiants transférés</a>.
<?php include_partial('form', array('form' => $form)) ?>
