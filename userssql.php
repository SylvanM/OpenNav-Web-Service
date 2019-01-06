<?php

// get json contents
$jsonStr    = file_get_contents("/configuration/userdb_login.json");
$login      = json_decode($jsonStr, true);

$Uservername = strval($login['servername']);
$Uusername   = strval($login['username']);
$Upassword   = strval($login['password']);
$Udatabase   = strval($login['database']);

// run the SQL command
function runSQL($sql) {
    try {
        $servername = $GLOBALS['Uservername'];
        $username   = $GLOBALS['Uusername'];
        $password   = $GLOBALS['Upassword'];
        $database   = $GLOBALS['Udatabase'];
        $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $a = $conn->exec($sql);
    } catch (PDOException $ex) {
        echo "Failed SQL execution: ".$ex->getMessage();
    }
}

function getSQLResult($sql, $request) {
    // Create connection
    $servername = $GLOBALS['Uservername'];
    $username   = $GLOBALS['Uusername'];
    $password   = $GLOBALS['Upassword'];
    $database   = $GLOBALS['Udatabase'];
    $conn = new mysqli($servername, $username, $password, $database);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $result = $conn->query($sql);

    if ($result->num_rows != 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            return $row[$request];
        }
    } else {
        return null;
    }
    $conn->close();
}

function testUserExistance($user) {
    $sql = "SELECT user_id from users WHERE user_id=\"$user\"";

    $servername = $GLOBALS['Uservername'];
    $username   = $GLOBALS['Uusername'];
    $password   = $GLOBALS['Upassword'];
    $database   = $GLOBALS['Udatabase'];

    $mysqli = new mysqli($servername, $username, $password, $database);
    $result = $mysqli->query("SELECT user_id FROM users WHERE user_id = '$user'");

    if($result->num_rows == 0) {
        return false;
    } else {
        return true;
    }
}

?>