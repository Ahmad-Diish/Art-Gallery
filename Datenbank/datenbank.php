<?php

require_once("../Datenbank/config.php");


/**
 * Klasse Database
 * 
 * Diese Klasse stellt eine Verbindung zur Datenbank her und verwaltet diese. Sie bietet Methoden
 * zum Vorbereiten und Ausführen von SQL-Anweisungen, Handhabung von Transaktionen und Überwachung
 * des Verbindungsstatus.
 */
class datenbank
{
    /**
     * @var string $dsn Der DSN-String, der die Verbindungsinformationen zur Datenbank enthält.
     */
    private $dsn= 'mysql:host='.DBHOST. ';dbname='.DBNAME;
    /**
     * @var string $user Der Benutzername für die Datenbankverbindung.
     */
    private $user=DBUSER;
    /**
     * @var string $password Das Passwort für die Datenbankverbindung.
     */
    private $password=DBPASS;
    /**
     * @var PDO $conn Die PDO-Instanz, die die Verbindung zur Datenbank repräsentiert.
     */
    private $conn ;

    /**
     * Öffnet eine Verbindung zur Datenbank.
     * 
     * Verwendet die in der Klasse definierten Verbindungsdaten, um eine neue PDO-Verbindung herzustellen.
     * Setzt Attribute für Fehlermodus und wirft eine Exception, wenn bereits eine Verbindung besteht.
     */
    public function connect()
    {
       if ($this->isConnected()){
        throw new Exception('the Database is already connected') ;
       }     
        try{
            $this->conn = new PDO ($this->dsn,$this->user,$this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE , PDO::ERRMODE_EXCEPTION);
        }catch(PDOException $e)
        {
                echo "connection failed:  " . $e->getMessage();
                echo "On line : ".$e->getLine() ;
                echo "Stack  Trace: ";print($e->getTrace());  
                
        }
    }
    /**
     * Bereitet ein SQL-Statement zur Ausführung vor.
     * 
     * Nimmt eine SQL-Abfrage als Parameter und gibt ein vorbereitetes Statement zurück.
     * Wirft eine Exception, wenn keine Verbindung zur Datenbank besteht.
     * 
     * @param string $query Das SQL-Statement, das vorbereitet werden soll.
     * @return PDOStatement Das vorbereitete PDOStatement.
     */
    public function prepareStatement($query){
        if (!$this->isConnected()){
        throw new Exception('((((the Database is not connected))))') ;
       }   
        return $this->conn->prepare($query);  
    }
    public function getConnection()
    {
        return $this->conn;
    }

    /**
     * Überprüft, ob eine Verbindung zur Datenbank besteht.
     * 
     * @return bool Wahr, wenn eine Verbindung besteht, sonst falsch.
     */
    public function isConnected(){return $this->conn!=null;}


    /**
     * Schließt die Verbindung zur Datenbank.
     */
    public function close(){return $this->conn = null;}

    /**
     * Startet eine Transaktion.
     * 
     * Beginnt eine neue Transaktion in der Datenbank und setzt die Verbindung in den Transaktionsmodus.
     * Wirft eine Exception im Fehlerfall.
     */
    public function beginTransaction()
    {
        try {
            $this->conn->beginTransaction();
        } catch (PDOException $e) {
            // Fehlerbehandlung, z.B. Loggen des Fehlers
            throw new Exception("Fehler beim Starten der Transaktion: " . $e->getMessage());
        }
    }

    /**
     * Macht alle Änderungen einer Transaktion rückgängig.
     * 
     * Führt ein Rollback der aktuellen Transaktion durch, wenn die Verbindung sich im Transaktionsmodus befindet.
     * Wirft eine Exception im Fehlerfall.
     */
    public function rollback()
    {
        try {
            if ($this->conn->inTransaction()) {
                $this->conn->rollback();
            }
        } catch (PDOException $e) {
            // Fehlerbehandlung
            throw new Exception("Fehler beim Rollback der Transaktion: " . $e->getMessage());
        }
    }

    /**
     * Schließt eine Transaktion ab und speichert die Änderungen.
     * 
     * Führt ein Commit der aktuellen Transaktion durch und speichert alle Änderungen dauerhaft in der Datenbank.
     * Im Fehlerfall wird ein Rollback ausgeführt und eine Exception geworfen.
     */
    public function commit()
    {
        try {
            $this->conn->commit();
        } catch (PDOException $e) {
            // Fehlerbehandlung, z.B. Loggen des Fehlers und Rollback
            $this->conn->rollback();
            throw new Exception("Fehler beim Commit der Transaktion: " . $e->getMessage());
        }
    }



}

?>
