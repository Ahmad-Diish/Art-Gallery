<?php

//* hier wurde die globale Variable sowie Methode definiert.
require("../Datenbank/datenbank.php");
require_once("artistManager.php");
require_once("subjectManager.php");
require_once("genreManager.php");
require_once("galleryClass.php");

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
        $this->gallery = Gallery::getDefaultGallery();
        $this->subjecti = new SubjectManager($this->datenbank);
        $this->genrei = new GenreManager($this->datenbank);
        $this->genres = array();
        $this->subjects = array();
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

    public function setSubjectsForArtwork($subjectAlsArray)
    {
        $subject = Subject::getDefaultSubject();
        $subject = Subject::fromState($subjectAlsArray);
        $this->subjects[] = $subject;
    }

    public function getSubjectsForArtwork()
    {
        return $this->subjects;
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

    // Methoden für Gallery
    private function getGalleryByArtworksID($artworksID)
    {
        $this->datenbank->connect();
        try {
            $anfrage = "SELECT ga.GalleryID , ga.GalleryName , ga.GalleryNativeName , ga.GalleryCity,ga.GalleryCountry , ga.Latitude,ga.Longitude , ga.GalleryWebSite 
                     FROM galleries ga
                     JOIN Artworks a ON a.GalleryID = ga.GalleryID
                     WHERE a.ArtWorkID = :artworksID";

            $stmt = $this->datenbank->prepareStatement($anfrage);

            $stmt->bindParam(':artworksID', $artworksID);

            $stmt->execute();

            $results = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Fehler: " . $e->getMessage();
            return false;
        } finally {
            $this->datenbank->close();
        }

        return $results;
    }
    public function getGalleryForArtwork($id)
    {
        $result = $this->getGalleryByArtworksID($id);

        if ($result === null) {
            return 'the GalleryId is not available';
        }

        $tempGallery = Gallery::fromState($result);
        return $tempGallery;
    }
    public function collapse()
    {
        $css = '
    <style>
    .card_c{
        border-radius: 20px;
        background-color: #fef3c7;
        border: 10px;
        width: 170%;
        padding: 15px;
    }
    .text{
        font-family: "Arial";
        color: #666;
        
    }
    .card-body{
        padding-top: 15px;
        font-family: "Arial";
        color: #666;
    }
    </style>
    ';
        // Ausgabe des CSS
        echo $css;
        $gallery = $this->getGalleryForArtwork($this->getArtWorksID());
        $galleryInfos = '<div id="accordion">
           <div class="card_c">
               <div class="card-header" id="headingOne">
                   <h6 class="mb-auto">
                       <a role="button" class="text" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">'
            . $gallery->getGalleryNativeName() .
            '</a>
                   </h6>
               </div>
               <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
               <div class="card-body">'
            . 'Galeriename: ' . $gallery->getGalleryName() . '<br>'
            . 'Nativer Galeriename: ' . $gallery->getGalleryNativeName() . '<br>'
            . 'Stadt: ' . $gallery->getGalleryCity() . '<br>'
            . 'Land : ' . $gallery->getGalleryCountry() . '<br>'
            . 'Breite: ' . $gallery->getLatitude() . '<br>'
            . 'Längengrad: ' . $gallery->getLongitude() . '<br>'
            . "Quelle: <a class='text' href='" . $gallery->getGalleryWebSite() . "'>Webseite</a>";
        '</div>
           </div>
       </div>
   </div>';
        return $galleryInfos;
    }
    public function SubjectLinks() {
        $subjects = $this->subjecti->getSubjectByArtworksID($this->getArtWorksID());
        $subjectLinks = '';

        foreach ($subjects as $subject) {
            $subjectId = $subject['SubjectID'];
            $subjectName = htmlspecialchars($subject['SubjectName']);
            $subjectLinks .= '<a class="textColor_gold" href="../Pages/singleSubject.php?SubjectId=' . $subjectId . '">' . $subjectName . '</a><br>';
        }

        return $subjectLinks;
    }
    public function genresLinks() {
        $Genres = $this->genrei->getGenresByArtworksID($this->getArtWorksID()); // Ändern von subjecti auf genrei
        $GenreLinks = '';
    
        foreach ($Genres as $Genre) {
            $GenreId = $Genre['GenreID'];
            $genreName = htmlspecialchars($Genre['GenreName']); // Ändern von $subjectName auf $genreName
            $GenreLinks .= '<a class="textColor_gold" href="../Pages/SingleGenre.php?GenreId=' . $GenreId . '">' . $genreName . '</a><br>'; // Ändern von $subjectName auf $genreName
        }
    
        return $GenreLinks;
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
            margin-top: 70px;
            
        }
        .By {
            color: #923f0e;
            font-family: "Arial";
        }
        h5 {
            color: #923f0e;
            font-family: "Arial";
        }
        .card-P {
            color: #666;
            margin-top: 60px;
            font-family: "Arial";
            padding-top: 10px;
            border: 100px;
            width: 100%;
        }

        .card-title {
            color: #923f0e;
            font-family: "";
            margin-top: 70px;
            margin-bottom: 1px;
        }
        .custom-table {
            width: 70%; 
            height: 50%; 
            color: #666;
            margin-top: 30px;
            margin-bottom: 30px;
            font-family: "Arial";
            background-color: #fef3c7;
            border: 10px solid #ddd; 
            border-radius: 10px; 
            overflow: hidden;
        }
        .custom-table th, .custom-table td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        .custom-table td {
            background-color: #f0e2b8;
            padding-right: 200px;  
            text-align: left;
            width: 500px;
        }
        
        
        /* Bild style */
        .modal-content {
            background-color: #fef3c7;
            border-radius: 10px;
        }
        .modal-header {
            border-bottom: none;
            padding-bottom: 0;
        }
        .modal-title {
            color: #923f0e;
            font-family: "Arial";
        }
        .modal-footer {
            border-top: none;
            padding-top: 0;
        }

        
    </style>
    ';
        // Ausgabe des CSS
        echo $css;

        // Die code Grundlagen
        $arti = new ArtistManager($this->datenbank);
        $this->artist = $arti->getArtist($this->getArtistId());
 
        echo '<h1 class="card-title">' . $this->getArtworkTitle() . '</h1>';
        echo '<h5>By' . " " . '<a class="By" href=../Pages/singleArtist.php?artistID='  . $this->getArtistId() . '>' . $this->artist->getArtistFirstName() . " " . $this->artist->getArtistLastName() . '</a></h5>';

        // Beschreibung des Bildes
        echo '<div class="row">';
        echo '<div class="col-md-8">';
        $artworkDescription = $this->getArtworkDescription();
        if (!empty($artworkDescription)) {
            echo '<p class="card-P">' . $artworkDescription . '</p>';
        } else {
            echo '<p class="card-P">Zu diesem Gemälde sind derzeit keine Informationen verfügbar. Wir wünschen Ihnen eine angenehme Zeit in unserer Gemäldegalerie.</p>';
        }

        // das Hinzufügen oder Entfernen von Kunstwerken aus der Favoritenliste
        echo '<div class="buttons_Single_Artwork">';
        $istFavorite = false;
        if (isset($_SESSION['favorite_artworks']) && in_array($this->getArtWorksID(), $_SESSION['favorite_artworks'])) {
            $istFavorite = true;
        }
        echo '<form action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '" method="POST">';
        echo '<input type="hidden" name="ArtworkId" value="' . $this->getArtWorksID() . '">';
        echo '<button type="submit" style="background-color: #923f0e; color: #fff;" name="action" value="' . ($istFavorite ? 'removeFavoriteArtwork' : 'addFavoriteArtwork') . '" class="' . ($istFavorite ? 'btn btn-secondary button_style_favourite' : 'btn btn-secondary button_style') . '" data-placement="bottom" title="Favoritenliste">' . ($istFavorite ? 'Von Favoriten entfernen' : 'Zu Favoriten hinzufügen') . '</button>';
        echo '</form>';
        echo '</div>';

        // Beschreibung des Tabelle
        echo '<table class="table custom-table">';
        echo '<tr><th colspan="2">Details zum Gemälde</th></tr>';
        echo '<tr><th>Datum:</th><td>' . $this->getArtworkYearOfWork() . '</td></tr>';
        echo '<tr><th>Medium:</th><td>' . $this->getArtworkMedium() . '</td></tr>';
        echo '<tr><th>Dimensionen:</th><td>' . $this->getArtworkWidth() . 'cm x ' . $this->getArtworkHeight() . 'cm </td></tr>';
        echo '<tr><th>Home:</th><td>' . $this->collapse() . ' </td></tr>';
        echo '<tr><th>Genres:</th><td>' . $this->genresLinks() . ' </td></tr>';
        echo '<tr><th>Subjekte:</th><td>' . $this->SubjectLinks() . ' </td></tr>';
        echo'</td></tr>';
        echo '</table>';
        echo '</div>';

        // Bild des Kunstwerks
        echo '<div class="col-md-4">';
        echo '<div class="card">';
        $image = strlen($this->getImageFileName()) == 5 ? "../assets/images/Art_Images v3/images/works/medium/0" . $this->getImageFileName() . ".jpg" : "../assets/images/Art_Images v3/images/works/medium/" . $this->getImageFileName() . ".jpg";
        $checkedImage = checkKunstwerkImage($image);
        echo '<a class="btn btn-link" data-bs-toggle="modal" data-bs-target="#BildGroß"><img src="' . $checkedImage . '" class="card-img-top" alt="' . $this->getArtworkTitle() . '"></a>';
        echo '</div>';
        echo '</div>';
        echo '</div>';

        // Die größe Blid
        echo '<div class="modal fade" id="BildGroß" tabindex="-1" aria-labelledby="BildGroßLabel" aria-hidden="true">';
        echo '    <div class="modal-dialog modal-dialog-centered">';
        echo '        <div class="modal-content">';
        echo '            <div class="modal-header">';
        echo '                <h5 class="modal-title">' . $this->getArtworkTitle() . " By " . $this->artist->getArtistFirstName() . " " . $this->artist->getArtistLastName() . '</h5>';
        echo '            </div>';
        echo '            <div class="modal-body">';
        echo '                <img src="' . $checkedImage . '" class="card-img-top" alt="' . $this->getArtworkTitle() . '">';
        echo '            </div>';
        echo '            <div class="modal-footer justify-content-center">';
        echo '                <button type="button" class="btn btn-secondary button_style" style="background-color: #923f0e; color: #fff;" data-bs-dismiss="modal">Schließen</button>';
        echo '            </div>';
        echo '        </div>';
        echo '    </div>';
        echo '</div>';
    }
}
