<h1>Gesseh periodes List</h1>

<table>
  <thead>
    <tr>
      <th>Id</th>
      <th>Debut</th>
      <th>Fin</th>
      <th>Created at</th>
      <th>Updated at</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($gesseh_periodes as $gesseh_periode): ?>
    <tr>
      <td><a href="<?php echo url_for('services/show?id='.$gesseh_periode->getId()) ?>"><?php echo $gesseh_periode->getId() ?></a></td>
      <td><?php echo $gesseh_periode->getDebut() ?></td>
      <td><?php echo $gesseh_periode->getFin() ?></td>
      <td><?php echo $gesseh_periode->getCreatedAt() ?></td>
      <td><?php echo $gesseh_periode->getUpdatedAt() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <a href="<?php echo url_for('services/new') ?>">New</a>
