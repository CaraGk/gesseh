<?php include_partial('menu') ?>
<?php include_partial('flash') ?>

<h1>Liste des étudiants</h1>

<table>
  <thead>
    <tr>
      <th>Promo</th>
      <th>Nom</th>
      <th>Email</th>
      <th>Téléphone</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($gesseh_etudiants as $gesseh_etudiant): ?>
    <tr>
      <td><?php echo $gesseh_etudiant->getGessehPromo()->getTitre(); ?></td>
      <td><?php echo $gesseh_etudiant->getNom()." ".$gesseh_etudiant->getPrenom(); ?></td>
      <td><?php echo $gesseh_etudiant->getEmail(); ?></td>
      <td><?php echo $gesseh_etudiant->getTel(); ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
