<?php

//* hier wurde die globale Variable sowie Methode definiert.


require("../Datenbank/datenbank.php");

function checkKunstwerkImage($verzeichnis)
{
  return file_exists($verzeichnis) ? $verzeichnis : "../assets/images/keinKunstwerkklein.jpg";
}


class Artwork
{
   //**Attribute */
   private $artWorksID;
   private $artistId;
   private $imageFileName;
   private $title;
   private $description;
   private $excerpt;
   private $artWorkType;
   private $yearOfWork;
   private $width;
   private $height;
   private $medium;
   private $originalHome;
   private $artworkGalleryID;
   private $artworkLink;

   private $googleLink;

   private $artist;

   private $gallery;
   private $genres;
   private $subjects;

   private $reviews;

   private $genrei;

   private $subjecti;

   private $reviewi;

   private $Datenbank;

   //Konstruktor der Klasse Artwork.
    
   
   public function __construct($artWorksID, $artistId, $imageFileName, $title, $description, $excerpt, $artWorkType, $yearOfWork, $width, $height, $medium, $originalHome, $artworkGalleryID, $artworkLink, $googleLink)
   {

      $this->artWorksID = $artWorksID;
      $this->artistId = $artistId;
      $this->imageFileName = $imageFileName;
      $this->title = $title;
      $this->description = $description;
      $this->excerpt = $excerpt;
      $this->artWorkType = $artWorkType;
      $this->yearOfWork = $yearOfWork;
      $this->width = $width;
      $this->height = $height;
      $this->medium = $medium;
      $this->originalHome = $originalHome;
      $this->artworkGalleryID = $artworkGalleryID;
      $this->artworkLink = $artworkLink;
      $this->googleLink = $googleLink;
      $this->Datenbank = new Datenbank();
   }

   //** Getter und Setter */
   public function getArtWorksID()
   {
      return $this->artWorksID;
   }

   public function getArtistId()
   {
      return $this->artistId;
   }

   public function getImageFileName()
   {
      return $this->imageFileName;
   }

   public function getArtworkTitle()
   {
      return $this->title;
   }

   public function getArtworkDescription()
   {
      return $this->description;
   }


   public function getArtworkYearOfWork()
   {
      return $this->yearOfWork;
   }
   public function setArtworkWidth($width)
   {
      $this->width = $width;
   }
   public function getArtworkWidth()
   {
      return $this->width;
   }

   public function getArtworkHeight()
   {
      return $this->height;
   }

   public function getArtworkMedium()
   {
      return $this->medium;
   }

   
   public static function getDefaultArtwork(): Artwork
   {
     //$artWorksID, $artistId, $imageFileName, $title, $description, $excerpt, $artWorkType, $yearOfWork, $width, $height, $medium, $originalHome, $artworkGalleryID, $artworkLink, $googleLink
      return new self(-1, -1, "", "", "", "", "", 0, 0, 0, 0, "", 0, "", "");
   }

   // Hier folgen die Getter und Setter Methoden...

   public static function fromState(array $artwork): Artwork
   {
      $artWorksID = $artwork["ArtWorkID"] ?? null;
      $artistId = $artwork["ArtistID"] ?? null;
      $imageFileName = $artwork["ImageFileName"] ?? null;
      $title = $artwork["Title"] ?? null;
      $description = $artwork["Description"] ?? null;
      $excerpt = $artwork["Excerpt"] ?? null;
      $artWorkType = $artwork["ArtWorkType"] ?? null;
      $yearOfWork = $artwork["YearOfWork"] ?? null;
      $width = $artwork["Width"] ?? null;
      $height = $artwork["Height"] ?? null;
      $medium = $artwork["Medium"] ?? null;
      $originalHome = $artwork["OriginalHome"] ?? null;
      $artworkGalleryID = $artwork["GalleryID"] ?? null;
      $artworkLink = $artwork["ArtWorkLink"] ?? null;
      $googleLink = $artwork["GoogleLink"] ?? null;
      return new self($artWorksID, $artistId, $imageFileName, $title, $description, $excerpt, $artWorkType, $yearOfWork, $width, $height, $medium, $originalHome, $artworkGalleryID, $artworkLink, $googleLink);
   }

// Methode zur Ausgabe von Kunstwerkkarten
public function outputArtworks()
{
    // CSS für den Rahmen um das Bild und den Mehr Infos Button
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
               width: 300px; /* Breite festlegen */
               height: 400px; /* Höhe festlegen */
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

    // Ausgabe des CSS
    echo $css;

    // Öffnen des Kartencontainers
    echo '<div class="col-md-3 col-lg-3 mb-4">';
    echo '<div class="card">';

    // Bildpfad generieren
    $image = $this->getImageFileName();
    $imagePath = strlen($image) == 5 ? "../assets/images/Art_Images v3/images/works/square-medium/0$image.jpg" : "../assets/images/Art_Images v3/images/works/square-medium/$image.jpg";
    $checkedImage = checkKunstwerkImage($imagePath);

    // Bild ausgeben in schönem Rahmen
    echo '<div class="card-img-container">';
    echo '<img src="' . $checkedImage . '" class="card-img-top" alt="' . $this->getArtworkTitle() . '">';
    echo '</div>';

    // Karteninhalt
    echo '<div class="card-body">';
    echo '<h6 class="card-title">' . $this->getArtworkTitle() . '</h6>';

    // Mehr Infos Button mit neuem Design
    echo '<div class="more-info-button">
            <a href="../Pages/SingleArtwork.php?artworkID=' . $this->getArtWorksID() . '" role="button" type="button" class="btn btn-sm button_user_erweitern">mehr Infos</a>
          </div>';

    // Kartencontainer schließen
    echo '</div>';
    echo '</div>';
    echo '</div>';
}

public function outputSingleArtwork()
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