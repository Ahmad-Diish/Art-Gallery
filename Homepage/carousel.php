<?php
//Todo
//*Es sollte auch ein Karussell (siehe Bootstrap-Site) mit mindestens drei Folien enthalten. Jede Folie sollte als Werbung für das Angebot der Website dienen (z. B. Marketing). Wenn die Folien Kunstwerke zeigen, sollten diese zufällig ausgewählt werden.//erledigt *//




/**
 * Wählt zufällig ein Bild aus einem Verzeichnis aus.
 *
 * @return string Der Dateiname des ausgewählten Bildes.
 */
function zufallsBildAuswahl()
{

  $verzeichnis = "../assets/images/carousel";

  // Alle Dateien im Verzeichnis auflisten (einschließlich "." und "..")
  $dateien = scandir($verzeichnis);

  // Die "." und ".." aus der Liste entfernen
  $dateien = array_diff($dateien, array('.', '..'));

  // Eine zufällige Datei auswählen
  $zufallsBild = $dateien[array_rand($dateien)];
  return $zufallsBild;
}
?>

<!-- Wurden diese Bilder in Folder CAROUSEL benutzt , weil sie als Querformat sind . Als Hochformat sehen sie sehr schlecht aus . -->
<style>
.carousel-caption {
background-color: rgba(0, 0, 0, 0.5); /* Hintergrundfarbe mit Transparenz */
color: white; /* Textfarbe */
padding: 15px; /* Innenabstand */
position: absolute; /* Positionierung */
bottom: 0; /* Abstand vom unteren Rand */
left: 0; /* Ausrichtung */
width: 40%; /* Breite */
}
</style>

<carousel>
  <div id="carouselExampleCaptions" class="carousel slide">
    <div class="carousel-indicators">
      <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
      <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
      <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
    </div>
    
    <div class="carousel-inner">
      <div class="carousel-item active">
        <?php
        $zufallsBild = zufallsBildAuswahl();
        echo "<img src='../assets/images/carousel/$zufallsBild' class='d-block mx-auto' alt='Kunstwerk' height='400 %' width='50%'>";
        ?>
       
        <div style="text-align: left; left:30%; top:70%;" class="carousel-caption d-none d-md-block">
          <h5> einzigartigen Online-Kunstausstellung!</h5>
          <p>Entdecken Sie einzigartige Genres von professionellen Künstlern, eine exklusive Auswahl, die Sie inspirieren wird</p>
        </div>
        
      </div>
      <div class="carousel-item">
        <?php
        $zufallsBild = zufallsBildAuswahl();
        echo "<img src='../assets/images/carousel/$zufallsBild' class='d-block mx-auto' alt='Kunstwerk' height='400 %' width='50%'>";
        ?>
       
        <div class="carousel-caption d-none d-md-block" style="text-align: left;left:30%;top:70%;">
          <h5 >Vielfalt unserer Kunstgalerie!</h5>
          <p >Tauchen Sie bedenkenlos in die Vielfalt unserer Kunstgalerie ein, wo talentierte Künstler aus verschiedenen Genres Sie mit ihren Werken begeistern</p>
        </div>
      </div>
      
      <div class="carousel-item">
        <?php
        $zufallsBild = zufallsBildAuswahl();
        echo "<img src='../assets/images/carousel/$zufallsBild' class='d-block mx-auto' alt='Kunstwerk' height='400 %' width='50%'>";
        ?>
        
        <div class="carousel-caption d-none d-md-block" style="text-align: left;left:30%;top:70%;">
          <h5 >Kunst mit voller Gewissheit!</h5>
          <p >Entdecken Sie mit voller Gewissheit die Kunstwerke, die Ihren Geschmack treffen, in unserem virtuellen Galerieerlebnis.</p>
        </div>

      </div>

      
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
     
      <span class="visually-hidden">Previous</span>
      
      
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Next</span>
      
    </div>
  </div>
</carousel>