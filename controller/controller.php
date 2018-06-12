<?php

// Chargement des classes
/*use Tchat\Model\User;
use Tchat\Model\Message;*/
require_once('model/User.php');
require_once('model/Message.php');

/**
 * Affiche la page d'accueil
 */
function index()
{
    require('view/accueil.php');
}

/**
 * Vérification login/mdp saisies
 * @return redirection/Exception
 */
function connexion()
{
    $user = new \Tchat\Model\User($_POST['login'], $_POST['pass']);
    $exist = $user->getUser();
    if ($exist !== false) {
        $_SESSION['idSender'] = $exist['id'];
        $_SESSION['login'] = $exist['login'];
        $user->setOnline(1);
        header('Location: index.php?action=listMessages');
    } else {
        throw new Exception('Identifiants incorect !');
    }
}

/**
 * Déconnexion de l'utilisateur
 * @return redirection
 */
function deconnexion()
{
    $user = new \Tchat\Model\User($_SESSION['idSender']);
    $user->setOnline(0);
    session_destroy();
    header('Location: index.php');
}

/**
 * Création d'un compte
 * @param  string $login
 * @param  string $pass
 * @return redirection/Exception
 */
function createAccount($login, $pass)
{
    $user = new \Tchat\Model\User($login, $pass);
    $exist = $user->checkLogin();

    if ($exist !== false) {
        throw new Exception('Le login est déjà utilisé !');
    } else {
        $retourCreate = $user->createUser();
        if ($retourCreate === false) {
            throw new Exception('Impossible de créer l\'utilisateur !');
        } else {
            $infos = $user->getUser($login, $pass);
            $_SESSION['idSender'] = $infos['id'];
            $_SESSION['login'] = $infos['login'];
            $user->setOnline($infos['id'], 1);
            header('Location: index.php?action=listMessages');
        }
    }
}

/**
 * Affiche la page listMessageView
 */
function listMessages()
{
    $message = new \Tchat\Model\Message($_SESSION['idSender']);
    $conversations = $message->getListConversation();
    $user = new \Tchat\Model\User($_SESSION['idSender']);
    $isOnline = $user->getIsOnline();
    require('view/listMessagesView.php');
}

/**
 * Affiche la page newConversation
 */
function newConversation()
{
    $user = new \Tchat\Model\User($_SESSION['idSender']);
    $isOnline = $user->getIsOnline();
    require('view/newConversationView.php');
}

/**
 * Fonction utilisée pour l'autocompletion lors de la recherche
 * d'un contact avec qui discuter
 * @param  string $input Saisie de l'utilisateur
 * @return JSON          JSON avec tous les utilisateurs correspondant au début de la saisie
 */
function ajaxLogin($input)
{
    $user = new \Tchat\Model\User($_SESSION['idSender']);
    $logins = $user->getLogins($input);
    $allLogins = array();
    while ($login = $logins->fetch()) {
        $allLogins[] = $login['login'];
    }

    echo json_encode($allLogins);
}

/**
 * Affiche une conversation entre 2 utilisateurs
 * @param  string $loginReceiver
 * @return redirection/Exception
 */
function conversation($loginReceiver)
{
    $user = new \Tchat\Model\User($loginReceiver, null);
    $exist = $user->checkLogin();
    if ($exist !== false) {
        $contact['login'] = $loginReceiver;
        $contact['id'] = $exist['id'];
        $message = new \Tchat\Model\Message($_SESSION['idSender'], $contact['id']);
        $messages = $message->getConversation();
        require('view/messageView.php');
    } else {
        throw new Exception('Le login saisi est inconnu!');
    }
}

/**
 * Sauvegarde d'un message envoyé durant une conversation
 * @param  int    $sender
 * @param  int    $receiver
 * @param  string $content
 * @return redirection/Exception
 */
function sendMessage($sender, $receiver, $content)
{
    //On enlève les sauts de ligne
    $content=str_replace("\n", " ", $content);
    $content=str_replace("\r\n", " ", $content);
    $content=str_replace("\r", " ", $content);
    
    $message = new \Tchat\Model\Message($sender, $receiver, htmlentities(addslashes($content)));
    $addMess = $message->sendMessage();

    if ($addMess === false) {
        throw new Exception('Impossible d\'envoyer le message !');
    } else {
        $user = new \Tchat\Model\User($receiver);
        $login = $user->getLoginWithId();
        conversation($login['login']);
    }
}
