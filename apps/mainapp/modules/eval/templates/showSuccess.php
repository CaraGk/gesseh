<table>
  <tbody>
    <tr>
      <th>Stage:</th>
      <td><?php echo $gesseh_eval->getStageId() ?></td>
    </tr>
    <tr>
      <th>Critere:</th>
      <td><?php echo $gesseh_eval->getCritereId() ?></td>
    </tr>
    <tr>
      <th>Valeur:</th>
      <td><?php echo $gesseh_eval->getValeur() ?></td>
    </tr>
  </tbody>
</table>

<hr />

<a href="<?php echo url_for('eval/edit?id='.$gesseh_eval->getId()) ?>">Editer</a>
&nbsp;
<a href="<?php echo url_for('eval/index') ?>">Liste</a>
