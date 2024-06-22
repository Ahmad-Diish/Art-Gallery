<?php


// Diese Klasse fr den Aufruf von Artwork-klasse

require_once("../Datenbank/artworkClass.php");




class ArtworkManager
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

            $this->datenbank->connect();
    
 
            $anfrage = "SELECT * FROM Artworks";
            $stmt = $this->datenbank->prepareStatement($anfrage);
            $stmt->execute();

            $this->collectionAllArtworks = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        } catch (Exception $ex) {

            exit('Could not retrieve Artwork' . $ex->getMessage());
        } finally {

            $this->datenbank->close();
        }
    }
    // für Artist
    public function getArtwork($id)
    {
        try {
            $result = $this->getArtworkByID($id);
            if ($result === null) {
                throw new Exception('The ArtworkID is not available');
            }
            $this->artworki = Artwork::fromState($result);
            return $this->artworki;
        } catch (Exception $e) {
            throw new Exception('Error fetching artwork: ' . $e->getMessage());
        }
    }

    private function getArtworkByID($artworkId)
    {
        $this->datenbank->connect();
        try {
            $anfrage = "SELECT * FROM artworks WHERE ArtWorkID = :artworkId";
            $stmt = $this->datenbank->prepareStatement($anfrage);
            $stmt->bindParam(':artworkId', $artworkId);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result === false) {
                $result = null;
            }
        } catch (Exception $ex) {
            throw new Exception('Could not retrieve Artwork: ' . $ex->getMessage());
        } finally {
            $this->datenbank->close();
        }
        return $result;
    }


    // TOP Artwork Homepage
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

                if ($sortierreihenfolge == 'aufsteigend') {
         
                    $anfrage = "SELECT * FROM Artworks ORDER BY YearOfWork ASC";
                }
                // absteigend Z---A 
                elseif ($sortierreihenfolge == 'absteigend') {
                    $anfrage = "SELECT * FROM Artworks ORDER BY YearOfWork DESC";
                }
            } elseif ($sortierungsart == 'ArtistID') {
                if ($sortierreihenfolge == 'aufsteigend') {

                    $anfrage = "SELECT * FROM Artworks ORDER BY ArtistID ASC";
                }
                // absteigend Z---A 
                elseif ($sortierreihenfolge == 'absteigend') {
                    $anfrage = "SELECT * FROM Artworks ORDER BY ArtistID DESC";
                }
            } elseif ($sortierungsart == 'Title') {
                if ($sortierreihenfolge == 'aufsteigend') {

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
