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
        <th><?php echo $form['PromoP2']->renderLabel() ?></th>
        <td colspan="2">
          <?php echo $form['PromoP2']['fichier']->renderError() ?>
          <?php echo $form['PromoP2']['fichier'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['Periode1']->renderLabel(); ?></th>
	<td><?php echo $form['Periode1']['debut'] ?></td>
	<td><?php echo $form['Periode1']['fin'] ?></td>
      </tr>
      <tr>
        <th><?php echo $form['Periode2']->renderLabel(); ?></th>
	<td><?php echo $form['Periode2']['debut'] ?></td>
	<td><?php echo $form['Periode2']['fin'] ?></td>
      </tr>
      <tr>
        <th><?php echo $form['Periode3']->renderLabel(); ?></th>
	<td><?php echo $form['Periode3']['debut'] ?></td>
	<td><?php echo $form['Periode3']['fin'] ?></td>
      </tr>
      <tr>
        <th><?php echo $form['Periode4']->renderLabel(); ?></th>
	<td><?php echo $form['Periode4']['debut'] ?></td>
	<td><?php echo $form['Periode4']['fin'] ?></td>
      </tr>
    </tbody>
  </table>
</form>
