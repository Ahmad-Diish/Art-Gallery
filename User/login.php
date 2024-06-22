<?php
require_once ("../Datenbank/userManager.php");

session_start();


$loginerr = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $identifier = trim($_POST["identifier"]);
    $password = $_POST["password"];
    $um = new UserManager();
    $user = null;


    $loginres = $um->checkLogin($identifier, $password);

    if (!isset($loginres['error'])) {

        // Erfolgreiche Anmeldung
        $_SESSION["CustomerID"] = $loginres['user']->getId();
        $_SESSION["username"] = $loginres['user']->getUsername();
        $_SESSION["UserData"] = $loginres['user'];

        header("Location: ../Homepage/index.php");
        exit();
    } else {
        $loginerr = $loginres['error'];
    }
}

?>

<?php
require_once ("../Homepage/header.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <style>

        .container1{
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

        .button1{
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

        .button1:hover{
            opacity: 0.80;
        }

        .error-message {
            color: red;
            margin-top: 0px;
            margin-bottom: 3px;
            font-size: 14px;
        }
      
    </style>
</head>

<body>


<div class="container1">
<div class="box1 form-box1">
<section class="signup-form">
    <h2>Login</h2>
    <div class="signup-form-form">
    <form action="login.php" method="post">
        <div class="field input">
            <label for="email">E-Mail/Username</label>
            <input type="text" name="identifier">
        </div>
        <div class="field input">
            <label for="password">Passwort</label>
            <input type="password" name="password">
        </div>

        <?php

        if (isset($loginerr)) {
            echo '<p class="error-message">' . htmlspecialchars($loginerr) . '</p>';
        }
        ?>

        <button class="button1" type="submit" name="submit">Login</button>
        <div class="link">
            Noch keinen Account? <a href="register.php">Registrieren</a>
        </div>
    </form>
    </div>
    </div>
    </div>
</section>
<?php
require_once ("../Homepage/footer.php");
?>    
</body>
</html>

