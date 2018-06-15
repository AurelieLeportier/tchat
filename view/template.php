<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title><?= $title ?></title>
        <link href="public/css/style.css" rel="stylesheet" /> 
        <link href="public/js/jquery-ui-1.12.1/jquery-ui.min.css" rel="stylesheet" /> 
        <script src="public/js/jquery-3.3.1.min.js"></script>
        <script src="public/js/jquery-ui-1.12.1/jquery-ui.min.js"></script>
    </head>
    <body>
        <div class="content">
            <?php
                if (isset($_GET['action']) && count($_SESSION) > 0) {
                    if ($_GET['action'] == "listMessages" || $_GET['action'] == "newConversation") {
            ?>
                        <div class="col left">
                            <div class="header">
                                <div class="deco">
                                    <form action="index.php?action=deconnexion" method="post">
                                        <div>
                                            <input type="submit" value="Déconnexion" />
                                        </div>
                                    </form>
                                </div>
                                <?php 
                                    if ($_GET['action'] == "listMessages") {
                                ?>
                                        <div class="newConv">
                                            <form action="index.php?action=newConversation" method="post">
                                                <div>
                                                    <input type="submit" value="New conversation" />
                                                </div>
                                            </form>
                                        </div>
                                <?php
                                    }
                                ?>
                            </div>
                            <div class="listMessages">
                                <?= $listMessages ?>
                            </div>
                        </div>
                        <div class="col right">
                            <?= $online ?>
                        </div>
            <?php
                    } else {
                        if(isset($content)){
                            echo $content;
                        }
                    }
                } else {
                    if(isset($content)){
                        echo $content;
                    }
                }
            ?>
        </div>
    </body>
</html>