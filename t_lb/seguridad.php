<?php
session_start();
if ($_SESSION['AUTENTICADO'] != "SI"){
header("Location:index.php");
exit();
}
