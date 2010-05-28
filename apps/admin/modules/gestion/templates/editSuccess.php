<h1>Edit Gesseh etudiant</h1>

Ce module va mettre à jour les promos des étudiants dans l'ordre qui suit proposé.<br />
Il vous restera ensuite à <a href="<?php url_for('admEtudiant/index'); ?>">modifier manuellement les étudiants doublants</a> et à <a href="<?php url_for('admEtudiant/new'); ?>">ajouter les étudiants transférés</a>.<br />

Le fichier excel que vous importerez doit être correctement paramétré pour que cela fonctionne :<br />
Numéro étudiant | Nom | Prénom

<?php include_partial('form', array('form' => $form)) ?>
