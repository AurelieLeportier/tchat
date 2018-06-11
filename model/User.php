<?php

namespace Tchat\Model;

require_once("model/Manager.php");

/**
 * Classe User
 */
class User extends Manager
{
    private $id;
    private $login;
    private $password;
    private $online;

    /**
     * Constructeur de la classe
     * @param string  $login
     * @param string  $password
     * @param boolean $online
     */
    /*public function __construct($login, $password, $online)
    {
        $this->login = $login;
        $this->password = $password;
        $this->online = $online;
    }*/

    /**
     * Retourne l'id
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Retourne le login de l'user
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }
    
    /**
     * Retourne si l'user est en ligne ou non
     * @return boolean
     */
    public function getOnline()
    {
        return $this->online;
    }

    /**
     * Récupération d'un user
     * @param  string $login
     * @param  string $pass
     * @return Array/false   Retourne un array avec les infos de l'user ou false si pas trouvé
     */
    public function getUser($login, $pass)
    {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT id, login FROM user WHERE login = ? AND password = ?;');
        $req->execute(array($login, hash("sha512", $pass)));
        $user = $req->fetch();

        return $user;
    }

    /**
     * Récupération d'un login avec un id
     * @param  int $id
     * @return Array   Retourne un array avec le login de l'user
     */
    public function getLoginWithId($id)
    {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT login FROM user WHERE id = ?;');
        $req->execute(array($id));
        $user = $req->fetch();

        return $user;
    }

    /**
     * Retourne la liste des membres en ligne
     * @param  int $idSender
     * @return PDO query      Retourne la requête
     */
    public function getIsOnline($idSender)
    {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT login FROM user WHERE online = 1 AND id <> ?;');
        $req->execute(array($idSender));

        return $req;
    }

    /**
     * Création d'un utilisateur
     * @param  string $login
     * @param  string $pass
     * @return PDO query     Retourne la réponse de la query
     */
    public function createUser($login, $pass)
    {
        $db = $this->dbConnect();
        $query = $db->prepare('INSERT INTO user(login, password, online) VALUES(?, ?, 0)');
        $retourCreate = $query->execute(array($login, hash("sha512", $pass)));

        return $retourCreate;
    }
    
    /**
     * Modifie le statut online de l'user
     * @param int $idSender
     * @param int $isOnline
     * @return PDO query
     */
    public function setOnline($idSender, $isOnline)
    {
        $db = $this->dbConnect();
        $query = $db->prepare('UPDATE user SET online=? WHERE id =?;');
        $query->execute(array($isOnline, $idSender));

        return $query;
    }

    /**
     * Retourne la liste des logins (pour l'Ajax autocomplete)
     * @param  int $idSender
     * @param  string $input Saisie de l'utilisateur
     * @return Array         Liste avec tous les logins
     */
    public function getLogins($idSender, $input)
    {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT DISTINCT(login) as login FROM user WHERE id <> ? AND login LIKE ?;');
        $req->execute(array($idSender, $input."%"));

        return $req;
    }

    /**
     * Vérification du login (notamment pour la création d'une conversation)
     * @param  string $login
     * @return PDO query
     */
    public function checkLogin($login)
    {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT id FROM user WHERE login = ?;');
        $req->execute(array($login));
        $exist = $req->fetch();

        return $exist;
    }
}
