<form
  action="<?php echo url_for('eval/'.($form->getObject()->isNew() ? 'create' : 'update').'?id='.$gesseh_stage->getId()) ?>" 
  method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>

  <?php if (!$form->getObject()->isNew()): ?>
    <input type="hidden" name="sf_method" value="put" />
  <?php endif; ?>
  <table>
    <tfoot>
      <tr>
        <td colspan="2">
	  &nbsp;<a href="<?php echo url_for('monstage/1') ?>">Retour</a>
	  <input type="submit" value="Enregistrer" />
	</td>
      </tr>
    </tfoot>
    <tbody>
        <?php echo $form ?>
    </tbody>
  </table>
</form>
