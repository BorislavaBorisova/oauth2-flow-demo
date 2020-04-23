<?php 
    
    $error = "";
    if(isset($_POST['submit'])){
        $connection = @mysqli_connect('localhost', 'borislava', 'bori', 'oauth_server_db');

        if(!$connection){
            echo "Cannot connect to database. Try again later.";
            exit;
        }

        $username = $_POST['username'];
        $password = $_POST['psw'];
        
        if(!preg_match("/^[a-zA-Z0-9]{0,80}$/", $username)){
            exit("Invalid username. The username can contain only letters and numbers.");
        }

        $query = "SELECT username,password
                    FROM oauth_users
                    WHERE username = '".$username."'";
        
        $result = @mysqli_query($connection, $query);
        if(!$result){
            echo "Database maintenance. Please try again later.";
            exit;
        }
        
        $row = @mysqli_fetch_assoc($result);

        //exit($row['password']. "***********".password_verify($password, $row['password']));
        if(!isset($row['username']) || !password_verify($password, $row['password'])){
            echo "Invalid username OR password";
            exit;
        }
        
        session_start();
        session_regenerate_id(true);

        $_SESSION['username'] = $_POST['username'];

        $error = "";
        
        header("Location: ./authorize.php?response_type=".$_GET['response_type']."&client_id=".$_GET['client_id']."&state=".$_GET['state']."&scope=".$_GET['scope']);
        exit();
        
        mysqli_close($connection);
    }
        
    ?>

<!DOCTYPE html>
<html>

<head>
    <meta charset='utf-8'>
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Server</title>
</head>

<body>
    <form method="post">
        <div class="container">
            <label for="username"><b>Username</b></label>
            <input type="text" value="" placeholder="Enter Username" name="username" required>
    
            <label for="psw"><b>Password</b></label>
            <input type="password" value="" placeholder="Enter Password" name="psw" required>
           
            <input name="submit" type="submit" value="Login">
            <p class="error"><?php echo $error; ?></p>
        </div>
    </form>
</body>

</html>