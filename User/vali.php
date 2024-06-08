<?php
// Inkludiere den UserManager, um auf die Überprüfungsfunktionen zuzugreifen
require_once ("../Datenbank/userManager.php");

class Validator
{
    private $userManager;
    private $generalError = false;

    public function __construct()
    {
        $this->userManager = new UserManager();
    }

    function validateFirstName($firstname)
    {

        $errors = array();

        if (preg_match('/[0-9]/', $firstname)) {
            array_push($errors, "Der Vorname darf keine Zahlen enthalten.");
        }

        if (preg_match('/[!\?@#$%^&*()\\-_=+{};:,<.>]/', $firstname)) {
            array_push($errors, "Der Vorname darf keine Sonderzeichen enthalten.");
        }

        if (mb_strlen($firstname) < 2) {
            array_push($errors, "Der Vorname muss mindestens 2 Zeichen lang sein.");
        }

        return $errors;
    }

    function validateLastName($lastname)
    {

        $errors = array();

        if (preg_match('/[0-9]/', $lastname)) {
            array_push($errors, "Der Nachname darf keine Zahlen enthalten.");
        }

        if (preg_match('/[!?@#$%^&*()\-_=+{};:,<.>]/', $lastname)) {
            array_push($errors, "Der Nachname darf keine Sonderzeichen enthalten.");
        }

        if (mb_strlen($lastname) < 2) {
            array_push($errors, "Der Nachname muss mindestens 2 Zeichen lang sein.");
        }

        return $errors;
    }

    function validateAddress($address)
    {
        $errors = array();

        if (preg_match('/[!?@#$%^&*()\_=+{};:,<>]/', $address)) {
            array_push($errors, "Die Addresse darf dieses Sonderzeichen nicht enthalten.");
        }

        if (!preg_match('/^[a-zA-ZäöüÄÖÜß\s\-.]+(\s\d+)?$/', $address)) {
            array_push($errors, "Es dürfen keine Zahlen mitten im Straßennamen enthalten sein.");
        }

        return $errors;
    }

