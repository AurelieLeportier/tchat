<?php $title = 'Tchat - Not found'; ?>

<?php ob_start(); ?>

<h1>Tchat - 404</h1>

<h2>Désolée</h2>

<p>La page que vous avez demandé est inconnu... :-/</p><br />
<p>Cliquez <a href="index.php?action=listMessages">ICI</a> pour être redirigé.</p>
        
<?php $content = ob_get_clean(); ?>

<?php require('template.php'); ?>