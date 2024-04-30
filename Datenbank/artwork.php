<?php

//* hier wurde die globale Variable sowie Methode definiert.


require("../Datenbank/datenbank.php");

function checkKunstwerkImage($verzeichnis)
{
  return file_exists($verzeichnis) ? $verzeichnis : "../assets/images/keinKunstwerkklein.jpg";
}

//*Funktion für verschönern nicht mehr als 3 Worte in Title zeigen ...
function truncate($title)
{
  $words = explode(" ", $title); // Teilt den Titel in Wörter auf
  if (count($words) > 3) {
    return implode(" ", array_slice($words, 0, 4)) . "..."; // Kürzt den Titel auf die ersten drei Wörter
  } else {
    return $title; // Gibt den Originaltitel zurück, wenn er 3 oder weniger Wörter hat
  }
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
                border: 2px solid #ddd; /* Schöner Rahmen um das Bild */
                border-radius: 8px; /* Abgerundete Ecken */
                overflow: hidden; /* Um das Bild innerhalb des Rahmens zu behalten */
            }

            .more-info-button {
                text-align: center; /* Zentriert den Button */
                margin-top: 15px; /* Abstand nach oben */
            }

            .button_user_erweitern {
                background-color: #4CAF50; /* Hintergrundfarbe */
                color: white; /* Textfarbe */
                border: none; /* Kein Rahmen */
                padding: 10px 20px; /* Innenabstand */
                text-align: center; /* Zentriert den Text */
                text-decoration: none; /* Kein Unterstrich */
                display: inline-block; /* Blockelement */
                font-size: 16px; /* Schriftgröße */
                border-radius: 4px; /* Abgerundete Ecken */
                cursor: pointer; /* Zeigt an, dass es sich um einen anklickbaren Button handelt */
                transition-duration: 0.4s; /* Animationsdauer */
            }

            .button_user_erweitern:hover {
                background-color: #45a049; /* Ändert die Hintergrundfarbe beim Überfahren */
                color: white; /* Ändert die Textfarbe beim Überfahren */
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
    $truncatedTitle = truncate($this->getArtworkTitle()); // Funktion zum Kürzen des Titels aufrufen
    echo '<h6 class="card-title">' . $truncatedTitle . '</h6>';

    // Mehr Infos Button mit neuem Design
    echo '<div class="more-info-button">
            <a href="../php/displaySingleArtwork.php?artworkID=' . $this->getArtWorksID() . '" role="button" type="button" class="btn btn-sm button_user_erweitern">mehr Infos</a>
          </div>';

    // Kartencontainer schließen
    echo '</div>';
    echo '</div>';
    echo '</div>';
}


}
