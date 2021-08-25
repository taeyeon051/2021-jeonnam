<?php

namespace Kty\Library;

class Format 
{
    public static function timeFormat($datetime)
    {
        return date("Y년 m월 d일 Ah:i", strtotime($datetime));
    }

    public static function dstateFormat($state)
    {
        $stateArr = array("order" => "주문 대기", "accept" => "상품 준비 중", "reject" => "주문 거절", "taking" => "배달 중", "complete" => "배달 완료");
        foreach ($stateArr as $key => $value) if ($key == $state) return $value;
    }

    public static function rstateFormat($state)
    {
        $stateArr = array("order" => "신청", "accept" => "승인", "reject" => "거절");
        foreach ($stateArr as $key => $value) if ($key == $state) return $value;
    }
}