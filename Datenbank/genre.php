
<?php
require("../Datenbank/datenbank.php");


function checkKunstwerkImage($verzeichnis)
{
  return file_exists($verzeichnis) ? $verzeichnis : "../assets/images/keinKunstwerk.png";
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
        
       
    }
  
    public function getGenreID():int
    {
        return $this->genreID;
    }

    
    public function getGenreName():string
    {
        return $this->genreName;
    }
    public function description():string
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
   
   
    public function outputGenres(): void
    {
        $css = '
        <style>
            .card {
                border: 1px solid #ddd;
                border-radius: 10px;
                overflow: hidden;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                transition: transform 0.3s;
                background-color: #fef3c7;
                
                cursor: pointer;
            }
            
            .card:hover {
                transform: translateY(-5px);
                box-shadow: 0 8px 12px rgba(0, 0, 0, 0.2);
            }
    
            .card-img {
                width: 100%;
                height: auto;
                border-bottom: 1px solid #ddd;
            }
    
            .card-title {
                text-align: center;
                padding: 10px;
                color: #923f0e  ;
                font-size: 16px;
                font-weight: bold;
                text-decoration: none;
            }
        </style>
        ';
    
        // CSS ausgeben
        echo $css;
    
        echo '<div class="col-md-3 col-lg-2 mb-4">';
        echo '<div class="card">';
    
        $image = "../assets/images/Art_Images v3/images/genres/square-medium/" . $this->getGenreID() . ".jpg";
        $checkedImage = checkKunstwerkImage($image);
        $checkedImage = "'" . $checkedImage . "'";
        echo '<img src=' . $checkedImage . ' class="card-img" alt=' . $this->getGenreName() . '>';
    
        echo '<a href="../Pages/singleGenre.php" class="card-title">' . $this->getGenreName() . '</a>';
    
        echo '</div>';
        echo '</div>';
    }
}    