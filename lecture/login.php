<?php

    $username = $email = $password = '';
    $errors = ['username'=>'', 'email'=>'','password'=>''];

    if(isset($_POST['submit'])) {
        $username = htmlspecialchars($_POST['username']);
        $password = htmlspecialchars($_POST['password']);

        //username
        if(empty(($username))) {
            $errors['username'] = 'Username cannot be empty';
        }else{
            if(!preg_match('/^[a-zA-Z\s]+$/', $username)) {
                $errors['username'] = 'Invalid username';
            }
        }

        //password
        if(empty($password)) {
            $errors['password'] = 'Password cannot be empty';
        }else{
            if(!preg_match('^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$^', $password)){
                $errors['password'] = 'invalid password';
            }
        }

        if(array_filter($errors)) {
            // echo 'errors in the userinput';
        }else{
            $con = mysqli_connect("Localhost", "root", "");
            if(!$con) {
                die('could not connect:'.mysqli_connect_error());
            }

            //create database
            $sql = "CREATE DATABASE IF NOT EXISTS myDb";

            if(!mysqli_query($con, $sql)) {
                echo "Error creating database". mysqli_connect_error();
            }

            //create table
            mysqli_select_db($con, "myDb");
            $sqlTable = "CREATE TABLE IF NOT EXISTS Patients(
                pNo int not null auto_increment, PRIMARY KEY(PNo), patientName varchar(50), patientEmail varchar(50), 
                patientPassword varchar(50) 
            )";

            //execute querry
            if(!mysqli_query($con, $sqlTable)){
                echo "Error creating table".mysqli_connect_error();
            }
            $sql = "SELECT * FROM Patients WHERE patientName='$username' AND patientPassword='$password'";
            $query = mysqli_query($con, $sql);

            if(mysqli_num_rows($query)>0) {
                header('Location:home.php');
            }else{
                $errors = ['username'=>'Wrong username or password', 'password'=>'Wrong username or password'];
            }     
        }
    }
?>

<html>
    <head>
        <title>Login</title>
        <style>
        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body{
            height: 100vh;
            width: 100%;
            font-family: sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background-size: cover;
            background-position: center;
            background-image: url(images/1.png);
            /* background-image: url('images/download (11).jpeg');  */
        }

        form{
            display: flex;
            flex-direction: column;
            justify-content: center;
            border: 3px solid #000;
            border-radius: 5px;
            padding: 20px;
            backdrop-filter: blur(20px);
        }

        h1{
            color: green;
            text-align: center;
            text-transform: uppercase;
        }

        input{
            margin: 20px 0;
            height: 30px;
            width: 250px;
        }

        label{
            color: green;
            font-weight: bold;
        }

        .btn{
            color: green;
            font-weight: bold;
            background: #000;
            border: 2px solid #000;
            transition: .5s ease;
        }

        .btn:hover{
            background: transparent;
            color: #000;
        }

        .error{
            color: red;
        }
        </style>
    </head>

    <body>
        
        <form action="login.php" method="POST">

            <h1>Login</h1>

            <label for="username">Username:</label>
            <input type="text" name="username" value="<?php echo $username; ?>">
            <p class="error"><?php echo $errors['username']; ?></p>

            <label for="password">Password:</label>
            <input type="password" name="password" value="<?php echo $password; ?>">
            <p class="error"><?php echo $errors['password']; ?></p>

            <input type="submit" name="submit" class="btn">
            <a href="index.php">Signup</a>
        </form>

    </body>
</html>