<?php
require_once("../Homepage/header.php");
require_once('datenbank.php');
require_once('artistClass.php');

if (isset($_POST['query'])) {
    $query = htmlspecialchars($_POST['query']);

    // Verbindung zur Datenbank herstellen
    $db = new datenbank();
    try {
        $db->connect();

        // Suchanfrage vorbereiten
        $sql = "SELECT * FROM artists WHERE FirstName LIKE :query OR LastName LIKE :query OR Nationality LIKE :query";
        $stmt = $db->prepareStatement($sql);
        $stmt->execute(['query' => '%' . $query . '%']);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $db->close();
    } catch (Exception $e) {
        echo 'Fehler: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suchergebnisse</title>
    <link rel="stylesheet" href="../css/bootstrap.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>
    <header>
        <!-- Sie können hier den Header-Teil wiederholen -->
    </header>
    <div class="container mt-5">
        <h1>Suchergebnisse</h1>
        <?php if (isset($results) && count($results) > 0): ?>
            <div class="row">
                <?php foreach ($results as $artistData): 
                    $artist = Artist::fromState($artistData);
                    $artist->outputArtist();
                endforeach; ?>
            </div>
        <?php else: ?>
            <p>Keine Ergebnisse gefunden.</p>
        <?php endif; ?>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
require_once("../Homepage/footer.php");
?>