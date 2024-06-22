<?php

require_once("../Datenbank/config.php");



class datenbank
{
  
    private $dsn= 'mysql:host='.DBHOST. ';dbname='.DBNAME;

    private $user=DBUSER;
 
    private $password=DBPASS;

    private $conn ;


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


    public function isConnected(){return $this->conn!=null;}

    public function close(){return $this->conn = null;}

 
    public function beginTransaction()
    {
        try {
            $this->conn->beginTransaction();
        } catch (PDOException $e) {

            throw new Exception("Fehler beim Starten der Transaktion: " . $e->getMessage());
        }
    }


    public function rollback()
    {
        try {
            if ($this->conn->inTransaction()) {
                $this->conn->rollback();
            }
        } catch (PDOException $e) {

            throw new Exception("Fehler beim Rollback der Transaktion: " . $e->getMessage());
        }
    }


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
