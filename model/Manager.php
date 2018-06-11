<?php

namespace Tchat\Model;

/**
 * Classe Manager : Connexion à la BDD
 */
class Manager
{
    /**
     * Fonction de connexion à la BDD
     * @return PDO
     */
    protected function dbConnect()
    {
        $db = new \PDO('mysql:host=localhost:3306;dbname=tchat;charset=utf8', 'root', 'usbw');
        return $db;
    }
}
