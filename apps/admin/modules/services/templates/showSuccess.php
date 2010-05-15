<table>
  <tbody>
    <tr>
      <th>Id:</th>
      <td><?php echo $gesseh_periode->getId() ?></td>
    </tr>
    <tr>
      <th>Debut:</th>
      <td><?php echo $gesseh_periode->getDebut() ?></td>
    </tr>
    <tr>
      <th>Fin:</th>
      <td><?php echo $gesseh_periode->getFin() ?></td>
    </tr>
    <tr>
      <th>Created at:</th>
      <td><?php echo $gesseh_periode->getCreatedAt() ?></td>
    </tr>
    <tr>
      <th>Updated at:</th>
      <td><?php echo $gesseh_periode->getUpdatedAt() ?></td>
    </tr>
  </tbody>
</table>

<hr />

<a href="<?php echo url_for('services/edit?id='.$gesseh_periode->getId()) ?>">Edit</a>
&nbsp;
<a href="<?php echo url_for('services/index') ?>">List</a>
