<?php
header("Content-Type: application/json; charset=UTF-8");

require_once 'config.php';


// POST veya GET ile gelen verileri al
$username = isset($_POST['username']) ? $_POST['username'] : (isset($_GET['username']) ? $_GET['username'] : null);
$password = isset($_POST['password']) ? $_POST['password'] : (isset($_GET['password']) ? $_GET['password'] : null);

if (!$username || !$password) {
    die(json_encode(array("error" => "Kullanıcı adı veya şifre eksik.")));
}

$sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    echo json_encode(array(
        "success" => true,
        "user_id" => $user['id'],
        "role" => $user['role'],
        "nickname" => $user['nickname'],
        "color" => $user['color']
    ));
} else {
    echo json_encode(array("success" => false, "message" => "Geçersiz kullanıcı adı veya şifre."));
}

$conn->close();
?>
