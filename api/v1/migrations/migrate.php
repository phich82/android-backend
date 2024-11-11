<?php
require_once(__DIR__ .'/../../../vendor/autoload.php');


use App\Response;
use App\Migrations\Migration;

// Migration
//Migration::up();
//Migration::seed();

return Response::success(null, 'Migration executed successfully.');