<?php 
    // include our OAuth2 Server object
    require_once __DIR__.'/server.php';

    session_start();
    session_regenerate_id(true);

    if(empty($_SESSION)){
        die("You are not logged in!");
    }

    $request = OAuth2\Request::createFromGlobals();
    $response = new OAuth2\Response();

    // validate the authorize request
    if (!$server->validateAuthorizeRequest($request, $response)) {
        $response->send();
        die;
    }

    // display an authorization form
    if (empty($_POST)) {
    exit('
    <form method="post">
    <label>Do You Authorize TestClient?</label><br />
    <input type="submit" name="authorized" value="Yes">
    <input type="submit" name="authorized" value="No">
    </form>');
    }

    // print the authorization code if the user has authorized your client
    $is_authorized = ($_POST['authorized'] === 'Yes');
    $server->handleAuthorizeRequest($request, $response, $is_authorized, $_SESSION['username']);
    if ($is_authorized) {
        $code = substr($response->getHttpHeader('Location'), strpos($response->getHttpHeader('Location'), 'code=')+5, 40);
    }
    $response->send();
    session_destroy();
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset='utf-8'>
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Server</title>
</head>

<body>
    
</body>

</html>