<?php

require_once("../Datenbank/genre.php");




class GenreRepository
{
    public $collectionAllGenres = array();
    private $Datenbank;
    private $genrei;
    private $artworki;

    public function __construct($db)
    {
        $this->Datenbank = $db;
        $this->getAllGenre();
        $this->genrei = Genre::getDefaultGenre();
    }



    private function getAllGenre() // Get all Subject from database 
    {
        $this->Datenbank->connect();
        try {
            $anfrage =
                "SELECT * FROM `genres` ORDER BY Era, GenreName";

            $stmt = $this->Datenbank->prepareStatement($anfrage);

            $stmt->execute();

            $genres = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {

            echo "Fehler: " . $e->getMessage();
            return false;
        } finally {
            $this->Datenbank->close();
        }

        $this->collectionAllGenres = $genres;
    }

    public function displayAllGenres() //display all Subject in the site "Subjects.php"
    {
        $genres = $this->collectionAllGenres;

        foreach ($genres as $genre) {

            $this->genrei = Genre::fromState($genre);

            $this->genrei->outputGenres();
        }
    }


    public function displayGenreById($genreID)
    {
        $genres = $this->collectionAllGenres;

        // Durchlaufen der Sammlung aller Subjekte
        foreach ($genres as $genre) {
            // Überprüfen, ob die ID des aktuellen Subjekts mit der gesuchten ID übereinstimmt
            if ($genre['GenreID'] == $genreID) {
                // Subjekt aus dem Zustand erstellen und ausgeben
                $genreObj = Genre::fromState($genre);
                $genreObj->outputSingleGenre();
                return; // Wenn das Subjekt gefunden wurde, die Schleife beenden
            }
        }

        // Fehlermeldung, falls das Subjekt mit der angegebenen ID nicht gefunden wurde
        echo "Subjekt mit der ID $genreID wurde nicht gefunden.";
    }

    public function getArtworksByGenreId($genreID)
    {
        try {
            // Connect to the database
            $this->Datenbank->connect();

            // SQL query to retrieve artworks for the provided subject ID
            $query = "SELECT *
            FROM artworkgenres, artworks
            WHERE artworkgenres.ArtworkID = artworks.ArtworkID
            AND artworkgenres.GenreID = :genreID";

            $statement = $this->Datenbank->prepareStatement($query);
            $statement->bindParam(':genreID', $genreID);
            $statement->execute();

            // Fetch artworks related to the subject
            $artworkData = $statement->fetchAll(PDO::FETCH_ASSOC);

            // Close the database connection
            $this->Datenbank->close();

            // Return artwork data
            return $artworkData;
        } catch (PDOException $e) {
            // Handle exceptions (e.g., database connection error)
            exit('Error retrieving artworks: ' . $e->getMessage());
        }
    }

    // Methode für Ahmad
    public function getGenresByArtworksID($artworksID)
    {
        try {
            // Verbindung zur Datenbank herstellen
            $this->Datenbank->connect();

            // SQL-Abfrage, um die Genres für die bereitgestellte Artwork-ID abzurufen
            $query = "SELECT genres.GenreID, genres.GenreName
            FROM artworkgenres
            INNER JOIN genres ON artworkgenres.GenreID = genres.GenreID
            WHERE artworkgenres.ArtworkID = :artworksID";

            $statement = $this->Datenbank->prepareStatement($query);
            $statement->bindParam(':artworksID', $artworksID);
            $statement->execute();

            // Genres für das Kunstwerk abrufen
            $genreData = $statement->fetchAll(PDO::FETCH_ASSOC);

            // Datenbankverbindung schließen
            $this->Datenbank->close();

            // Genres zurückgeben
            return $genreData;
        } catch (PDOException $e) {
            // Ausnahmen behandeln (z.B. Datenbankverbindungsfehler)
            exit('Fehler beim Abrufen der Genres: ' . $e->getMessage());
        }
  
    }
}
    
    
    

    

    



    



