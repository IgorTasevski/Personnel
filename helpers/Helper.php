<?php

namespace App\Helper;

require_once __DIR__ . "/../config/consts.php";

class Helper
{     
    public static function redirect($route)
    {
        header("Location: " . APP_URL . "$route");
        die();
    }    
}