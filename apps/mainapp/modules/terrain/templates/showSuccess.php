<?php slot('title'); ?>
  <?php echo sprintf('GESSEH : %s à %s', $gesseh_terrain->getTitre(), $gesseh_terrain->getGessehHopital()->getTitre()); ?>
<?php end_slot(); ?>

<h1>Informations : <?php echo $gesseh_terrain->getTitre() ?> à <?php echo $gesseh_terrain->getGessehHopital()->getTitre() ?> (<?php echo $gesseh_terrain->getPatron(); ?>).</h1>

<a href="<?php echo url_for('@terrain_index'); ?>">Retour à la liste</a>
<br /><a href="<?php echo url_for('eval/terrain?id='.$gesseh_terrain->getId()); ?>">Évaluations du le terrain de stage</a>

<table class="infos">
  <tbody>
    <tr>
      <th>Hopital :</th>
      <td><?php echo $gesseh_terrain->getGessehHopital()->getTitre() ?></td>
    </tr>
    <tr>
      <th>Adresse :</th>
      <td><?php echo $gesseh_terrain->getGessehHopital()->getAdresse() ?></td>
    </tr>
    <tr>
      <th>Téléphone :</th>
      <td><?php echo $gesseh_terrain->getGessehHopital()->getTelephone() ?></td>
    </tr>
  </tbody>
</table>

<?php if ($gesseh_terrain->getGessehHopital()->getPage() == null): ?>
  <div>Pas d'informations sur l'hôpital.</div>
<?php else: ?>
  <div><?php echo $bb_parser->qparse($gesseh_terrain->getGessehHopital()->getPage()); ?></div>
<?php endif; ?>

<?php if (null == $gesseh_terrain->getPage()): ?>
  <div>Il n'y a aucune information supplémentaire à afficher pour ce terrain de stage pour le moment. Référez-vous à l'administrateur du site.</div>
<?php else: ?>
  <div><?php echo $bb_parser->qparse($gesseh_terrain->getPage()); ?></div>
<?php endif; ?>
