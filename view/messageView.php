<?php $title = 'Tchat'; ?>

<?php ob_start(); ?>

<p><a href="index.php?action=listMessages">Retour à la liste des messages</a></p>

<h1>Discuter avec <font color='red'><?= $contact['login'] ?></font></h1>
<div id="divConv" class="conv">
<?php
    if ($messages->rowCount() > 0) {
        while ($mess = $messages->fetch()) {
            $class = ($mess['sender'] == $_SESSION['idSender']) ? "sender" : "receiver";
?>
            <div class="<?= $class ?>">
                <h5>
                    <em>le <?= $mess['date_send_fr'] ?></em>
                </h5>
                <p>
                    <?= nl2br(html_entity_decode(stripslashes($mess['content']))) ?>
                </p>
            </div>
<?php
        }
    } else {
?>
        <p>Commencer la conversation...</p>
<?php
    }
?>
    </div>
    <div class="newMessage">
        <form action="index.php?action=sendMessage" method="POST">
            <input type="hidden" name="idReceiver" value="<?= $contact["id"] ?>">
            <textarea name="newMessage"></textarea>
            <input type="submit" value="Envoyer">
        </form>
    </div>
    <input type="button" id="refresh" value="Refresh">
    <script type="text/javascript">
        $(function() {
            $("#refresh").on("click", function(){
                startRefresh();
            });
            $("#divConv").scrollTop( $("#divConv")[0].scrollHeight );
        });

        function startRefresh() {
            //setTimeout(startRefresh, 1000);
            var login = "<?= $contact["login"] ?>";
            $.get('index.php?action=searchContact&loginReceiver='+login, function(data) {
                $(document.body).html(data); 
            });
        }
    </script>
<?php 
    $content = ob_get_clean();
    require('template.php');
?>