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
    }
    .navbar-brand:hover
    {
      color : #923f0e;
    }
    .navbar-toggler-icon {
      color: #fef3c7;
    }
    .nav-link {
      color: #fef3c7;
    }
    .nav-link:hover {
      color: #adb5bd;
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
    }
    .search-btn:hover {
      background-color: #6c757d;
    }
    .heart-btn, .person-btn {
      background-color: transparent;
      border: none;
      color: #fef3c7;
    }
    .heart-btn:hover, .person-btn:hover {
      color: #adb5bd;
    }
  </style>
</head>
<body>
<header>
<nav class="navbar navbar-expand-lg">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">
      <img src="../assets/images/logo.jpg" width="50" height="50" alt="">
    </a>
    <a class="navbar-brand mb-0 h1" style="font-family: Rancho, cursive;" href="#">Art Gallery</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link" href="#">Startseite</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Über uns</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Mehr über der Kunstwerk
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="#">Künstler</a></li>
            <li><a class="dropdown-item" href="#">Kunstwerk</a></li>
            <li><a class="dropdown-item" href="#">Genres</a></li>
            <li><a class="dropdown-item" href="#">Themen</a></li>
          </ul>
        </li>
      </ul>
      <form class="d-flex">
        <input class="form-control me-2" type="search" placeholder="Suche" aria-label="Search">
        <button class="btn search-btn" type="submit"><i class="bi bi-search"></i></button>
      </form>
      <button class="btn heart-btn"><i class="bi bi-heart"></i></button>
      <button class="btn person-btn"><i class="bi bi-person"></i></button>
    </div>
  </div>
</nav>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</header>
</body>
</html>