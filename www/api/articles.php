<?php
header('Content-Type: application/json');
require_once ('../inc/config.php');
try {
    $db_educh = new PDO('mysql:host=176.31.182.222;dbname=educh', $user, $pass);
    $db_educh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Trop de connexion';
    exit;
}
require_once ('../inc/class/article.php');
require_once ('../inc/class/index.php');
require_once ('../inc/manager/articleManager.class.php');
$articleManager = new ArticleManager($db_educh);
$articles = $articleManager->getList(0,10);

foreach ($articles as $art){
$item = array('id' => $art->id(),
    'title' => utf8_encode($art->titre()),
    //'texte' => $art->texte(),
    'url' 	=> $art->url(),
	'img'	=> $art->imgurl(),
    'is_public' => true,
    'is_public_for_contacts' => false);
$items[] = $item;
}
$json = json_encode(array('items' => $items), JSON_FORCE_OBJECT);
echo $json;