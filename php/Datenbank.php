<?php

require_once("../php/DBConfig.php");


class Database
{
    /**
     * @var string 
     */
    private $dsn= 'mysql:host='.DBHOST. ';dbname='.DBNAME;
    /**
     * @var string 
     */
    private $user=DBUSER;
    /**
     * @var string 
     */
    private $password=DBPASS;
    /**
     * @var PDO 
     */
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
}
?>