<?php

//* hier wurde die globale Variable sowie Methode definiert.

require_once("artistRepository.php");
require("../Datenbank/datenbank.php");

function checkKunstwerkImage($verzeichnis)
{
  return file_exists($verzeichnis) ? $verzeichnis : "../assets/images/keinKunstwerk.png";
}


class Artwork
{
   //**Attribute */
   private $artWorksID;
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
   private $artistId;

   private $gallery;
   private $genres;
   private $subjects;

   private $reviews;

   private $genrei;

   private $subjecti;

   private $reviewi;

   private $datenbank;

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
      $this->datenbank = new Datenbank();
      $this->artist = Artist::getDefaultArtist();
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
            <a href="../Pages/singleArtwork.php?artworkID=' . $this->getArtWorksID() . '" role="button" type="button" class="btn btn-sm button_user_erweitern">mehr Infos</a>
          </div>';

    // Kartencontainer schließen
    echo '</div>';
    echo '</div>';
    echo '</div>';
}


public function outputSingleArtwork()
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
          width: 400px; 
          height: 500px; 
          margin-Bottom: 70px;
      }
      p {
         color: #666;
         margin-Top: 100px;
         font-family: "Arial ";
      }
      .card-title{
         color: #923f0e;
         font-family: "";
         margin-TOP: 70px;
         margin-bottom: 1px;
     }
      table {
         color: #666;
         margin-TOP: 50px;
         font-family: "Arial ";
         background-color: #fef3c7;
          border: 5px solid #ddd; 
           border-radius: 10px; 
           overflow: hidden;
      }
   </style>
';
// Ausgabe des CSS
echo $css;
   // Die code Grundlagen
   $arti = new ArtistRepository($this->datenbank);
   $this->artist = $arti->getArtist($this->getArtistId());
   

   // Beschreibung des Bildes
   echo '<h1 class="card-title">' . $this->getArtworkTitle() . '</h1>';
   echo '<h6>By' . " " . '<a class="textColor_gold" href=../Pages/singleArtist.php?artistID='  . $this->getArtistId() . '>' . $this->artist->getArtistFirstName() . " " . $this->artist->getArtistLastName() . '</a></h6>';
   echo '<p>Beschreibung des Bildes hier...</p>';

   // Öffnungs-Tags der Tabelle und der ersten Zeile
   echo '<div class="row">';
   echo '<div class="col-md-8">'; // Spalte für die Tabelle

   // Tabelle für die Details des Kunstwerks
   echo '<table class="table">';
   echo '<tr><th>Titel</th><td>' . $this->getArtworkTitle() . '</td></tr>';
   // Weitere Details hier einfügen, falls benötigt
   
   // Fünf zusätzliche Zeilen hinzufügen
   for ($i = 1; $i <= 6; $i++) {
       echo '<tr><th>Zusätzliche Information ' . $i . '</th><td>Wert ' . $i . '</td></tr>';
   }
   echo '</table>';

   echo '</div>'; // Ende der Spalte für die Tabelle
   echo '<div class="col-md-4">'; // Spalte für das Bild

   // Bild des Kunstwerks
   echo '<div class="card">';
   $image = strlen($this->getImageFileName()) == 5 ? "../assets/images/Art_Images v3/images/works/medium/0" . $this->getImageFileName() . ".jpg" : "../assets/images/Art_Images v3/images/works/medium/" . $this->getImageFileName() . ".jpg";
   $checkedImage = checkKunstwerkImage($image);
   echo '<a class="btn btn-link" data-bs-toggle="modal" data-bs-target="#exampleModal"><img src="' . $checkedImage . '" class="card-img-top" alt="' . $this->getArtworkTitle() . '"></a>';
   echo '</div>';

   echo '</div>'; 
   echo '</div>'; 

}
}