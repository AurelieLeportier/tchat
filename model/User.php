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
    private $online = false;

    public function __construct()
    {
        //Nombre d'arguments passé
        $ctp = func_num_args();
        //Récupération des arguments sous forme de tableau
        $args = func_get_args();

        switch($ctp){
            case 1:
                $this->construct1($args[0]);
                break;
            case 2:
                $this->construct2($args[0],$args[1]);
                break;
            case 3:
                $this->construct3($args[0],$args[1],$args[2]);
                break;
             default:
                break;
        }
    }

    /**
     * Constructeur de la classe avec 1 argument
     * @param int  $id
     */
    private function construct1($id)
    {
        $this->id = $id;
    }

    /**
     * Constructeur de la classe avec 2 arguments
     * @param string  $login
     * @param string  $password
     */
    private function construct2($login, $password)
    {
        $this->login = $login;
        $this->password = $password;
    }

    /**
     * Constructeur de la classe avec 3 arguments
     * @param string  $login
     * @param string  $password
     * @param boolean $online
     */
    private function construct3($login, $password, $online)
    {
        $this->login = $login;
        $this->password = $password;
        $this->online = $online;
    }

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
     * @return Array/false   Retourne un array avec les infos de l'user ou false si pas trouvé
     */
    public function getUser()
    {
        $db = parent::dbConnect();
        $req = $this->db->prepare('SELECT id, login FROM user WHERE login = ? AND password = ?;');
        $req->execute(array($this->login, hash("sha512", $this->password)));
        $user = $req->fetch();
        if ($user !== false) {
            //Sauvegarde des infos retournées
            $this->id = $user['id'];
            $this->login = $user['login'];
        }

        return $user;
    }

    /**
     * Récupération d'un login avec un id
     * @return Array   Retourne un array avec le login de l'user
     */
    public function getLoginWithId()
    {
        $db = parent::dbConnect();
        $req = $db->prepare('SELECT login FROM user WHERE id = ?;');
        $req->execute(array($this->id));
        $user = $req->fetch();

        return $user;
    }

    /**
     * Retourne la liste des membres en ligne
     * @return PDO query      Retourne la requête
     */
    public function getIsOnline()
    {
        $db = parent::dbConnect();
        $req = $db->prepare('SELECT login FROM user WHERE online = 1 AND id <> ?;');
        $req->execute(array($this->id));

        return $req;
    }

    /**
     * Création d'un utilisateur
     * @return PDO query Retourne la réponse de la query
     */
    public function createUser()
    {
        $db = parent::dbConnect();
        $query = $db->prepare('INSERT INTO user(login, password, online) VALUES(?, ?, false)');
        $retourCreate = $query->execute(array($this->login, hash("sha512", $this->password)));

        return $retourCreate;
    }
    
    /**
     * Modifie le statut online de l'user
     * @param int $isOnline
     * @return PDO query
     */
    public function setOnline($isOnline)
    {
        $db = parent::dbConnect();
        $query = $db->prepare('UPDATE user SET online=? WHERE id =?;');
        $query->execute(array($isOnline, $this->id));

        return $query;
    }

    /**
     * Retourne la liste des logins (pour l'Ajax autocomplete)
     * @param  string $input Saisie de l'utilisateur
     * @return Array         Liste avec tous les logins
     */
    public function getLogins($input)
    {
        $db = parent::dbConnect();
        $req = $db->prepare('SELECT DISTINCT(login) as login FROM user WHERE id <> ? AND login LIKE ?;');
        $req->execute(array($this->id, $input."%"));

        return $req;
    }

    /**
     * Vérification du login
     * @return PDO query
     */
    public function checkLogin()
    {
        $db = parent::dbConnect();
        $req = $db->prepare('SELECT id FROM user WHERE login = ?;');
        $req->execute(array($this->login));
        $exist = $req->fetch();

        return $exist;
    }
}
