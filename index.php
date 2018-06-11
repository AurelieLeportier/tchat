<?php
// Afficher les erreurs à l'écran
//ini_set('display_errors', 1);
// Afficher les erreurs et les avertissements
//error_reporting(E_ALL);

session_start();

require('controller/controller.php');

try {
    if (isset($_GET['action'])) {
        switch ($_GET['action']) {
            //Fonction connexion
            case 'connexion':
                connexion();
                break;
            //Fonction déconnexion    
            case 'deconnexion':
                deconnexion();
                break;
            //Fonction de création d'un compte
            case 'createAccount':
                if (!empty($_POST['login']) && !empty($_POST['pass'])) {
                    createAccount($_POST['login'], $_POST['pass']);
                } else {
                    throw new Exception('Veuillez saisir un login ET un mot de passe.');
                }
                break;
            //Affichage des messages
            case 'listMessages':
                listMessages();
                break;
            //Affichage d'une nouvelle conversation
            case 'newConversation':
                newConversation();
                break;
            //Récupération de tous les logins pour l'autocompletion
            case 'getLogins':
                ajaxLogin($_POST['input']);
                break;
            //Recherche d'une conversation
            case 'searchContact':
                if ((isset($_SESSION['idSender']) && $_SESSION['idSender'] > 0) && (isset($_POST['loginReceiver']) || isset($_GET['loginReceiver']))) {
                    $loginReceiver = (isset($_POST['loginReceiver'])) ? $_POST['loginReceiver'] : $_GET['loginReceiver'];
                    conversation($loginReceiver);
                } else {
                    throw new Exception('Aucune conversation trouvée.');
                }
                break;
            //Envoie d'un message
            case 'sendMessage':
                if ((isset($_SESSION['idSender']) && $_SESSION['idSender'] > 0) && (isset($_POST['idReceiver']) && $_POST['idReceiver'] > 0)) {
                    if (!empty($_POST['newMessage'])) {
                        sendMessage($_SESSION['idSender'], $_POST['idReceiver'], $_POST['newMessage']);
                    } else {
                        throw new Exception('Vous n\'avez pas saisie de message à envoyer !');
                    }
                } else {
                    throw new Exception('Aucune conversation trouvée.');
                }
                break;
            //Par défaut on affiche la page d'accueil
            default:
                index();
        }
    } else {
        index();
    }
} catch (Exception $e) { // S'il y a eu une erreur, alors...
    echo 'Erreur : ' . $e->getMessage();
}
