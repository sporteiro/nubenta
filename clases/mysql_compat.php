<?php
/**
 * Polyfill for PHP ext/mysql (removed in PHP 7). Maps to mysqli using $conexion.
 * Loaded after mysqli_connect from connections/conexion.php.
 */

if (!function_exists('mysql_query')) {
    function mysql_query($query, $link = null)
    {
        if ($link === null) {
            global $conexion;
            $link = $conexion;
        }
        return mysqli_query($link, $query);
    }
}

if (!function_exists('mysql_select_db')) {
    function mysql_select_db($database_name, $link = null)
    {
        if ($link === null) {
            global $conexion;
            $link = $conexion;
        }
        return mysqli_select_db($link, $database_name);
    }
}

if (!function_exists('mysql_fetch_assoc')) {
    function mysql_fetch_assoc($result)
    {
        return mysqli_fetch_assoc($result);
    }
}

if (!function_exists('mysql_num_rows')) {
    function mysql_num_rows($result)
    {
        return mysqli_num_rows($result);
    }
}

if (!function_exists('mysql_free_result')) {
    function mysql_free_result($result)
    {
        return mysqli_free_result($result);
    }
}

if (!function_exists('mysql_error')) {
    function mysql_error($link = null)
    {
        if ($link === null) {
            global $conexion;
            $link = $conexion;
        }
        return mysqli_error($link);
    }
}

if (!function_exists('mysql_real_escape_string')) {
    function mysql_real_escape_string($string, $link = null)
    {
        if ($link === null) {
            global $conexion;
            $link = $conexion;
        }
        return mysqli_real_escape_string($link, $string);
    }
}

if (!function_exists('mysql_escape_string')) {
    function mysql_escape_string($string)
    {
        global $conexion;
        return mysqli_real_escape_string($conexion, $string);
    }
}

if (!function_exists('mysql_pconnect')) {
    function mysql_pconnect($hostname, $username, $password, $new_link = false, $client_flags = 0)
    {
        global $conexion;
        return $conexion;
    }
}
