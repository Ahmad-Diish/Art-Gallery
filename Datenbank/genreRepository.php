<?php


require_once("../Datenbank/genre.php");
require_once("../Datenbank/datenbank.php");




class GenreRepository
{
  
    private $collectionAllGenres = array();

    private $Datenbank;
    private $genrei;
    private $artworki ;
    


   
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
            $anfrage ="SELECT * FROM `genres` ORDER BY Era, GenreName"; 

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
    
    public function getGenreByID($genreId)
    {
        $this->Datenbank->connect();
        try{
            $anfrage = "SELECT * FROM genres WHERE GenreID = :genreId";

            $stmt = $this->Datenbank->prepareStatement($anfrage);

            $stmt->bindParam(":genreId", $genreId);

            $stmt->execute();
            

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result->fetch_assoc();
        } catch (Exception $ex) {
            exit('Genres konnten nicht abgerufen werden' . $ex->getMessage());
        } finally {
            $this->Datenbank->close();
        }
    }
        

        public function getArtworksForGenre($genreId)
         {
            $this->Datenbank->connect();
          
            $anfrage = "SELECT * FROM artworks WHERE GenreID = :genreId";
             $stmt = $this->Datenbank->prepare($anfrage);
             $stmt->bind_param(":genreId", $genreId);
             $stmt->execute();
             $result = $stmt->get_result();
             $artworks = array();
             while ($row = $result->fetch_assoc()) {
               $artworks[] = $row;
             }
             return $artworks;
        }
    
}
    



    



