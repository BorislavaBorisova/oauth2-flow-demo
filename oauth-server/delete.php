<?php
    // include our OAuth2 Server object
    require_once __DIR__.'/server.php';

    // Handle a request to a resource and authenticate the access token
    $request = OAuth2\Request::createFromGlobals();
    $response = new OAuth2\Response();
    $scopeRequired = 'delete'; 
    if (!$server->verifyResourceRequest($request, $response, $scopeRequired)) {
      // if the scope required is different from what the token allows, this will send a "401 insufficient_scope" error
      $response->send();
      die;
    }
    
    $connection = @mysqli_connect("localhost", "borislava", "bori", "oauth_server_db");

    if(!$connection){
        echo "Cannot connect to database. Try again later.";
        exit;
    }
    
    $request = OAuth2\Request::createFromGlobals();
    
    $sql_get_resource_owner_username = "SELECT user_id
                                        FROM oauth_access_tokens
                                        WHERE access_token = '" . $request->request('access_token') . "'";

    $result = mysqli_query($connection, $sql_get_resource_owner_username);

    if(!$result){
        echo "Database maintenance. Please try again later.";
        exit;
    }

    $response = @mysqli_fetch_assoc($result);
    $resource_owner_username = $response['user_id'];

	  $sql_get_contacts = "DELETE 
			                FROM contacts
			                WHERE username = '" . $resource_owner_username . "' AND contact_name = '" . $request->request('contact_name') . "'";

    mysqli_query($connection, $sql_get_contacts);

?>