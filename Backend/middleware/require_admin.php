<?php
require_once "require_login.php";

if ($_SESSION["user"]["rol"] != 1) {
    header("Location: ../../Frontend/no-autorizado.php");
    exit;
}
