<?php
ini_set("display_errors", "1");
error_reporting(E_ALL);

include_once("config.php");

function parseContent($string) {
    $string = (string)$string;
    $string = html_entity_decode((string)$string,ENT_COMPAT);
    $string = str_replace(array(
        '?',
        '?',
        '?',
        '[[',
        ']]',
        '&#147;', //Wordpress made ? this for some reason
    ),array(
        '&#147;',
        '&#148;',
        '&#189;',
        '&#91;&#91;',
        '&#93;&#93;',
        '?',
    ),$string);
    return $string;
}


?>
