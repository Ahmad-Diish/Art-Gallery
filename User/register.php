<?php
require_once("header.php");
?>

<?php
require_once("../Homepage/footer.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

</head>

<body>

<section class="signup-form">
    <h2>Sign up</h2>
    <div class="signup-form-form">
    <form action="signup.inc.php" method="post">
        <label for="firstname">First name:</label>
        <input type="text" name="firstname"><br>
        <label for="lastname">Last name:</label>
        <input type="text" name="lastname"><br>
        <label for="adress">Adress:</label>
        <input type="text" name="adress"><br>
        <label for="password">City:</label>
        <input type="text" name="city"><br>
        <label for="password">Region:</label>
        <input type="text" name="region"><br>
        <label for="password">Country:</label>
        <input type="text" name="country"><br>
        <label for="password">Postal:</label>
        <input type="text" name="postal"><br>
        <label for="password">Phone:</label>
        <input type="text" name="phone"><br>
        <label for="password">E-Mail:</label>
        <input type="text" name="email"><br>
        <label for="password">Repeat E-Mail:</label>
        <input type="text" name="emailrepeat"><br>
        <label for="password">Username:</label>
        <input type="text" name="username"><br>
        <label for="password">Password:</label>
        <input type="password" name="password"><br>
        <label for="password">Repeat Password:</label>
        <input type="password" name="passwordrepeat"><br><br>
        <button type="submit" name="submit">Sign up</button>
    </form>
    </div>
</section>


    
</body>
</html>