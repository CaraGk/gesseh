<?php include_partial('menu') ?>
<?php include_partial('flash') ?>

<h1>Derniers commentaires publiés</h1>

<table>
  <thead>
    <tr>
      <th>Période</th>
      <th>Stage</th>
      <th>Type</th>
      <th>Commentaire</th>
      <th>Posté le</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($gesseh_evals as $gesseh_eval): ?>
    <tr>
      <td><?php echo $gesseh_eval->getGessehStage()->getGessehPeriode()->getDebut()." - ".$gesseh_eval->getGessehStage()->getGessehPeriode()->getFin(); ?></td>
      <td><?php echo $gesseh_eval->getGessehStage()->getGessehTerrain()->getFiliere()." ".$gesseh_eval->getGessehStage()->getGessehTerrain()->getGessehHopital()->getNom(); ?></td>
      <td><?php echo $gesseh_eval->getGessehCritere()->getTitre(); ?></td>
      <td><?php echo $gesseh_eval->getValeur(); ?></td>
      <td><?php echo $gesseh_eval->getCreatedAt(); ?></td>
      <td><?php echo link_to('Supprimer', 'gestion/delete?id='.$gesseh_eval->getId(), array('method' => 'delete', 'confirm' => 'Are you sure?')) ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

