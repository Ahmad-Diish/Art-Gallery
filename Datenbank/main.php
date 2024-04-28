<?php

//* hier wurde die globale Variable sowie Methode definiert.

$isLoggedIn = false;
if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true) {

  $isLoggedIn = true;
}

require("../Datenbank/datenbank.php");


//* Funktionen für überprüfen , ob das Bild (Artwork/Artist) verfügbar oder nicht 
function checkArtistImage($verzeichnis)
{
  return file_exists($verzeichnis) ? $verzeichnis : "../assets/images/noArtistSquare.png";
}
function checkKunstwerkImage($verzeichnis)
{
  return file_exists($verzeichnis) ? $verzeichnis : "../assets/images/keinKunstwerkSquare.jpg";
}
//*Funktion für verschönern nicht mehr als 3 Worte in Title zeigen ...
function truncate($title)
{
  $words = explode(" ", $title); // Teilt den Titel in Wörter auf
  if (count($words) > 3) {
    return implode(" ", array_slice($words, 0, 4)) . "..."; // Kürzt den Titel auf die ersten drei Wörter
  } else {
    return $title; // Gibt den Originaltitel zurück, wenn er 3 oder weniger Wörter hat
  }
}