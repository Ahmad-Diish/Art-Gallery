<?php
require_once ("../Datenbank/userManager.php");
require_once ("../Datenbank/userClass.php");
require_once ("../User/validation.php");

session_start();

if (isset($_POST['submit'])) {
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $address = trim($_POST['address']);
    $postal = trim($_POST['postal']);
    $city = trim($_POST['city']);
    $region = trim($_POST['region']);
    $country = trim($_POST['country']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $emailRepeat = trim($_POST['emailrepeat']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $passwordRepeat = trim($_POST['passwordrepeat']);
    
    $firstname = capitalizeFirstName($firstname);
    $lastname = capitalizeLastName($lastname);
    $city = capitalizeCity($city);
    $address = capitalizeAddress($address);

    $address = expandAddressAbbreviations($address);

    $errors = array();
    $generalError = false;

    // Überprüfen, ob Pflichtfelder leer sind
    $requiredFields = ['firstname','lastname', 'address', 'city', 'country', 'email', 'emailRepeat', 'username', 'password', 'passwordRepeat'];
    foreach ($requiredFields as $field) {
        if (empty($$field)) {
            $generalError = true;
        }
    }

    if ($generalError) {
        $errors['general'] = "Bitte füllen Sie die mit * gekennzeichneten Felder aus.";
    }

    // Weiterprüfen, auch wenn Pflichtfelder fehlen, um spezifische Fehler zu erfassen
    if (!empty($firstname)) {
        $firstnameErrors = validateFirstName($firstname);
        if (!empty($firstnameErrors)) {
            $errors['firstname'] = implode("\n", $firstnameErrors);
        }
    }

    if (!empty($lastname)) {
        $lastnameErrors = validateLastName($lastname);
        if (!empty($lastnameErrors)) {
            $errors['lastname'] = implode("\n", $lastnameErrors);
        }
    }

    if (!empty($address)) {
        $addressErrors = validateAddress($address);
        if (!empty($addressErrors)) {
            $errors['address'] = implode("\n", $addressErrors);
        }
    }

    if (!empty($postal)) {
        $postalError = validatePostal($postal, $country);
        if ($postalError) {
            $errors['postal'] = $postalError;
        }
    }

    if (!empty($city)) {
        $cityErrors = validateCity($city);
        if (!empty($cityErrors)) {
            $errors['city'] = implode("\n", $cityErrors);
        }
    }

    if (!empty($region)) {
        $regionError = validateRegion($region);
        if ($regionError) {
            $errors['region'] = $regionError;
        }
    }
    
    if (!empty($country)) {
        $countryError = validateCountry($country);
        if ($countryError) {
            $errors['country'] = $countryError;
        }
    }

    if (!empty($email) || !empty($emailRepeat)) {
        $emailErrors = validateEmails($email, $emailRepeat);
        if (!empty($emailErrors)) {
            if (isset($emailErrors['email'])) {
                $errors['email'] = $emailErrors['email'];
            }
            if (isset($emailErrors['emailrepeat'])) {
                $errors['emailrepeat'] = $emailErrors['emailrepeat'];
            }
        }
    }
    
    if (!empty($username)) {
        $usernameError = validateUsername($username);
        if ($usernameError) {
            $errors['username'] = $usernameError;
        }
    }

    if (!empty($password) || !empty($passwordRepeat)) {
        $passwordErrors = validatePassword($password, $passwordRepeat);
        if (!empty($passwordErrors)) {
            if (isset($passwordErrors['password'])) {
                $errors['password'] = $passwordErrors['password'];
            }
            if (isset($passwordErrors['passwordrepeat'])) {
                $errors['passwordrepeat'] = $passwordErrors['passwordrepeat'];
            }
        }
    }    

    $allRequiredFieldsEmpty = true;
    foreach ($requiredFields as $field) {
        if (!empty($$field)) {
            $allRequiredFieldsEmpty = false;
            break;
        }
    }

    if ($allRequiredFieldsEmpty) {
        $errors = array('general' => "Bitte füllen Sie die mit * gekennzeichneten Felder aus.");
    }

    if (!empty($errors)) {
        // Wenn es Fehler gibt, leite zurück zur Registrierungsseite und zeige die Fehlermeldungen an
        $errorMessage = json_encode($errors);
        header("Location: register.php?error=validation&message=" . urlencode($errorMessage));
        exit();
    }

    // Wenn keine Fehler vorhanden sind, fahre mit der Benutzerregistrierung fort
    try {
        $userManager = new UserManager();
        $user = new User($firstname, $lastname, $address, $postal, $city, $region, $country, $phone, $email, $username, password_hash($password, PASSWORD_DEFAULT));

        if ($userManager->addUser($user)) {
            header("Location: ../Homepage/index.php?signup=success");
            exit();
        } else {
            header("Location: register.php?error=sqlerror");
            exit();
        }
    } catch (Exception $e) {
        header("Location: register.php?error=exception");
        exit();
    }
} else {
    header("Location: register.php");
    exit();
}
?>
