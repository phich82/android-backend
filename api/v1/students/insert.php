<?php
require_once(__DIR__ .'/../../../vendor/autoload.php');

use App\DB;
use App\Response;
use App\Student;


$method = $_SERVER['REQUEST_METHOD'];
$request = explode("/", substr(@$_SERVER['PATH_INFO'], 1));

$_POST = json_decode(file_get_contents('php://input'), true);

// var_dump($request);

// Only method allowed as POST
if (strtoupper($method) != 'POST') {
    return Response::error('Not Allowed Method', '405');
}

if (!isset($_POST['full_name']) || empty($_POST['full_name'])) {
    return Response::error('Fullname must not be empty.');
}
if (!is_numeric($_POST['birth_year']) || !ctype_digit(strval($_POST['birth_year'])) || (int) $_POST['birth_year'] < 1) {
    return Response::error('Birth year be a positive integer.');
}
if (!isset($_POST['address']) || empty($_POST['address'])) {
    return Response::error('Address must not be empty.');
}   

try {
    $db = new DB();

    $bindings = [
        'full_name'  => $_POST['full_name'],
        'birth_year' => $_POST['birth_year'],
        'address'    => $_POST['address']
    ];

    $sql = 'INSERT INTO student(full_name, birth_year, address) VALUES (:full_name, :birth_year, :address)';
    $id = $db->prepare($sql, $bindings);

    return Response::success([ 'id' => $id ]);
} catch (Exception $e) {
    return Response::error($e->getMessage(), $e->getCode());
}