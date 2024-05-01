<?php

require_once("../Homepage/header.php");
require("../Datenbank/genreRepository.php");


$conn = new datenbank();
$genrei = new GenreRepository($conn);

?>

<genres>
    <body>
        <div class="container mt-4">
            <h2>Genres Galerie</h2>
            <div class="row mt-4">
               
                <?php
                $genrei->AllGenres();
                ?>
            </div>
        </div>
    </body>
</genres>


<?php
require_once("../Homepage/footer.php");
?>
