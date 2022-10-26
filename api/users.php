<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Methods: GET,POST,PUT,DELETE");

connect_db();
function connect_db()
{
    global $db;

    try {
        $db = new mysqli('localhost', 'root', 'password', 'reqres_database');
    } catch (Exception $e) {
        $connection_error = mysqli_connect_error();
        header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
        die();
    }
}

$method = $_SERVER['REQUEST_METHOD'];

if ($method == 'GET') {

    global $db;

    $sql = "SELECT * FROM users";
    $result = mysqli_query($db, $sql) or die("Error in Selecting"
        . mysqli_error($db));

    $userArr = array();
    while ($row = mysqli_fetch_assoc($result))
    {
        $userArr[] = $row;
    }

    $support = array('url'=>'https://reqres.in/#support-heading',
        'text'=> 'To keep ReqRes free, contributions towards server costs are appreciated!');

    $mes = array('page'=> 1, 'per_page' => 6, 'total' => 12, 'total_pages' => 2,
        "data"=>$userArr, 'support'=>$support);

    echo json_encode($mes,JSON_UNESCAPED_SLASHES);

} elseif ($method == 'POST') {

    global $db;

    $data = file_get_contents('php://input');

    $jsonData = json_decode($data);

    $id = rand(100,500);
    $email = $jsonData->email;
    $first_name = $jsonData->first_name;
    $last_name = $jsonData->last_name;
    $avatar = $jsonData->avatar;

    $date = date('d-m-y h:i:s');

    $sql ="INSERT INTO users (id, email, first_name, last_name, avatar) VALUES
  ($id, '$email', '$first_name', '$last_name', '$avatar')";

    if (mysqli_query($db, $sql)) {

        $mes = array("id"=>$id, "email"=>$email, "first_name"=>$first_name,
            "last_name"=>$last_name, "avatar"=>$avatar, "createdAt"=>$date);

        echo json_encode($mes, JSON_UNESCAPED_SLASHES);

    } else {
        echo "Could not create a new user";
    }

} elseif ($method == 'PUT') {

    global $db;

    $id = (int)$_GET['id'];

    $data = file_get_contents('php://input');

    $jsonData = json_decode($data);

    $email = $jsonData->email;
    $first_name = $jsonData->first_name;
    $last_name = $jsonData->last_name;
    $avatar = $jsonData->avatar;

    $date = date('d-m-y h:i:s');

    $sql = "UPDATE users SET email='$email', first_name='$first_name', 
   last_name = '$last_name', avatar = '$avatar' WHERE id = '$id'";

    if (mysqli_query($db, $sql)) {

        $mes = array("email"=>$email, "first_name"=>$first_name,
            "last_name"=>$last_name, "avatar"=>$avatar, "updatedAt"=>$date);

        echo json_encode($mes, JSON_UNESCAPED_SLASHES);

    } else {
        header('HTTP/1.1 404 Not Found');
    }

} elseif ($method == 'DELETE') {

    global $db;

    $id = (int)$_GET['id'];

    $sql ="DELETE FROM users WHERE id='$id'";

    if (mysqli_query($db, $sql)) {
        http_response_code(204);
    } else {
        http_response_code(404);
    }

} else {

    global $db;

    echo json_encode(
        array('message' => 'method unknown')
    );
    mysqli_close($db);
}

?>

