<?php include_partial('menu') ?>
<?php include_partial('flash') ?>

<h1>Création d'un formulaire d'évaluation</h1>

<div>Ce module n'existe pas encore pour l'administration simplifiée. Veuillez vous référer à l'administration avancée.</div>

<table>
  <thead>
    <tr>
      <th></th>
      <th>Titre</th>
      <th>Type</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th>Critère :</th>
      <td><?php echo $form['titre']; ?></td>
      <td><?php echo $form['type']; ?></td>
    </tr>
  </tbody>
</table>
