<?php
require_once ("../Datenbank/userClass.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    // Logout-Logik
    session_destroy();
    $redirect = isset($_GET['redirect']) ? $_GET['redirect'] : '../Homepage/index.php';
    if (basename($redirect) == 'account.php') {
        $redirect = '../Homepage/index.php';
    }
    header("Location: $redirect");
    exit();
}

function renderHeader()
{
    $currentUrl = $_SERVER['REQUEST_URI'];
    echo '
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="../Homepage/index.php" style="text-decoration:none;">
                <img src="../assets/images/logo.png" alt="imageLogo" width="50" height="50"> Art Gallery
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="../Homepage/index.php">Startseite</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../Pages/aboutUs.php">Über uns</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Galerie erkunden
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="../Pages/artists.php">Künstler</a></li>
                            <li><a class="dropdown-item" href="../Pages/artsworks.php">Kunstwerk</a></li>
                            <li><a class="dropdown-item" href="../Pages/genres.php">Genres</a></li>
                            <li><a class="dropdown-item" href="../Pages/subjects.php">Themen</a></li>
                        </ul>
                    </li>
                </ul>
               <table>
                    <tr>
                        <td>
                            <form class="d-flex align-items-center" method="POST" action="../Pages/search.php">
                                <input class="form-control me-2" type="text" name="searchQuery" placeholder="Suche" aria-label="Suche">
                                <button class="btn search-btn" type="submit" name="submit-search"><i class="bi bi-search"></i></button>
                            </form>
                        </td>
                    </tr>
                </table>';

    if (isset($_SESSION['UserData'])) {
        // Benutzer ist eingeloggt
        $username = htmlspecialchars($_SESSION['UserData']->getUsername());
        echo '
                <button class="btn heart-btn"><i class="bi bi-heart"></i></button>
                <div class="user-menu">
                    <span class="username" onclick="toggleMenu()">' . $username . '</span>
                    <div class="sub-menu-wrap" id="subMenu">
                        <div class="sub-menu">
                            <div class="user-info">
                                <a href="../User/account.php" class="sub-menu-link"><p>My Account</p></a>
                                <a href="?action=logout&redirect=' . urlencode($currentUrl) . '" class="sub-menu-link"><p>Logout</p></a>
                            </div>
                        </div>
                    </div>
                </div>';
    } else {
        // Benutzer ist nicht eingeloggt
        echo '
                <button class="btn heart-btn"><i class="bi bi-heart"></i></button>
                <button class="btn person-btn" onclick="toggleMenu()"><i class="bi bi-person"></i></button>
                <div class="sub-menu-wrap" id="subMenu">
                    <div class="sub-menu">
                        <div class="user-info">
                            <a href="../User/login.php" class="sub-menu-link"><p>Login</p></a>
                            <a href="../User/register.php" class="sub-menu-link"><p>Registrieren</p></a>
                        </div>
                    </div>
                </div>';
    }

    echo '
            </div>
        </div>
    </nav>';
}

renderHeader();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Art-Gallery</title>
    <link rel="stylesheet" href="../css/bootstrap.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa;
        }

        .navbar {
            background-color: #d5a27c;
        }

        .navbar-brand {
            font-size: 1.5rem;
            color: #923f0e;
            font-family: "Rancho", cursive;
        }

        .navbar-brand:hover {
            color: #923f0e;
        }

        .navbar-toggler-icon {
            color: #fef3c7;
        }

        .nav-link {
            color: #fef3c7;
        }

        .nav-link:hover {
            color: #fef3c7;
        }

        .dropdown-item {
            color: #fef3c7;
            background-color: #d5a27c;
        }

        .dropdown-item:hover {
            color: #923f0e;
        }

        .footer {
            padding: 20px;
            background-color: #d5a27c;
            color: white;
            text-align: center;
            position: fixed;
            bottom: 0;
            width: 100%;
        }

        .search-btn {
            background-color: #d5a27c;
            border: none;
            color: #fef3c7;
            margin-top: 14px;
        }

        .search-btn:hover {
            background-color: #6c757d;
        }

        .form-control {
            margin-top: 14px;
        }

        .heart-btn,
        .person-btn {
            background-color: transparent;
            border: none;
            color: #fef3c7;
            margin-top: -2px;
        }

        .heart-btn:hover,
        .person-btn:hover {
            color: #adb5bd;
        }

        .user-menu {
            position: relative;
            display: flex;
            align-items: center;
        }

        .username {
            cursor: pointer;
            color: #fef3c7;
            font-size: 1rem;
            margin-left: 1rem;
            margin-top: -2px;
        }

        .username:hover {
            color: #adb5bd;
        }

        .sub-menu-wrap {
            position: absolute;
            top: 110%;
            right: 0;
            width: 320px;
            max-height: 0px;
            overflow: hidden;
            transition: max-height 0.5s;
            z-index: 1001;
            background-color: white;
        }

        .sub-menu-wrap.open-menu {
            max-height: 400px;
            background-color: white;
        }

        .sub-menu {
            background-color: white;
            padding: 20px;
            margin: 10px;
        }

        .user-info {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .sub-menu-link {
            display: flex;
            align-items: center;
            text-decoration: none;
            color: #6c757d;
            margin: 12px 0;
        }

        .sub-menu-link p {
            width: 100%;
        }

        .sub-menu-link:hover {
            opacity: 0.80;
        }
    </style>
</head>

<body>
    <header>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
        <script>
            function toggleMenu() {
                let subMenu = document.getElementById("subMenu");
                subMenu.classList.toggle("open-menu");
            }
        </script>
    </header>
</body>

</html>