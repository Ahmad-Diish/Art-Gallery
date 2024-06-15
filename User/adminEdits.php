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

if (!isset($_SESSION['UserData']) || $_SESSION['UserData']->getType() != 1) {
    header("Location: ../Homepage/index.php");
    exit();
}

$userManager = new UserManager();
$validator = new Validator();

$successMessage = ""; // Erfolgsmeldung
$errorMessages = []; // Fehlermeldungen
$userData = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_changes']) && isset($_POST['user_to_edit'])) {
    $userData = $userManager->getUserByUsername($_POST['user_to_edit']);

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
        $phoneErrors = $validator->validateUpdatePhone($_POST['phone'], $userData->getPhone());
        if (!empty($phoneErrors)) {
            $errorMessages['phone'] = implode("<br/>", $phoneErrors);
        }
    }

    if (!empty($_POST['email'])) {
        $emailErrors = $validator->validateUpdateEmail($_POST['email']);
        if (!empty($emailErrors['email'])) {
            $errorMessages['email'] = implode("<br/>", $emailErrors['email']);
        }
    }

    if (!empty($_POST['username'])) {
        $usernameError = $validator->validateUpdateUsername($_POST['username'], $userData->getUsername());
        if ($usernameError) {
            $errorMessages['username'] = $usernameError;
        }
    }

    if (!empty($_POST['password']) && !empty($_POST['passwordrepeat'])) {
        $passwordErrors = $validator->validateUpdatePassword($_POST['password'], $_POST['passwordrepeat'], $_POST['oldpassword'], $userData->getId());
        if (!empty($passwordErrors['password'])) {
            $errorMessages['password'] = implode("<br/>", $passwordErrors['password']);
        }
        if (!empty($passwordErrors['passwordrepeat'])) {
            $errorMessages['passwordrepeat'] = implode("<br/>", $passwordErrors['passwordrepeat']);
        }
        if (!empty($passwordErrors['oldpassword'])) {
            $errorMessages['oldpassword'] = implode("<br/>", $passwordErrors['oldpassword']);
        }
    }

    if (empty($errorMessages)) {
        // Initialisiere das User-Objekt
        $user = new User(
            empty($_POST['firstname']) ? $userData->getFirstname() : $_POST['firstname'],
            empty($_POST['lastname']) ? $userData->getLastname() : $_POST['lastname'],
            empty($_POST['address']) ? $userData->getAddress() : $_POST['address'],
            empty($_POST['postal']) ? $userData->getPostal() : $_POST['postal'],
            empty($_POST['city']) ? $userData->getCity() : $_POST['city'],
            empty($_POST['region']) ? $userData->getRegion() : $_POST['region'],
            empty($_POST['country']) ? $userData->getCountry() : $_POST['country'],
            empty($_POST['phone']) ? $userData->getPhone() : $_POST['phone'],
            empty($_POST['email']) ? $userData->getEmail() : $_POST['email'],
            empty($_POST['username']) ? $userData->getUsername() : $_POST['username'],
            !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : $userData->getPasswordHash(),
            $userData->getId()
        );

        // Aktualisiere die Datenbank
        if ($userManager->updateUser($user)) {
            $successMessage = "Änderungen erfolgreich gespeichert.";
            $userData = $userManager->getUserByUsername($userData->getUsername());
            $_SESSION['UserData'] = $userData;
        } else {
            $errorMessages['general'] = "Fehler beim Speichern der Änderungen.";
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit-account'])) {
    $username = $_POST['user_to_edit'];
    $userData = $userManager->getUserByUsername($username);
}

ob_end_flush();
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

        .form-box1 form .field input,
        .form-box1 form .field select{
            height: 35px;
            width: 100%;
            font-size: 15px;
            padding: 0 10px;
            border-radius: 5px;
            border: 1px solid lightgrey;
            outline: none;
            background-color: #ffffff;
        }

        .form-box1 form .input input {
            height: 35px;
            width: 100%;
            font-size: 15px;
            padding: 0 10px;
            border-radius: 5px;
            border: 1px solid lightgrey;
            outline: none;
        }
        .form-box1 form .field label {
            display: inline-block;
            width: 400px;
        }
        .form-box1 form .field input[type="submit"] {
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
        .form-box1 form .error-message {
            color: red;
            margin-top: 5px;
            margin-bottom: 5px;
            font-size: 12px;
        }
        .success-message {
            color: #66cdaa;
            font-size: 16px;
            font-weight: 700;
            text-align: center;
            position: absolute;
            top: 530px;
            left: 50%;
            transform: translateX(-50%);
            background: #f5fffa;
            border: 1px solid lightgrey;
            padding: 10px 20px;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }
    </style>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const successMessage = document.querySelector(".success-message");
            if (successMessage) {
                setTimeout(() => {
                    successMessage.style.display = 'none';
                }, 800); // 0.8 Sekunden
            }
        });
    </script>
