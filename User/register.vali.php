<?php
// Inkludiere den UserManager, um auf die Überprüfungsfunktionen zuzugreifen
require_once ("../Datenbank/userManager.php");

class RegistrationValidator
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
            "Albanien",
            "Algerien",
            "Andorra",
            "Angola",
            "Antigua und Barbuda",
            "Argentinien",
            "Armenien",
            "Australien",
            "Österreich",
            "Aserbaidschan",
            "Bahamas",
            "Bahrain",
            "Bangladesch",
            "Barbados",
            "Weißrussland",
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
            "Cabo Verde",
            "Kambodscha",
            "Kamerun",
            "Kanada",
            "Zentralafrikanische Republik",
            "Tschad",
            "Chile",
            "China",
            "Kolumbien",
            "Komoren",
            "Demokratische Republik Kongo",
            "Republik Kongo",
            "Costa Rica",
            "Kroatien",
            "Kuba",
            "Zypern",
            "Tschechische Republik",
            "Dänemark",
            "Dschibuti",
            "Dominica",
            "Dominikanische Republik",
            "Ecuador",
            "Ägypten",
            "El Salvador",
            "Äquatorialguinea",
            "Eritrea",
            "Estland",
            "Eswatini",
            "Äthiopien",
            "Fidschi",
            "Finnland",
            "Frankreich",
            "Gabun",
            "Gambia",
            "Georgien",
            "Deutschland",
            "Ghana",
            "Griechenland",
            "Grenada",
            "Guatemala",
            "Guinea",
            "Guinea-Bissau",
            "Guyana",
            "Haiti",
            "Honduras",
            "Ungarn",
            "Island",
            "Indien",
            "Indonesien",
            "Iran",
            "Irak",
            "Irland",
            "Israel",
            "Italien",
            "Jamaika",
            "Japan",
            "Jordanien",
            "Kasachstan",
            "Kenia",
            "Kiribati",
            "Nordkorea",
            "Südkorea",
            "Kosovo",
            "Kuwait",
            "Kirgisistan",
            "Laos",
            "Lettland",
            "Libanon",
            "Lesotho",
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
            "Niederlande",
            "Neuseeland",
            "Nicaragua",
            "Niger",
            "Nigeria",
            "Nordmazedonien",
            "Norwegen",
            "Oman",
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
            "Katar",
            "Rumänien",
            "Russland",
            "Ruanda",
            "Saint Kitts und Nevis",
            "Saint Lucia",
            "Saint Vincent und die Grenadinen",
            "Samoa",
            "San Marino",
            "Sao Tome und Principe",
            "Saudi-Arabien",
            "Senegal",
            "Serbien",
            "Seychellen",
            "Sierra Leone",
            "Singapur",
            "Slowakei",
            "Slowenien",
            "Salomonen",
            "Somalia",
            "Südafrika",
            "Südsudan",
            "Spanien",
            "Sri Lanka",
            "Sudan",
            "Suriname",
            "Schweden",
            "Schweiz",
            "Syrien",
            "Taiwan",
            "Tadschikistan",
            "Tansania",
            "Thailand",
            "Timor-Leste",
            "Togo",
            "Tonga",
            "Trinidad und Tobago",
            "Tunesien",
            "Türkei",
            "Turkmenistan",
            "Tuvalu",
            "Uganda",
            "Ukraine",
            "Vereinigte Arabische Emirate",
            "Vereinigtes Königreich",
            "Vereinigte Staaten",
            "Uruguay",
            "Usbekistan",
            "Vanuatu",
            "Vatikanstadt",
            "Venezuela",
            "Vietnam",
            "Jemen",
            "Sambia",
            "Simbabwe"
        ];

        // Überprüfung, ob das angegebene Land in der Liste der gültigen Länder enthalten ist
        if (!in_array($country, $validCountries)) {
            array_push($errors, "Das angegebene Land ist ungültig.");
        }

        return $errors;
    }

    public function validatePhone($phone)
    {
        // Prüfe, ob die Telefonnummer bereits verwendet wird und nicht zum aktuellen Benutzer gehört
        if ($this->userManager->phoneExists($phone)) {
            return "Die Telefonnummer wird bereits verwendet.";
        }
        return "";
    }

    public function validateEmail($email, $emailrepeat)
    {
        $result = array("email" => array(), "repeat" => array());

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            array_push($result["email"], "Die E-Mail ist ungültig.");
        }

        $userManager = new UserManager();
        if ($userManager->emailExists($email)) {
            array_push($result["email"], "Die Email existiert bereits.");
        }

        if ($email !== $emailrepeat) {
            array_push($result["repeat"], "Die E-Mail-Adressen stimmen nicht überein.");
        }

        return $result;
    }

    public function validateUsername($username)
    {
        // Prüfe, ob der Benutzername bereits existiert und nicht zum aktuellen Benutzer gehört
        if ($this->userManager->usernameExists($username)) {
            return "Der Benutzername ist bereits vergeben.";
        }
        return "";
    }

    public function validatePassword($password, $passwordRepeat)
    {
        $result = array("password" => array(), "repeat" => array());

        if (!password_verify($password, $passwordRepeat)) {
            array_push($result['repeat'], "Die Passwörter stimmen nicht überein.");
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

    public function hasGeneralError() {
        return $this->generalError;
    }
}

