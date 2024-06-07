<?php
require_once ("../Homepage/header.php");
require_once ("../Datenbank/userManager.php");
require_once ("../Datenbank/userClass.php");
require_once ("../User/validation.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<style>
      
        .container1{
            margin-top: 50px;
            margin-bottom: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 90vh;
        }

        .box1{
            background: #fdfdfd;
            display: flex;
            flex-direction: column;
            padding: 25px 25px;
            border-radius: 20px;
            box-shadow: 0 0 50px 0 lightgrey,
                        0 32px 64px -48px lightgrey;
        }
        
        .form-box1{
            width: 500px;
            margin: 0px 10px;
        }

        .form-box1 h2{
            font-size: 35px;
            font-weight: 300;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }

        .form-box1 form .field{
            display: flex;
            margin-bottom: 10px;
            flex-direction: column;
        }

        .form-box1 form .input input{
            height: 35px;
            width: 100%;
            font-size: 15px;
            padding: 0 10px;
            border-radius: 5px;
            border: 1px solid lightgrey;
            outline: none;
        }

        .button2{
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

        .button2:hover{
            opacity: 0.80;
        }

        .error-message {
            color: red;
            margin-top: 0px;
            margin-bottom: 3px;
            font-size: 14px;
        }

        .spaced-error-message {
        margin-top: 15px;
    }
    </style>

</head>

<body>

<div class="container1">
<div class="box1 form-box1">
<section class="signup-form">
    <h2>Registrieren</h2>
    <div class="required-fields"><p>*Pflichtfelder</p></div>
    <div class="signup-form-form">
    <form action="signup.inc.php" method="post">
        <div class="field input">
            <label for="firstname">Vorname*</label>
            <input type="text" name="firstname">
        </div>
        <?php
        if (isset($_GET['error']) && isset($_GET['message'])) {
            $errors = json_decode(urldecode($_GET['message']), true);
            if (isset($errors['firstname'])) {
                $firstnameErrors = explode("\n", $errors['firstname']);
                foreach ($firstnameErrors as $error) {
                    echo '<p class="error-message">' . $error . '</p>';
                }
            }
        }
        ?>
        <div class="field input">
            <label for="lastname">Nachname*</label>
            <input type="text" name="lastname">
        </div>
        <?php
        if (isset($_GET['error']) && isset($_GET['message'])) {
            $errors = json_decode(urldecode($_GET['message']), true);
            if (isset($errors['lastname'])) {
                $lastnameErrors = explode("\n", $errors['lastname']);
                foreach ($lastnameErrors as $error) {
                    echo '<p class="error-message">' . $error . '</p>';
                }
            }
        }
        ?>
        <div class="field input">
            <label for="address">Adresse*</label>
            <input type="text" name="address">
        </div>
        <?php
        if (isset($_GET['error']) && isset($_GET['message'])) {
            $errors = json_decode(urldecode($_GET['message']), true);
            if (isset($errors['address'])) {
                $addressErrors = explode("\n", $errors['address']);
                foreach ($addressErrors as $error) {
                    echo '<p class="error-message">' . $error . '</p>';
                }
            }
        }
        ?>
        <div class="field input">
            <label for="password">Postleitzahl</label>
            <input type="text" name="postal">
        </div>
        <?php
        if (isset($_GET['error']) && isset($_GET['message'])) {
            $errors = json_decode(urldecode($_GET['message']), true);
            if (isset($errors['postal'])) {
                echo '<p class="error-message">' . $errors['postal'] . '</p>';
            }
        }
        ?>
        <div class="field input">
            <label for="password">Stadt*</label>
            <input type="text" name="city">
        </div>
        <?php
        if (isset($_GET['error']) && isset($_GET['message'])) {
            $errors = json_decode(urldecode($_GET['message']), true);
            if (isset($errors['city'])) {
                $cityErrors = explode("\n", $errors['city']);
                foreach ($cityErrors as $error) {
                    echo '<p class="error-message">' . $error . '</p>';
                }
            }
        }
        ?>
        <div class="field input">
            <label for="password">Region</label>
            <input type="text" name="region">
        </div>
        <?php
        if (isset($_GET['error']) && isset($_GET['message'])) {
            $errors = json_decode(urldecode($_GET['message']), true);
            if (isset($errors['region'])) {
                $regionErrors = explode("\n", $errors['region']);
                foreach ($regionErrors as $error) {
                    echo '<p class="error-message">' . $error . '</p>';
                }
            }
        }
        ?>
        <div class="field input">
            <label for="password">Land*</label>
            <input type="text" name="country">
        </div>
        <?php
        if (isset($_GET['error']) && isset($_GET['message'])) {
            $errors = json_decode(urldecode($_GET['message']), true);
            if (isset($errors['country'])) {
                $countryErrors = explode("\n", $errors['country']);
                foreach ($countryErrors as $error) {
                    echo '<p class="error-message">' . $error . '</p>';
                }
            }
        }
        ?>
        <div class="field input">
            <label for="password">Telefon</label>
            <input type="text" name="phone">
        </div>
        <div class="field input">
            <label for="password">E-Mail*</label>
            <input type="text" name="email">
        </div>
        <?php
        if (isset($_GET['error']) && isset($_GET['message'])) {
            $errors = json_decode(urldecode($_GET['message']), true);
            if (isset($errors['email'])) {
                echo '<p class="error-message">' . nl2br(htmlentities($errors['email'])) . '</p>';
            }
        }
        ?>
        <div class="field input">
            <label for="password">E-Mail wiederholen*</label>
            <input type="text" name="emailrepeat">
        </div>
        <?php
        if (isset($_GET['error']) && isset($_GET['message'])) {
            $errors = json_decode(urldecode($_GET['message']), true);
            if (isset($errors['emailrepeat'])) {
                echo '<p class="error-message">' . nl2br(htmlentities($errors['emailrepeat'])) . '</p>';
            }
        }
        ?>
        <div class="field input">
            <label for="password">Username*</label>
            <input type="text" name="username">
        </div>
        <?php
        if (isset($_GET['error']) && isset($_GET['message'])) {
            $errors = json_decode(urldecode($_GET['message']), true);
            if (isset($errors['username'])) {
                echo '<p class="error-message">' . nl2br(htmlentities($errors['username'])) . '</p>';
            }
        }
        ?>
        <div class="field input">
            <label for="password">Passwort*</label>
            <input type="password" name="password">
        </div>
        <?php
        if (isset($_GET['error']) && isset($_GET['message'])) {
            $errors = json_decode(urldecode($_GET['message']), true);
            if (isset($errors['password'])) {
                if (is_array($errors['password'])) {
                    $passwordErrors = implode("\n", $errors['password']);
                } else {
                    $passwordErrors = $errors['password'];
                }
                echo '<p class="error-message">' . nl2br(htmlentities($passwordErrors)) . '</p>';
            }
        }
        ?>
        <div class="field input">
            <label for="password">Passwort wiederholen*</label>
            <input type="password" name="passwordrepeat">
        </div>
        <?php
        if (isset($_GET['error']) && isset($_GET['message'])) {
            $errors = json_decode(urldecode($_GET['message']), true);
            if (isset($errors['passwordrepeat'])) {
                echo '<p class="error-message">' . nl2br(htmlentities($errors['passwordrepeat'])) . '</p>';
            }
        }
        ?>
        <?php
        if (isset($_GET['error']) && isset($_GET['message'])) {
            $errors = json_decode(urldecode($_GET['message']), true);
            if (isset($errors['general'])) {
                echo '<p class="error-message spaced-error-message">' . $errors['general'] . '</p>';
            }
        }
        ?>
        <button class="button2" type="submit" name="submit">Registrieren</button>
        <div class="link">
            Bereits registriert? <a href="login.php">Login</a>
        </div>
    </form>
    </div>
    </div>
    </div>
</section>
  
</body>
</html>

<?php
require_once ("../Homepage/footer.php");
?>

