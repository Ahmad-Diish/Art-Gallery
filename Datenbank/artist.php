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

    private $isFavorite;
    private $datenbank;

    private $imagePath;

    public function __construct($artistId, $firstName, $lastName, $nationality, $yearOfBirth, $yearOfDeath, $details, $artistLink, $isFavorite)
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
    
    public function getIsFavorite()
    {
        return $this->isFavorite;
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
        $isFavorite = $artist["IsFavorite"] ?? null;
        return new self($id, $firstName, $lastName, $nationality, $yearOfBirth, $yearOfDeath, $details, $artistLink, $isFavorite);
    }


    public static function getDefaultArtist(): Artist
    {
        return new self(-1, "", "", "", 0, 0, "", "",false);
    }

    public function outputArtist()
    {
        // CSS for styling the card and the more info button
        $css = '
        <style>
            .card-img-container {
                border: 5px solid #ddd; 
                border-radius: 10px; 
                overflow: hidden;
            }
            .card {
                border-radius: 10px;
                background-color: #fef3c7;
                width: 300px;
                height: 400px;
            }
            .more-info-button {
                text-align: center; 
                margin-top: 15px; 
            }
            .button_user_erweitern {
                background-color: #d5a27c; 
                color: black; 
                border: none; 
                padding: 5px 10px; 
                text-align: center; 
                text-decoration: none; 
                display: inline-block; 
                font-size: 16px; 
                border-radius: 4px; 
                cursor: pointer; 
                transition-duration: 0.4s; 
            }
            .button_user_erweitern:hover {
                background-color: #fef3c7; 
                color: black; 
            }
        </style>
    ';

        // Output the CSS
        echo $css;

        // Open the card container
        echo '<div class="col-md-3 col-lg-3 mb-4">';
        echo '<div class="card">';

        // Image path
        $image = "../assets/images/Art_Images v3/images/artists/square-medium/" . $this->getArtistID() . ".jpg";
        $checkedImage = checkArtistImage($image);

        // Output the image within a container
        echo '<div class="card-img-container">';
        echo '<img src="' . $checkedImage . '" class="card-img-top" alt="' . $this->getArtistFirstName() . ' ' . $this->getArtistLastName() . '">';
        echo '</div>';

        // Card body
        echo '<div class="card-body">';
        echo '<h6 class="card-title">' . htmlspecialchars($this->getArtistFirstName() . " " . $this->getArtistLastName()) .  '</h6>';

        // More info button
        echo '<div class="more-info-button">
            <a href="../Pages/singleArtist.php?artistID=' . $this->getArtistID() . '" role="button" type="button" class="btn btn-sm button_user_erweitern">mehr Infos</a>
          </div>';

        // Close card container
        echo '</div>';
        echo '</div>';
        echo '</div>';
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
        $css = '
        <style>
          
        .button_style {
        background-color: #923f0e;
        color: #fff;
        border: none;
        padding: 8px 16px;
        cursor: pointer;
        margin-bottom: 4px;
        margin-top: 1px;
          margin-left: 4rem;
    }

    .button_style:hover {
        background-color: #D1BB9E;
    }

        p {
            font-size: 15px;
            line-height: 1.6;
            margin-bottom: 20px;
            text-align: justify;
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 5px;
            background-color: #f9f9f9;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
           }
            
            p::before {
            content: "";
            display: block;
            width: 50px;
            height: 3px;
            background-color: #923f0e;
            margin-bottom: 10px;
            }

    .artist-card {
        
        border-radius: 10px;
        box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.1);
        background: #D1BB9E;
        padding: 20px;
        margin-bottom: 1rem;
        margin-top: 1rem;
        margin-right: 1rem;

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

   .table {
    margin-left: 4rem;
  width: 80%;
  border-collapse: collapse;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
  border-radius: 10px;
  overflow: hidden;
}

/* Table header styles */
.table th {
  background-color: #D1BB9E;
  color: #322C2B;
  text-align: left;
  padding: 12px 15px;
  font-size: 17px;
}

/* Table body row styles */
.table tr {
  border-bottom: 1px solid black;
}

.table tr:last-child {
  border-bottom: 2px splid #923f0e;
}

/* Table body cell styles */
.table td {
  padding: 12px 15px;
  color: #923f0e;
  font-size: 18px;
}


.textColor_gold {
  color: blue; /* Gold color for links */
  text-decoration: none;
}

.textColor_gold:hover {
  text-decoration: underline;
}


@media (max-width: 768px) {
  .table th,
  .table td {
    display: block;
    width: 100%;
  }

  .table th {
    text-align: right;
    padding-right: 20px;
    position: relative;
  }

  .table td {
    text-align: left;
    padding-top: 0;
    padding-bottom: 0;
  }

 
}
h2 {
    margin-bottom: 2rem;
}
            </style>
        ';
     
        echo $css;
        echo '<body style="background-color: #fffbeb;">';
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
        // echo '<form action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '" method="GET">';
        
        $isFavorite = false;
        echo '<input type="hidden" name="artistId" value="' . $this->getArtistID() . '">';
        echo '<input type="hidden" name="artistId" value="' . $this->getArtistID() . '">';
        echo '<button type="button" class="' . ($isFavorite ? 'btn btn-secondary button_style_favourite' : 'btn btn-secondary button_style') . '" data-placement="bottom" title="Favoritenliste">' . ($isFavorite ? 'Von Favoriten entfernen' : 'Zu Favoriten hinzufügen') . '</button>';
        //echo '<button type="submit" name="action" value="' . ($isFavorite ? 'removeFavoriteArtist' : 'addFavoriteArtist') . '" class="' . ($isFavorite ? 'btn btn-secondary button_style_favourite' : 'btn btn-secondary button_style') . '" data-placement="bottom" title="Favoritenliste">' . ($isFavorite ? 'Von Favoriten entfernen' : 'Zu Favoriten hinzufügen') . '</button>';
        //echo '<button type="submit" name="action" value="' . ($isFavorite ? 'removeFavoriteArtist' : 'addFavoriteArtist') . '" class="' . ($isFavorite ? 'btn btn-secondary button_style_favourite' : 'btn btn-secondary button_style') . '" data-placement="bottom" title="Favoritenliste">' . ($isFavorite ? 'Von Favoriten entfernen' : 'Zu Favoriten hinzufügen') . '</button>';
 
        
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
        echo '</div>';
        echo '</table>';


        echo '</div>';
        //echo '</div>';
        echo '<h2 style="padding-left:10px;" class="mt-4">Kunstwerke von  ' . $this->getArtistFirstName() . " " . $this->getArtistLastName() . '</h2>';
        //echo '</div>'; // Close the current container
        echo '<div class="row">';
        echo '</div>'; // Close the current container
        echo '<div class="row">'; // Open a new row
        echo '</div>';
    
        
       
    }
}

function checkArtistImage($verzeichnis)
{
    if (file_exists($verzeichnis)) {
        return $verzeichnis;
    } else {
        return "../assets/images/keinPerson.png";
    }
}
?>

<style>
    .card {
        margin-bottom: 4rem;
    }
</style>