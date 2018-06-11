<?php $title = 'Tchat'; ?>

<?php ob_start(); ?>
<h1>Tchat</h1>
<h2>Bienvenue <font color='red'><?= $_SESSION['login'] ?></font></h2>
<p>Les messages</p>

<?php
    if ($conversations->rowCount() == 0) {
?>
        <div class="listconv">
            <p>
                Vous n'avez pas encore de conversation.
            </p>
        </div>
<?php
    } else {
        while ($conv = $conversations->fetch()) {
            if ($conv['id'] != $_SESSION['idSender']) {
?>
                <div class="listconv">
                    <form action="index.php?action=searchContact" method="POST">
                        <h3>
                            <em><?= $conv['login'] ?></em>
                            <input type="hidden" name="loginReceiver" value="<?= $conv['login'] ?>">
                            <input type="submit" value="Voir">
                        </h3>
                    </form>
                </div>
<?php
            }
        }
    }
    $listMessages = ob_get_clean();
?>

<div class="online">
    <h3>Membres en ligne</h3>
    <ul>
        <?php 
            if ($isOnline->rowCount() > 0) {
                while ($userOnline = $isOnline->fetch()) {
        ?>
                    <li><?= $userOnline["login"] ?></li>
        <?php 
                }
            } else {
        ?>
                <li>Il n'y a que vous en ligne ^^"</li>
        <?php
            }
        ?>
    </ul>
</div>

<?php
    $online = ob_get_clean();
    require('template.php');
?>