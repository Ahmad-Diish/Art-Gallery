<?php

function zufallsBildAuswahl()
{

  $verzeichnis = "../assets/images/carousel";


  $dateien = scandir($verzeichnis);


  $dateien = array_diff($dateien, array('.', '..'));


  $zufallsBild = $dateien[array_rand($dateien)];
  return $zufallsBild;
}
?>


<style>
  #carouselExampleCaptions {
    margin-top: 20px;
    margin-bottom: 20px;
  }

  .carousel-inner {
    border-radius: 100px;
    overflow: hidden;
  }

  .carousel-item {
    height: 500px;

    background-color: #E5DDC5;
    ;
    padding: 5px;
    border: 1px solid #E5DDC5;
    border-radius: 10px;
  }

  .carousel-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 10px 10px 0 0;
  }

  .carousel-caption {
    background-color: rgba(0, 0, 0, 0.6);
    padding: 10px;
    border-radius: 5px;
    color: #fff;
  }

  .carousel-caption h5 {
    font-weight: bold;
    margin-top: 0;
  }

  .carousel-caption p {
    font-size: 16px;
    margin-bottom: 10px;
  }

  .carousel-control-prev,
  .carousel-control-next {
    background-color: #d5a27c;
    border: none;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    padding: 10px;
    font-size: 20px;
    color: #fff;
    cursor: pointer;
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
  }

  .carousel-control-prev:hover,
  .carousel-control-next:hover {
    background-color: #E5DDC5;
  }

  .carousel-control-prev-icon,
  .carousel-control-next-icon {
    font-size: 24px;
    color: #fff;
  }

  .carousel-indicators {
    bottom: -15px;
    left: 33%;
    transform: translateX(-50%);
  }

  .carousel-indicators button {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background-color: #FFEBB2;
    border: 2px;
    margin: px;
    cursor: pointer;
  }

  .carousel-indicators button.active {
    background-color: black;
  }
</style>

<div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
  <div class="carousel-indicators">
    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
  </div>

  <div class="carousel-inner">
    <div class="carousel-item active">
      <?php
      $zufallsBild = zufallsBildAuswahl();
      echo "<img src='../assets/images/carousel/$zufallsBild' class='d-block mx-auto' alt='Kunstwerk'>";
      ?>

      <div class="carousel-caption d-none d-md-block">
        <h5>Einzigartige Online-Kunstausstellung!</h5>
        <p>Entdecken Sie einzigartige Genres von professionellen Künstlern, eine exklusive Auswahl, die Sie inspirieren wird</p>
      </div>

    </div>
    <div class="carousel-item">
      <?php
      $zufallsBild = zufallsBildAuswahl();
      echo "<img src='../assets/images/carousel/$zufallsBild' class='d-block mx-auto' alt='Kunstwerk'>";
      ?>

      <div class="carousel-caption d-none d-md-block">
        <h5>Vielfalt unserer Kunstgalerie!</h5>
        <p>Tauchen Sie bedenkenlos in die Vielfalt unserer Kunstgalerie ein, wo talentierte Künstler aus verschiedenen Genres Sie mit ihren Werken begeistern</p>
      </div>
    </div>

    <div class="carousel-item">
      <?php
      $zufallsBild = zufallsBildAuswahl();
      echo "<img src='../assets/images/carousel/$zufallsBild' class='d-block mx-auto' alt='Kunstwerk'>";
      ?>

      <div class="carousel-caption d-none d-md-block">
        <h5>Kunst mit voller Gewissheit!</h5>
        <p>Entdecken Sie mit voller Gewissheit die Kunstwerke, die Ihren Geschmack treffen, in unserem virtuellen Galerieerlebnis.</p>
      </div>
    </div>

  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>