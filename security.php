<?php
session_start();
header('Content-Type: text/html; charset=utf-8;');
if ($_POST["security"] == "diablo3")
{
	$_SESSION["DIABLO"] = "1";
}