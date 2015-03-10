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
$objets = $atelierManager->getListActive(0,10);

foreach ($objets as $obj){
$item = array('id' => $obj->id(),
    'title' => utf8_encode($obj->titre()),
    //'texte' => $art->texte(),
    'url' 	=> $obj->url(),
    'is_public' => true,
    'is_public_for_contacts' => false);
$items[] = $item;
}
$json = json_encode(array('items' => $items), JSON_FORCE_OBJECT);
echo $json;