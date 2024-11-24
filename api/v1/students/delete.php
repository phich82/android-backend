<?php
require_once(__DIR__ .'/../../../vendor/autoload.php');

use App\DB;
use App\Response;
use App\Utils\Util;


$_POST = Util::getRequestBodyPost();


// Only method allowed as POST
if (strtoupper($_SERVER['REQUEST_METHOD']) != 'POST') {
    return Response::error('Not Allowed Method', '405');
}

if (!isset($_POST['id']) || empty($_POST['id'])) {
    return Response::error('ID must not be empty.');
}
$id = $_POST['id'];
if (!is_numeric($id) || !ctype_digit(strval($id)) || (int) $id < 1) {
    return Response::error('ID must be a positive integer.');
}

try {
    $db = new DB();

    // Check exists of student
    $result = $db->select('SELECT * FROM student WHERE id = :id', ['id' => $id]);
    if (empty($result)) {
        return Response::error(sprintf('ID (%s) not found.', $id));
    }

    $student = $result[0];

    $bindings = ['id' => $id];

    $sql = 'DELETE FROM student WHERE id = :id';
    $rowCount = $db->prepare($sql, $bindings);
    if ($rowCount > 0) {
        return Response::success();
    }
    return Response::error('Unknown error occured. Please contact to administration team!');
} catch (Exception $e) {
    return Response::error($e->getMessage(), $e->getCode());
}