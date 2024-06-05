<?php
// Einbindung der Datei, die die Klasse Artwork enthält
require_once("../Datenbank/artworkClass.php");

// Definition der Klasse Genre
class Genre
{
    // Private Variablen zur Speicherung der Eigenschaften eines Genres
    private int $genreID;
    private string $genreName;
    private string $era;
    private string $description;
    private string $link;

    // Konstruktor zur Initialisierung eines Genre-Objekts mit den angegebenen Werten
    public function __construct(int $genreID, string $genreName, string $era, string $description, string $link)
    {
        $this->genreID = $genreID;
        $this->genreName = $genreName;
        $this->era = $era;
        $this->description = $description;
        $this->link = $link;
    }

    // Getter-Methode für genreID
    public function getGenreID(): int
    {
        return $this->genreID;
    }

    // Getter-Methode für genreName
    public function getGenreName(): string
    {
        return $this->genreName;
    }

    // Getter-Methode für era
    public function getEra(): string
    {
        return $this->era;
    }

    // Getter-Methode für description
    public function getDescription(): string
    {
        return $this->description;
    }

    // Statische Methode zur Erstellung eines Genre-Objekts aus einem Array
    public static function fromState(array $genre): Genre
    {
        $genreID = $genre["GenreID"] ?? null;
        $genreName = $genre["GenreName"] ?? null;
        $era = $genre["Era"] ?? null;
        $description = $genre["Description"] ?? null;
        $link = $genre["Link"] ?? null;
        return new self($genreID, $genreName, $era, $description, $link);
    }

    // Statische Methode zur Erstellung eines Standard-Genre-Objekts
    public static function getDefaultGenre(): Genre
    {
        return new self(-1, -1, "", "", "");
    }

    // Methode zur Ausgabe der Genre-Informationen als HTML-Karte
    public function outputGenres(): void
    {
        // CSS für die Darstellung der Karte
        $css = '
        <style>
         .card {
                border: 1px solid #ddd;
                border-radius: 10px;
                overflow: hidden;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                transition: transform 0.3s, box-shadow 0.3s;
                background-color: #fef3c7;
                cursor: pointer;
                position: relative;
            }
            
            .card:hover {
                transform: translateY(-5px);
                box-shadow: 0 12px 18px rgba(0, 0, 0, 0.2);
            }
        
            .card:active {
                transform: translateY(-3px);
                box-shadow: 0 8px 10px rgba(0, 0, 0, 0.1);
            }
        
            .card-img {
                width: 100%;
                height: auto;
                border-bottom: 1px solid #ddd;
            }
        
            .card-title {
                text-align: center;
                padding: 10px;
                color: #923f0e;
                font-size: 16px;
                font-weight: bold;
                text-decoration: none;
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                height: 3.6em; 
                overflow: hidden;
            }
        
            .card:hover .card-title {
               
                text-decoration: underline;
            }
        </style>
    ';

        // Ausgabe des CSS
        echo $css;

        // HTML-Struktur für die Karte
        echo '<div class="col-md-3 col-lg-2 mb-4">';
        echo '<div class="card">';

        // Pfad zum Bild des Genres
        $image = "../assets/images/Art_Images v3/images/genres/square-medium/" . $this->getGenreID() . ".jpg";
        // Überprüfung, ob das Bild vorhanden ist
        $checkedImage = checkKunstwerkImage($image);
        $checkedImage = "'" . $checkedImage . "'";
        // Bild und Link zur Detailseite des Genres
        echo '<a href="../Pages/SingleGenre.php?genreID=' . $this->getGenreID() . '">';
        echo '<img src=' . $checkedImage . ' class="card-img" alt=' . $this->getGenreName() . '>';
        echo '</a>';

        // Titel des Genres mit Link zur Detailseite
        echo '<a href="../Pages/singleGenre.php?genreID=' . $this->getGenreID() . '" class="card-title">' . $this->getGenreName() . '</a>';

        // Abschluss der HTML-Struktur
        echo '</div>';
        echo '</div>';
    }

    // Methode zur Ausgabe der Informationen eines einzelnen Genres
    public function outputSingleGenre()
    {
        // CSS für die Darstellung der Genre-Informationen
        $css =
        '
            <style>
               .titleGenre {
                text-align: center;
                color: #923f0e;
                font-family: "Goudy Stout";
                margin-TOP: 70px;
                margin-bottom: 50px;
                    }
                .genre-info {
                    background-color: #f5f5dc; /* Hellbrauner Hintergrund */
                    padding: 30px ;
                    text-align: center;
                    color: #923f0e; /* Dunkelbraune Schriftfarbe */
                    border-radius: 30px;
                    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
                    margin-bottom: 100px;
                    margin-top: 50px;
                    position: relative;
                    overflow: hidden; /* Verbergen des Überlaufs für eine glattere Darstellung */
                    }
                    .genre-info:before {
                    content: "";
                    position: absolute;
                    top: 0;
                    left: 0;        
                    border-radius: 30px;
                    }
            </style>
        ';

        // Ausgabe des CSS
        echo $css;

        // HTML-Struktur für die Genre-Informationen
        echo '<body style="background-color: #fffbeb;">';
        echo '<h3 class="titleGenre">' . $this->getGenreName() . '</h3>';
        echo '<p class="genre-info">' . $this->getDescription() . '</p>';
        echo '<div class="row">';
        echo '<div class="col-md-4">';

        // Formular für weitere Aktionen (falls benötigt)
        echo '<form action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '" method="GET">';
        echo '<input type="hidden" name="genreID" value="' . $this->getGenreID() . '">';
        echo '</form>';

        // Abschluss der HTML-Struktur
        echo '</div>';
        echo '</div>';
    }
}
?>
