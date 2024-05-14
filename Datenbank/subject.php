<?php

require_once("../Datenbank/Artwork.php");


class Subject
{

    public $subjectId;
    private $subjectName;

    public function __construct($subjectId, $subjectName)
    {
        $this->subjectId = $subjectId;
        $this->subjectName = $subjectName;
    }

    public function getSubjectId(): int
    {
        return $this->subjectId;
    }

    public function getSubjectName(): string
    {
        return $this->subjectName;
    }

    public static function fromState( $subject): Subject
    {
        $subjectId = $subject["SubjectId"] ?? null;
        $subjectName = $subject["SubjectName"] ?? null;

        return new self($subjectId, $subjectName);
    }


    public static function getDefaultSubject(): Subject
    {
        return new  self(-1, '');
    }


    public function outputSubjects(): void
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

        $image = "../assets/images/Art_Images v3/images/subjects/square-medium/" . $this->getSubjectId() . ".jpg";
      
        $Images = "'" . $image . "'";
        echo '<a href="../Pages/singleSubject.php?subjectId=' . $this->getSubjectId() . '">';
        echo '<img src=' . $Images . ' class="card-img" alt=' . $this->getSubjectName() . '>';
        echo '</a>';

        echo '<a href="../Pages/singleSubject.php?subjectId=' . $this->getSubjectId() . '" class="card-title">' . $this->getSubjectName() . '</a>';

        echo '</div>';
        echo '</div>';
    }
   
    public function outputSingleSubject()
    {
        $css =
        '
            <style>

               .titleSubject {
                text-align: center;
                color: #923f0e;
                font-family: "Goudy Stout";
                margin-TOP: 70px;
                margin-bottom: 100px;
                    }
            </style>
        ';

        // Ausgabe des CSS
        echo $css;

        echo '<body style="background-color: #fffbeb;">';
      //  echo '<div class="container mt-5">';
        echo '<h3 class="titleSubject">' . $this->getSubjectName() . '</h3>';
        echo '<div class="row">';
        echo '<div class="col-md-4">';
        
        // Formular für weitere Aktionen (falls benötigt)
        echo '<form action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '" method="GET">';
        echo '<input type="hidden" name="artistId" value="' . $this->getSubjectId() . '">';
        echo '</form>';

        echo '</div>';
        echo '</div>';

    }
    
}

?>