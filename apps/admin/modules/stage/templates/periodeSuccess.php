<?php include_partial('menu') ?>
<?php include_partial('flash') ?>

<h1>Changer d'année universitaire et mettre à jour les promotions d'étudiants</h1>

<div>Les 4 périodes correspondent aux dates de début et fin de stage de l'années suivante.</div>

<form action="<?php echo url_for('gestion/periodecreate') ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
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
      <tr>
        <th>Periode1</th>
	<td><?php echo $form['debut'] ?></td>
	<td><?php echo $form['fin'] ?></td>
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
