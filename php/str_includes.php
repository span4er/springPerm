<?php

function str_includes(string $haystack, string $needle){
    return !(mb_strpos(mb_strtolower($haystack, 'UTF-8'), mb_strtolower($needle, 'UTF-8'), 0, 'UTF-8') === false);
}
