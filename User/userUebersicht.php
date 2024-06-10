<?php
require_once ("../Homepage/header.php");
require_once ("../Datenbank/userManager.php");
require_once ("../Datenbank/userClass.php");

$um = new UserManager();
$userlist = $um->getAllUsers();
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

        .pencil-btn {
            background: url('../assets/images/penicl-removebg-preview.png') no-repeat center center;
            background-size: contain;
            border: none;
            width: 32px;
            height: 32px;
            cursor: pointer;
            background-color: transparent;
        }

        .form-box1 .table {
            width: 100%;
            border-collapse: collapse;
        }

        .form-box1 th, 
        .form-box1 td {
            border-bottom: 1px solid black;
            text-align: left;
            padding: 8px;
        }

        .form-box1 th {
            border: none;
            color: #923f0e;
        }

        .form-box1 .admin {
            width: 20%; /* Schmale Spalte */
        }
        .form-box1 .username {
            width: 45%; /* Breite Spalte */
        }
        .form-box1 .edit {
            width: 35%;
        }
    </style>
</head>
<body>
    <div class="container1">
        <div class="box1 form-box1">
            <h2>Benutzerübersicht</h2>
            <table>
                <tr>
                    <th class="admin">Admin</th>
                    <th class="u-name">Username</th>
                    <th class="u-name"></th>
                </tr>
                <?php foreach ($userlist as $user) { ?>
                                    <tr>
                                        <td><?php echo $user->getType() == 1 ? '*' : '' ?></td>
                                        <td><?php echo $user->getUsername() ?></td>
                                        <td>
                                        <form action="adminEdits.php" method="post">
                                                    <input type="hidden" name="user_to_edit" value="<?php echo $user->getUsername() ?>"/>
                                                    <input type="submit" class= pencil-btn name="submit-account" value=""/>
                                            </form>
                                        </td>
                                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>
</body>
</html>
<?php
require_once ("../Homepage/footer.php");
?>