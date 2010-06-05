<h1>Gesseh stages List</h1>

<table>
  <thead>
    <tr>
      <th>Période</th>
      <th>Stage</th>
      <th>Action</th>
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
      <td><?php echo $gesseh_stage->getGessehTerrain()->getFiliere() ?> à <?php echo $gesseh_stage->getGessehTerrain()->getGessehHopital()->getNom(); ?> (<?php echo $gesseh_stage->getGessehTerrain()->getPatron(); ?>)</td>
      <td>
        <?php if($gesseh_stage->getIsActive()): ?>
	  <a href="<?php echo url_for('eval/new?idstage='.$gesseh_stage->getId()); ?>">Evaluer</a>
	<?php else: ?>
	  <a href="<?php echo url_for('eval/show?idstage='.$gesseh_stage->getId()); ?>">Voir</a>
	<?php endif; ?>
      </td>
      </a>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
