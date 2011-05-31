<h1>Liste des terrains de stage</h1>

<table class="list">
  <thead>
    <tr>
      <th><a href=<?php echo url_for('terrain/index?'.$tri['hopital']); ?>>Hôpital</a></th>
      <th>Intitulé</th>
      <th><a href=<?php echo url_for('terrain/index?'.$tri['filiere']); ?>>Filière</a></th>
      <th>Chef de service</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($gesseh_terrains as $gesseh_terrain): ?>
    <tr>
      <td><?php echo $gesseh_terrain->getGessehHopital()->getNom() ?></td>
      <td><a href="<?php echo url_for('terrain/show?id='.$gesseh_terrain->getId()) ?>"><?php echo $gesseh_terrain->getTitre() ?></a></td>
      <td>
        <?php if ($gesseh_terrain->getGessehFiliere()):
          echo $gesseh_terrain->getGessehFiliere()->getTitre();
        else: ?>
          -
        <?php endif; ?>
      </td>
      <td><a href="<?php echo url_for('terrain/show?id='.$gesseh_terrain->getId()) ?>"><?php echo $gesseh_terrain->getPatron() ?></a></td>
      <td>
        <a href="<?php echo url_for('terrain/show?id='.$gesseh_terrain->getId()) ?>">Infos</a>
        <a href="<?php echo url_for('eval/terrain?id='.$gesseh_terrain->getId()) ?>">Évals</a>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
