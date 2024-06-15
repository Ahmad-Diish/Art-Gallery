<?php
ob_start();

require_once ("../Homepage/header.php");
require_once ("../Datenbank/userManager.php");
require_once ("../Datenbank/userClass.php");

// Starten der Session, falls noch nicht geschehen
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Überprüfung, ob der Benutzer eingeloggt und ein Administrator ist
if (!isset($_SESSION['UserData']) || $_SESSION['UserData']->getType() != 1) {
    header("Location: ../Homepage/index.php");
    exit();
}

$um = new UserManager();
$userlist = $um->getAllUsers();
$activeUsers = [];
$inactiveUsers = [];

// Toggle user type if requested
if (isset($_POST['toggle_admin'])) {
    $username = $_POST['user_to_toggle'];
    $user = $um->getUserByUsername($username);
    if ($user && ($um->canDemoteAdmin($user->getId()) || $user->getType() == 0)) {
        $um->toggleUserType($username);
        // Aktualisiere nur die Session-Daten, wenn der aktuelle Benutzer betroffen ist
        if ($username == $_SESSION['UserData']->getUsername()) {
            $_SESSION['UserData']->setType($user->getType() == 1 ? 0 : 1);
        }
        header("Location: userUebersicht.php");
        exit;
    } else {
        // Fehlerbehandlung, falls der letzte Admin nicht degradiert werden kann
        echo "Der letzte Administrator kann nicht degradiert werden.";
    }
}

// Toggle user active status if requested
if (isset($_POST['toggle_status'])) {
    $username = $_POST['user_to_toggle_status'];
    $user = $um->getUserByUsername($username);
    $new_status = $_POST['toggle_status'];
    if ($new_status == '1') {
        $um->activateUser($username);
    } else {
        if ($user && $user->getType() == 1 && !$um->canDemoteAdmin($user->getId())) {
            echo "Der letzte Administrator kann nicht deaktiviert werden.";
        } else {
            $um->deactivateUser($username);
        }
    }
    // Aktualisiere nur die Session-Daten, wenn der aktuelle Benutzer betroffen ist
    if ($username == $_SESSION['UserData']->getUsername()) {
        $_SESSION['UserData']->setState($new_status);
    }
    header("Location: userUebersicht.php");
    exit;
}

foreach ($userlist as $user) {
    if ($user->getState()) {
        $activeUsers[] = $user;
    } else {
        $inactiveUsers[] = $user;
    }
}

