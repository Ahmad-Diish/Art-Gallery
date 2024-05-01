<?php


// Diese Klasse fr den Aufruf von Artwork-klasse

require_once("../Datenbank/artwork.php");




class ArtworkRepository
{


    private $collectionAllArtworks = array();


 
    private $datenbank;
    private $artworki;


    public function __construct($db)
    {

        $this->datenbank = $db;
        $this->getAllArtwork();
        $this->artworki = Artwork::getDefaultArtwork();
    }


    public function AllArtworks()
    {
        $artworks = $this->collectionAllArtworks;

        foreach ($artworks as $artwork) {

            $this->artworki = Artwork::fromState($artwork);
            $this->artworki->outputArtworks();
        }
    }



    public function getAllArtwork()
    {
        try {
            // Verbindung zur Datenbank herstellen
            $this->datenbank->connect();
    
            // SQL-Anfrage zum Abrufen aller Kunstwerke
            $anfrage = "SELECT * FROM Artworks";
            $stmt = $this->datenbank->prepareStatement($anfrage);
            $stmt->execute();
    
            // Kunstwerke aus der Datenbank abrufen
            $this->collectionAllArtworks = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        } catch (Exception $ex) {
            // Fehlerbehandlung, falls das Abrufen der Kunstwerke fehlschlägt
            exit('Could not retrieve Artwork' . $ex->getMessage());
        } finally {
            // Datenbankverbindung schließen, unabhängig davon, ob ein Fehler aufgetreten ist oder nicht
            $this->datenbank->close();
        }
    }
    public function getArtwork($id)
    {
        $result = $this->getArtworkByID($id);
        if ($result === null) {
            throw new Exception('the ArtworksID is not available');
        }

        $this->artworki = Artwork::fromState($result);
        return $this->artworki;
    }
    private function getArtworkByID($artworkId)
    {
        $this->datenbank->connect();
        try{
            $anfrage = "SELECT * FROM artworks WHERE ArtWorkID = :artworkId";

            $stmt = $this->datenbank->prepareStatement($anfrage);

            $stmt->bindParam(':artworkId', $artworkId);

            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $ex) {
            exit('could not retrieve Artwork' . $ex->getMessage());
        } finally {
            $this->datenbank->close();
        }
        
        return $result;
    }


    // TOPArtwork Homepage
    public function TopArtwork()
    {

        $topArtworks = $this->getTopArtwork();

        foreach ($topArtworks as $topArtwork) {

           $this->artworki = Artwork::fromState($topArtwork);

            $this->artworki->outputArtworks();
        }
    }



    private function getTopArtwork()
    {
        $this->datenbank->connect();
       try{
            $anfrage = "SELECT artworks.ArtWorkID,artworks.ImageFileName,artworks.Title,AVG(reviews.Rating) as average_rating
                    FROM   artworks
                    JOIN   reviews ON artworks.ArtWorkID = reviews.ArtWorkID
                    GROUP BY artworks.ArtWorkID
                    HAVING   COUNT(reviews.ReviewId) >= 3
                    ORDER BY average_rating DESC LIMIT 3";

            $stmt = $this->datenbank->prepareStatement($anfrage);

            $stmt->execute();

            $TopArtworks = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $ex) {
            exit('could not retrieve Artwork' . $ex->getMessage());
        } finally {
            $this->datenbank->close();
        }
        

        return $TopArtworks;
    }


    function sortiereArtworks($sortierungsart, $sortierreihenfolge)
    {
        $this->datenbank->connect();
        
        try{
            if ($sortierungsart == 'YearOfWork') {
                //aufsteigend A---Z
                if ($sortierreihenfolge == 'aufsteigend') {
                    //SELECT column_name(s) FROM table_name ORDER BY column_name(s) ASC|DESC
                    $anfrage = "SELECT * FROM Artworks ORDER BY YearOfWork ASC";
                }
                // absteigend Z---A 
                elseif ($sortierreihenfolge == 'absteigend') {
                    $anfrage = "SELECT * FROM Artworks ORDER BY YearOfWork DESC";
                }
            } elseif ($sortierungsart == 'ArtistID') {
                if ($sortierreihenfolge == 'aufsteigend') {
                    //SELECT column_name(s) FROM table_name ORDER BY column_name(s) ASC|DESC
                    $anfrage = "SELECT * FROM Artworks ORDER BY ArtistID ASC";
                }
                // absteigend Z---A 
                elseif ($sortierreihenfolge == 'absteigend') {
                    $anfrage = "SELECT * FROM Artworks ORDER BY ArtistID DESC";
                }
            } elseif ($sortierungsart == 'Title') {
                if ($sortierreihenfolge == 'aufsteigend') {
                    //SELECT column_name(s) FROM table_name ORDER BY column_name(s) ASC|DESC
                    $anfrage = "SELECT * FROM Artworks ORDER BY Title ASC";
                }
                // absteigend Z---A 
                elseif ($sortierreihenfolge == 'absteigend') {
                    $anfrage = "SELECT * FROM Artworks ORDER BY Title DESC";
                }
            }
            $stmt = $this->datenbank->prepareStatement($anfrage);

            $stmt->execute();

            $artworks = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $ex) {
            exit('could not retrieve Artwork' . $ex->getMessage());
        } finally {
            $this->datenbank->close();
        }
        
        $this->collectionAllArtworks = $artworks;
    }
}
