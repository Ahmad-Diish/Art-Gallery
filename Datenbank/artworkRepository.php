<?php


// Diese Klasse fr den Aufruf von Artwork-klasse

require_once("../Datenbank/artwork.php");




class ArtworkRepository
{


    private $collectionAllArtworks = array();


 
    private $database;
    private $artworki;


    public function __construct($db)
    {

        $this->database = $db;
        $this->getAllArtwork();
        $this->artworki = Artwork::getDefaultArtwork();
    }






    private function getAllArtwork()
    {
        $this->database->connect();
        try{
            $anfrage = "SELECT * FROM `artworks`";

            $stmt = $this->database->prepareStatement($anfrage);

            $stmt->execute();

            $artworks = $stmt->fetchAll(PDO::FETCH_ASSOC);

            
        }catch(Exception $ex){
            exit('could not retrieve Artwork'.$ex->getMessage());

        } finally {
            $this->database->close();
        }
       

        $this->collectionAllArtworks = $artworks;
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
        $this->database->connect();
        try{
            $anfrage = "SELECT * FROM artworks WHERE ArtWorkID = :artworkId";

            $stmt = $this->database->prepareStatement($anfrage);

            $stmt->bindParam(':artworkId', $artworkId);

            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $ex) {
            exit('could not retrieve Artwork' . $ex->getMessage());
        } finally {
            $this->database->close();
        }
        
        return $result;
    }

    // TOPArtwork Homepage
    public function displayTopArtwork()
    {

        $topArtworks = $this->getTopArtwork();

        foreach ($topArtworks as $topArtwork) {

           $this->artworki = Artwork::fromState($topArtwork);

            $this->artworki->outputArtworks();
        }
    }



    private function getTopArtwork()
    {
        $this->database->connect();
       try{
            $anfrage = "SELECT artworks.ArtWorkID,artworks.ImageFileName,artworks.Title,AVG(reviews.Rating) as average_rating
                    FROM   artworks
                    JOIN   reviews ON artworks.ArtWorkID = reviews.ArtWorkID
                    GROUP BY artworks.ArtWorkID
                    HAVING   COUNT(reviews.ReviewId) >= 3
                    ORDER BY average_rating DESC LIMIT 3";

            $stmt = $this->database->prepareStatement($anfrage);

            $stmt->execute();

            $TopArtworks = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $ex) {
            exit('could not retrieve Artwork' . $ex->getMessage());
        } finally {
            $this->database->close();
        }
        

        return $TopArtworks;
    }

}
