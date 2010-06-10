<?php include_partial('menu') ?>
<?php include_partial('flash') ?>

<h1>Liste des attributions de stage</h1>

<table>
  <thead>
    <tr>
      <th>Période</th>
      <th>Etudiant</th>
      <th>Promo</th>
      <th>Stage</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($gesseh_stages as $gesseh_stage): ?>
    <tr>
      <td><?php echo $gesseh_stage->getGessehPeriode()->getDebut()." - ".$gesseh_stage->getGessehPeriode()->getFin(); ?></td>
      <td><?php echo $gesseh_stage->getGessehEtudiant()->getNom()." ".$gesseh_stage->getGessehEtudiant()->getPrenom(); ?></td>
      <td><?php echo $gesseh_stage->getGessehEtudiant()->getGessehPromo()->getTitre(); ?></td>
      <td><?php echo $gesseh_stage->getGessehTerrain()->getFiliere()." à ".$gesseh_stage->getGessehTerrain()->getGessehHopital()->getNom()." (".$gesseh_stage->getGessehTerrain()->getPatron().")"; ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
