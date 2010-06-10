<?php include_partial('menu') ?>
<?php include_partial('flash') ?>

<h1>Importer la liste des stages attribués aux étudiants</h1>

<div class="content">Ce module va mettre à jour la liste des stages attribués par étudiant. Le fichier doit être au format Ms/Excel (.xls). Les colonnes doivent être :<br />
Veillez à bien vérifier que les colonnes définies dans <a href="<?php echo url_for('csSetting/index') ?>">Paranètres</a> correspondent bien à votre fichier.</div>

<form action="<?php echo url_for('gestion/importcreate') ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
<table>
  <tbody>
    <?php echo $form; ?>
  </tbody>
</table>
</form>
