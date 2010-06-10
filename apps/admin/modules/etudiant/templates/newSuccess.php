<?php include_partial('menu') ?>
<?php include_partial('flash') ?>

<h1>Création d'un formulaire d'évaluation</h1>

<div>Ce module n'existe pas encore pour l'administration simplifiée. Veuillez vous référer à l'administration avancée.</div>

<table>
  <thead>
    <tr>
      <th>Nom</th>
      <th>Prénom</th>
      <th>Numéro étudiant</th>
    </tr>
  </thead>
  <tfoot>
    <tr>
      <td>Ajouter un nouvel étudiant</td>
      <td><input type="submit" value="Enregistrer" /></td>
  <tbody>
    <tr>
      <td><?php echo $form['nom']; ?></td>
      <td><?php echo $form['prenom']; ?></td>
      <td><?php echo $form['id']; ?></td>
    </tr>
  </tbody>
</table>
