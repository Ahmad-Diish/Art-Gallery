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
    /* Stildefinition für Rechts */
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

    /* Stildefinition für den Link */
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


    /* Stildefinition für Footer */
    .footer {
      padding: 20px;
      background-color: #d5a27c;
      color: white;
      text-align: center;
      position: fixed;
      bottom: 0;
      width: 100%;
    }

    /* Stildefinition für Links */
    .search-btn {
      background-color: #d5a27c;
      border: none;
      color: #fef3c7;
    }

    .search-btn:hover {
      background-color: #6c757d;
    }

    .heart-btn,
    .person-btn {
      background-color: transparent;
      border: none;
      color: #fef3c7;
    }

    .heart-btn:hover,
    .person-btn:hover {
      color: #adb5bd;
    }

    .sub-menu-wrap{
      position: absolute;
      top: 110%;
      right: 2%;
      width: 320px;
      max-height: 0px;
      overflow: hidden;
      transition: max-height 0.5s;
      z-index: 1001;
      background-color: white;
    }

  .sub-menu-wrap.open-menu{
    max-height: 400px;
    background-color: white;
    
  }

    .sub-menu{
      background-color: white;
      padding: 20px;
      margin: 10px;
    }

    .user-info{
      display: flex;
      flex-direction: column;
      align-items: center;

    }

    .sub-menu-link{
      display: flex;
      align-items: center;
      text-decoration: none;
      color: #6c757d;
      margin: 12px 0;
    }

    .sub-menu-link p{
      width: 100%;
    }

    .sub-menu-link:hover{
      opacity: 0.80;
    }

  </style>
</head>

<body>
  <header>
    <nav class="navbar navbar-expand-lg">
      <div class="container-fluid">
        <a class="navbar-brand" href="../Homepage/index.php" style="text-decoration:none;">
          <img src="../assets/images/logo.png" alt="imageLogo" width="50" height="50">
          Art Gallery </a>
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
              <a class="nav-link dropdown-toggle" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" color:hover:white;>
                Mehr über der Kunstwerk
              </a>
              <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                <li><a class="dropdown-item" href="../Pages/artists.php">Künstler</a></li>
                <li><a class="dropdown-item" href="../Pages/artsworks.php">Kunstwerk</a></li>
                <li><a class="dropdown-item" href="../Pages/genres.php">Genres</a></li>
                <li><a class="dropdown-item" href="../Pages/subjects.php">Themen</a></li>
              </ul>
            </li>
          </ul>
          <form class="d-flex" method="post" action="search.php">
            <input class="form-control me-2" type="search" placeholder="Suche" aria-label="Search">
            <button class="btn search-btn" type="submit"><i class="bi bi-search"></i></button>
          </form>
          <button class="btn heart-btn"><i class="bi bi-heart"></i></button>
          <button class="btn person-btn" onclick="toggleMenu()"><i class="bi bi-person"></i></button>
        </div>
      </div>
      <div class="sub-menu-wrap" id="subMenu">
        <div class="sub-menu">
          <div class="user-info">
          <a  href="../User/login.php" class="sub-menu-link"><p>Login</p></a>
          <a  href="../User/register.php" class="sub-menu-link"><p>Registrieren</p></a>
          </div>
        </div>
      </div>
    </nav>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
      let subMenu = document.getElementById("subMenu");

      function toggleMenu(){
        subMenu.classList.toggle("open-menu");
      }
    </script>
  </header>
</body>

</html>