<?php
header('Content-Type: application/json');
require_once ('../inc/config.php');
try {
$db_educh = new PDO('mysql:host=localhost;dbname=educh', $user, $pass);
	$db_educh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
	echo 'Trop de connexion';
	exit;
}
require_once ('../inc/class/atelier.php');
require_once ('../inc/class/index.php');
require_once ('../inc/manager/atelierManager.class.php');
$atelierManager = new AtelierManager($db_educh);
$objet = $atelierManager->getById($_REQUEST['id']);

$item = array('id' => $objet->id(),
    'title' => utf8_encode($objet->titre()),
    'texte' => $objet->description(),
    'url' 	=> 'formation-coaching/'.$objet->url(),
	'label_link' => 'Inscription et information',
    'is_public' => true,
    'is_public_for_contacts' => false);

$json = json_encode(array('item' => $item), JSON_FORCE_OBJECT);
echo $json;