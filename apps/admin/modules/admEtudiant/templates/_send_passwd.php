Bonjour <?php echo $user->getFirstName(); ?><br/><br/>

Ce courriel vous a été envoyé parce que vous avez été ajouté sur le site "<?php echo csSettings::get('titre_du_site'); ?>".<br/><br/>

Vous pouvez définir votre mot de passe en cliquant sur le lien ci-dessous. Attention, ce mot de passe n'est valable que 24 heures mais vous pouvez en re-générer un en cliquant sur "Mot de passe oublié ?" sur ce même site. Votre identifiant est l'adresse e-mail sur laquelle vous venez de recevoir ce message.<br/><br/>

<?php echo link_to('Cliquez pour enregistrer un mot de passe', $sf_request->getUriPrefix().$sf_request->getRelativeUrlRoot().'/index.php/guard/forgot_password/'.$forgot_password->unique_key, 'absolute=true') ?><br/><br/>

Pour tout problème, veuillez contacter un administrateur.<br/><br/>

L'équipe GESSEH.
