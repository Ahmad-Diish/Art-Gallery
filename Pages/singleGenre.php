<?php
require_once("../Datenbank/datenbank.php");
require_once("../Datenbank/genreRepository.php");


$conn = new Datenbank();
$genreRepository = new GenreRepository($conn);






$genreId = isset($_GET["genre_id"]) ? $_GET["genre_id"] : null;


if (!$genreId) {
    header("Location: error.php");
    exit;
}


$genre = $genreRepository->getGenreByID($genreId);


if (!$genre) {
    header("Location: error.php");
    exit;
}

$artworks = $genreRepository->getArtworksForGenre($genreId);
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Single Genre</title>
    <link rel="stylesheet" href="../Homepage/genres">
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
                content: 'Display Single Genre';
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
        <h2><?php echo $genre['GenreName']; ?></h2>
        <p><?php echo $genre['Description']; ?></p>
        <h3>Kunstwerke in diesem Genre</h3>
        <div class="row mt-4">
            <?php foreach ($artworks as $artwork) { ?>
                <div class="col-md-3 col-lg-2 mb-4">
                    <div class="card">
                        <img src="<?php echo $artwork['Image']; ?>" class="card-img" alt="<?php echo $artwork['Title']; ?>">
                        <div class="card-body">
                            <a href="../Pages/singleArtwork.php?artwork_id=<?php echo $artwork['ArtWorkID']; ?>" class="card-title"><?php echo $artwork['Title']; ?></a>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</body>
</html>
