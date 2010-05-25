<form
  action="<?php echo url_for('eval/'.($form->getObject()->isNew() ? 'create' : 'update').'?iduser='.$user.'&idstage='.$gesseh_stage->getId()) ?>" 
  method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>

  <?php if (!$form->getObject()->isNew()): ?>
    <input type="hidden" name="sf_method" value="put" />
  <?php endif; ?>
  <table>
    <tfoot>
      <tr>
        <td colspan="2">
	  &nbsp;<a href="<?php echo url_for('etudiant/index?iduser='.$user) ?>">Retour à la liste des stages</a>
	  <input type="submit" value="Enregistrer" />
	</td>
      </tr>
    </tfoot>
    <tbody>
        <?php echo $form ?>
    </tbody>
  </table>
</form>
