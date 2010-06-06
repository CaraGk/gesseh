<h1>Changer d'année universitaire et mettre à jour les promotions d'étudiants</h1>

<div>Ce module va mettre à jour les promos des étudiants dans l'ordre déterminé ci-dessous. Attention, si vous modifiez le schéma ci-dessous, à ne pas fusionner 2 promotions !<br />
Le fichier à transmettre doit être au format MS/Excel (.xls) ou CSV (.csv). Ses colonnes doivent être : Numéro étudiant | Nom | Prénom<br />
Les 4 périodes correspondent aux dates de début et fin de stage de l'années suivante.<br />
Il ne vous restera ensuite plus qu'à <a href="<?php url_for('admEtudiant/index'); ?>">modifier manuellement les étudiants doublants</a> et à <a href="<?php url_for('admEtudiant/new'); ?>">ajouter les étudiants transférés</a>.<br /></div>

<div><?php include_partial('form', array('form' => $form, 'count_promos' => $count_promos)) ?></div>
