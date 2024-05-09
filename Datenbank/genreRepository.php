<?php

require_once("../Datenbank/genre.php");

class GenreRepository
{
    private $collectionAllGenres = array();
    private $Datenbank;
    private $genrei;
    private $artworki;

    public function __construct($db)
    {
        $this->Datenbank = $db;
        $this->getAllGenres();
        $this->genrei = Genre::getDefaultGenre();
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
        try {
            $anfrage = "SELECT * FROM `genres` ORDER BY Era, GenreName";
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
        try {
            $anfrag = "SELECT * FROM genres WHERE GenreID = :genreId";
            $stmt = $this->Datenbank->prepareStatement($anfrag);
            $stmt->bindParam(":genreId", $genreId);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $ex) {
            exit('could not retrieve Genre' . $ex->getMessage());
        } finally {
            $this->Datenbank->close();
        }
        return $result;
    }

    public function getArtworksForGenre($genreId)
    {
        $this->Datenbank->connect();
        try {
            $anfrag = "SELECT * FROM artworkgenres
                      INNER JOIN artwork ON artworkgenres.ArtworkID = artwork.ArtworkID
                      WHERE artworkgenres.GenreID = :genreId";
            $stmt = $this->Datenbank->prepareStatement($anfrag);
            $stmt->bindParam(":genreId", $genreId);
            $stmt->execute();
            $artworks = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $artworks = [
                ['title' => 'Artwork 1'],
                ['title' => 'Artwork 2'],
                ['title' => 'Artwork 3']
            ];
        } catch (Exception $ex) {
            exit('could not retrieve Artworks for Genre' . $ex->getMessage());
        } finally {
            $this->Datenbank->close();
        }

        return $artworks;
    }
}
    

    



    