ob_end_flush();
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            color: #923f0e; /* Farbe des Titels */
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
            font-size: 14px.
        }

        .form-box1 form .spaced-error-message {
            margin-top: 10px;
        }

        .pencil-btn {
            background: url('../assets/images/pencil-removebg-preview.png') no-repeat center center;
            background-size: contain;
            border: none;
            width: 32px;
            height: 32px;
            cursor: pointer;
            background-color: transparent;
            vertical-align: middle;
            margin-right: -30px;
        }

        .star-btn {
            background: url('../assets/images/star.png') no-repeat center center;
            background-size: contain;
            border: none;
            width: 32px;
            height: 32px;
            cursor: pointer;
            background-color: transparent;
            margin-top: 10px;
        }

        .star-btn.empty {
            background: none;
        }

        .status-icon {
            width: 27px;
            height: 25px;
            cursor: pointer;
            border: none;
            background-color: transparent;
            vertical-align: middle;
        }

        .form-box1 .table {
            width: 100%;
            border-collapse: collapse;
        }

        .form-box1 th, 
        .form-box1 td {
            border-bottom: 1px solid lightgrey; /* Dünne Linien für die Zeilen */
            text-align: left;
            padding: 8px;
            position: relative; /* Notwendig für ::after */
            vertical-align: middle; /* Mittige Ausrichtung */
        }

        .form-box1 th {
            border: none;
            color: #923f0e; /* Gleiche Farbe wie der Titel */
            position: relative;
            cursor: default; /* Verhindert, dass die Überschrift wie ein Link aussieht */
        }

        .form-box1 th::after {
            content: '';
            display: block;
            width: 100%;
            height: 5px; /* Dicke des Strichs */
            background-color: #923f0e; /* Farbe des Strichs */
            position: absolute;
            bottom: 0; /* Abstand zwischen dem Strich und dem Text */
            left: 0;
        }

        .form-box1 .admin,
        .form-box1 .edit {
            width: 20%;
            text-align: center;
        }

        .form-box1 .status {
            width: 5%;
            text-align: center;
        }

        .form-box1 .username {
            width: 55%; /* Breite Spalte */
        }

        .form-box1 .admin form,
        .form-box1 .edit form,
        .form-box1 .status form {
            display: inline-block;
        }

        .form-box1 .admin form input,
        .form-box1 .edit form input,
        .form-box1 .status form input {
            display: inline-block;
            vertical-align: middle;
        }

        .inactive-users {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container1">
        <div class="box1 form-box1">
            <h2>Benutzerübersicht</h2>
            <table class="table">
                <tr>
                    <th class="admin">Admin</th>
                    <th class="u-name">Username</th>
                    <th class="edit"></th>
                    <th class="status">Status</th>
                </tr>
                <?php foreach ($activeUsers as $user) { ?>
                    <tr>
                        <td class="admin">
                            <form action="userUebersicht.php" method="post">
                                <input type="hidden" name="user_to_toggle" value="<?php echo htmlspecialchars($user->getUsername(), ENT_QUOTES, 'UTF-8'); ?>"/>
                                <input type="submit" class="star-btn <?php echo $user->getType() == 1 ? '' : 'empty'; ?>" name="toggle_admin" value=""/>
                            </form>
                        </td>
                        <td class="u2-name"><?php echo $user->getUsername(); ?></td>
                        <td class="edit">
                            <form action="adminEdits.php" method="post">
                                <input type="hidden" name="user_to_edit" value="<?php echo htmlspecialchars($user->getUsername(), ENT_QUOTES, 'UTF-8'); ?>"/>
                                <input type="submit" class="pencil-btn" name="submit-account" value=""/>
                            </form>
                        </td>
                        <td class="status">
                            <form action="userUebersicht.php" method="post">
                                <input type="hidden" name="user_to_toggle_status" value="<?php echo htmlspecialchars($user->getUsername(), ENT_QUOTES, 'UTF-8'); ?>"/>
                                <input type="hidden" name="toggle_status" value="<?php echo $user->getState() ? '0' : '1'; ?>"/>
                                <input type="image" src="../assets/images/<?php echo $user->getState() ? 'active.png' : 'passive.png'; ?>" class="status-icon" alt="Status Icon" />
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </table>
            <div class="inactive-users">
                <h2>Deaktivierte Benutzer</h2>
                <details>
                    <summary>Liste der deaktivierten Benutzer anzeigen</summary>
                    <table class="table">
                        <tr>
                            <th class="admin">Admin</th>
                            <th class="u-name">Username</th>
                            <th class="edit"></th>
                            <th class="status">Status</th>
                        </tr>
                        <?php foreach ($inactiveUsers as $user) { ?>
                            <tr>
                                <td class="admin">
                                    <form action="userUebersicht.php" method="post">
                                        <input type="hidden" name="user_to_toggle" value="<?php echo htmlspecialchars($user->getUsername(), ENT_QUOTES, 'UTF-8'); ?>"/>
                                        <input type="submit" class="star-btn <?php echo $user->getType() == 1 ? '' : 'empty'; ?>" name="toggle_admin" value=""/>
                                    </form>
                                </td>
                                <td class="u2-name"><?php echo $user->getUsername(); ?></td>
                                <td class="edit">
                                    <form action="adminEdits.php" method="post">
                                        <input type="hidden" name="user_to_edit" value="<?php echo htmlspecialchars($user->getUsername(), ENT_QUOTES, 'UTF-8'); ?>"/>
                                        <input type="submit" class="pencil-btn" name="submit-account" value=""/>
                                    </form>
                                </td>
                                <td class="status">
                                    <form action="userUebersicht.php" method="post">
                                        <input type="hidden" name="user_to_toggle_status" value="<?php echo htmlspecialchars($user->getUsername(), ENT_QUOTES, 'UTF-8'); ?>"/>
                                        <input type="hidden" name="toggle_status" value="<?php echo $user->getState() ? '0' : '1'; ?>"/>
                                        <input type="image" src="../assets/images/<?php echo $user->getState() ? 'active.png' : 'passive.png'; ?>" class="status-icon" alt="Status Icon" />
                                    </form>
                                </td>
                            </tr>
                        <?php } ?>
                    </table>
                </details>
            </div>
        </div>
    </div>
</body>
</html>
<?php
require_once ("../Homepage/footer.php");
?>
