<?php
//TODO


require_once("../Homepage/header.php");
require_once("../Datenbank/subjectManager.php");


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