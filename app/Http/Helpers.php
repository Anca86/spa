<?php
namespace App\Http;


class Helpers
{
    public static function clean_user_input($data)
    {
        $data = trim($data);
        $data = strip_tags($data);
        return $data;
    }
}