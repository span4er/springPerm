<?php

function tryConvertToStr(string $input )
{
    try 
    {
        $int = (int) $input;
        return true;
    }
     catch (Exception $e) {
        return false;
    }
} 