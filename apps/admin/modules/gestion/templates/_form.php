<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<form action="<?php echo url_for('gestion/create') ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
  <table>
    <tfoot>
      <tr>
        <td colspan="3">
          <?php echo $form->renderHiddenFields(false) ?>
          <input type="submit" value="Valider" />
        </td>
      </tr>
    </tfoot>
    <tbody>
      <?php echo $form->renderGlobalErrors() ?>
        <?php for($i=1 ; $i < $count_promos ; $i++): ?>
      <tr>
        <th><?php echo $form['promo_debut_'.$i]->renderLabel() ?></th>
	<td>
	  <?php echo $form['promo_debut_'.$i]->renderError() ?>
	  <?php echo $form['promo_debut_'.$i] ?>
	</td>
	<td>
	  >>>
	  <?php echo $form['promo_fin_'.$i]->renderError() ?>
	  <?php echo $form['promo_fin_'.$i] ?>
	</td>
      </tr>
	<?php endfor; ?>
      <tr>
        <th><?php echo $form['fichier']->renderLabel() ?></th>
        <td colspan="2">
          <?php echo $form['fichier']->renderError() ?>
          <?php echo $form['fichier'] ?>
        </td>
      </tr>
    </tbody>
  </table>
</form>
