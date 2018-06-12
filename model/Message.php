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
     * @param int $sender
     */
    private function construct1($sender)
    {
        $this->sender = $sender;
    }

    /**
     * Constructeur de la classe avec 2 arguments
     * @param int $sender
     * @param int $receiver
     */
    private function construct2($sender, $receiver)
    {
        $this->sender = $sender;
        $this->receiver = $receiver;
    }

    /**
     * Constructeur de la classe avec 3 arguments
     * @param int      $sender
     * @param int      $receiver
     * @param string   $content
     */
    private function construct3($sender, $receiver, $content)
    {
        $this->sender = $sender;
        $this->receiver = $receiver;
        $this->content = $content;
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
     * @return PDO query
     */
    public function sendMessage()
    {
        $db = parent::dbConnect();
        $query = $db->prepare('INSERT INTO message(sender, receiver, content, date_send) VALUES(?, ?, ?, NOW())');
        $query->execute(array($this->sender, $this->receiver, $this->content));

        return $query;
    }

    /**
     * Retourne la liste des logins avec qui l'user a eu une conversation
     * @return PDO query
     */
    public function getListConversation()
    {
        $db = parent::dbConnect();
        $query = $db->prepare('SELECT DISTINCT u.id, u.login
                               FROM user u INNER JOIN message m ON u.id = m.sender
                               WHERE m.sender = :sender
                               OR m.receiver = :sender
                               UNION
                               SELECT DISTINCT u.id, u.login
                               FROM user u INNER JOIN message m ON u.id = m.receiver
                               WHERE m.receiver = :sender
                               OR m.sender = :sender;');
        $query->execute(array("sender" => $this->sender));

        return $query;
    }

    /**
     * Retourne la conversation entre 2 utilisateurs
     * @return PDO query
     */
    public function getConversation()
    {
        $db = parent::dbConnect();
        $query = $db->prepare('SELECT *, DATE_FORMAT(date_send, \'%d/%m/%Y à %Hh%imin%ss\') AS date_send_fr FROM message WHERE sender = ? AND receiver = ? OR sender = ? AND receiver = ? ORDER BY date_send ASC;');
        $query->execute(array($this->sender, $this->receiver, $this->receiver, $this->sender));

        return $query;
    }
}
