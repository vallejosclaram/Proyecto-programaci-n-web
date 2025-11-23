<?php
require_once(__DIR__ . '/../includes/globals.php');

// remove all session variables
session_unset();

// destroy the session
session_destroy();

header('Location: ' . $dirBase . '/auth/login.php');
?>