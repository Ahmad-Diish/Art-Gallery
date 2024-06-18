<?php
ob_start();

require_once ("../Homepage/header.php");
echo "<tr><td>&nbsp;</td></tr>";
require_once ("../Datenbank/userManager.php");
require_once ("../Datenbank/userClass.php");
require_once ("../User/vali.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$userManager = new UserManager();
$validator = new Validator();

$errorMessages = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $requiredFieldErrors = $validator->validateRequiredFields($_POST);
    $errorMessages = array_merge($errorMessages, $requiredFieldErrors);

    if ($validator->hasGeneralError()) {
        // Wenn mindestens ein Pflichtfeld leer ist, den allgemeinen Fehler setzen
        $errorMessages['general'] = "Bitte füllen Sie die mit * gekennzeichneten Felder aus.";
    }

    // Überprüfung spezifischer Fehler für ausgefüllte Felder
    if (!empty($_POST['firstname'])) {
        $firstNameErrors = $validator->validateFirstName($_POST['firstname']);
        if (!empty($firstNameErrors)) {
            $errorMessages['firstname'] = implode("<br/>", $firstNameErrors);
        }
    }

    if (!empty($_POST['lastname'])) {
        $lastNameErrors = $validator->validateLastName($_POST['lastname']);
        if (!empty($lastNameErrors)) {
            $errorMessages['lastname'] = implode("<br/>", $lastNameErrors);
        }
    }

    if (!empty($_POST['address'])) {
        $addressErrors = $validator->validateAddress($_POST['address']);
        if (!empty($addressErrors)) {
            $errorMessages['address'] = implode("<br/>", $addressErrors);
        }
    }

    if (!empty($_POST['postal'])) {
        $postalErrors = $validator->validatePostal($_POST['postal'], $_POST['country']);
        if (!empty($postalErrors)) {
            $errorMessages['postal'] = implode("<br/>", $postalErrors);
        }
    }

    if (!empty($_POST['city'])) {
        $cityErrors = $validator->validateCity($_POST['city']);
        if (!empty($cityErrors)) {
            $errorMessages['city'] = implode("<br/>", $cityErrors);
        }
    }

    if (!empty($_POST['region'])) {
        $regionErrors = $validator->validateRegion($_POST['region']);
        if (!empty($regionErrors)) {
            $errorMessages['region'] = implode("<br/>", $regionErrors);
        }
    }

    if (!empty($_POST['country'])) {
        $countryErrors = $validator->validateCountry($_POST['country']);
        if (!empty($countryErrors)) {
            $errorMessages['country'] = implode("<br/>", $countryErrors);
        }
    }

    if (!empty($_POST['phone'])) {
        $phoneError = $validator->validateRegistrationPhone($_POST['phone']);
        if ($phoneError) {
            $errorMessages['phone'] = $phoneError;
        }
    }

    if (!empty($_POST['email']) && !empty($_POST['emailrepeat'])) {
        $emailErrors = $validator->validateRegistrationEmail($_POST['email'], $_POST['emailrepeat']);
        if (!empty($emailErrors['email'])) {
            $errorMessages['email'] = implode("<br/>", $emailErrors['email']);
        }
        if (!empty($emailErrors['repeat'])) {
            $errorMessages['emailrepeat'] = implode("<br/>", $emailErrors['repeat']);
        }
    }

    if (!empty($_POST['username'])) {
        $usernameError = $validator->validateRegisterUsername($_POST['username']);
        if ($usernameError) {
            $errorMessages['username'] = $usernameError;
        }
    }

    if (!empty($_POST['password']) && !empty($_POST['passwordrepeat'])) {
        $passwordErrors = $validator->validateRegistrationPassword($_POST['password'], $_POST['passwordrepeat']);
        if (!empty($passwordErrors['password'])) {
            $errorMessages['password'] = implode("<br/>", $passwordErrors['password']);
        }
        if (!empty($passwordErrors['passwordrepeat'])) {
            $errorMessages['passwordrepeat'] = implode("<br/>", $passwordErrors['passwordrepeat']);
        }
    }

    if (empty($errorMessages)) {
        $user = new User(
            $_POST['firstname'] ?? NULL,
            $_POST['lastname'] ?? NULL,
            $_POST['address'] ?? NULL,
            $_POST['postal'] ?? NULL,
            $_POST['city'] ?? NULL,
            $_POST['region'] ?? NULL,
            $_POST['country'] ?? NULL,
            $_POST['phone'] ?? NULL,
            $_POST['email'] ?? NULL,
            $_POST['username'] ?? NULL,
            password_hash($_POST['password'], PASSWORD_DEFAULT),
            'default_type',
            'default_state'
        );

        if ($userManager->addUser($user)) {
            header("Location: ../Homepage/index.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            position: relative;
        }

        .container1 {
            margin-top: 50px;
            margin-bottom: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 90vh;
        }

        .box1 {
            background: #fdfdfd;
            display: flex;
            flex-direction: column;
            padding: 25px 25px;
            border-radius: 20px;
            box-shadow: 0 0 50px 0 lightgrey,
                        0 32px 64px -48px lightgrey;
        }

        .form-box1 {
            width: 500px;
            margin: 0px 10px;
        }

        .form-box1 h2 {
            font-size: 35px;
            font-weight: 300;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }

        .form-box1 form .field {
            display: flex;
            margin-bottom: 10px;
            flex-direction: column;
        }

        .form-box1 form .field input,
        .form-box1 form .field select {
            height: 35px;
            width: 100%;
            font-size: 15px;
            padding: 0 10px;
            border-radius: 5px;
            border: 1px solid lightgrey;
            outline: none;
            background-color: #ffffff;
        }

        .button2 {
            height: 35px;
            width: 100%;
            background: #d5a27c;
            border: 0;
            border-radius: 5px;
            color: white;
            font-size: 19px;
            cursor: pointer;
            transition: all .3s;
            margin-top: 10px;
            padding: 0px 10px;
        }

        .button2:hover {
            opacity: 0.80;
        }

        .form-box1 form .field label {
            display: inline-block;
            width: 400px;
        }

        .form-box1 form .error-message {
            color: red;
            margin-top: 0px;
            margin-bottom: 3px;
            font-size: 14px;
        }

        .form-box1 form .spaced-error-message {
            margin-top: 10px;
        }
    </style>
</head>
<body>

<div class="container1">
    <div class="box1 form-box1">
        <h2>Registrieren</h2>
        <div class="required-fields"><p>*Pflichtfelder</p></div>
        <form action="register.php" method="post">
            <div class="field">
                <label for="firstname">Vorname*</label>
                <input type="text" name="firstname">
                <?php if (isset($errorMessages['firstname'])): ?>
                        <p class="error-message"><?php echo $errorMessages['firstname']; ?></p>
                <?php endif; ?>
            </div>

            <div class="field">
                <label for="lastname">Nachname*</label>
                <input type="text" name="lastname">
                <?php if (isset($errorMessages['lastname'])): ?>
                        <p class="error-message"><?php echo $errorMessages['lastname']; ?></p>
                <?php endif; ?>
            </div>

            <div class="field">
                <label for="address">Adresse*</label>
                <input type="text" name="address">
                <?php if (isset($errorMessages['address'])): ?>
                        <p class="error-message"><?php echo $errorMessages['address']; ?></p>
                <?php endif; ?>
            </div>

            <div class="field">
                <label for="postal">Postleitzahl</label>
                <input type="text" name="postal">
                <?php if (isset($errorMessages['postal'])): ?>
                        <p class="error-message"><?php echo $errorMessages['postal']; ?></p>
                <?php endif; ?>
            </div>

            <div class="field">
                <label for="city">Stadt*</label>
                <input type="text" name="city">
                <?php if (isset($errorMessages['city'])): ?>
                        <p class="error-message"><?php echo $errorMessages['city']; ?></p>
                <?php endif; ?>
            </div>

            <div class="field">
                <label for="region">Region</label>
                <input type="text" name="region">
                <?php if (isset($errorMessages['region'])): ?>
                        <p class="error-message"><?php echo $errorMessages['region']; ?></p>
                <?php endif; ?>
            </div>

            <div class="field">
                <label for="country">Land*</label>
                <select name="country">
                    <option value="" disabled selected></option>
                    <?php
                    $countries = [
                        "Afghanistan",
                        "Ägypten",
                        "Albanien",
                        "Algerien",
                        "Andorra",
                        "Angola",
                        "Antigua und Barbuda",
                        "Äquatorialguinea",
                        "Argentinien",
                        "Armenien",
                        "Aserbaidschan",
                        "Äthiopien",
                        "Australien",

                        "Bahamas",
                        "Bahrain",
                        "Bangladesch",
                        "Barbados",
                        "Belgien",
                        "Belize",
                        "Benin",
                        "Bhutan",
                        "Bolivien",
                        "Bosnien und Herzegowina",
                        "Botswana",
                        "Brasilien",
                        "Brunei",
                        "Bulgarien",
                        "Burkina Faso",
                        "Burundi",

                        "Chile",
                        "China",
                        "Costa Rica",

                        "Dänemark",
                        "Deutschland",
                        "Dominica",
                        "Dominikanische Republik",
                        "Dschibuti",

                        "Ecuador",
                        "El Salvador",
                        "Eritrea",
                        "Estland",
                        "Eswatini",

                        "Fidschi",
                        "Finnland",
                        "Frankreich",

                        "Gabun",
                        "Gambia",
                        "Georgien",
                        "Ghana",
                        "Grenada",
                        "Griechenland",
                        "Guatemala",
                        "Guinea",
                        "Guinea-Bissau",
                        "Guyana",

                        "Haiti",
                        "Honduras",

                        "Indien",
                        "Indonesien",
                        "Irak",
                        "Iran",
                        "Irland",
                        "Island",
                        "Israel",
                        "Italien",

                        "Jamaika",
                        "Japan",
                        "Jemen",
                        "Jordanien",

                        "Kambodscha",
                        "Kamerun",
                        "Kanada",
                        "Kap Verde",
                        "Kasachstan",
                        "Katar",
                        "Kenia",
                        "Kirgisistan",
                        "Kiribati",
                        "Kolumbien",
                        "Komoren",
                        "Kosovo",
                        "Kroatien",
                        "Kuba",
                        "Kuwait",

                        "Laos",
                        "Lesotho",
                        "Lettland",
                        "Libanon",
                        "Liberia",
                        "Libyen",
                        "Liechtenstein",
                        "Litauen",
                        "Luxemburg",

                        "Madagaskar",
                        "Malawi",
                        "Malaysia",
                        "Malediven",
                        "Mali",
                        "Malta",
                        "Marshallinseln",
                        "Mauretanien",
                        "Mauritius",
                        "Mexiko",
                        "Mikronesien",
                        "Moldawien",
                        "Monaco",
                        "Mongolei",
                        "Montenegro",
                        "Marokko",
                        "Mosambik",
                        "Myanmar",

                        "Namibia",
                        "Nauru",
                        "Nepal",
                        "Neuseeland",
                        "Nicaragua",
                        "Niederlande",
                        "Niger",
                        "Nigeria",
                        "Nordkorea",
                        "Nordmazedonien",
                        "Norwegen",

                        "Oman",
                        "Österreich",

                        "Pakistan",
                        "Palau",
                        "Palästina",
                        "Panama",
                        "Papua-Neuguinea",
                        "Paraguay",
                        "Peru",
                        "Philippinen",
                        "Polen",
                        "Portugal",

                        "Rumänien",
                        "Ruanda",
                        "Russland",

                        "Saint Kitts und Nevis",
                        "Saint Lucia",
                        "Saint Vincent und die Grenadinen",
                        "Sambia",
                        "Samoa",
                        "San Marino",
                        "Sao Tome und Principe",
                        "Saudi-Arabien",
                        "Schweden",
                        "Schweiz",
                        "Senegal",
                        "Serbien",
                        "Seychellen",
                        "Sierra Leone",
                        "Simbabwe",
                        "Singapur",
                        "Slowakei",
                        "Slowenien",
                        "Somalia",
                        "Spanien",
                        "Sri Lanka",
                        "Südafrika",
                        "Sudan",
                        "Südsudan",
                        "Suriname",
                        "Syrien",

                        "Tadschikistan",
                        "Tansania",
                        "Thailand",
                        "Timor-Leste",
                        "Togo",
                        "Tonga",
                        "Trinidad und Tobago",
                        "Tschad",
                        "Tschechische Republik",
                        "Tunesien",
                        "Türkei",
                        "Turkmenistan",
                        "Tuvalu",

                        "Uganda",
                        "Ukraine",
                        "Ungarn",
                        "Uruguay",
                        "Usbekistan",

                        "Vanuatu",
                        "Vatikanstadt",
                        "Venezuela",
                        "Vereinigte Arabische Emirate",
                        "Vereinigte Staaten",
                        "Vereinigtes Königreich",
                        "Vietnam",

                        "Weißrussland",

                        "Zentralafrikanische Republik"
                    ];


                    foreach ($countries as $country) {
                        echo '<option value="' . $country . '">' . $country . '</option>';
                    }
                    ?>
                </select>
                <?php if (isset($errorMessages['country'])): ?>
                        <p class="error-message"><?php echo $errorMessages['country']; ?></p>
                <?php endif; ?>
            </div>

            <div class="field">
                <label for="phone">Telefon</label>
                <input type="text" name="phone">
                <?php if (isset($errorMessages['phone'])): ?>
                        <p class="error-message"><?php echo $errorMessages['phone']; ?></p>
                <?php endif; ?>
            </div>

            <div class="field">
                <label for="email">E-Mail*</label>
                <input type="text" name="email">
                <?php if (isset($errorMessages['email'])): ?>
                        <p class="error-message"><?php echo $errorMessages['email']; ?></p>
                <?php endif; ?>
            </div>

            <div class="field">
                <label for="email">E-Mail wiederholen*</label>
                <input type="text" name="emailrepeat">
                <?php if (isset($errorMessages['emailrepeat'])): ?>
                        <p class="error-message"><?php echo $errorMessages['emailrepeat']; ?></p>
                <?php endif; ?>
            </div>

            <div class="field">
                <label for="username">Username*</label>
                <input type="text" name="username">
                <?php if (isset($errorMessages['username'])): ?>
                        <p class="error-message"><?php echo $errorMessages['username']; ?></p>
                <?php endif; ?>
            </div>

            <div class="field">
                <label for="password">Passwort*</label>
                <input type="password" name="password">
                <?php if (isset($errorMessages['password'])): ?>
                        <p class="error-message"><?php echo $errorMessages['password']; ?></p>
                <?php endif; ?>
            </div>

            <div class="field">
                <label for="passwordrepeat">Passwort wiederholen*</label>
                <input type="password" name="passwordrepeat">
                <?php if (isset($errorMessages['passwordrepeat'])): ?>
                        <p class="error-message"><?php echo $errorMessages['passwordrepeat']; ?></p>
                <?php endif; ?>
            </div>
            
            <?php if (isset($errorMessages['general'])): ?>
                    <p class="error-message spaced-error-message"><?php echo $errorMessages['general']; ?></p>
            <?php endif; ?>

            <button class="button2" type="submit" name="submit">Registrieren</button>
                <div class="link">
                    Bereits registriert? <a href="login.php">Login</a>
                </div>
        </form>
    </div>
</div>
</body>
</html>

<?php
echo "<tr><td>&nbsp;</td></tr>";
require_once ("../Homepage/footer.php");
ob_end_flush();
?>
