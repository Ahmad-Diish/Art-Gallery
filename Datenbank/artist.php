<?php
require_once("artwork.php");
class Artist
{

    private $artistID;
    private $firstName;
    private $lastName;
    private $nationality;
    private $yearOfBirth;
    private $yearOfDeath;
    private $details;
    private $artistLink;

    private $artworks;

    private $datenbank;

    private $imagePath;

    public function __construct($artistId, $firstName, $lastName, $nationality, $yearOfBirth, $yearOfDeath, $details, $artistLink)
    {
        // Initialisierung der Eigenschaften...
        $this->artistID = $artistId;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->nationality = $nationality;
        $this->yearOfBirth = $yearOfBirth;
        $this->yearOfDeath = $yearOfDeath;
        $this->details = $details;
        $this->artistLink = $artistLink;
    }
    //**Setter und Getter */
    public function getArtistID()
    {
        return $this->artistID;
    }


    public function getArtistFirstName()
    {
        return $this->firstName;
    }


    public function getArtistLastName()
    {
        return $this->lastName;
    }

    public function getArtistNationality()
    {
        return $this->nationality;
    }

    public function getArtistDateBirth()
    {
        return $this->yearOfBirth;
    }

    public function getArtistDateDeath()
    {
        return $this->yearOfDeath;
    }

    public function getDetailsArtist()
    {
        return $this->details;
    }

    public function getArtistLink()
    {
        return $this->artistLink;
    }


    public static function fromState(array $artist): Artist
    {
        $id = $artist["ArtistID"];
        $firstName = $artist["FirstName"] ?? null;
        $lastName = $artist["LastName"] ?? null;
        $nationality = $artist["Nationality"] ?? null;
        $yearOfBirth = $artist["YearOfBirth"] ?? null;
        $yearOfDeath = $artist["YearOfDeath"] ?? null;
        $details = $artist["Details"] ?? null;
        $artistLink = $artist["ArtistLink"] ?? null;
        return new self($id, $firstName, $lastName, $nationality, $yearOfBirth, $yearOfDeath, $details, $artistLink);
    }


    public static function getDefaultArtist(): Artist
    {
        return new self(-1, "", "", "", 0, 0, "", "");
    }

    public function outputArtist() // Homepage
    {

        echo '<div class="col-md-3 col-lg-3 mb-4">';
        echo '<div class="card">';
        $image = "../assets/images/Art_Images v3/images/artists/square-medium/" . $this->getArtistID() . ".jpg";
        $checkedImage = checkArtistImage($image);
        $checkedImage = "'" . $checkedImage . "'";
        echo '<img src=' . $checkedImage . ' class="card-img-top" alt="' . htmlspecialchars($this->getArtistFirstName() . ' ' . $this->getArtistLastName()) . '">';

        echo '<div class="card-body" style="background-color: #fffbeb; display: flex; align-items: center; justify-content: space-between; flex-direction: column; height: 90px;">';
        echo '<div style="display: flex; align-items: center; justify-content: space-between;">';
        echo '<a class="artist-name" style="color: #d5a27c; text-decoration: none; height: 100%; display: flex; align-items: center;" href="../pages/singleArtist.php?artistID=' . $this->getArtistID() . '">';
        echo '<h5 class="card-title" style="margin: 0;">' . htmlspecialchars($this->getArtistFirstName() . " " . $this->getArtistLastName()) . '</h5></a>'; // Added display: flex; align-items: center;
        echo '<a href="../php/addToFavorites.php?artistID=' . $this->getArtistID() . '"<button class="btn heart-btn"><i class="bi bi-heart" style="width: 6px; height: 36px; margin-left: 10px; color: red; filter: drop-shadow(1px 1px 2px grey);" ></i></button></a>';
        echo '</div>'; // Close flex container

        echo '</div>'; // Close card-body
        echo '</div>'; // Close card
        echo '</div>'; // Close column

    }

    

    public function setArtworksForArtist($artworkAlsArray)
    {
        $artwork = Artwork::getDefaultArtwork();
        $artwork = Artwork::fromState($artworkAlsArray);
        $this->artworks[] = $artwork;
    }
    public function getArtworksForArtist()
    {
        return $this->artworks;
    }

    public function outputSingleArtist()
    {
        echo '<div class="container mt-5" style="max-width: 1100px; margin: 0 auto; padding: 60px; background-color: #E5DDC5; border: 1px solid #ddd; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); margin-bottom: 40px;">';
        echo '<h3>' . $this->getArtistFirstName() . " " . $this->getArtistLastName() . '</h3>';
        echo '<div class="row">';
        echo '<div class="col-md-4">';
        echo '<div class="artist-card">';
        $image = "../assets/images/Art_Images v3/images/artists/medium/" . $this->getArtistID() . ".jpg";
        $checkedImage = checkArtistImage($image);
        echo '<img src="' . $checkedImage . '" class="card-img-top" alt="' . $this->getArtistFirstName() . '">';
        echo '</div>';
        echo '</div>';
        echo '<div class="col-md-8">';

        echo '<p>' . $this->getDetailsArtist() . '</p>';
        echo '<form action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '" method="GET">';
        echo '<input type="hidden" name="artistId" value="' . $this->getArtistID() . '">';
        //echo '<button type="submit" name="action" value="' . ($isFavorite ? 'removeFavoriteArtist' : 'addFavoriteArtist') . '" class="' . ($isFavorite ? 'btn btn-secondary button_style_favourite' : 'btn btn-secondary button_style') . '" data-placement="bottom" title="Favoritenliste">' . ($isFavorite ? 'Von Favoriten entfernen' : 'Zu Favoriten hinzufügen') . '</button>';
        echo '</form>';
        echo '<table class="table mt-4">';
        echo '<tbody>';
        echo '<tr>';
        echo '<th scope="row">Name</th>';
        echo '<td>' . $this->getArtistFirstName() . " " . $this->getArtistLastName() . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<th scope="row">Datum</th>';
        echo '<td>' . $this->getArtistDateBirth() . '-' . $this->getArtistDateDeath() . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<th scope="row">Nationalität des Künstlers</th>';
        echo '<td>' . $this->getArtistNationality() . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<th scope="row">Mehr Infos</th>';
        echo '<td><a class="textColor_gold" href="' . $this->getArtistLink() . '">' . $this->getArtistLink() . '</a></td>';
        echo '</tr>';
        echo '</tbody>';
        echo '</table>';
        echo '</div>';
        echo '</div>';
        echo '<h2 style="padding-left:10px;" class="mt-4">Kunstwerke von  ' . $this->getArtistFirstName() . " " . $this->getArtistLastName() . '</h2>';
        echo '<div class="row">';
    }
}

function checkArtistImage($verzeichnis)
{
    return file_exists($verzeichnis) ? $verzeichnis : "../assets/images/KeinPersonklein.png";
}

?>
<style>
    .form-select {
        width: 200px;
        margin-right: 10px;
    }

    .button_style {
        background-color: #343a40;
        color: #fff;
        border: none;
        padding: 8px 16px;
        cursor: pointer;
    }

    .button_style:hover {
        background-color: #1d2124;
    }

    /* Künstlerbilder */
    .artist-card {
        border-radius: 10px;
        box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.1);
        background: #D1BB9E;
        padding: 20px;

    }

    .artist-card img {
        width: 100%;
        border-radius: 10px;
        margin-bottom: 20px;
    }


    .artist-card:hover {
        box-shadow: 0px 15px 30px rgba(0, 0, 0, 0.15);
        transform: translateY(-5px);
    }
    
</style>