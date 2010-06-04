<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
  <head>
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <title><?php include_slot('title', 'GESSEH Administration') ?></title>
    <link rel="shortcut icon" href="images/favicon.ico" />
    <?php include_stylesheets() ?>
    <?php include_javascripts() ?>
  </head>
  <body>
    <div id="container">
      <div id="header">
        <div class="content">
	  <h1><a href="<?php echo url_for('@homepage') ?>"><?php echo image_tag('gesseh_logo.png', 'alt=GesseH'); ?> Titre du site de gestion</a></h1>
	  
	  <?php if ($sf_user->isAuthenticated()): ?>
	  <div id="menu">
	    <ul>
	      <li>Administration basique :</li>
	      <li><a href="<?php echo url_for('gestion/index'); ?>">Evaluations non rendues</a></li>
	      <li><a href="<?php echo url_for('gestion/new'); ?>">Changement d'année</a></li>
	      <li><a href="<?php echo url_for('@homepage'); ?>">Import des stages</a></li>
	      <li><?php echo link_to('Se déconnecter', 'sf_guard_signout'); ?></li>
	    </ul>
	    <ul>
	      <li>Administration avancée :</li>
	      <li><a href="<?php echo url_for('admEtudiant/index'); ?>">Etudiants</a></li>
	      <li><a href="<?php echo url_for('admCritere/index'); ?>">Critères d'évaluation</a></li>
	      <li><a href="<?php echo url_for('admTerrain/index'); ?>">Terrains de stage</a></li>
	      <li><a href="<?php echo url_for('admPeriode/index'); ?>">Périodes de stage</a></li>
	      <li><a href="<?php echo url_for('admEval/index'); ?>">Evaluations</a></li>
	      <li><a href="<?php echo url_for('admStage/index'); ?>">Attributions de stage</a></li>
	      <li><?php echo link_to('Identifiants', 'sf_guard_user'); ?></li>
	    </ul>
	  </div>
	  <?php endif; ?>
	</div>
      </div>
	
      <div id="content">
           <?php echo $sf_content ?>
      </div>
  
      <div id="footer">
        <div class="content">
          <span class="symfony"><a href="http://code.google.com/p/gesseh/"><?php echo image_tag('gesseh_logo_small.png', 'alt=gesseh'); ?></a> powered by <a href="http://www.symfony-project.org/"><?php echo image_tag('symfony.gif', 'alt=symfony framework'); ?></a></span>
          <ul>
            <li><a href="http://code.google.com/p/gesseh/issues/list">Reporter un bug ou un souhait</a></li>
            <li><a href="http://code.google.com/p/gesseh/w/list">Assistance - documentation</a></li>
	  </ul>
	</div>
      </div>
    </div>
  </body>
</html>
