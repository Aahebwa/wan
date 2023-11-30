<?php 

    $username = $email = $password = '';
    $errors = ['username'=>'', 'email'=>'','password'=>''];

    if(isset($_POST['submit'])) {
        $username = htmlspecialchars($_POST['username']);
        $email = htmlspecialchars($_POST['email']);
        $password = htmlspecialchars($_POST['password']);

        //username
        if(empty(($username))) {
            $errors['username'] = 'Username cannot be empty';
        }else{
            if(!preg_match('/^[a-zA-Z\s]+$/', $username)) {
                $errors['username'] = 'Invalid username';
            }
        }

        //email
        if(empty($email)) {
            $errors['email'] = 'Email cannot be empty';
        }else{
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'Email is invalid';
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
            //errors in user input
        }else{
            $con = mysqli_connect("Localhost", "root", "");
            if(!$con) {
                die('could not connect'.mysqli_connect_error());
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

            //eliminating username duplicates
            $dup = "SELECT * FROM Patients WHERE patientName='$username'";
            $dupQuerry = mysqli_query($con, $dup);
            if(mysqli_num_rows($dupQuerry)>0){
                $errors['username']='Username is taken';
            }

            //eliminating email duplicates
            $dup = "SELECT * FROM Patients WHERE patientEmail='$email'";
            $dupQuerry = mysqli_query($con, $dup);
            if(mysqli_num_rows($dupQuerry)>0){
                $errors['email']='Email is taken';
            }

            if(!array_filter($errors)){
                //insert form data in the db
                $insertQuery = "INSERT INTO Patients(patientName, patientEmail, patientPassword) VALUES('$username', '$email', '$password')";

                if(!mysqli_query($con,$insertQuery)){
                    die('could not connect'.mysqli_connect_error());
                }

                header('Location:home.php');
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
        
        <form action="index.php" method="POST">

            <h1>Signup</h1>

            <label for="username">Username:</label>
            <input type="text" name="username" value="<?php echo $username; ?>">
            <p class="error"><?php echo $errors['username']; ?></p>

            <label for="email">Email:</label>
            <input type="email" name="email" value="<?php echo $email; ?>">
            <p class="error"><?php echo $errors['email']; ?></p>

            <label for="password">Password:</label>
            <input type="password" name="password" value="<?php echo $password; ?>">
            <p class="error"><?php echo $errors['password']; ?></p>

            <input type="submit" name="submit" class="btn">

            <a href="login.php">login</a>
        </form>

    </body>
</html>