<?php

function getFormatDate(string $comment) : string
{
    $timestamp = strtotime($comment);
    $date = date('d/m/Y h:i', $timestamp);
    return $date;
}

function debug(array $array) : void
{
    echo '<pre>';
    print_r($array);
    echo '</pre>';
    die;
}