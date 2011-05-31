<?php slot('title'); ?>
  <?php echo sprintf('GESSEH : %s à %s', $gesseh_terrain->getFiliere(), $gesseh_terrain->getGessehHopital()->getNom()); ?>
<?php end_slot(); ?>

<h1><?php echo $gesseh_terrain->getFiliere() ?> à <?php echo $gesseh_terrain->getGessehHopital()->getNom() ?>.</h1>

<table class="infos">
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

<?php $ligne = 0; ?>
<table class="evals">
  <thead>
    <th colspan="6">Moyenne des évaluations chiffrées</th>
  </thead>
  <tbody>
    <tr>
      <?php foreach ($gesseh_evals as $critere): ?>
        <?php if($ligne == 3): $ligne = 0; ?>
          </tr><tr>
        <?php endif; ?>
        <td><?php echo $critere['titre']; ?> : </td><td class="<?php echo gesseh::showColoredScore($critere['moyenne'], '5'); ?>"><?php echo $critere['moyenne']; ?></td>
        <?php $ligne++; ?>
      <?php endforeach; ?>
    </tr>
  </tbody>
</table>

<div class="comments">
  <?php $etudiant = false; ?>
  <?php foreach ($gesseh_comments as $gesseh_eval): ?>
    <?php if ($gesseh_eval->getGessehStage()->getEtudiantId() != $etudiant): ?>
      <?php if ($etudiant): ?>
        </div><div>
      <?php endif; ?>
      <div class="content">
        <div class="titre">Stage du <?php echo $gesseh_eval->getGessehStage()->getGessehPeriode()->getDebut() ?> au <?php echo $gesseh_eval->getGessehStage()->getGessehPeriode()->getFin() ?>.</div>
        <div class="content">
    <?php endif; ?>
          <em><?php echo $gesseh_eval->getGessehCritere()->getTitre() ?></em> : <?php echo $gesseh_eval->getValeur() ?><br />
          <?php $etudiant = $gesseh_eval->getGessehStage()->getEtudiantId(); ?>
  <?php endforeach; ?>
        </div>
      </div>
</div>