    function validatePostal($postal, $country)
{
    $errors = array();

    // Liste der regulären Ausdrücke für Postleitzahlen nach Land
    $postalPatterns = array(
        "Afghanistan" => '/^[0-9]{4}$/',
        "Ägypten" => '/^[0-9]{5}$/',
        "Albanien" => '/^[0-9]{4}$/',
        "Algerien" => '/^[0-9]{5}$/',
        "Andorra" => '/^AD[0-9]{3}$/',
        "Angola" => '/^$/',
        "Antigua und Barbuda" => '/^$/',
        "Äquatorialguinea" => '/^$/',
        "Argentinien" => '/^[A-Z]{1}[0-9]{4}[A-Z]{3}$/',
        "Armenien" => '/^[0-9]{4}$/',
        "Aserbaidschan" => '/^[0-9]{4}$/',
        "Äthiopien" => '/^[0-9]{4}$/',
        "Australien" => '/^[0-9]{4}$/',
        "Bahamas" => '/^$/',
        "Bahrain" => '/^[0-9]{3,4}$/',
        "Bangladesch" => '/^[0-9]{4}$/',
        "Barbados" => '/^BB[0-9]{5}$/',
        "Belgien" => '/^[0-9]{4}$/',
        "Belize" => '/^$/',
        "Benin" => '/^$/',
        "Bhutan" => '/^[0-9]{5}$/',
        "Bolivien" => '/^[0-9]{4}$/',
        "Bosnien und Herzegowina" => '/^[0-9]{5}$/',
        "Botswana" => '/^$/',
        "Brasilien" => '/^[0-9]{5}-[0-9]{3}$/',
        "Brunei" => '/^[A-Z]{2}[0-9]{4}$/',
        "Bulgarien" => '/^[0-9]{4}$/',
        "Burkina Faso" => '/^[0-9]{2}$/',
        "Burundi" => '/^$/',
        "Chile" => '/^[0-9]{7}$/',
        "China" => '/^[0-9]{6}$/',
        "Costa Rica" => '/^[0-9]{5}$/',
        "Dänemark" => '/^[0-9]{4}$/',
        "Deutschland" => '/^[0-9]{5}$/',
        "Dominica" => '/^$/',
        "Dominikanische Republik" => '/^[0-9]{5}$/',
        "Dschibuti" => '/^$/',
        "Ecuador" => '/^$/',
        "El Salvador" => '/^[0-9]{4}$/',
        "Eritrea" => '/^$/',
        "Estland" => '/^[0-9]{5}$/',
        "Eswatini" => '/^[A-Z]{1}[0-9]{3}$/',
        "Fidschi" => '/^$/',
        "Finnland" => '/^[0-9]{5}$/',
        "Frankreich" => '/^[0-9]{5}$/',
        "Gabun" => '/^$/',
        "Gambia" => '/^$/',
        "Georgien" => '/^[0-9]{4}$/',
        "Ghana" => '/^$/',
        "Grenada" => '/^$/',
        "Griechenland" => '/^[0-9]{3} [0-9]{2}$/',
        "Guatemala" => '/^[0-9]{5}$/',
        "Guinea" => '/^$/',
        "Guinea-Bissau" => '/^[0-9]{4}$/',
        "Guyana" => '/^$/',
        "Haiti" => '/^[0-9]{4}$/',
        "Honduras" => '/^[0-9]{5}$/',
        "Indien" => '/^[0-9]{6}$/',
        "Indonesien" => '/^[0-9]{5}$/',
        "Irak" => '/^[0-9]{5}$/',
        "Iran" => '/^[0-9]{10}$/',
        "Irland" => '/^[A-Z0-9]{3} [A-Z0-9]{4}$/',
        "Island" => '/^[0-9]{3}$/',
        "Israel" => '/^[0-9]{5,7}$/',
        "Italien" => '/^[0-9]{5}$/',
        "Jamaika" => '/^$/',
        "Japan" => '/^[0-9]{3}-[0-9]{4}$/',
        "Jemen" => '/^$/',
        "Jordanien" => '/^[0-9]{5}$/',
        "Kambodscha" => '/^[0-9]{5}$/',
        "Kamerun" => '/^$/',
        "Kanada" => '/^[A-Z][0-9][A-Z] [0-9][A-Z][0-9]$/',
        "Kap Verde" => '/^[0-9]{4}$/',
        "Kasachstan" => '/^[0-9]{6}$/',
        "Katar" => '/^$/',
        "Kenia" => '/^[0-9]{5}$/',
        "Kirgisistan" => '/^[0-9]{6}$/',
        "Kiribati" => '/^$/',
        "Kolumbien" => '/^[0-9]{6}$/',
        "Komoren" => '/^[0-9]{5}$/',
        "Kosovo" => '/^[0-9]{5}$/',
        "Kroatien" => '/^[0-9]{5}$/',
        "Kuba" => '/^[0-9]{5}$/',
        "Kuwait" => '/^[0-9]{5}$/',
        "Laos" => '/^$/',
        "Lesotho" => '/^[0-9]{3}$/',
        "Lettland" => '/^[0-9]{4}$/',
        "Libanon" => '/^[0-9]{4}$/',
        "Liberia" => '/^[0-9]{4}$/',
        "Libyen" => '/^$/',
        "Liechtenstein" => '/^[0-9]{4}$/',
        "Litauen" => '/^[0-9]{5}$/',
        "Luxemburg" => '/^[0-9]{4}$/',
        "Madagaskar" => '/^[0-9]{3}$/',
        "Malawi" => '/^$/',
        "Malaysia" => '/^[0-9]{5}$/',
        "Malediven" => '/^[0-9]{5}$/',
        "Mali" => '/^$/',
        "Malta" => '/^[A-Z]{3} [0-9]{2,4}$/',
        "Marshallinseln" => '/^[0-9]{5}(-[0-9]{4})?$/',
        "Mauretanien" => '/^[0-9]{5}$/',
        "Mauritius" => '/^[0-9]{5}$/',
        "Mexiko" => '/^[0-9]{5}$/',
        "Mikronesien" => '/^[0-9]{5}(-[0-9]{4})?$/',
        "Moldawien" => '/^[0-9]{4}$/',
        "Monaco" => '/^98000$/',
        "Mongolei" => '/^[0-9]{5}$/',
        "Montenegro" => '/^[0-9]{5}$/',
        "Marokko" => '/^[0-9]{5}$/',
        "Mosambik" => '/^[0-9]{4}$/',
        "Myanmar" => '/^[0-9]{5}$/',
        "Namibia" => '/^[0-9]{5}$/',
        "Nauru" => '/^$/',
        "Nepal" => '/^[0-9]{5}$/',
        "Neuseeland" => '/^[0-9]{4}$/',
        "Nicaragua" => '/^[0-9]{5}$/',
        "Niederlande" => '/^[1-9][0-9]{3}\s?[A-Z]{2}$/',
        "Niger" => '/^[0-9]{4}$/',
        "Nigeria" => '/^[0-9]{6}$/',
        "Nordkorea" => '/^$/',
        "Nordmazedonien" => '/^[0-9]{4}$/',
        "Norwegen" => '/^[0-9]{4}$/',
        "Oman" => '/^[0-9]{3}$/',
        "Österreich" => '/^[0-9]{4}$/',
        "Pakistan" => '/^[0-9]{5}$/',
        "Palau" => '/^[0-9]{5}(-[0-9]{4})?$/',
        "Palästina" => '/^$/',
        "Panama" => '/^[0-9]{4}$/',
        "Papua-Neuguinea" => '/^[0-9]{3}$/',
        "Paraguay" => '/^[0-9]{4}$/',
        "Peru" => '/^[0-9]{5}$/',
        "Philippinen" => '/^[0-9]{4}$/',
        "Polen" => '/^[0-9]{2}-[0-9]{3}$/',
        "Portugal" => '/^[0-9]{4}-[0-9]{3}$/',
        "Rumänien" => '/^[0-9]{6}$/',
        "Ruanda" => '/^$/',
        "Russland" => '/^[0-9]{6}$/',
        "Saint Kitts und Nevis" => '/^$/',
        "Saint Lucia" => '/^$/',
        "Saint Vincent und die Grenadinen" => '/^$/',
        "Sambia" => '/^[0-9]{5}$/',
        "Samoa" => '/^$/',
        "San Marino" => '//^4789[0-9]$/',
        "Sao Tome und Principe" => '/^[0-9]{4}$/',
        "Saudi-Arabien" => '/^[0-9]{5}$/',
        "Schweden" => '/^[0-9]{3} [0-9]{2}$/',
        "Schweiz" => '/^[0-9]{4}$/',
        "Senegal" => '/^[0-9]{5}$/',
        "Serbien" => '/^[0-9]{5}$/',
        "Seychellen" => '/^$/',
        "Sierra Leone" => '/^$/',
        "Simbabwe" => '/^$/',
        "Singapur" => '/^[0-9]{6}$/',
        "Slowakei" => '/^[0-9]{3} [0-9]{2}$/',
        "Slowenien" => '/^[0-9]{4}$/',
        "Somalia" => '//^$/',
        "Spanien" => '/^[0-9]{5}$/',
        "Sri Lanka" => '/^[0-9]{5}$/',
        "Südafrika" => '/^[0-9]{4}$/',
        "Sudan" => '/^$/',
        "Südsudan" => '/^$/',
        "Suriname" => '//^$/',
        "Syrien" => '//^$/',
        "Tadschikistan" => '//^[0-9]{6}$/',
        "Tansania" => '//^[0-9]{4}$/',
        "Thailand" => '//^[0-9]{5}$/',
        "Timor-Leste" => '//^$/',
        "Togo" => '//^$/',
        "Tonga" => '//^$/',
        "Trinidad und Tobago" => '//^$/',
        "Tschad" => '//^$/',
        "Tschechische Republik" => '//^[0-9]{3} [0-9]{2}$/',
        "Tunesien" => '//^[0-9]{4}$/',
        "Türkei" => '//^[0-9]{5}$/',
        "Turkmenistan" => '//^[0-9]{6}$/',
        "Tuvalu" => '//^$/',
        "Uganda" => '//^$/',
        "Ukraine" => '//^[0-9]{5}$/',
        "Ungarn" => '//^[0-9]{4}$/',
        "Uruguay" => '//^[0-9]{5}$/',
        "Usbekistan" => '//^[0-9]{6}$/',
        "Vanuatu" => '//^$/',
        "Vatikanstadt" => '//^00120$/',
        "Venezuela" => '//^[0-9]{4}$/',
        "Vereinigte Arabische Emirate" => '//^$/',
        "Vereinigte Staaten" => '//^[0-9]{5}(-[0-9]{4})?$/',
        "Vereinigtes Königreich" => '//^(GIR 0AA|[A-Z]{1,2}[0-9R][0-9A-Z]? [0-9][ABD-HJLNP-UW-Z]{2})$/',
        "Vietnam" => '//^[0-9]{6}$/',
        "Weißrussland" => '//^[0-9]{6}$/',
        "Zentralafrikanische Republik" => '//^$/'
    );

    // Überprüfung, ob ein Muster für das Land existiert
    if (isset($postalPatterns[$country])) {
        $pattern = $postalPatterns[$country];

        // Überprüfung der Postleitzahl anhand des Musters
        if (!preg_match($pattern, $postal)) {
            array_push($errors, "Die Postleitzahl ist für das angegebene Land ungültig.");
        }
    } else {
        array_push($errors, "Für das angegebene Land ist keine Postleitzahlprüfung verfügbar.");
    }

    return $errors;
}

