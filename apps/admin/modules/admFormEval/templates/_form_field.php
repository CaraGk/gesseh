<?php if ($field->isPartial()): ?>
  <?php include_partial('admFormEval/'.$name, array('form' => $form, 'attributes' => $attributes instanceof sfOutputEscaper ? $attributes->getRawValue() : $attributes)) ?>
<?php elseif ($field->isComponent()): ?>
  <?php include_component('admFormEval', $name, array('form' => $form, 'attributes' => $attributes instanceof sfOutputEscaper ? $attributes->getRawValue() : $attributes)) ?>
<?php else: ?>
  <div class="<?php echo $class ?><?php $form[$name]->hasError() and print ' errors' ?>">
    <?php echo $form[$name]->renderError() ?>
    <div>
      <?php echo $form[$name]->renderLabel($label) ?>

      <?php if ($name == "GessehCritere"): ?>
        <div class="content">
          <table>
            <tr>
              <td></td>
              <td>Titre</td>
              <td>Type</td>
              <td>Ratio (si 'radio')</td>
              <td>Ordre</td>
              <td>Supprimer</td>
            </tr>
            <?php foreach ($form[$name] as $criteria_form): ?>
              <tr>
                <?php foreach ($criteria_form as $criteria_form_field): ?>
                  <td><?php echo $criteria_form_field ?></td>
                <?php endforeach; ?>
              </tr>
            <?php endforeach; ?>
          </table>
        </div>
      <?php else: ?>
        <div class="content"><?php echo $form[$name]->render($attributes instanceof sfOutputEscaper ? $attributes->getRawValue() : $attributes) ?></div>
      <?php endif; ?>

      <?php if ($help): ?>
        <div class="help"><?php echo __($help, array(), 'messages') ?></div>
      <?php elseif ($help = $form[$name]->renderHelp()): ?>
        <div class="help"><?php echo $help ?></div>
      <?php endif; ?>
    </div>
  </div>
<?php endif; ?>
