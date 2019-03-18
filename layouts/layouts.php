<?php
ini_set('display_errors', 1); 
error_reporting(E_ALL);

include_once("layoutssql.php");
include_once("../crypto/crypto.php");

// get json contents
$jsonStr  = file_get_contents("../../configuration/layoutdb_login.json");
$login    = json_decode($jsonStr, true);

$servername = strval($login['servername']);
$username   = strval($login['username']);
$password   = strval($login['password']);
$database   = strval($login['database']);

// get crypto decryption contents
$rsaJsonStr = file_get_contents("../../configuration/keys.json");
$keys = json_decode($rsaJsonStr, true);

$encryptionKey = strval($keys['public_key']);
$decryptionKey = strval($keys['private_key']);

if (function_exists($_GET['f'])) {
    $_GET['f']($_GET['code']);
}

function testCode($code) {
    $codeExists = testCodeExistance($code);
    if ($codeExists) {
        echo "1";
        return true;
    } else {
        echo "0";
        return false;
    }
}

function addImagePart($code) {
    echo "Adding image part";

    $servername = $GLOBALS['servername'];
    $username   = $GLOBALS['username'];
    $password   = $GLOBALS['password'];
    $database   = $GLOBALS['database'];

    $conn=mysqli_connect("$servername","$username","$password","$database");
    $images = mysqli_real_escape_string($conn, $_GET['part']);

    $getSQL = "SELECT images FROM layouts WHERE code = \"$code\"";

    $previousValue = getLayoutSQLResult($getSQL, "images");

    $sql = "";
    
    if ($previousValue == null) {
        echo "Null previous value";
        $sql = "UPDATE layouts SET images = \"{$images}\" WHERE code = '$code'";
    } else {
        echo "Previous value: ". $previousValue;
        $sql = "UPDATE layouts SET images = \"CONCAT(\"{$previousValue}\", \"{$images}\")\" WHERE code = '$code'";
    }

    runLayoutSQL($sql);
}

function getrooms($code) {
    $sql = "SELECT rooms FROM layouts WHERE code=\"$code\"";
    echo getLayoutSQLResult($sql, "rooms");
}

function getimages($code) {
    $sql = "SELECT images from layouts WHERE code=\"$code\"";
    echo getLayoutSQLResult($sql, "images");
}

function getlayout($code) {
    $sql = "SELECT layout from layouts WHERE code=\"$code\"";
    echo getLayoutSQLResult($sql, "layout");
}

function getinfo($code) {
    $sql = "SELECT information from layouts WHERE code=\"$code\"";
    echo getLayoutSQLResult($sql, "information");
}

function getcrypto($code) {
    include_once("../users.php");
    $userLoginDir = ("../../configuration/userdb_login.json");

    $user_id = $_GET['id'];
    // get plaintext
    $sql = "SELECT encryption from layouts WHERE code=\"$code\"";
    $cipherText = getLayoutSQLResult($sql, "encryption");
    $plaintext  = $cipherText; //decrypt("{$cipherText}", $GLOBALS['decryptionKey']);
    
    // get user key
    $key = getUserKey($user_id, $userLoginDir);

    $servername = $GLOBALS['servername'];
    $username   = $GLOBALS['username'];
    $password   = $GLOBALS['password'];
    $database   = $GLOBALS['database'];

    $conn = new mysqli("$servername","$username","$password","$database");

    $escaped = mysqli_real_escape_string($conn, $plaintext);
    $cipherText = encrypt($plaintext, $key);

    echo $cipherText;
}

// adding stuff

function addcode($code) {
    if (testCode($code)) {
        // no need to make a duplicate of the code!
        return;
    }

    $sql = "INSERT INTO layouts (code) VALUES (\"$code\")";

    $servername = $GLOBALS['servername'];
    $username   = $GLOBALS['username'];
    $password   = $GLOBALS['password'];
    $database   = $GLOBALS['database'];

    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $mysqli = new mysqli($servername, $username, $password, $database);
    $a = $mysqli->query("SELECT code FROM layouts WHERE code = '$code'");

    if ($a->num_rows > 0) {
        // layout exists
    } else {
        runLayoutSQL($sql);
    }
}

function addlayout($code) {

    $servername = $GLOBALS['servername'];
    $username   = $GLOBALS['username'];
    $password   = $GLOBALS['password'];
    $database   = $GLOBALS['database'];

    $conn=mysqli_connect("$servername","$username","$password","$database");

    $encodedLayout = $_GET['layout'];
    $escaped = mysqli_real_escape_string($conn, $encodedLayout);
    $sql = "UPDATE layouts SET layout = \"{$escaped}\" WHERE code = \"$code\"";
    runLayoutSQL($sql);
}

function addimages($code) {
    $servername = $GLOBALS['servername'];
    $username   = $GLOBALS['username'];
    $password   = $GLOBALS['password'];
    $database   = $GLOBALS['database'];

    $conn = mysqli_connect("$servername","$username","$password","$database");

    $encodedImages = $_GET['images'];
    $escaped = mysqli_real_escape_string($conn, $encodedImages);
    $sql = "UPDATE layouts SET images = \"{$escaped}\" WHERE code = \"$code\"";
    runLayoutSQL($sql);
}

function addinfo($code) {
    $servername = $GLOBALS['servername'];
    $username   = $GLOBALS['username'];
    $password   = $GLOBALS['password'];
    $database   = $GLOBALS['database'];

    $conn=mysqli_connect("$servername","$username","$password","$database");

    $encodedInfo = $_GET['info'];
    $escaped = mysqli_real_escape_string($conn, $encodedInfo);
    $sql = "UPDATE layouts SET information = \"$escaped\" WHERE code = \"$code\"";
    runLayoutSQL($sql);
}

function addrooms($code) {
    $servername = $GLOBALS['servername'];
    $username   = $GLOBALS['username'];
    $password   = $GLOBALS['password'];
    $database   = $GLOBALS['database'];

    $conn=mysqli_connect("$servername","$username","$password","$database");

    $encodedRooms = $_GET['rooms'];
    $escaped = mysqli_real_escape_string($conn, $encodedRooms);
    $sql = "UPDATE layouts SET rooms = \"{$escaped}\" WHERE code = \"$code\"";
    runLayoutSQL($sql);
}

function addcrypto($code) {
    $servername = $GLOBALS['servername'];
    $username   = $GLOBALS['username'];
    $password   = $GLOBALS['password'];
    $database   = $GLOBALS['database'];

    $conn=mysqli_connect("$servername","$username","$password","$database");

    $encodedCrypto = $_GET['crypto'];
    $escaped = mysqli_real_escape_string($conn, $encodedCrypto);
    //$encrypted = encrypt("{$escaped}", $GLOBALS['encryptionKey']);
    //$escaped = mysqli_real_escape_string($conn, $encrypted);
    $sql = "UPDATE layouts SET encryption = \"{$escaped}\" WHERE code = \"$code\"";

    runLayoutSQL($sql);
}

?>