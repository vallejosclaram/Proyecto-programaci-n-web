<?php
function getBaseUrl(){
    $projectFolder = '';
    $currentDir = dirname(__DIR__);
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? 'https://' : 'http://';
    $host = $_SERVER['HTTP_HOST'];
    return rtrim($protocol . $host . '/' . $projectFolder, '/') . '/';
}
define('BASE_URL', getBaseUrl());

/* Agregar <?php echo BASE_URL?> cuando se utilice una ruta  */