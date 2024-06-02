<?php
//TODO

//*The system displays a list of all the subjects in the database, along with their subject image (see art-images/subjects) sorted by SubjectName. Each should be a link to Display Single Subject case. This list does not need to be sortable. 
require_once("../Homepage/header.php");
require_once("../Datenbank/subjectManager.php");

// Erstellen einer neuen Datenbankverbindung und einer ArtistManager-Instanz.
$conn = new datenbank();
$subjectManager = new SubjectManager($conn);

?>

<subjects>

    <head>
        <style>
            body {
                background-color: #fffbeb;
            }

            .title {
                text-align: center;
                color: #923f0e;
                font-family: "Goudy Stout";
                margin-TOP: 70px;
                margin-bottom: 100px;

            }
        </style>
    </head>

    <body>
        <div class="container mt-5">
            <h2 class="title">Subjects Galerie</h2>
            <div class="row mt-4">
                <?php
                    $subjectManager->displayAllSubjects();
                ?>
            </div>
        </div>
    </body>
</subjects>

<?php
require_once("../Homepage/footer.php");

?>