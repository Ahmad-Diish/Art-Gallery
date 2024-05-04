<?php
// Require necessary files
require_once("../Homepage/header.php");
require_once("../Datenbank/artistRepository.php");
require_once("../Datenbank/artworkRepository.php");


//*Dieser Anwendungsfall wird initiiert, wenn ein Benutzer einen einzelnen Künstler zur Anzeige auswählt (z. B. auf einen Link klickt, der den Benutzer zu dieser Seite führt). 2. Das System zeigt Informationen zu einem einzelnen Künstler an (angegeben über die Künstler-ID, die über einen Query-String-Parameter übergeben wird). Diese Seite sollte fehlende oder ungültige Abfragezeichenfolgen-ID-Parameter behandeln, indem sie auf eine Fehlerseite umleitet. Alle Informationen in der Künstlertabelle sollten angezeigt werden. Es sollte eine Möglichkeit geben, alle Kunstwerke des Künstlers anzuzeigen. Die Miniaturansicht, der Titel und die Schaltfläche „Ansicht“ des Kunstwerks müssen Links zu diesem Kunstwerk sein (gehen Sie also zum Anwendungsfall „Einzelnes Kunstwerk anzeigen“). Diese Seite muss es dem Benutzer ermöglichen, den Künstler zu einer sitzungsbasierten Favoritenliste hinzuzufügen (d. h. zum Anwendungsfall „Zu Favoriten hinzufügen“ wechseln). In diesem Fall wird es zur Liste der bevorzugten Künstler hinzugefügt.

