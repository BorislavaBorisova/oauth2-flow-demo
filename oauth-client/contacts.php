<?php
    session_start();
    session_regenerate_id(true);
    
    $url = 'http://localhost/oauth2-flow-demo/oauth-server/resource.php';
    $data = array('access_token' => $_SESSION['access_token']);
    
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

    $result = file_get_contents($url, false, $context);
    if ($result === FALSE) { 
        exit("Could not fetch your contacts. Try again.");
    }
    
    $decoded = json_decode($result, true);

    echo "<table class=\"table\">";
    echo    "<thead>";
    echo        "<tr>";
    echo            "<th scope=\"col\">#</th>";
    echo            "<th scope=\"col\">Name</th>";
    echo            "<th scope=\"col\">Number</th>";
    echo            "<th scope=\"col\">Delete</th>";
    echo        "</tr>";
    echo    "</thead>";
    echo    "<tbody>";

    $counter = 1;
    foreach($decoded as $contact){
        echo "<tr>";
        echo    "<th scope=\"row\">" . $counter . "</th>";
        echo        "<td>". $contact['contact_name'] ."</td>";
        echo        "<td>". $contact['contact_number'] ."</td>";
        echo        "<td>"; 
        echo            "<form  method=\"post\" action=\"delete.php?contact_name=<?php echo \$contact['contact_name']; ?>\">"; 
        echo                "<input name=\"Submit2\" type=\"button\" class=\"button\" onclick=\"javascript:location.href='delete.php?contact_name=" . $contact['contact_name'] . "';\" value=\"Delete\" />"; 
        echo            "</form>"; 
        echo        "</td>";
       
        echo "</tr>";
        $counter = $counter + 1;
    }

    echo        "</tbody>";
    echo    "</table>";
?>


<!DOCTYPE html>
<html>

<head>
    <meta charset='utf-8'>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <title>Client</title>
</head>

<body>
    
</body>

</html>