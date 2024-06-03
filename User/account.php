<?php
require_once ("../Homepage/header.php");
echo "<tr><td>&nbsp;</td></tr>";
require_once ("../Datenbank/userManager.php");
require_once ("../Datenbank/userClass.php");
require_once ("../User/account.vali.php");

function emptystr($s, $defval)
{
    $res = trim($s);
    return (strlen($res) == 0) ? $defval : $res;
}

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$userManager = new UserManager();
$validator = new AccountValidator();

$successMessage = ""; // Erfolgsmeldung
$errorMessages = []; // Fehlermeldungen

if (isset($_SESSION['username'])) {
    $userData = $userManager->getUserByUsername($_SESSION['username']);

    // Prüfe, ob der Benutzer existiert
    if (!$userData) {
        echo "Benutzer nicht gefunden.";
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_changes'])) {
    // Validierung
    if ($_POST['username'] !== $userData->getUsername()) {
        $usernameError = $validator->validateUsername($_POST['username']);
        if ($usernameError) {
            $errorMessages['username'] = $usernameError;
        }
    }

    if ($_POST['email'] !== $userData->getEmail()) {
        $emailError = $validator->validateEmail($_POST['email']);
        if ($emailError) {
            $errorMessages['email'] = $emailError;
        }
    }

    if ($_POST['phone'] !== $userData->getPhone()) {
        $phoneError = $validator->validatePhone($_POST['phone']);
        if ($phoneError) {
            $errorMessages['phone'] = $phoneError;
        }
    }

    if (!empty($_POST['newpassword']) || !empty($_POST['newpasswordconfirm'])) {
        $passwordErrors = $validator->validatePassword($_POST['oldpassword'], $_POST['newpassword'], $userData->getId());
        if (!empty($passwordErrors)) {
            foreach ($passwordErrors as $error) {
                if (strpos($error, 'Das alte Passwort') !== false) {
                    $errorMessages['oldpassword'] = $error;
                } elseif (strpos($error, 'Das neue Passwort') !== false) {
                    $errorMessages['newpasswordconfirm'] = $error;
                } else {
                    $errorMessages['newpassword'] = (isset($errorMessages['newpassword']) ? $errorMessages['newpassword'] : "") . "<br/>" . $error;
                }
            }
        }

        if ($_POST['newpassword'] !== $_POST['newpasswordconfirm']) {
            $errorMessages['newpasswordconfirm'] = "Die neuen Passwörter stimmen nicht überein.";
        }
    }

    if (empty($errorMessages)) {
        // Initialisiere das User-Objekt
        $user = new User(
            $_POST['firstname'] ?? $userData->getFirstname(),
            $_POST['lastname'] ?? $userData->getLastname(),
            $_POST['address'] ?? $userData->getAddress(),
            $_POST['postal'] ?? $userData->getPostal(),
            $_POST['city'] ?? $userData->getCity(),
            $_POST['region'] ?? $userData->getRegion(),
            $_POST['country'] ?? $userData->getCountry(),
            $_POST['phone'] ?? $userData->getPhone(),
            $_POST['email'] ?? $userData->getEmail(),
            $_POST['username'] ?? $userData->getUsername(),
            !empty($_POST['newpassword']) ? password_hash($_POST['newpassword'], PASSWORD_DEFAULT) : $userData->getPasswordHash(),
            $userData->getId()
        );

        // Aktualisiere die Datenbank
        if ($userManager->updateUser($user)) {
            $successMessage = "Änderungen erfolgreich gespeichert.";
        } else {
            $errorMessages['general'] = "Fehler beim Speichern der Änderungen.";
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
            margin-bottom: 15px;
            flex-direction: column;
            border-radius: 5px;
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
            width: auto;
            padding: 5px 10px;
            background-color: #007bff;
            border: 1px solid lightgrey;
            border-radius: 5px;
            cursor: pointer;
            float: right;
            margin-right: 5px;
            margin-top: 5px;
            color: white;
            background: #d5a27c;
            font-size: 19px;
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
        <h2>Meine Daten</h2>
        <form action="account.php" method="post">
            <div class="field">
                <label for="firstname">Vorname:</label>
                <input type="text" name="firstname" value="<?php echo $userData->getFirstname() ? $userData->getFirstname() : ''; ?>">
            </div>

            <div class="field">
                <label for="lastname">Nachname:</label>
                <input type="text" name="lastname" value="<?php echo $userData->getLastname() ? $userData->getLastname(): ''; ?>">
            </div>

            <div class="field">
                <label for="address">Adresse:</label>
                <input type="text" name="address" value="<?php echo $userData->getAddress() ? $userData->getAddress(): ''; ?>">
            </div>

            <div class="field">
                <label for="postal">Postleitzahl:</label>
                <input type="text" name="postal" value="<?php echo $userData->getPostal() ? $userData->getPostal() : ''; ?>">
            </div>

            <div class="field">
                <label for="city">Stadt:</label>
                <input type="text" name="city" value="<?php echo $userData->getCity() ? $userData->getCity() : ''; ?>">
            </div>

            <div class="field">
                <label for="region">Region:</label>
                <input type="text" name="region" value="<?php echo $userData->getRegion() ? $userData->getRegion() : ''; ?>">
            </div>

            <div class="field">
                <label for="country">Land:</label>
                <input type="text" name="country" value="<?php echo $userData->getCountry() ? $userData->getCountry() : ''; ?>">
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

            <div class="field">
                <label for="username">Username:</label>
                <input type="text" name="username" value="<?php echo $userData->getUsername() ? $userData->getUsername() : ''; ?>">
                <?php if (isset($errorMessages['username'])): ?>
                        <p class="error-message"><?php echo $errorMessages['username']; ?></p>
                <?php endif; ?>
            </div>

            <div class="field">
                <label for="oldpassword">Altes Passwort:</label>
                <input type="password" name="oldpassword" value="">
                <?php if (isset($errorMessages['oldpassword'])): ?>
                        <p class="error-message"><?php echo $errorMessages['oldpassword']; ?></p>
                <?php endif; ?>
            </div>

            <div class="field">
                <label for="newpassword">Neues Passwort:</label>
                <input type="password" name="newpassword" value="">
                <?php if (isset($errorMessages['newpassword'])): ?>
                        <p class="error-message"><?php echo $errorMessages['newpassword']; ?></p>
                <?php endif; ?>
            </div>

            <div class="field">
                <label for="newpasswordconfirm">Neues Passwort bestätigen:</label>
                <input type="password" name="newpasswordconfirm" value="">
                <?php if (isset($errorMessages['newpasswordconfirm'])): ?>
                        <p class="error-message"><?php echo $errorMessages['newpasswordconfirm']; ?></p>
                <?php endif; ?>
            </div>

            <div class="field">
                <input type="submit" name="save_changes" value="Speichern"/>
            </div>
            <?php if (isset($errorMessages['general'])): ?>
                    <p class="error-message"><?php echo $errorMessages['general']; ?></p>
            <?php endif; ?>
        </form>
    </div>
</div>
</body>
</html>

<?php
echo "<tr><td>&nbsp;</td></tr>";
require_once ("../Homepage/footer.php");
?>
