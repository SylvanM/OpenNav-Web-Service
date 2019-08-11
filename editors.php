<?php

require_once("sql.php");

$pass = isset($_GET['pass']) ? $_GET['pass'] : "";
$usr =  isset($_GET['u'])    ? $_GET['u']    : "";

if ($_GET['f'] == "a") {
    addUser($usr, $pass);
} else if ($_GET['f'] == "v") {
    verify($usr, $pass);
}

// ADDING a user
function addUser($user, $pass) {
    $testSQL = "SELECT username FROM editors";
    $result = getSQLResult($testSQL, "username");
    if ($result != null) {
        die("User already exists");
    }
    $hash = hash("sha256", $pass, false);
    $sql = "INSERT INTO editors (username, hashword) VALUES (\"$user\", \"$hash\")";
    run($sql);
}

// Make sure user owns a code
function verifyOwnership($user, $code) {

    // get response
    $sql = "SELECT code_owner FROM code_owners WHERE code = \"$code\"";
    $result = getSQLResult($sql, "code_owner");
    if ($result == null) {
        $sql = "INSERT INTO code_owners (code, code_owner) VALUES (\"$code\", \"$user\")";
        run($sql);
        return true;
    } else if ($result == $user) {
        return true;
    } else {
        return false;
    }
}

// VERIFYING a user
function verify($username, $recievedPassword) {
    
    // first, does the user even exist?
    $testSQL = "SELECT username FROM editors where username = \"$username\"";
    $result = getSQLResult($testSQL, "username");
    if ($result == null) {
        echo "User does not exist";
        return false;
    }
    $recievedHash = hash("sha256", $recievedPassword, false);
    $sql = "SELECT hashword FROM editors WHERE username = \"$username\"";
    $hash = getSQLResult($sql, "hashword");
    if ($hash == $recievedHash) {
        echo "1";
        return true;
    }
    echo "0";
    return false;
}

?>