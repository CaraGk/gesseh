<h1>Gesseh evals List</h1>

<table>
  <thead>
    <tr>
      <th>Stage</th>
      <th>Critere</th>
      <th>Valeur</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($gesseh_evals as $gesseh_eval): ?>
    <tr>
      <td><?php echo $gesseh_eval->getStageId() ?></td>
      <td><?php echo $gesseh_eval->getCritereId() ?></td>
      <td><?php echo $gesseh_eval->getValeur() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <a href="<?php echo url_for('eval/new') ?>">Nouvelle évaluation</a>
