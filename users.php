<?php

ini_set('display_errors',1); 
error_reporting(E_ALL);

include_once("userssql.php");
include_once("crypto/Crypt/RSA.php");

// get info
if (function_exists($_GET['f'])) {
    if ($_GET['f'] == "addUser") {
        addUser($_GET['id'], $_GET['key']);
    } elseif ($_GET['f'] == "removeUser") {
        removeUser($_GET['id']);
    } elseif ($_GET['f'] == "getTable") {
        getTable();
    } elseif ($_GET['f'] == "testUser") {
        testUser($_GET['id']);
    } elseif ($_GET['f'] == "updateUser") {
        updateUser($_GET['id'], $_GET['key']);
    } elseif ($_GET['f'] == "testEncryption") {
        testEncryption($_GET['plaintext'], $_GET['key']);
    }
}

function addUser($user_id, $public_key) {

    // verify that request is coming from valid source
    $signatue = $_GET['signature'];
    $encrypted = $_GET['data'];

    $rsa = new Crypt_RSA();
    $verified = $rsa->verify($encrypted, $signatue);

    /*
    if ($verified) {
        // it's fine
    } else {
        // invalid signature
        echo "invalid signature";
        return;
    }
    */

    // we're gonna ignore signatures for now :)
    $database_login = "/storage/ssd2/452/6882452/configuration/userdb_login.json";
    if (!testUser($user_id, $database_login)) {
        $sql = "INSERT INTO users (user_id, public_key) VALUES (\"$user_id\", \"$public_key\")";
        runSQL($sql, $database_login);
        echo $sql;
    } else {
        // user already exists
    }
}

function updateUser($user, $public_key) {
    $sql = "UPDATE users SET public_key = \"$public_key\" WHERE user_id = \"$user\"";
    runSQL($sql, "/storage/ssd2/452/6882452/configuration/userdb_login.json");
}

// if user exists, return 1, else, return 0
function testUser($user, $database_login) {
    $userExists = testUserExistance($user, $database_login);

    switch ($userExists) {
        case true:
            echo "1"; // user exists
            break;
        case false:
            echo "0"; // user does not exist]
            break;
    }
}

function getUserKey($user_id, $DBlogin) {
    $sql = "SELECT public_key FROM users WHERE user_id=\"$user_id\"";

    return getSQLResult($sql, "public_key", $DBlogin);
}

?>