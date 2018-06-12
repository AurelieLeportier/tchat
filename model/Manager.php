<?php

namespace Tchat\Model;

/**
 * Classe Manager : Connexion à la BDD
 */
class Manager
{
	protected $db;

    /**
     * Fonction de connexion à la BDD
     * @return PDO
     */
    protected function dbConnect()
    {
        $this->db = new \PDO('mysql:host=localhost:3306;dbname=tchat;charset=utf8', 'root', 'usbw');
        return $this->db;
    }
}
