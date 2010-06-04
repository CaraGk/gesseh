<h1>Formulaire d'évaluation soumis :</h1>

<table class="evals">
  <?php foreach ($gesseh_evals as $eval): ?>
    <tr>
      <td><?php echo $eval->getGessehCritere()->getTitre(); ?> : </td>
      <td><?php echo $eval->getValeur(); ?></td>
    </tr>
  <?php endforeach; ?>
</table>

<a href="<?php echo url_for('etudiant/index?iduser='.$user) ?>">Retour à la liste des stages</a>
