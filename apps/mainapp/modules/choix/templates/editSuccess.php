<h1>Mes choix :</h1>

<?php if(null == $monchoix): ?>
  <div>Attention ! La table de simulation n'a pas été générée ! Veuillez contacter l'administrateur du site.</div>
<?php else: ?>

  <?php if (null != $monchoix->getAbsent()): ?>
    <div>Si vous désirez à nouveau participer aux simulations, <a href="<?php echo url_for('@choix_present'); ?>">cliquez ici</a>.</div>
  <?php else: ?>
    <div>Si pour une quelconque raison (grossesse, master, etc.) vous ne souhaitez pas participer au prochain choix de stage, <a href="<?php echo url_for('@choix_absent'); ?>">cliquez-ici</a>.</div>

    <?php if(null == $monchoix->getPoste()): ?>
      <div>Vous n'avez aucun choix valide pour le moment. Veuillez ajouter de nouveaux voeux à la simulation.</div>
    <?php else: ?>
      <div>Votre choix retenu par la simulation est : <em><?php echo $monchoix->getGessehTerrain()->getTitre(); ?> (<?php echo $monchoix->getGessehTerrain()->getGessehFiliere()->getTitre(); ?>) à <?php echo $monchoix->getGessehTerrain()->getGessehHopital()->getNom(); ?></em>. Après vous, il reste encore <em><?php echo $monchoix->getReste(); ?> places</em> pour ce poste.</div>
    <?php endif; ?>

    <div><em><?php echo $absents; ?> personnes</em> devant vous n'ont pas de choix valide à ce jour.</div>

    <table>
      <thead>
        <tr>
          <th>Ordre</th>
          <th>Choix</th>
          <th colspan='3'>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($gesseh_choix as $choix):	?>
          <tr>
            <td><?php echo $choix->getOrdre(); ?></td>
            <td><?php echo $choix->getGessehTerrain()->getTitre(); ?> (<?php echo $choix->getGessehTerrain()->getGessehFiliere()->getTitre(); ?>) à <?php echo $choix->getGessehTerrain()->getGessehHopital()->getNom(); ?></td>
            <td><a href="<?php echo url_for('choix/edit?up='.$choix->getId()); ?>"><?php echo image_tag('up.png', 'alt=monter'); ?></a></td>
            <td><a href="<?php echo url_for('choix/edit?down='.$choix->getId()); ?>"><?php echo image_tag('down.png', 'alt=descendre'); ?></a></td>
            <td><a href="<?php echo url_for('choix/edit?del='.$choix->getId()); ?>"><?php echo image_tag('del.png', 'alt=supprimer'); ?></a></td>
          </tr>
        <?php endforeach;	?>
      </tbody>
    </table>

    <form action="<?php echo url_for('@choix_update'); ?>" method="post">
      <div><?php echo $form->renderGlobalErrors(); ?></div>
      <div><?php echo $form; ?><input type="submit" value="Ajouter" /></div>
    </form>

    <div>Cliquer ici pour mettre à jour les valeurs de la simulation (attention cette étape peut prendre plusieurs minutes) : <a href="<?php echo url_for('choix/simul'); ?>">simulation</a></div>

    <?php if(null != $autres): ?>
      <table>
        <thead>
          <tr>
            <th>Etudiant</th>
            <th>Position</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($autres as $autre): ?>
            <tr>
              <td><a href="<?php echo url_for('@choix_edit'); ?>">
                <?php if ($autre->getGessehEtudiant()->getAnonyme()): ?>
                  *** anonyme ***
                <?php else: ?>
                  <?php echo $autre->getGessehEtudiant()->getSfGuardUser()->getLastName(); ?> <?php echo $autre->getGessehEtudiant()->getSfGuardUser()->getFirstName(); ?>
                <?php endif; ?>
              </a></td>
              <td><?php if (!$autre->getOrdre()): echo "supprimé"; else: echo "choix ".$autre->getOrdre(); endif; ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  <?php endif; ?>
<?php endif; ?>
