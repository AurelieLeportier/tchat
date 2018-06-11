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
    $user = new \Tchat\Model\User();
    $exist = $user->getUser($_POST['login'], $_POST['pass']);
    if ($exist !== false) {
        $_SESSION['idSender'] = $exist['id'];
        $_SESSION['login'] = $exist['login'];
        $user->setOnline($exist['id'], 1);
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
    $user = new \Tchat\Model\User();
    $user->setOnline($_SESSION['idSender'], false);
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
    $user = new \Tchat\Model\User();
    $exist = $user->getUser($login, $pass);

    if ($exist !== false) {
        throw new Exception('Le login est déjà utilisé !');
    } else {
        $retourCreate = $user->createUser($login, $pass);
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
    $message = new \Tchat\Model\Message();
    $conversations = $message->getListConversation($_SESSION['idSender']);
    $user = new \Tchat\Model\User();
    $isOnline = $user->getIsOnline($_SESSION['idSender']);
    require('view/listMessagesView.php');
}

/**
 * Affiche la page newConversation
 */
function newConversation()
{
    $user = new \Tchat\Model\User();
    $isOnline = $user->getIsOnline($_SESSION['idSender']);
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
    $user = new \Tchat\Model\User();
    $logins = $user->getLogins($_SESSION['idSender'], $input);
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
    $user = new \Tchat\Model\User();
    $exist = $user->checkLogin($loginReceiver);
    if ($exist !== false) {
        $contact['login'] = $loginReceiver;
        $contact['id'] = $exist['id'];
        $message = new \Tchat\Model\Message();
        $messages = $message->getConversation($_SESSION['idSender'], $contact['id']);
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
    $message = new \Tchat\Model\Message();

    //On enlève les sauts de ligne
    $content=str_replace("\n", " ", $content);
    $content=str_replace("\r\n", " ", $content);
    $content=str_replace("\r", " ", $content);
    
    $addMess = $message->sendMessage($sender, $receiver, htmlentities(addslashes($content)));

    if ($addMess === false) {
        throw new Exception('Impossible d\'envoyer le message !');
    } else {
        $user = new \Tchat\Model\User();
        $login = $user->getLoginWithId($receiver);
        conversation($login['login']);
    }
}
