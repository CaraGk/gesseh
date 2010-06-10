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
      <?php echo $form; ?>
    </tbody>
  </table>
</form>
