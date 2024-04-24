<?php
/**
 * Definiert die Konstanten für die Datenbankverbindung.
 */

/**
 * DBHOST: Der Hostname oder die IP-Adresse des Datenbankservers.
 * Im Beispiel ist "localhost:3307" angegeben, was auf einen lokalen Server hindeutet, der auf Port 3307 läuft.
 */
define("DBHOST","localhost:4306");
/**
 * DBNAME: Der Name der Datenbank, zu der eine Verbindung hergestellt wird.
 * Im Beispiel ist "art" angegeben, was der Name der spezifischen Datenbank ist.
 */
define("DBNAME","art");
/**
 * DBUSER: Der Benutzername, der für die Verbindung zur Datenbank verwendet wird.
 * Im Beispiel ist "root" angegeben, was der Standardbenutzername für viele lokale MySQL-Installationen ist.
 */
define("DBUSER","root");
/**
 * DBPASS: Das Passwort für den Benutzer, der für die Verbindung zur Datenbank verwendet wird.
 * Im Beispiel ist es leer "", was darauf hindeutet, dass kein Passwort für den Benutzer "root" gesetzt ist.
 */
define("DBPASS","");
?>


