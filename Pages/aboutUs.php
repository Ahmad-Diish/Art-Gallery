<?php
require_once ("../Homepage/header.php");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
    <style>
        body {
            background-color: #fffbeb;
        }

        .containerUS {
            max-width: 100%;
            margin: 20px auto;
            padding: 20px;
            background-color: #fef3c7;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #923f0e;
            font-family: "Goudy Stout";
            margin-TOP: 70px;
            margin-bottom: 100px;
        }


        .team-members {
            margin-top: 30px;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        .team-member {
            margin: 0px 50px 20px 50px;
            padding: 20px 20px 20px 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 300px;
        }

        .team-member h2 {
            color: #333;
            margin-bottom: 5px;
            font-family: "Brush Script MT";
        }

        .team-member p {
            color: #666;
            margin-bottom: 0;
            font-family: "Arial ";

        }



        .our-story {
            margin-top: 20px;
            overflow: hidden;
        }

        .our-story img {
            max-width: 100%;
            height: auto;
            border-radius: 50%;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            float: left;
            margin-right: 20px;
        }

        .our-story h1 {
            text-align: center;
            margin-TOP: 100px;
            color: #923f0e;
            font-family: "Brush Script MT";

        }

        .our-story p {
            text-align: center;
            margin-top: 20px;
            color: #923f0e;
            font-family: "Bradley Hand ITC";
            font-size: 20px;
        }

        .our-story:nth-child(even) img {
            float: right;
            margin-left: 20px;
            margin-right: 0;
        }
    </style>
</head>

<body>


    <!-- Main content -->
    <div class="containerUS">
        <h1>Unser Story</h1>
        <div class="our-story">
            <img src="../assets/images/US3.jpg" alt="Our Story">
            <h1>Die Geburt der Inspiration</h1>
            <p>Unsere Gemäldeausstellung ist das Ergebnis einer langen Reise durch die Welt der Kunst, beginnend mit
                einem Funken Inspiration, der vor Jahren entzündet wurde. Diese Ausstellung ist das Produkt von
                unzähligen Stunden der Leidenschaft, des Lernens und der kreativen Entfaltung. Jedes Gemälde erzählt
                eine Geschichte, jedes Pinselstrich spricht von einer Reise, die von der ersten Idee bis zum letzten
                Hauch von Farbe reicht. Es ist eine Feier der Künstlerinnen und Künstler, die mit ihren Werken die
                Grenzen des Ausdrucks überschritten haben.</p>
        </div>

        <div class="our-story">
            <img src="../assets/images/US2.jpg" alt="Our Story">
            <h1>Der kreative Prozess entfaltet sich</h1>
            <p> In den Ateliers und Werkstätten der Künstlerinnen und Künstler begann der kreative Prozess, der diese
                Ausstellung zum Leben erweckte. Hier wurden Ideen geboren, Skizzen gezeichnet und Farben gemischt. Jedes
                Gemälde trägt die einzigartige Handschrift seines Schöpfers und offenbart die Vielfalt künstlerischer
                Ausdrucksformen. Von abstrakten Impressionen bis hin zu realistischen Porträts spiegeln diese Werke die
                Vielschichtigkeit menschlicher Emotionen und Erfahrungen wider.</p>
        </div>

        <div class="our-story">
            <img src="../assets/images/US1.jpg" alt="Our Story">
            <h1>Die Reise der Betrachter:</h1>
            <p>Für die Besucherinnen und Besucher beginnt die Reise durch unsere Ausstellung mit einem Schritt über die
                Schwelle. Hier tauchen sie ein in eine Welt der Farben, Formen und Geschichten. Jedes Gemälde lädt sie
                ein, sich zu verlieren, zu reflektieren und zu träumen. Doch diese Ausstellung ist mehr als nur eine
                Ansammlung von Bildern - sie ist ein Spiegel, der die Vielfalt und Schönheit der menschlichen Seele
                widerspiegelt. Es ist unsere Hoffnung, dass die Betrachterinnen und Betrachter inspiriert und berührt
                werden, und dass sie mit neuen Perspektiven und Erkenntnissen nach Hause zurückkehren.</p>
        </div>


        <h1>About Us</h1>
        <div class="team-members">
            <div class="team-member">
                <h2>Ahmad</h2>
                <p>Gruppe 3</p>
                <p>Email: ahmad.diish@th-wildau.de</p>
                <p>Telefon: +49 123 456789</p>
                <p>USE CASE: 1-2-3-4-6-12-15-16-17 </p>
            </div>
            <div class="team-member">
                <h2>Omer </h2>
                <p>Gruppe 3</p>
                <p>Email: omar.al_sharaa@th-wildau.de</p>
                <p>Telefon: +49 123 456789</p>
                <p>USE CASE: 1-2-3-5-8-11-14-15-16-17 </p>
            </div>
            <div class="team-member">
                <h2>Rami </h2>
                <p>Gruppe 3</p>
                <p>Email: rami.balasem@th-wildau.de</p>
                <p>Telefon: +49 123 456789</p>
                <p>USE CASE: 1-2-3-7-13-15 </p>
            </div>
            <div class="team-member">
                <h2>Saskia</h2>
                <p>Gruppe 3</p>
                <p>Email: isaskia.keller@th-wildau.de</p>
                <p>Telefon: +49 123 456789</p>
                <p>USE CASE: 9-10-18-19-20-21-22-23-24-25 </p>
            </div>
            <div class="team-member">
                <h2>Anneli</h2>
                <p>Gruppe 3</p>
                <p>Email: anneli.pommnitz@th-wildau.de</p>
                <p>Telefon: +49 123 456789</p>
                <p>USE CASE: 9-10-18-19-20-21-22-23-24-25 </p>
            </div>
        </div>
    </div>
</body>

</html>




<?php
require_once ("../Homepage/footer.php");
?>