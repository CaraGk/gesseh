<h1>Liste des terrains de stage</h1>

<table class="list">
  <thead>
    <tr>
      <th><a href=<?php echo url_for('terrain/index?'.$tri['hopital']); ?>>Hopital</a></th>
      <th><a href=<?php echo url_for('terrain/index?'.$tri['filiere']); ?>>Filiere</a></th>
      <th>Patron</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($gesseh_terrains as $gesseh_terrain): ?>
    <tr>
      <td><?php echo $gesseh_terrain->getGessehHopital()->getNom() ?></td>
      <td><a href="<?php echo url_for('terrain/show?id='.$gesseh_terrain->getId()) ?>"><?php echo $gesseh_terrain->getFiliere() ?></a></td>
      <td><a href="<?php echo url_for('terrain/show?id='.$gesseh_terrain->getId()) ?>"><?php echo $gesseh_terrain->getPatron() ?></a></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
