<h1>Liste des terrains de stage</h1>

<table>
  <thead>
    <tr>
      <th><a href=<?php echo url_for('services/index?tri=hopital&order='.$order); ?>>Hopital</a></th>
      <th><a href=<?php echo url_for('services/index?tri=terrain&order='.$order); ?>>Filiere</a></th>
      <th>Patron</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($gesseh_terrains as $gesseh_terrain): ?>
    <tr>
      <td><?php echo $gesseh_terrain->getGessehHopital()->getNom() ?></td>
      <td><a href="<?php echo url_for('services/show?id='.$gesseh_terrain->getId()) ?>"><?php echo $gesseh_terrain->getFiliere() ?></a></td>
      <td><a href="<?php echo url_for('services/show?id='.$gesseh_terrain->getId()) ?>"><?php echo $gesseh_terrain->getPatron() ?></a></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
