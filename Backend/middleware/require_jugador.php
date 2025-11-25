<?php
require_once "require_login.php";

if ($_SESSION["user"]["rol"] != 2) {
    header("Location: ../../Frontend/no-autorizado.php");
    exit;
}
