<?php

require_once("../Homepage/header.php");
require("../Datenbank/genreRepository.php");


$conn = new datenbank();
$genrei = new GenreRepository($conn);

?>
<genres>

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
        <div class="container mt-4">
            <h2 class="title">Genres Galerie</h2>
            <div class="row mt-4">
                <?php
                $genrei->AllGenres();
                ?>
            </div>
        </div>
    </body>
</genres>



<?php require_once("../Homepage/footer.php");
?>