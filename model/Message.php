<?php

namespace Tchat\Model;

require_once("model/Manager.php");

/**
 * Classe Message
 */
class Message extends Manager
{
    private $id;
    private $sender;
    private $receiver;
    private $content;
    private $dateSend;

    /**
     * Constructeur de la classe
     * @param int      $sender
     * @param int      $receiver
     * @param string   $content
     * @param DateTime $dateSend
     */
    /*public function __construct($sender, $receiver, $content, $dateSend)
    {
        $this->sender = $sender;
        $this->receiver = $receiver;
        $this->content = $content;
        $this->dateSend = $dateSend;
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
     * Retourne l'id du Sender
     * @return int
     */
    public function getSender()
    {
        return $this->sender;
    }
    
    /**
     * Retourne l'id du Receiver
     * @return int
     */
    public function getReceiver()
    {
        return $this->receiver;
    }
    
    /**
     * Retourne le contenu du message
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }
    
    /**
     * Retourne la date d'envoie du message
     * @return DateTime
     */
    public function getDateSend()
    {
        return $this->dateSend;
    }

    /**
     * Enregistrement d'un message envoyé
     * @param  int    $idSender
     * @param  int    $idReceiver
     * @param  string $content
     * @return PDO query
     */
    public function sendMessage($idSender, $idReceiver, $content)
    {
        $db = $this->dbConnect();
        $query = $db->prepare('INSERT INTO message(sender, receiver, content, date_send) VALUES(?, ?, ?, NOW())');
        $query->execute(array($idSender, $idReceiver, $content));

        return $query;
    }

    /**
     * Retourne la liste des logins avec qui l'user a eu une conversation
     * @param  int $idSender
     * @return PDO query
     */
    public function getListConversation($idSender)
    {
        $db = $this->dbConnect();
        $query = $db->prepare('SELECT DISTINCT u.id, u.login
                               FROM user u INNER JOIN message m ON u.id = m.sender
                               WHERE m.sender = :sender
                               OR m.receiver = :sender
                               UNION
                               SELECT DISTINCT u.id, u.login
                               FROM user u INNER JOIN message m ON u.id = m.receiver
                               WHERE m.receiver = :sender
                               OR m.sender = :sender;');
        $query->execute(array("sender" => $idSender));

        return $query;
    }

    /**
     * Retourne la conversation entre 2 utilisateurs
     * @param  int $idSender
     * @param  int $idReceiver
     * @return PDO query
     */
    public function getConversation($idSender, $idReceiver)
    {
        $db = $this->dbConnect();
        $query = $db->prepare('SELECT *, DATE_FORMAT(date_send, \'%d/%m/%Y à %Hh%imin%ss\') AS date_send_fr FROM message WHERE sender = ? AND receiver = ? OR sender = ? AND receiver = ? ORDER BY date_send ASC;');
        $query->execute(array($idSender, $idReceiver, $idReceiver, $idSender));

        return $query;
    }
}
