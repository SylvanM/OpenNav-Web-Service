<?php

// Used for all functions managine editors

// run an SQL command
function runSQL($sql, $DBlogin) {

    // get json contents
    $jsonStr    = file_get_contents($DBlogin);
    $login      = json_decode($jsonStr, true);

    $servername = strval($login['servername']);
    $username   = strval($login['username']);
    $password   = strval($login['password']);
    $database   = strval($login['database']);

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $a = $conn->exec($sql);
    } catch (PDOException $ex) {
        echo "Failed SQL execution: ".$ex->getMessage();
    }
}

// get result of an SQL query
function getSQLResult($sql, $request, $DBlogin) {
    $jsonStr    = file_get_contents($DBlogin);
    $login      = json_decode($jsonStr, true);

    $servername = strval($login['servername']);
    $username   = strval($login['username']);
    $password   = strval($login['password']);
    $database   = strval($login['database']);
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

// returns TRUE if an editor exists, and FALSE if otherwise
function testEditorExistance($editor, $DBlogin) {

    // get json contents
    $jsonStr    = file_get_contents($DBlogin);
    $login      = json_decode($jsonStr, true);

    $servername = strval($login['servername']);
    $username   = strval($login['username']);
    $password   = strval($login['password']);
    $database   = strval($login['database']);

    $sql = "SELECT user_id from users WHERE user_id=\"$user\"";

    $mysqli = new mysqli($servername, $username, $password, $database);
    $result = $mysqli->query("SELECT editorname FROM editors WHERE editorname = '$editor'");

    if($result->num_rows == 0) {
        return false;
    } else {
        return true;
    }
}

?>