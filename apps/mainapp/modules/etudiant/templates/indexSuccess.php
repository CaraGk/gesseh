<h1>Ma liste de stage effectués / à valider</h1>

<table>
  <thead>
    <tr>
      <th>Période</th>
      <th>Stage</th>
      <th>Agrément</th>
      <?php if (csSettings::get('mod_eval')): ?>
        <th>Évaluation</th>
      <?php endif; ?>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($gesseh_stages as $gesseh_stage): ?>
    <tr class="
      <?php if($gesseh_stage->getIsActive()):
        echo 'active';
      else:
        echo 'valide';
      endif; ?>">
      <td><?php echo $gesseh_stage->getGessehPeriode()->getDebut().' - '.$gesseh_stage->getGessehPeriode()->getFin(); ?></td>
      <td><?php echo $gesseh_stage->getGessehTerrain()->getTitre(); ?> à <?php echo $gesseh_stage->getGessehTerrain()->getGessehHopital()->getTitre(); ?> (<?php echo $gesseh_stage->getGessehTerrain()->getPatron(); ?>)</td>
      <td><?php echo $gesseh_stage->getGessehTerrain()->getGessehFiliere()->getTitre(); ?></td>
      <?php if (csSettings::get('mod_eval')): ?>
        <td>
          <?php if($gesseh_stage->getIsActive()): ?>
	          <a href="<?php echo url_for('eval/new?idstage='.$gesseh_stage->getId()); ?>">Evaluer</a>
          <?php else: ?>
            <a href="<?php echo url_for('eval/show?idstage='.$gesseh_stage->getId()); ?>">Voir</a>
          <?php endif; ?>
        </td>
      <?php endif; ?>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
