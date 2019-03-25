<?php

// get json contents of login
$jsonStr  = file_get_contents("../../configuration/layoutdb_login.json");
$login    = json_decode($jsonStr, true);

$servername = strval($login['servername']);
$username   = strval($login['username']);
$password   = strval($login['password']);
$database   = strval($login['database']);

$hsh = $_GET['h'];
$usr = $_GET['u'];

if ($_GET['f'] == "a") {
    addUser($usr, $hsh);
}

// ADDING a user
function addUser($username, $hashword) {
    $sql = "INSERT INTO editors (username, hashword) VALUES (\"$username\", \"$hashword\")";
    run($sql);
}

// VERIFYING a user
function verify($username, $recievedHash) {
    $sql = "SELECT hashword FROM editors WHERE username = \"$username\"";
    $hash = getHash($sql);
    if ($hash == $recievedHash) {
        return true;
    }
    return galse;
}

// Run an SQL command
function run($sql) {
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $a = $conn->exec($sql);
    } catch (PDOException $ex) {
        echo "Failed SQL execution: ".$ex->getMessage();
    }
}

// Get result of SQL Command
function getHash($sql) {
    // Create connection
    $conn = new mysqli($servername, $username, $password, $database);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            return $row["hashword"];
        }
    } else {
        return null;
    }
    $conn->close();
}

?>