<?php $title = 'Tchat'; ?>

<?php ob_start(); ?>
<br />
<p><a href="index.php?action=listMessages">Retour à la liste des messages</a></p>
<h1>Tchat</h1>

<p>Discuter avec ?</p>

<form action="index.php?action=searchContact" method="post">
    <input type="text" id="searchLogin" name="loginReceiver" class="ui-autocomplete-input" title="Taper une lettre pour consulter la liste des contacts du tchat" value="" required>
    <br />
    <input type="submit" value="Chercher">
</form>

<script type="text/javascript">
    $(function() {
        $("#searchLogin").tooltip();
        $("#searchLogin").autocomplete({
            source: function( request, response ) { 
                $.ajax({
                    type : 'POST',
                    url : 'index.php?action=getLogins',
                    dataType: 'json',
                    data: {
                        input: request.term
                    },
                    success:function(data){
                        response(data);
                    },
                    error:function(data){
                        alert('Error chargement des logins');
                    }
                });
            },
            minLength: 1,
            select: function( event, ui ) {
                $('#searchLogin').val(ui.item.label);
                return false;
            }
        });
    });
</script>

<?php
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