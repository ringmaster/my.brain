<?php
require_once('init.php');
session_start();
$utilisateur = $db->getUtilisateur($_REQUEST['username']);								
/****************************************
          Test du mot de passe
****************************************/
if (!$utilisateur || (md5($_REQUEST['password']) != $utilisateur['password'])) {
	header("Location: login.php");
	exit();
}
$_SESSION['logged'] = true;
header("Location: index.php");
?>