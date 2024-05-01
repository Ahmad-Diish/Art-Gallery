
<?php
require("../Datenbank/datenbank.php");


function checkKunstwerkImage($verzeichnis)
{
  return file_exists($verzeichnis) ? $verzeichnis : "../assets/images/keinKunstwerkklein.jpg";
}
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
        
        $this->Datenbank = new Datenbank();
    }
  
    public function getGenreID():int
    {
        return $this->genreID;
    }

    
    public function getGenreName():string
    {
        return $this->genreName;
    }


 
    public static function fromState(Array $genre):Genre
    {
        $genreID = $genre["GenreID"] ?? null;
        $genreName = $genre["GenreName"] ?? null;
        $era = $genre["Era"] ?? null;
        $description = $genre["Description"] ?? null;
        $link = $genre["Link"] ?? null;
        return new self($genreID,$genreName,$era,$description,$link);
    }
  
    public static function getDefaultGenre(): Genre
    {
        return new self(-1, -1, "", "", "");
    }
   
   
    public function outputGenres():void
    {
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
               width: 100%; /* Breite festlegen */
               height: 100%; /* Höhe festlegen */
           }
           .titleColor{
            text-align: center;
            color: black;

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
        echo '<img src=' . $checkedImage . '"class="card-img-top" alt=' . $this->getGenreName() . '>';
      
        echo '<div class="card-body">';
        echo '<a href="../Pages/SingleGenre.php" class="titleColor"> <h6 class="card-title">' . $this->getGenreName() . '</h6></a>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
}
