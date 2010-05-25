<h1>Gesseh stages List</h1>

<table>
  <thead>
    <tr>
      <th>Période</th>
      <th>Stage</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($gesseh_stages as $gesseh_stage): ?>
    <tr class="
      <?php if($gesseh_stage->getIsActive()):
        echo 'active';
      else:
        echo 'valide';
      endif; ?>">
      <td><?php echo $gesseh_stage->getGessehPeriode()->getDebut().' - '.$gesseh_stage->getGessehPeriode()->getFin(); ?></td>
      <td><a href="
        <?php if($gesseh_stage->getIsActive()):
	    echo url_for('eval/new?iduser='.$user.'&idstage='.$gesseh_stage->getId()); 
	  else:
	    echo url_for('eval/show?iduser='.$user.'&idstage='.$gesseh_stage->getId());
	  endif; ?>
	  "><?php echo $gesseh_stage->getGessehTerrain()->getFiliere() ?> à <?php echo $gesseh_stage->getGessehTerrain()->getGessehHopital()->getNom(); ?> (<?php echo $gesseh_stage->getGessehTerrain()->getPatron(); ?>)</a></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
