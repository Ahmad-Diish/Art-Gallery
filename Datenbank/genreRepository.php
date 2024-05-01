<?php


require_once("../Datenbank/genre.php");
require_once("../Datenbank/datenbank.php");


class GenreRepository
{
  
    private $collectionAllGenres = array();

    private $Datenbank;
    private $genrei;
    


   
    public function __construct($db)
    {
        $this->Datenbank = $db;
        $this->getAllGenres();
        $this->genrei =Genre::getDefaultGenre();
     

    }

  
    public function AllGenres()
    {
        $genres = $this->collectionAllGenres;

        foreach ($genres as $genre) {

            $this->genrei = Genre::fromState($genre);

            $this->genrei->outputGenres();
        }
    }

  
    private function getAllGenres()
    {  
        $this->Datenbank->connect();
        try{
            $anfrage = "SELECT * FROM `genres`";

            $stmt = $this->Datenbank->prepareStatement($anfrage);

            $stmt->execute();

            $genres = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $ex) {
            exit('could not retrieve Genres' . $ex->getMessage());
        } finally {
            $this->Datenbank->close();
        }
        
        $this->collectionAllGenres = $genres;
    }
    



    


}