    function validateCity($city)
    {

        $errors = array();

        if (preg_match('/[0-9]/', $city)) {
            array_push($errors, "Der Stadtname darf keine Zahlen enthalten.");
        }

        if (preg_match('/[!?@#$%^&*()\_=+{};:,<>]/', $city)) {
            array_push($errors, "Der Stadtname darf dieses Sonderzeichen nicht enthalten.");
        }

        return $errors;
    }

    function validateRegion($region)
    {
        $errors = array();

        if (preg_match('/[0-9]/', $region)) {
            array_push($errors, "Das Kürzel der Region darf keine Zahlen enthalten.");
        }

        if (preg_match('/[!?@#$%^&*()\_=+{};:,<.>]/', $region)) {
            array_push($errors, "Das Kürzel der Region darf keine Sonderzeichen enthalten.");
        }

        if (mb_strlen($region) > 3) {
            array_push($errors, "Das Kürzel der Region darf maximal 3 Zeichen lang sein.");
        }

        $region = strtoupper($region);

        return $errors;
    }

    function validateCountry($country)
    {
        $errors = array();

        // Liste der gültigen Länder auf Deutsch
        $validCountries = [
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

        // Überprüfung, ob das angegebene Land in der Liste der gültigen Länder enthalten ist
        if (!in_array($country, $validCountries)) {
            array_push($errors, "Das angegebene Land ist ungültig.");
        }

        return $errors;
    }

    public function validateRegistrationPhone($phone)
    {
        $errors = array();

        if (!preg_match('/^\+?[0-9]{10,15}$/', $phone)) {
            array_push($errors, "Die Telefonnummer ist ungültig.");
        }

        if ($this->userManager->phoneExists($phone)) {
            array_push($errors, "Die Telefonnummer wird bereits verwendet.");
        }

        return $errors;
    }

    public function validateUpdatePhone($newphone, $oldphone)
    {
        $errors = array();

        if ($newphone !== $oldphone) {
            if (!preg_match('/^\+?[0-9]{10,15}$/', $newphone)) {
                array_push($errors, "Die Telefonnummer ist ungültig.");
            }

            if ($this->userManager->phoneExists($newphone)) {
                array_push($errors, "Die Telefonnummer wird bereits verwendet.");
            }
        }

        return $errors;
    }

    public function validateRegistrationEmail($email, $emailrepeat)
    {
        $result = array("email" => array(), "repeat" => array());

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            array_push($result["email"], "Die E-Mail ist ungültig.");
        }

        if ($this->userManager->emailExists($email)) {
            array_push($result["email"], "Die Email existiert bereits.");
        }

        if ($email !== $emailrepeat) {
            array_push($result["repeat"], "Die E-Mail-Adressen stimmen nicht überein.");
        }

        return $result;
    }
    public function validateUpdateEmail($email)
    {
        $result = array();

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            array_push($result, "Die E-Mail ist ungültig.");
        }

