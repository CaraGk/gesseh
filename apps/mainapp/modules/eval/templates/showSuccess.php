<h1>Formulaire d'évaluation soumis :</h1>

<div id="eval">
  <div class="content">
    <?php foreach ($gesseh_evals as $eval): ?>
      <?php echo $eval->getGessehCritere()->getTitre(); ?> : <?php echo $eval->getValeur(); ?><br />
    <?php endforeach; ?>
  </div>
</div>

<hr />

<a href="<?php echo url_for('monstage/1') ?>">Retour</a>
