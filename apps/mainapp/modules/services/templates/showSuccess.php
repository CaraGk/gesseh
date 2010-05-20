<?php slot('title'); ?>
  <?php echo sprintf('GESSEH : %s à %s', $gesseh_terrain->getFiliere(), $gesseh_terrain->getGessehHopital()->getNom()); ?>
<?php end_slot(); ?>

<h1><?php echo $gesseh_terrain->getFiliere() ?> à <?php echo $gesseh_terrain->getGessehHopital()->getNom() ?>.</h1>

<table>
  <tbody>
    <tr>
      <th>Hopital :</th>
      <td><?php echo $gesseh_terrain->getGessehHopital()->getNom() ?></td>
    </tr>
    <tr>
      <th>Adresse :</th>
      <td><?php echo $gesseh_terrain->getGessehHopital()->getAdresse() ?></td>
    </tr>
    <tr>
      <th>Téléphone :</th>
      <td><?php echo $gesseh_terrain->getGessehHopital()->getTelephone() ?></td>
    </tr>
    <tr>
      <th>Localisation :</th>
      <td><?php echo $gesseh_terrain->getLocalisation() ?></td>
    </tr>
    <tr>
      <th>Chef de service :</th>
      <td><?php echo $gesseh_terrain->getPatron() ?></td>
    </tr>
  </tbody>
</table>
<hr />

<div id="eval">
  <div class="titre">Moyenne des évaluations chiffrées :</div>
  <div class="content">
    <?php foreach ($gesseh_evals as $critere): ?>
      <?php echo $critere['titre']; ?> : <?php echo $critere['moyenne']; ?><br />
    <?php endforeach; ?>
  </div>
</div>

<hr />

<div id="comments">
<?php $etudiant = -1; ?>
<?php foreach ($gesseh_comments as $gesseh_eval): ?>
  <?php if ($gesseh_eval->getGessehStage()->getEtudiantId() != $etudiant): ?>
    <?php if ($etudiant): ?>
      </div></div>
    <?php endif; ?>
    <div class="contents">
      <div class="titre">Stage du <?php echo $gesseh_eval->getGessehStage()->getGessehPeriode()->getDebut() ?> au <?php echo $gesseh_eval->getGessehStage()->getGessehPeriode()->getFin() ?>.</div>
      <div class="content">
  <?php endif; ?>
      <?php echo $gesseh_eval->getGessehCritere()->getTitre() ?> : <?php echo $gesseh_eval->getValeur() ?><br />
      <?php $etudiant = $gesseh_eval->getGessehStage()->getEtudiantId(); ?>
<?php endforeach; ?>
</div></div>
</div>

<hr />

<a href="<?php echo url_for('@homepage') ?>">Retour</a>
