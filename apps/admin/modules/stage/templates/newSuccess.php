<?php include_partial('menu') ?>
<?php include_partial('flash') ?>

<h1>Création d'un formulaire d'évaluation</h1>

<div>Ce module n'existe pas encore pour l'administration simplifiée. Veuillez vous référer à l'administration avancée.</div>

<table>
  <thead>
    <tr>
      <th>Période</th>
      <th>Terrain de stage</th>
      <th>Etudiant</th>
      <th>Formulaire associé</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td><?php echo $form['periode_id']; ?></td>
      <td><?php echo $form['terrain_id']; ?></td>
      <td><?php echo $form['etudiant_id']; ?></td>
      <td><?php echo $form['form']; ?></td>
    </thead>
    </tr>
  </tbody>
</table>

