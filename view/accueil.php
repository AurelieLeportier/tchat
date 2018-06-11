<?php $title = 'Tchat - Bienvenue'; ?>

<?php ob_start(); ?>

<h1>Tchat - Bienvenue</h1>

<h2>Log in !</h2>

<form action="index.php?action=connexion" method="POST">
    <div>
        <label for="login">Login</label>
        <input type="text" name="login" id="login" value="" required>
    </div>
    <div>
        <label for="pass">Password</label>
        <input type="password" name="pass" id="pass" value="" required>
    </div>
    <div>
        <input type="submit" value="GO" />
    </div>
</form>

<h2>Sign in !</h2>

<form action="index.php?action=createAccount" method="POST">
    <div>
        <label for="login">Login</label>
        <input type="text" name="login" id="login" value="" required>
    </div>
    <div>
        <label for="pass">Password</label>
        <input type="password" name="pass" id="pass" value="" required>
    </div>
    <div>
        <input type="submit" value="Create" />
    </div>
</form>

        
<?php $content = ob_get_clean(); ?>

<?php require('template.php'); ?>