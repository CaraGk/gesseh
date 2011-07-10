<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
  <head>
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <title><?php include_slot('title', 'GESSEH') ?></title>
    <link rel="shortcut icon" href="<?php echo $sf_request->getRelativeUrlRoot() ?>/images/favicon.ico" />
    <?php include_stylesheets() ?>
    <?php include_javascripts() ?>
  </head>
  <body>
    <div id="container">
      <div id="header">
        <a href="<?php echo url_for('@homepage') ?>"><?php echo image_tag('gesseh_logo.png', 'alt=GesseH'); ?><h1> <?php echo csSettings::get('titre_du_site'); ?></h1></a>

        <div id="menu">
          <ul>
            <li><a href="<?php echo url_for('@homepage'); ?>">Accueil</a></li>
            <li><a href="<?php echo url_for('@terrain_index'); ?>">Terrains de stage</a></li>
            <?php if ($sf_user->isAuthenticated()): ?>
                <li><a href="<?php echo url_for('@etudiant_index'); ?>">Mes stages</a></li>
              <?php if (csSettings::get('mod_simul')): ?>
                <li><a href="<?php echo url_for('@choix_edit'); ?>">Mes voeux</a></li>
              <?php endif; ?>
              <?php if (csSettings::get('mod_garde')): ?>
                <li><a href="<?php echo url_for('@homepage'); ?>">Mes gardes</a></li>
              <?php endif; ?>
              <?php if (csSettings::get('mod_message')): ?>
                <li><a href="<?php echo url_for('@homepage'); ?>">Messages</a></li>
              <?php endif; ?>
              <li><a href="<?php echo url_for('@etudiant_edit'); ?>">Préférences</a></li>
              <li><?php echo link_to('Déconnexion', 'sf_guard_signout'); ?></li>
            <?php else: ?>
              <li><?php echo link_to('Connexion', 'sf_guard_signin'); ?></li>
            <?php endif; ?>
            <?php if (csSettings::get('mod_referent')): ?>
              <li><a href="<?php echo url_for('@homepage'); ?>">Référents</a></li>
            <?php endif; ?>
          </ul>
        </div>
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
          <span class="miniature"><a href="http://code.google.com/p/gesseh/"><?php echo image_tag('gesseh_logo_small.png', 'alt=gesseh'); ?></a> powered by <a href="http://www.symfony-project.org/"><?php echo image_tag('symfony.gif', 'alt=symfony framework'); ?></a></span>
          <ul>
            <li><a href="http://code.google.com/p/gesseh/issues/list">Reporter un bug ou un souhait</a></li>
            <li><a href="http://code.google.com/p/gesseh/w/list">Assistance - documentation</a></li>
            <li><a href="<?php echo $sf_request->getRelativeUrlRoot() ?>/admin.php">Administrer</a></li>
          </ul>
        </div>
      </div>
    </div>
  </body>
</html>
