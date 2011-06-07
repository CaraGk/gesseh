<h1>Liste des terrains de stage</h1>

<table class="list">
  <thead>
    <tr>
      <th>Hôpital</th>
      <th>Intitulé</th>
      <th><Filière</th>
      <th>Chef de service</th>
      <?php if (null != $postes_restants): ?>
        <th>Reste / Postes</th>
      <?php else: ?>
        <th>Postes</th>
      <?php endif; ?>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($pager->getResults() as $gesseh_terrain): ?>
    <tr>
      <td><?php echo $gesseh_terrain->getGessehHopital()->getNom() ?></td>
      <td><a href="<?php echo url_for('terrain/show?id='.$gesseh_terrain->getId()) ?>"><?php echo $gesseh_terrain->getTitre() ?></a></td>
      <td>
        <?php if ($gesseh_terrain->getGessehFiliere()):
          echo $gesseh_terrain->getGessehFiliere()->getTitre();
        else: ?>
          -
        <?php endif; ?>
      </td>
      <td><a href="<?php echo url_for('terrain/show?id='.$gesseh_terrain->getId()) ?>"><?php echo $gesseh_terrain->getPatron() ?></a></td>
      <td>
        <?php if (null != $postes_restants): ?>
          <?php echo $postes_restant[$gesseh_terrain->getId()]; ?> /
        <?php endif; ?>
        <?php echo $gesseh_terrain->getTotal(); ?>
      </td>
      <td>
        <a href="<?php echo url_for('terrain/show?id='.$gesseh_terrain->getId()) ?>">Infos</a>
        <a href="<?php echo url_for('eval/terrain?id='.$gesseh_terrain->getId()) ?>">Évals</a>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<?php if ($pager->haveToPaginate()): ?>
  <div class="pagination">
    <a href="<?php echo url_for('@terrain_index_pager'); ?>?page=1"><<</a>
    <a href="<?php echo url_for('@terrain_index_pager'); ?>?page=<?php echo $pager->getPreviousPage() ?>"><</a>
    <?php foreach ($pager->getLinks() as $page): ?>
      <?php if ($page == $pager->getPage()): ?>
        <?php echo $page ?>
      <?php else: ?>
        <a href="<?php echo url_for('@terrain_index_pager'); ?>?page=<?php echo $page ?>"><?php echo $page ?></a>
      <?php endif; ?>
    <?php endforeach; ?>
    <a href="<?php echo url_for('@terrain_index_pager/g'); ?>?page=<?php echo $pager->getNextPage() ?>">></a>
    <a href="<?php echo url_for('@terrain_index_pager/g'); ?>?page=<?php echo $pager->getLastPage() ?>">>></a>
  </div>
<?php endif; ?>

<div class="pagination_desc">
  <strong><?php echo count($pager) ?></strong> pages
  <?php if ($pager->haveToPaginate()): ?>
    - page <strong><?php echo $pager->getPage() ?>/<?php echo $pager->getLastPage() ?></strong>
  <?php endif; ?>
</div>
