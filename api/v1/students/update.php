<?php
require_once(__DIR__ .'/../../../vendor/autoload.php');

use App\DB;
use App\Response;
use App\Utils\Util;


$_POST = Util::getRequestBodyPost();

$method = $_SERVER['REQUEST_METHOD'];
$request = explode("/", substr(@$_SERVER['PATH_INFO'], 1));

//var_dump($request);

// Only method allowed as POST
if (strtoupper($method) != 'POST') {
    return Response::error('Not Allowed Method', '405');
}

if (!isset($_POST['id']) || empty($_POST['id'])) {
    return Response::error('ID must not be empty.');
}
$id = $_POST['id'];
if (!is_numeric($id) || !ctype_digit(strval($id)) || (int) $id < 1) {
    return Response::error('ID must be a positive integer.');
}
if (isset($_POST['full_name']) && empty($_POST['full_name'])) {
    return Response::error('Fullname must not be empty.');
}
if (isset($_POST['birth_year']) && empty($_POST['birth_year'])) {
    return Response::error('Birth year must not be empty.');
}
if (isset($_POST['address']) && empty($_POST['address'])) {
    return Response::error('Address must not be empty.');
}

try {
    $db = new DB();

    // Check exists of student
    $result = $db->select('SELECT * FROM student WHERE id = :id', ['id' => $id]);
    if (empty($result)) {
        return Response::error(sprintf('ID (%s) not found.', $id));
    }

    $student = $result[0];

    $bindings = [
        'id'         => $id,
        'full_name'  => isset($_POST['full_name'])  ? $_POST['full_name']  : $student['full_name'],
        'birth_year' => isset($_POST['birth_year']) ? $_POST['birth_year'] : $student['birth_year'],
        'address'    => isset($_POST['address'])    ? $_POST['address']    : $student['address'],
    ];

    $sql = 'UPDATE student SET full_name =:full_name, birth_year = :birth_year, address = :address WHERE id = :id';
    $rowCount = $db->prepare($sql, $bindings);
    if ($rowCount > 0) {
        return Response::success();
    }
    return Response::error('Unknown error occured. Please contact to administration team!');
} catch (Exception $e) {
    return Response::error($e->getMessage(), $e->getCode());
}