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
//* Enum für Passwort Validierung 
enum PasswordValidationResult: string
{
  case Valid = "Passwort ist gültig.";
  case ShortPassword = "<p>Passwort zu kurz; mindestens 8 Zeichen erforderlich.</p>";
  case NoLowerCase = "<p>Passwort muss Kleinbuchstaben enthalten.</p>";
  case NoUpperCase = "<p>Passwort muss Großbuchstaben enthalten.</p>";
  case NoNumber = "<p>Passwort muss Zahlen enthalten.</p>";
  case NoSpecialCharacter = "<p>Passwort muss Sonderzeichen enthalten.</p>";
}

//* Function für Validierung von Passwort
function validatePassword($password): PasswordValidationResult
{
  if (strlen($password) < 8) {
    return PasswordValidationResult::ShortPassword;
  }
  //kleine Buchstaben
  if (!preg_match("/[a-z]/", $password)) {
    return PasswordValidationResult::NoLowerCase;
  }
  //große Buchstaben
  if (!preg_match("/[A-Z]/", $password)) {
    return PasswordValidationResult::NoUpperCase;
  }
  //Zahlen
  if (!preg_match("/[0-9]/", $password)) {
    return PasswordValidationResult::NoNumber;
  }
  //sonderzeichen außer "_" und "@"
  if (!preg_match("/[\W]/", $password)) {
    return PasswordValidationResult::NoSpecialCharacter;
  }

  return PasswordValidationResult::Valid;
}



//* Umleiten Funktionen ohne Parameter
function umleiten($fileName): void
{
  echo '<script type="text/javascript">';
  echo 'window.location.href="' . $fileName . '";';
  echo '</script>';
}
//* Umleiten Funktionen mit Parameter und Modal
function umleitenMitParameterModal($fileName, $Id): void
{

  echo '<script>var variable = "' . $Id . '";
  window.location.href = "' . $fileName . '?modal=ja&parameter=" + encodeURIComponent(variable);</script>';
}
//* Umleiten Funktionen mit Parameter
function umleitenMitParameter($fileName, $Id): void
{

  echo '<script>var variable = "' . $Id . '";
  window.location.href = "' . $fileName . '?parameter=" + encodeURIComponent(variable);</script>';
}

//*Funktion für Reload
function reload()
{
  echo '<script>
        window.location.reload();
        </script>';
}

function removeFavoriteArtist($artistId): void
{
    if (($key = array_search($artistId, $_SESSION['favorite_artists'])) !== false) {
        unset($_SESSION['favorite_artists'][$key]);
    }
}
//Funktion für Löschen des Kunstwerks von FavouriteListe
function removeFavoriteArtwork($artworkId): void
{
    if (($key = array_search($artworkId, $_SESSION['favorite_artworks'])) !== false) {
        unset($_SESSION['favorite_artworks'][$key]);
    }
}

function addFavoriteArtwork($artworkId): void

{
    // hier für Hinzufügen eines Elementes Ende der Liste
    $_SESSION['favorite_artworks'][] = $artworkId;

}
