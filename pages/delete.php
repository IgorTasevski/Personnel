<?php

use App\Database\DB;
use App\Helper\Helper as Helper;

session_start();

require_once __DIR__ . "/../database/DB.php";
require_once __DIR__ . "/../helpers/Helper.php";

$sqlDelete = "DELETE FROM calls WHERE id = :id";

$connection = DB::connect();
$sqlDelete .= " LIMIT 1";
$stmtDelete = $connection->prepare($sqlDelete);

if ($stmtDelete->execute(['id' => $_GET['id']])) {
    $_SESSION['delete_success'] = true;
    Helper::redirect("index.php");
    die();
} else {
    $_SESSION['delete_error'] = true;
    Helper::redirect("index.php");
    die();
}

Helper::redirect("index.php");