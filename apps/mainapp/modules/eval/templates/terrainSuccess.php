<?php slot('title'); ?>
  <?php echo sprintf('GESSEH : %s à %s', $gesseh_terrain->getTitre(), $gesseh_terrain->getGessehHopital()->getTitre()); ?>
<?php end_slot(); ?>

<h1>Évaluations : <?php echo $gesseh_terrain->getTitre() ?> à <?php echo $gesseh_terrain->getGessehHopital()->getTitre() ?>.</h1>

<a href="<?php echo url_for('@terrain_index'); ?>">Retour à la liste</a>
<br /><a href="<?php echo url_for('terrain/show?id='.$gesseh_terrain->getId()); ?>">Informations sur le terrain de stage</a>

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