</head>
<body>

<?php if (!empty($successMessage)): ?>
                <p class="success-message"><?php echo $successMessage; ?></p>
<?php endif; ?>

<div class="container1">
    <div class="box1 form-box1">
    <h2><?php echo htmlspecialchars($userData->getUsername(), ENT_QUOTES, 'UTF-8'); ?></h2>
        <form action="adminEdits.php" method="post">
            <div class="field">
                <label for="firstname">Vorname:</label>
                <input type="text" name="firstname" value="<?php echo $userData->getFirstname() ? $userData->getFirstname() : ''; ?>">
                <?php if (isset($errorMessages['firstname'])): ?>
                            <p class="error-message"><?php echo $errorMessages['firstname']; ?></p>
                <?php endif; ?>
            </div>

            <div class="field">
                <label for="lastname">Nachname:</label>
                <input type="text" name="lastname" value="<?php echo $userData->getLastname() ? $userData->getLastname() : ''; ?>">
                <?php if (isset($errorMessages['lastname'])): ?>
                            <p class="error-message"><?php echo $errorMessages['lastname']; ?></p>
                <?php endif; ?>
            </div>

            <div class="field">
                <label for="address">Adresse:</label>
                <input type="text" name="address" value="<?php echo $userData->getAddress() ? $userData->getAddress() : ''; ?>">
                <?php if (isset($errorMessages['address'])): ?>
                            <p class="error-message"><?php echo $errorMessages['address']; ?></p>
                <?php endif; ?>
            </div>

            <div class="field">
                <label for="postal">Postleitzahl:</label>
                <input type="text" name="postal" value="<?php echo $userData->getPostal() ? $userData->getPostal() : ''; ?>">
                <?php if (isset($errorMessages['postal'])): ?>
                            <p class="error-message"><?php echo $errorMessages['postal']; ?></p>
                <?php endif; ?>
            </div>

            <div class="field">
                <label for="city">Stadt:</label>
                <input type="text" name="city" value="<?php echo $userData->getCity() ? $userData->getCity() : ''; ?>">
                <?php if (isset($errorMessages['city'])): ?>
                            <p class="error-message"><?php echo $errorMessages['city']; ?></p>
                <?php endif; ?>
            </div>

            <div class="field">
                <label for="region">Region:</label>
                <input type="text" name="region" value="<?php echo $userData->getRegion() ? $userData->getRegion() : ''; ?>">
                <?php if (isset($errorMessages['region'])): ?>
                            <p class="error-message"><?php echo $errorMessages['region']; ?></p>
                <?php endif; ?>
            </div>

            <div class="field">
                <label for="country">Land:</label>
                <select name="country">
                    <?php
                    $selectedCountry = $userData->getCountry();
                    $selectedCountry = empty($selectedCountry) ? '' : $selectedCountry;
                    ?>
                    <option value="<?php echo $selectedCountry; ?>" selected><?php echo $selectedCountry; ?></option>
                    <?php
                    error_log('Land ausgewählt:' . $selectedCountry);
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
                        if ($country !== $selectedCountry) {
                            echo '<option value="' . $country . '">' . $country . '</option>';
                        }
                    }
                    ?>
                </select>
                <?php if (isset($errorMessages['country'])): ?>
                            <p class="error-message"><?php echo $errorMessages['country']; ?></p>
                <?php endif; ?>
            </div>

            <div class="field">
                <label for="phone">Telefon:</label>
                <input type="text" name="phone" value="<?php echo $userData->getPhone() ? $userData->getPhone() : ''; ?>">
                <?php if (isset($errorMessages['phone'])): ?>
                            <p class="error-message"><?php echo $errorMessages['phone']; ?></p>
                <?php endif; ?>
            </div>

            <div class="field">
                <label for="email">E-Mail:</label>
                <input type="text" name="email" value="<?php echo $userData->getEmail() ? $userData->getEmail() : ''; ?>">
                <?php if (isset($errorMessages['email'])): ?>
                                <p class="error-message"><?php echo $errorMessages['email']; ?></p>
                <?php endif; ?>
            </div>

            <?php if (isset($errorMessages['general'])): ?>
                            <p class="error-message"><?php echo $errorMessages['general']; ?></p>
            <?php endif; ?>

            <input type="hidden" name="user_to_edit" value="<?php echo $userData->getUsername() ?>"/>

            <div class="field">
                <input type="submit" name="save_changes" value="Speichern"/>
            </div>
        </form>
    </div>
</div>
</body>
</html>

<?php
echo "<tr><td>&nbsp;</td></tr>";
require_once ("../Homepage/footer.php");
?>
