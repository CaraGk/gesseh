<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
  <head>
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <title><?php include_slot('title', 'GESSEH') ?></title>
    <link rel="shortcut icon" href="images/favicon.ico" />
    <?php include_stylesheets() ?>
    <?php include_javascripts() ?>
  </head>
  <body>
    <div id="container">
      <div id="header">
        <div class="content">
	  <h1><a href="<?php echo url_for('@homepage') ?>"><img src="images/gesseh_logo.png" alt="Gesseh, session de test" /> Titre du site de gestion</a></h1>
	  
	  <div id="menu">
	    <ul>
	      <li><a href="<?php echo url_for('@homepage') ?>">Accueil</a></li>
	      <?php if ($sf_user->isAuthenticated()): ?>
	      <li><a href="<?php echo url_for('etudiant/index?iduser='.$sf_user->getUsername()); ?>">Mes évaluations de stage</a></li>
	      <li><?php echo link_to('Se déconnecter', 'sf_guard_signout'); ?></li>
	      <?php else: ?>
	      <li><?php echo link_to('Se connecter', 'sf_guard_signin'); ?></li>
	      <?php endif; ?>
	      <li><a href="admin_dev.php">Administrer</a></li>
	    </ul>
	  </div>
	</div>
	
<!--	<div id="search">
          <form action="" method="get">
	    Terrains de stage :
	    <select name="terrain">
	      <option value="" selected></option>
	      <option value="test">test</option>
	    </select>
            <input type="submit" value="search" />
	  </form>
	</div>
-->
      </div>
  
      <div id="content">
        <?php if ($sf_user->hasFlash('notice')): ?>
          <div class="flash_notice"><?php echo $sf_user->getFlash('notice') ?></div>
        <?php endif; ?>
        <?php if ($sf_user->hasFlash('error')): ?>
          <div class="flash_error"><?php echo $sf_user->getFlash('error') ?></div>
        <?php endif; ?>
    
        <div class="content">
           <?php echo $sf_content ?>
        </div>
      </div>
  
      <div id="footer">
        <div class="content">
	  <span class="symfony"><a href="http://code.google.com/p/gesseh/"><img src="images/gesseh_logo_small.png" alt="gesseh" /></a> powered by <a href="http://www.symfony-project.org/"><img src="images/symfony.gif" alt="symfony framework" /></a></span>
	  <ul>
	    <li><a href="http://code.google.com/p/gesseh/issues/list">Reporter un bug ou un souhait</a></li>
	    <li><a href="http://code.google.com/p/gesseh/w/list">Assistance - documentation</a></li>
	  </ul>
	</div>
      </div>
    </div>
  </body>
</html>
