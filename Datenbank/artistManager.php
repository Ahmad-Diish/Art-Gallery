<?php

require_once("../Datenbank/artistClass.php");

class ArtistManager
{

    private $datenbank; // Datenbankverbindung
    private $artist; // Künstlerobjekt

    public function __construct($datenbank)
    {
        $this->datenbank = $datenbank;
        $this->artist = Artist::getDefaultArtist(); // Standardkünstler setzen
    }

   // Funktion, um die Top-Künstler für die Homepage abzurufen
    private function getTopArtists() // Homepage
    {
        $this->datenbank->connect();

        $sql = "SELECT artists.ArtistID, artists.FirstName, artists.LastName, COUNT(reviews.ReviewId) AS review_count 
             FROM artists 
             JOIN artworks ON artists.ArtistID = artworks.ArtistID 
             JOIN reviews ON artworks.ArtWorkID = reviews.ArtWorkId 
             GROUP BY artists.ArtistID 
             ORDER BY review_count DESC LIMIT 3";

        try {
            $stmt = $this->datenbank->prepareStatement($sql);
            $stmt->execute();
            $TopArtists = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $ex) {
            // Log or handle the specific PDO exception here
            exit('Could not retrieve artists: ' . $ex->getMessage());
        } finally {
            $this->datenbank->close();
        }

        return $TopArtists;
    }

     // Funktion, um die Top-Künstler anzuzeigen
    public function displayTopArtists()  // Homepage
    {

        $topArtists = $this->getTopArtists();

        foreach ($topArtists as $topArtist) {

            $this->artist = Artist::fromState($topArtist);

            $this->artist->outputArtist();
        }
    }
    

    
    // funktion um Künstler nach aufsteigend oder absteigend zu sotieren. Wird in [function displayAllArtist] genutzt
    private function sortiereKuenstler($sortierreihenfolge) 
    {
        $this->datenbank->connect();

        try {
            //aufsteigend A---Z
            if ($sortierreihenfolge == 'aufsteigend') {
                //SELECT column_name(s) FROM table_name ORDER BY column_name(s) ASC|DESC
                $anfrage = "SELECT * FROM Artists ORDER BY LastName ASC";
            }
            // absteigend Z---A 
            elseif ($sortierreihenfolge == 'absteigend') {
                $anfrage = "SELECT * FROM Artists ORDER BY LastName DESC";
            }

            $stmt = $this->datenbank->prepareStatement($anfrage);

            $stmt->execute();

            $artists = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $ex) {
            exit('could not retrieve Artist' . $ex->getMessage());
        } finally {
            $this->datenbank->close();
        }

        return $artists;
    }

    // Funktion die all künstler mit Reihfolge anzeigt
    public function displayAllArtist($sortierreihenfolge)
    {


        $artists = $this->sortiereKuenstler($sortierreihenfolge);

        foreach ($artists as $artist) {
            

            $this->artist = Artist::fromState($artist);

            $this->artist->outputArtist();
        }
    }


    // Funktion die aus der Datenbank der artist anhand seiner ID ausgebt wird in [function getArtist] genutzt.
    private function getArtistByID($artistId)
    {
        $this->datenbank->connect();
        try {
            $anfrage = "SELECT * FROM artists WHERE ArtistID = :artistId";

            $stmt = $this->datenbank->prepareStatement($anfrage);

            $stmt->bindParam(':artistId', $artistId);

            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $ex) {

            exit('Could not retrieve artist: ' . $ex->getMessage());
        } finally {
            $this->datenbank->close();
        }

        return $result;
    }

    //Die Funktionalität zur Abfrage eines Künstlers aus der Datenbank zu kapseln und das Ergebnis als Artist-Objekt zurückzugeben. 
    public function getArtist($id)
    {
        $result = $this->getArtistByID($id); // Retrieve artist data from database

        // Check if the result is an array (artist data) or null (no artist found)
        if (is_array($result)) {
            return Artist::fromState($result); // Convert array to Artist object
        } else {
            throw new Exception('Artist with ID ' . $id . ' not found'); // Handle no artist scenario
        }
    }



    // Funktion die eine kunstwerk anhand eine  Artist-ID zurückgibt. Wird in [function getArtworks] verwendet
    private function getArtworksByArtistID($artistId)
    {
        $this->datenbank->connect();
        try {
            $anfrage = "SELECT * FROM artworks WHERE ArtistID = :artistId";

            $stmt = $this->datenbank->prepareStatement($anfrage);

            $stmt->bindParam(':artistId', $artistId);

            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $ex) {

            exit('could not retrieve artist ' . $ex->getMessage());
        } finally {
            $this->datenbank->close();
        }

        return $result;
    }

    //Funktion hat den Zweck, alle Kunstwerke eines Künstlers anhand seiner ID abzurufen und zurückzugeben.
    public function getArtworks($id)
    {
        $results = $this->getArtworksByArtistID($id);

        if ($results === null) {
            throw new Exception('the ArtworkId is not available');
        }
        $tempArtist = $this->artist;


        foreach ($results as $result) {

            $tempArtist->setArtworksForArtist($result);
        }
        $tempArtworks = $tempArtist->getArtworksForArtist();

        return $tempArtworks;
    }

}       

?>
