<?php 
    $url = 'https://localhost/oauth2-flow-demo/oauth-server/token.php';
    $data = array('code' => $_GET['code'], 'grant_type' => 'authorization_code');
    
    $connection = @mysqli_connect('localhost', 'borislava', 'bori', 'oauth_client_db');

    if(!$connection){
        echo "Cannot connect to database. Try again later.";
        exit;
    }

    $sql = "SELECT client_id, client_secret
            FROM client_credentials";
    
    $result = @mysqli_query($connection, $sql);
    
    if(!$result){
        echo "Database maintenance. Please try again later.";
        exit;
    }
    
    $row = @mysqli_fetch_assoc($result);
    if(!isset($row['client_id']) || !isset($row['client_secret'])){
        exit("You do not yet have one or both of your client credentials.");
    }

    $encoded_credentials = base64_encode($row['client_id'].":".$row['client_secret']);
    
    $options = array(
        'http' => array(
            'header'  => "Content-type: application/x-www-form-urlencoded\r\nAuthorization: Basic ".$encoded_credentials."\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data)
        ),
        "ssl"=>array(
            "verify_peer"=>false,
            "verify_peer_name"=>false,
        ),
    );

    $context  = stream_context_create($options); 

    $result = file_get_contents($url, false, $context);
    if ($result === FALSE) { 
        exit("Could not fetch your token. Try again.");
    }

    $decoded = json_decode($result, true);

    session_start();
    session_regenerate_id(true);

    $_SESSION['access_token'] = $decoded['access_token'];

    header("Location: https://localhost/oauth2-flow-demo/oauth-client/contacts.php");
?>