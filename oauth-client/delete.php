<?php  
    session_start();
    session_regenerate_id(true);


    $url = 'http://localhost/oauth2-flow-demo/oauth-server/delete.php';
    $data = array('access_token' => $_SESSION['access_token'], 'contact_name' => $_GET['contact_name']);

    $options = array(
        'http' => array(
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data)
        ),
        "ssl"=>array(
            "verify_peer"=>false,
            "verify_peer_name"=>false,
        ),
    );

    $context  = stream_context_create($options); 

    $result = @file_get_contents($url, false, $context);
    if ($result === FALSE) { 
        echo ("You did not give permission to delete contacts.");

        echo   "<form  method=\"post\" action=\"contacts.php\">"; 
        echo       "<input name=\"Submit2\" type=\"button\" class=\"button\" onclick=\"javascript:location.href='contacts.php'\" value=\"Back to Contacts\" />"; 
        echo   "</form>"; 
    } else {
        header("Location: https://localhost/oauth2-flow-demo/oauth-client/contacts.php");
    }
    
?>