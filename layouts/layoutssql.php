<?php

// get json contents
$jsonStr  = file_get_contents("../../configuration/layoutdb_login.json");
$login    = json_decode($jsonStr, true);

$Lservername = strval($login['servername']);
$Lusername   = strval($login['username']);
$Lpassword   = strval($login['password']);
$Ldatabase   = strval($login['database']);

// run the SQL command
function runLayoutSQL($sql) {
    try {
        $servername = $GLOBALS['Lservername'];
        $username   = $GLOBALS['Lusername'];
        $password   = $GLOBALS['Lpassword'];
        $database   = $GLOBALS['Ldatabase'];
        $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $a = $conn->exec($sql);
    } catch (PDOException $ex) {
        echo "Failed SQL execution: ".$ex->getMessage();
    }
}

function getLayoutSQLResult($sql, $request) {
    // Create connection
    $servername = $GLOBALS['Lservername'];
    $username   = $GLOBALS['Lusername'];
    $password   = $GLOBALS['Lpassword'];
    $database   = $GLOBALS['Ldatabase'];
    $conn = new mysqli($servername, $username, $password, $database);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            return $row[$request];
        }
    } else {
        return null;
    }
    $conn->close();
}

function testCodeExistance($code) {
    $sql = "SELECT code from layouts WHERE code=\"$code\"";

    $servername = $GLOBALS['Lservername'];
    $username   = $GLOBALS['Lusername'];
    $password   = $GLOBALS['Lpassword'];
    $database   = $GLOBALS['Ldatabase'];

    $mysqli = new mysqli($servername, $username, $password, $database);
    $result = $mysqli->query("SELECT code FROM layouts WHERE code = '$code'");

    if($result->num_rows > 0) {
        return true;
    } else {
        return false;
    }
}

?>