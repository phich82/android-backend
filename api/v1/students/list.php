<?php
require_once(__DIR__ .'/../../../vendor/autoload.php');

use App\DB;
use App\Response;

try {
    $db = new DB();
    $sql = 'SELECT * FROM student';
    $data = $db->select($sql);
    return Response::success($data);
} catch (Exception $e) {
    return Response::error($e->getMessage(), $e->getCode());
}