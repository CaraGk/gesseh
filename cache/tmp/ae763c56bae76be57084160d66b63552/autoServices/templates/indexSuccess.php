<h1>Gesseh terrains List</h1>

<table>
  <thead>
    <tr>
      <th>Id</th>
      <th>Hopital</th>
      <th>Filiere</th>
      <th>Patron</th>
      <th>Is active</th>
      <th>Created at</th>
      <th>Updated at</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($gesseh_terrains as $gesseh_terrain): ?>
    <tr>
      <td><a href="<?php echo url_for('services/show?id='.$gesseh_terrain->getId()) ?>"><?php echo $gesseh_terrain->getId() ?></a></td>
      <td><?php echo $gesseh_terrain->getHopitalId() ?></td>
      <td><?php echo $gesseh_terrain->getFiliere() ?></td>
      <td><?php echo $gesseh_terrain->getPatron() ?></td>
      <td><?php echo $gesseh_terrain->getIsActive() ?></td>
      <td><?php echo $gesseh_terrain->getCreatedAt() ?></td>
      <td><?php echo $gesseh_terrain->getUpdatedAt() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <a href="<?php echo url_for('services/new') ?>">New</a>
