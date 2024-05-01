<?php
require_once("../Homepage/header.php");
require_once("../Datenbank/artworkRepository.php");
require_once("../Datenbank/artwork.php");


// Erstellen einer neuen Datenbankverbindung und einer ArtistRepository-Instanz.
$conn = new Datenbank();
$artistRepository = new ArtistRepository($conn);
$artist = Artist::getDefaultArtist();
$artworkRepository = new ArtworkRepository($conn);


?>



<singleArtwork>
    <body>
        <div class="container mt-4">
            <?php
            //* hier wurde die Funktion outSingleArtwork von Klasse Artwork gerufen , um das Kunstwerk allein zu zeigen
            $artwork->outputSingleArtwork();
            ?>
            <!-- wenn der Benutzer eingeloggt ist , kann er dann hier bewerten    -->
            <?php if ($isLoggedIn) {
                echo ' <form action="submitReview.php" method="post" class="review">
                        <input type="hidden" name="artworkId" value="' . $artworkID . '">
                        <div class="form-group">
                            <label for="rating">Bewertung:</label>
                            <select name="rating" id="rating" class="form-control">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="comment">Kommentar:</label>
                            <textarea name="comment" id="comment" style="padding:20px"  class="textarea-style"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary button_style">Bewertung absenden</button>
                    </form>';
            }
            ?>
        </div>
        <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="errorModalLabel">Fehler</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <?php echo 'Sie sind noch nicht angemeldet, bitte melden Sie sich  an' ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary button_style" data-bs-dismiss="modal">schließen</button>

                    </div>
                </div>
            </div>
        </div>

    </body>
</singleArtwork>





<?php
require_once("../Homepage/footer.php");
?>