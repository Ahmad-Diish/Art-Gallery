<?php
require_once("../Datenbank/Artwork.php");

class Genre
{
    private int $genreID;
    private string $genreName;
    private string $era;
    private string $description;
    private string $link;

    public function __construct(int $genreID, string $genreName, string $era, string $description, string $link)
    {
        $this->genreID = $genreID;
        $this->genreName = $genreName;
        $this->era = $era;
        $this->description = $description;
        $this->link = $link;
    }

    public function getGenreID(): int
    {
        return $this->genreID;
    }

    public function getGenreName(): string
    {
        return $this->genreName;
    }

    public function getEra(): string
    {
        return $this->era;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public static function fromState(array $genre): Genre
    {
        $genreID = $genre["GenreID"] ?? null;
        $genreName = $genre["GenreName"] ?? null;
        $era = $genre["Era"] ?? null;
        $description = $genre["Description"] ?? null;
        $link = $genre["Link"] ?? null;
        return new self($genreID, $genreName, $era, $description, $link);
    }

    public static function getDefaultGenre(): Genre
    {
        return new self(-1, -1, "", "", "");
    }

    public function outputGenres(): void
    {
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


        echo '<div class="col-md-3 col-lg-2 mb-4">';
        echo '<div class="card">';

        $image = "../assets/images/Art_Images v3/images/genres/square-medium/" . $this->getGenreID() . ".jpg";
        $checkedImage = checkKunstwerkImage($image);
        $checkedImage = "'" . $checkedImage . "'";
        echo '<a href="../Pages/SingleGenre.php?genreID=' . $this->getGenreID() . '">';
        echo '<img src=' . $checkedImage . ' class="card-img" alt=' . $this->getGenreName() . '>';
        echo '</a>';

        echo '<a href="../Pages/SingleGenre.php?genreID=' . $this->getGenreID() . '" class="card-title">' . $this->getGenreName() . '</a>';

        echo '</div>';
        echo '</div>';
    }
    public function outputSingleGenre()
    {
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

                    background-color: #f5f5dc; /* Light brown background */
                    padding: 30px ;
                    text-align: center;
                    color: #923f0e; /* Darker brown font color */
                    border-radius: 30px;
                    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
                    margin-bottom: 100px;
                    margin-top: 50px;
                    position: relative;
                    overflow: hidden; /* Hide overflow for smoother appearance */
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

        echo '<body style="background-color: #fffbeb;">';
        //  echo '<div class="container mt-5">';
        echo '<h3 class="titleGenre">' . $this->getGenreName() . '</h3>';
        echo '<p class="genre-info">' . $this->getDescription() . '</p>';
        echo '<div class="row">';
        echo '<div class="col-md-4">';

        // Formular für weitere Aktionen (falls benötigt)
        echo '<form action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '" method="GET">';
        echo '<input type="hidden" name="genreID" value="' . $this->getGenreID() . '">';
        echo '</form>';

        echo '</div>';
        echo '</div>';
    }
}
