<?php
namespace App\Helpers;

class LinkHelper
{
    public static function generateLinkString()
    {
        return bin2hex(random_bytes(5));
    }
}