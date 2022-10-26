<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Methods: GET,POST,PUT,DELETE");

$method = $_SERVER['REQUEST_METHOD'];

if ($method == 'GET') {

    $support = array('url'=>'https://reqres.in/#support-heading',
        'text'=> 'To keep ReqRes free, contributions towards server costs are appreciated!');

// open .con to db

    $conn = mysqli_connect("localhost","root","password","reqres_database")
    or die("Error " . mysqli_error($conn));

// fetch from db

    $sql = "SELECT * FROM users";
    $result = mysqli_query($conn, $sql) or die("Error in Selecting"
        . mysqli_error($conn));

    $userArr = array();
    while ($row = mysqli_fetch_assoc($result))
    {
        $userArr[] = $row;
    }
    $mes = array('page'=> 1, 'per_page' => 6, 'total' => 12, 'total_pages' => 2,
        "data"=>$userArr, 'support'=>$support);

    echo json_encode($mes,JSON_UNESCAPED_SLASHES);

// close db conn

    mysqli_close($conn);

} elseif ($method == 'POST') {

    $data = file_get_contents('php://input');

    $jsonData = json_decode($data);

    $id = rand(100,500);
    $email = $jsonData->email;
    $first_name = $jsonData->first_name;
    $last_name = $jsonData->last_name;
    $avatar = $jsonData->avatar;

    $date = date('d-m-y h:i:s');
    // open .con to db

    $conn = mysqli_connect("localhost","root","password","reqres_database")
    or die ("Error " . mysqli_error($conn));

    // db query
    $sql ="INSERT INTO users (id, email, first_name, last_name, avatar) VALUES
  ($id, '$email', '$first_name', '$last_name', '$avatar')";

    if (mysqli_query($conn, $sql)) {

        $mes = array("id"=>$id, "email"=>$email, "first_name"=>$first_name,
            "last_name"=>$last_name, "avatar"=>$avatar, "createdAt"=>$date);

        echo json_encode($mes, JSON_UNESCAPED_SLASHES);

    } else {
        echo "Could not create a new user";
    }

    mysqli_close($conn);

} else {
    echo json_encode(
        array('message' => 'method unknown')
    );
}

?>

