<?php include_partial('menu') ?>
<?php include_partial('flash') ?>

<h1>Changer d'année universitaire et mettre à jour les promotions d'étudiants</h1>

<div>Ce module va mettre à jour les promos des étudiants dans l'ordre déterminé ci-dessous. Attention, si vous modifiez le schéma ci-dessous, à ne pas fusionner 2 promotions !<br />
Il ne vous restera ensuite plus qu'à <a href="<?php url_for('admEtudiant/index'); ?>">modifier manuellement les étudiants doublants</a> et à <a href="<?php url_for('admEtudiant/new'); ?>">ajouter les étudiants transférés</a>.<br /></div>

<form action="<?php echo url_for('gestion/update') ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
  <table>
    <tfoot>
      <tr>
        <td colspan="4">
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
	<td> >>> </td>
	<td>
	  <?php echo $form['promo_fin_'.$i]->renderError() ?>
	  <?php echo $form['promo_fin_'.$i] ?>
	</td>
      </tr>
	<?php endfor; ?>
    </tbody>
  </table>
</form>

