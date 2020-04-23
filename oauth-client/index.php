<?php 
    if(isset($_POST['read']) || isset($_POST['read_and_delete'])){
        $connection = @mysqli_connect('localhost', 'borislava', 'bori', 'oauth_client_db');

        if(!$connection){
            echo "Connection to database failed.";
            exit;
        }

        $sql = "SELECT client_id
                FROM client_credentials";
        
        $result = @mysqli_query($connection, $sql);
        
        if(!$result){
            echo "Database maintenance. Please try again later.";
            exit;
        }
        
        $row = @mysqli_fetch_assoc($result);
        if(!isset($row['client_id'])){
            exit("You do not yet have client credentials.");
        }

        if(isset($_POST['read'])){
            header("Location: https://localhost/oauth2-flow-demo/oauth-server/index.php?response_type=code&client_id=".$row['client_id']."&state=xyz&scope=read");
        } elseif (isset($_POST['read_and_delete'])){
            header("Location: https://localhost/oauth2-flow-demo/oauth-server/index.php?response_type=code&client_id=".$row['client_id']."&state=xyz&scope=delete read");
        }
    }
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset='utf-8'>
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Client</title>
</head>

<body>
    <form method="post">
        <div class="container">
            <h3>What do you want to do?</h3>
           
            <input name="read" type="submit" value="Read my contacts">
            <input name="read_and_delete" type="submit" value="Read and Delete my contacts">
        </div>
    </form>
</body>

</html>