        if ($this->userManager->emailExists($email)) {
            array_push($result, "Die Email existiert bereits.");
        }

        return $result;
    }

    public function validateRegisterUsername($username)
    {
        // Prüfe, ob der Benutzername bereits existiert und nicht zum aktuellen Benutzer gehört
        if ($this->userManager->usernameExists($username)) {
            return "Der Benutzername ist bereits vergeben.";
        }
        return "";
    }
    public function validateUpdateUsername($newusername, $oldusername)
    {
        if ($newusername !== $oldusername) {
            // Prüfe, ob der Benutzername bereits existiert und nicht zum aktuellen Benutzer gehört
            if ($this->userManager->usernameExists($newusername)) {
                return "Der Benutzername ist bereits vergeben.";
            }
        }
        return "";
    }

    private function validatePasswordCommon($password, $passwordrepeat)
    {
        $result = array("password" => array(), "passwordrepeat" => array(), "oldpassword" => array());

        if ($password !== $passwordrepeat) {
            array_push($result['passwordrepeat'], "Die Passwörter stimmen nicht überein.");
        }

        if (strlen($password) < 12) {
            array_push($result['password'], "Das Passwort muss mindestens 12 Zeichen lang sein.");
        }

        if (!preg_match("/[0-9]/", $password)) {
            array_push($result['password'], "Das Passwort muss mindestens eine Zahl enthalten.");
        }

        if (!preg_match("/[A-Z]/", $password)) {
            array_push($result['password'], "Das Passwort muss mindestens einen Großbuchstaben enthalten.");
        }

        if (!preg_match("/[!?@#$%^&*()\-_=+{};:,<.>]/", $password)) {
            array_push($result['password'], "Das Passwort muss mindestens ein Sonderzeichen enthalten.");
        }

        return $result;
    }
    public function validateRegistrationPassword($password, $passwordrepeat)
    {
        $result = $this->validatePasswordCommon($password, $passwordrepeat);

        return $result;
    }
    public function validateUpdatePassword($password, $passwordrepeat, $oldpassword, $userId)
    {
        $result = $this->validatePasswordCommon($password, $passwordrepeat);

        $hashedpasswordfromDb = $this->userManager->getUserPasswordById($userId);
        if (!password_verify($oldpassword, $hashedpasswordfromDb)) {
            array_push($result['oldpassword'], "Das alte Passwort ist nicht korrekt.");
        }

        return $result;

    }

    public function validateRequiredFields($data)
    {
        $errors = [];
        $requiredFields = ['firstname', 'lastname', 'address', 'city', 'country', 'email', 'emailrepeat', 'username', 'password', 'passwordrepeat'];
        $emptyFieldsCount = 0;

        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                $emptyFieldsCount++;
                $errors[$field] = "";
            }
        }

        if ($emptyFieldsCount > 0) {
            $this->generalError = true;
            $errors['general'] = "Bitte füllen Sie die mit * gekennzeichneten Felder aus.";
        }

        return $errors;
    }

    public function hasGeneralError()
    {
        return $this->generalError;
    }
}

