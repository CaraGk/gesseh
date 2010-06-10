<?php include_partial('menu') ?>
<?php include_partial('flash') ?>

<h1>Importer la liste des étudiants de la nouvelle promotion</h1>

<div class="content">Ce module va importer une nouvelle promotion d'étudiants. Le fichier doit être au format Ms/Excel (.xls).<br />
Veillez à bien vérifier que les colonnes définies dans <a href="<?php echo url_for('csSetting/index') ?>">Paranètres</a> correspondent bien à votre fichier.</div>

<form action="<?php echo url_for('gestion/importcreate') ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
<table>
  <tfoot>
    <tr>
      <td><input type="submit" value="Importer" /></td>
    </tr>
  </tfoot>
  <tbody>
    <?php echo $form; ?>
  </tbody>
</table>
</form>
