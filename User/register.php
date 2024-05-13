<?php
require_once("../Homepage/header.php");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">


    <style>
        *{
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }

        body{
            background: #fffbeb;
        }
        
        .container{
            margin-top: 50px;
            margin-bottom: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 90vh;
        }

        .box{
            background: #fdfdfd;
            display: flex;
            flex-direction: column;
            padding: 25px 25px;
            border-radius: 20px;
            box-shadow: 0 0 50px 0 lightgrey,
                        0 32px 64px -48px lightgrey;
        }
        
        .form-box{
            width: 500px;
            margin: 0px 10px;
        }

        .form-box h2{
            font-size: 35px;
            font-weight: 300;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }

        .form-box form .field{
            display: flex;
            margin-bottom: 10px;
            flex-direction: column;
        }

        .form-box form .input input{
            height: 35px;
            width: 100%;
            font-size: 15px;
            padding: 0 10px;
            border-radius: 5px;
            border: 1px solid lightgrey;
            outline: none;
        }

        button{
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

        button:hover{
            opacity: 0.80;
        }
      
    </style>

</head>

<body>

<div class="container">
<div class="box form-box">
<section class="signup-form">
    <h2>Sign up</h2>
    <div class="signup-form-form">
    <form action="signup.inc.php" method="post">
        <div class="field input">
            <label for="firstname">First name</label>
            <input type="text" name="firstname">
        </div>
        <div class="field input">
            <label for="lastname">Last name</label>
            <input type="text" name="lastname">
        </div>
        <div class="field input">
            <label for="adress">Adress</label>
            <input type="text" name="adress">
        </div>
        <div class="field input">
            <label for="password">City</label>
            <input type="text" name="city">
        </div>
        <div class="field input">
            <label for="password">Region</label>
            <input type="text" name="region">
        </div>
        <div class="field input">
            <label for="password">Country</label>
            <input type="text" name="country">
        </div>
        <div class="field input">
            <label for="password">Postal</label>
            <input type="text" name="postal">
        </div>
        <div class="field input">
            <label for="password">Phone</label>
            <input type="text" name="phone">
        </div>
        <div class="field input">
            <label for="password">E-Mail</label>
            <input type="text" name="email">
        </div>
        <div class="field input">
            <label for="password">Repeat E-Mail</label>
            <input type="text" name="emailrepeat">
        </div>
        <div class="field input">
            <label for="password">Username</label>
            <input type="text" name="username">
        </div>
        <div class="field input">
            <label for="password">Password</label>
            <input type="password" name="password">
        </div>
        <div class="field input">
            <label for="password">Repeat Password</label>
            <input type="password" name="passwordrepeat">
        </div>
        <button type="submit" name="submit">Sign up</button>
        <div class="link">
            Already have an account? <a href="login.php">Go to login </a>
        </div>
    </form>
    </div>
    </div>
    </div>
</section>
  
</body>
</html>

<?php
require_once("../Homepage/footer.php");
?>