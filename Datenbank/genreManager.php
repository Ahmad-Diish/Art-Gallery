<?php

// Einbindung der Datei, die die Klasse Genre enthält
require_once("../Datenbank/genreClass.php");

// Definition der Klasse GenreManager
class GenreManager
{
    // Öffentliche Variable zur Speicherung aller Genres
    public $collectionAllGenres = array();
    // Private Variablen zur Speicherung der Datenbankverbindung und des aktuellen Genres
    private $Datenbank;
    private $genrei;
    private $artworki;

    // Konstruktor zur Initialisierung des GenreManagers mit der Datenbankverbindung
    public function __construct($db)
    {
        $this->Datenbank = $db;
        $this->getAllGenre(); // Abrufen aller Genres aus der Datenbank
        $this->genrei = Genre::getDefaultGenre(); // Initialisieren des aktuellen Genres mit einem Standardwert
    }

    // Private Methode zum Abrufen aller Genres aus der Datenbank
    private function getAllGenre()
    {
        // Verbindung zur Datenbank herstellen
        $this->Datenbank->connect();
        try {
            // SQL-Abfrage zum Abrufen aller Genres, sortiert nach Ära und GenreName
            $anfrage = "SELECT * FROM `genres` ORDER BY Era, GenreName";

            // Vorbereiten der SQL-Anfrage
            $stmt = $this->Datenbank->prepareStatement($anfrage);

            // Ausführen der SQL-Anfrage
            $stmt->execute();

            // Abrufen der Genres als assoziatives Array
            $genres = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Fehlerbehandlung
            echo "Fehler: " . $e->getMessage();
            return false;
        } finally {
            // Schließen der Datenbankverbindung
            $this->Datenbank->close();
        }

        // Speichern der abgerufenen Genres in der Sammlung
        $this->collectionAllGenres = $genres;
    }

    // Methode zur Ausgabe aller Genres auf der Seite "Genres.php"
    public function displayAllGenres()
    {
        $genres = $this->collectionAllGenres;

        // Durchlaufen der Sammlung aller Genres
        foreach ($genres as $genre) {
            // Erstellen eines Genre-Objekts aus dem Zustand und Ausgabe der Informationen
            $this->genrei = Genre::fromState($genre);
            $this->genrei->outputGenres();
        }
    }

    // Methode zur Anzeige eines einzelnen Genres basierend auf der GenreID
    public function displayGenreById($genreID)
    {
        $genres = $this->collectionAllGenres;

        // Durchlaufen der Sammlung aller Genres
        foreach ($genres as $genre) {
            // Überprüfen, ob die ID des aktuellen Genres mit der gesuchten ID übereinstimmt
            if ($genre['GenreID'] == $genreID) {
                // Erstellen eines Genre-Objekts aus dem Zustand und Ausgabe der Informationen
                $genreObj = Genre::fromState($genre);
                $genreObj->outputSingleGenre();
                return; // Wenn das Genre gefunden wurde, die Schleife beenden
            }
        }

        // Fehlermeldung, falls das Genre mit der angegebenen ID nicht gefunden wurde
        echo "Genre mit der ID $genreID wurde nicht gefunden.";
    }

    // Methode zum Abrufen der Kunstwerke basierend auf der GenreID
    public function getArtworksByGenreId($genreID)
    {
        try {
            // Verbindung zur Datenbank herstellen
            $this->Datenbank->connect();

            // SQL-Abfrage zum Abrufen der Kunstwerke für die angegebene GenreID
            $query = "SELECT *
            FROM artworkgenres, artworks
            WHERE artworkgenres.ArtworkID = artworks.ArtworkID
            AND artworkgenres.GenreID = :genreID";

            // Vorbereiten der SQL-Abfrage
            $statement = $this->Datenbank->prepareStatement($query);
            $statement->bindParam(':genreID', $genreID);
            $statement->execute();

            // Abrufen der Kunstwerke als assoziatives Array
            $artworkData = $statement->fetchAll(PDO::FETCH_ASSOC);

            // Schließen der Datenbankverbindung
            $this->Datenbank->close();

            // Rückgabe der Kunstwerke
            return $artworkData;
        } catch (PDOException $e) {
            // Fehlerbehandlung
            exit('Error retrieving artworks: ' . $e->getMessage());
        }
    }

    // Methode zum Abrufen der Genres basierend auf der ArtworkID (für Ahmad)
    public function getGenresByArtworksID($artworksID)
    {
        try {
            // Verbindung zur Datenbank herstellen
            $this->Datenbank->connect();

            // SQL-Abfrage zum Abrufen der Genres für die angegebene ArtworkID
            $query = "SELECT genres.GenreID, genres.GenreName
            FROM artworkgenres
            INNER JOIN genres ON artworkgenres.GenreID = genres.GenreID
            WHERE artworkgenres.ArtworkID = :artworksID";

            // Vorbereiten der SQL-Abfrage
            $statement = $this->Datenbank->prepareStatement($query);
            $statement->bindParam(':artworksID', $artworksID);
            $statement->execute();

            // Abrufen der Genres als assoziatives Array
            $genreData = $statement->fetchAll(PDO::FETCH_ASSOC);

            // Schließen der Datenbankverbindung
            $this->Datenbank->close();

            // Rückgabe der Genres
            return $genreData;
        } catch (PDOException $e) {
            // Fehlerbehandlung
            exit('Fehler beim Abrufen der Genres: ' . $e->getMessage());
        }
    }
}
?>
