<div class="content">Le fichier doit être au format Ms/Excel (.xls). Veillez à bien vérifier que les colonnes définies dans <a href="<?php echo url_for('csSetting/index') ?>">Paramètres</a> correspondent bien à votre fichier.</div>

<form action="<?php echo url_for('admEtudiant/importcreate') ?>" method="post" enctype="multipart/form-data">
<table>
  <tfoot>
    <tr>
      <td colspan="2"><input type="submit" value="Importer" /></td>
    </tr>
  </tfoot>
  <tbody>
    <?php echo $form; ?>
  </tbody>
</table>
</form>
