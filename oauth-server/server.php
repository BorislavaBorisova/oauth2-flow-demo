<?php
    $dsn      = 'mysql:dbname=oauth_server_db;host=localhost';
    $username = 'borislava';
    $password = 'bori';

    // error reporting (this is a demo, after all!)
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    // Autoloading (composer is preferred, but for this example let's just do this)
    require_once('oauth2-server-php/src/OAuth2/Autoloader.php');
    OAuth2\Autoloader::register();

    // $dsn is the Data Source Name for the database
    $storage = new OAuth2\Storage\Pdo(array('dsn' => $dsn, 'username' => $username, 'password' => $password));

    $config = array('allow_public_clients'     => false);
    // Pass a storage object or array of storage objects to the OAuth2 server class
    $server = new OAuth2\Server($storage, $config);

    // Add the "Client Credentials" grant type (it is the simplest of the grant types)
    // $server->addGrantType(new OAuth2\GrantType\ClientCredentials($storage));

    // Add the "Authorization Code" grant type (this is where the oauth magic happens)
    $server->addGrantType(new OAuth2\GrantType\AuthorizationCode($storage));

    $defaultScope = 'read';
    $supportedScopes = array(
    'read',
    'delete'
    );

    $memory = new OAuth2\Storage\Memory(array(
    'default_scope' => $defaultScope,
    'supported_scopes' => $supportedScopes
    ));
    $scopeUtil = new OAuth2\Scope($memory);

    $server->setScopeUtil($scopeUtil);
?>