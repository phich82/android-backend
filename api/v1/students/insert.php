<?php
require_once(__DIR__ .'/../../../vendor/autoload.php');

use App\DB;
use App\Response;
use App\Student;

if (true) { 
    return Response::error('Not Allowed Method', '405');
}

$method = $_SERVER['REQUEST_METHOD'];
$request = explode("/", substr(@$_SERVER['PATH_INFO'], 1));

// var_dump($request);

// Only method allowed as POST
if (strtoupper($method) != 'POST') {
    echo Response::error('Not Allowed Method', '405');
    //return;
}

if (!isset($_POST['full_name']) || empty($_POST['full_name'])) {
    echo Response::error('Fullname must not be empty.');
    //return;
}
if (!isset($_POST['birth_year']) || empty($_POST['birth_year'])) {
    echo Response::error('Birth year must not be empty.');
    //return;
}
if (!isset($_POST['address']) || empty($_POST['address'])) {
    echo Response::error('Address must not be empty.');
    //return;
}   


$bindings = [
    'full_name'  => $_POST['full_name'],
    'birth_year' => $_POST['birth_year'],
    'address'    => $_POST['address']
];

$db = new DB();

// $sql = 'INSERT INTO student(full_name, birth_year, address) VALUES (:full_name, :birth_year, :address)';
// $data = $db->prepare($sql, $bindings);

// echo json_encode($data);