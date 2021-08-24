<?php

namespace Kty\Library;

class TimeFormat 
{
    public static function format($datetime)
    {
        return date("Y년 m월 d일 Ah:i", strtotime($datetime));
    }
}