<h1>Formulaire d'évaluation soumis :</h1>

<div id="eval">
  <div class="content">
    <?php foreach ($gesseh_evals as $eval): ?>
      <?php echo $eval->getGessehCritere()->getTitre(); ?> : <?php echo $eval->getValeur(); ?><br />
    <?php endforeach; ?>
  </div>
</div>

<hr />

<a href="<?php echo url_for('etudiant/index?iduser='.$user) ?>">Retour à la liste des stages</a>
