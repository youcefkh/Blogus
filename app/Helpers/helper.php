<?php

namespace App\Helpers;

class Helper
{

    public static function highlightKeyword(string $text, string $keyword)
    {
        $textArray = preg_split('/[.|!|?]/', strip_tags($text));
        $resultArray = array_filter($textArray, function ($item) use ($keyword) {
            return str_contains(strtolower($item), strtolower($keyword));
        });

        $firstValue = reset($resultArray);
        $firstKey = key($resultArray);
        $lastKey = array_key_last($textArray);

        if(empty($firstValue)) {
            return mb_strimwidth(strip_tags($text), 0, 200, '...');
        }

        if($firstKey != 0) {
            $firstValue = "...".$firstValue;
        }
        if($lastKey > $firstKey) {
            $firstValue = $firstValue."...";
        }
        return str_ireplace(
            $keyword,
            "<span class='bg-yellow-400'>" . $keyword . '</span>',
            $firstValue
        );
    }
}
