<?php

function debug(array $array) : void
{
    echo '<pre>';
    print_r($array);
    echo '</pre>';
    die;
}