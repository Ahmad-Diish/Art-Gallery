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
                font-size: 3rem;
                margin-bottom: 2rem;
                text-transform: uppercase;
                letter-spacing: 2px;
                position: relative;
                overflow: hidden;
                color: #923f0e;
            }

            .title::before {
                content: 'Genres Galerie';
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                color: #fef3c7;
                overflow: hidden;
                animation: animate 3s linear infinite;
            }

            @keyframes animate {
                0% {
                    transform: translateY(-100%);
                }
                100% {
                    transform: translateY(100%);
                }
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




