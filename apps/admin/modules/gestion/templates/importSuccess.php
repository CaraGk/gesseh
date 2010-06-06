<h1>Importer la liste des stages attribués aux étudiants</h1>
<div class="content">Ce module va mettre à jour la liste des stages attribués par étudiant. Le fichier doit être au format Ms/Excel (.xls) ou CSV (.csv). Les colonnes doivent être :<br />
Date de début du stage | Date de fin du stage | Nom de l'étudiant | Prénom de l'étudiant | Stage | Hopital | Patron</div>

<form action="<?php echo url_for('gestion/importcreate') ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
<table>
  <tbody>
    <?php echo $form; ?>
  </tbody>
</table>
</form>
