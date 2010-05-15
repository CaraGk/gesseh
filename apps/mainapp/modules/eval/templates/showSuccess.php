<table>
  <tbody>
<?php foreach ($gesseh_criteres as $gesseh_critere): ?>
    <tr>
      <th>Critère d'évaluation :</th>
      <td><?php echo $gesseh_critere->getTitre() ?></td>
    </tr>
    <tr>
      <th>Type :</th>
      <td><?php echo $gesseh_critere->getType() ?></td>
    </tr>
<?php endforeach; ?>
  </tbody>
</table>

<hr />

<a href="<?php echo url_for('eval/edit?id='.$gesseh_eval->getId()) ?>">Editer</a>
&nbsp;
<a href="<?php echo url_for('eval/index') ?>">Liste</a>
