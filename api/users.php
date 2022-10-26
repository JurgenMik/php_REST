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

} else {
    echo json_encode(
        array('message' => 'method unknown')
    );
}

?>

