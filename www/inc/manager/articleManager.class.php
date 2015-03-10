<?php
class ArticleManager {
	private $_db;
	
	public function __construct($db)
	{
		$this->setDb($db);
	}
	public function getPreview($id){
		try {
			$q = $this->_db->prepare('Select id_new as id from tbl_new where id_new > :id and vl_valider = 1 Order by id_new ASC Limit 0,1');
			$q->bindParam('id', $id, PDO::PARAM_INT);
			$q->execute();
			$donnees = $q->fetch(PDO::FETCH_ASSOC);
			$q->closeCursor();
			if (isset($donnees['id'])) {
				return $donnees['id'];
			} else {
				return false;
			}
		} catch (PDOException $e) {
			print "Error!: " . $e->getMessage() . "<br/>";
			die();
		}
	}
	public function getNextone($id){
		try {
			$q = $this->_db->prepare('Select id_new as id from tbl_new where id_new < :id and vl_valider = 1  Order by id_new DESC Limit 0,1');
			$q->bindParam('id', $id, PDO::PARAM_INT);
			$q->execute();
			$donnees = $q->fetch(PDO::FETCH_ASSOC);
			$q->closeCursor();
			if (isset($donnees['id'])) {
				return $donnees['id'];
			} else {
				return false;
			}
		} catch (PDOException $e) {
			print "Error!: " . $e->getMessage() . "<br/>";
			die();
		}
	}
	public function getById($id){
		//var_dump($id);
		try {
			$q = $this->_db->prepare('Select tbl_new.id_new as id, txt_titre as titre, txt_texte as texte, date, vl_valider as valider, tbl_new.id_categorie as idcategorie, iddomaine, www, tbl_new.num_click as view, url_img as img, statut, notonhome, tbl_groupes.dossier as dossier from tbl_new, tbl_groupes, tbl_categorie where tbl_new.id_new = :id AND tbl_new.ID_categorie = tbl_categorie.ID_categorie AND tbl_categorie.id_groupe = tbl_groupes.id_groupe Limit 0,1');
			$q->bindParam('id', $id, PDO::PARAM_INT);
			$q->execute();
			$donnees = $q->fetch(PDO::FETCH_ASSOC);
			$q->closeCursor();
			//var_dump($donnees);
			if (isset($donnees) && is_array($donnees)) {
				$article = new Article($donnees);
				return $article;
			} else {
				return new Article();
			}
		} catch (PDOException $e) {
			print "Error!: " . $e->getMessage() . "<br/>";
			die();
		}
	}
	public function getViewId() {
		try {
			$q = $this->_db->prepare('Select id_new as id from tbl_new where statut = 1');
			$q->execute();
			$donnees = $q->fetch(PDO::FETCH_ASSOC);
			//var_dump($donnees);
			$q->closeCursor();
			if (isset($donnees)) {
				return $donnees['id'];
			} else {
				return 0;
			}
		} catch (PDOException $e) {
			print "Error!: " . $e->getMessage() . "<br/>";
			die();
		}
	}
	function getByIdCategorie($idcategorie,$idarticle,$intlimit) {
		$articles = array();
		try {
			$q = $this->_db->prepare('Select txt_titre as titre, txt_texte as texte, notonhome, ID_New as id, tbl_groupes.dossier as dossier, tbl_new.date, tbl_new.www,tbl_new.ID_categorie as idcategorie, iddomaine From tbl_new, tbl_groupes, tbl_categorie Where ID_New < :idarticle and tbl_new.ID_categorie = :idcategorie AND tbl_new.ID_categorie = tbl_categorie.ID_categorie AND tbl_categorie.id_groupe = tbl_groupes.id_groupe Order by tbl_new.Num_Click Desc limit 0, :intlimit');
			$q->bindParam('idarticle', $idarticle, PDO::PARAM_INT);
			$q->bindParam('intlimit', $intlimit, PDO::PARAM_INT);
			$q->bindParam('idcategorie', $idcategorie, PDO::PARAM_INT);
			$q->execute();
			$donnees = $q->fetchAll();
			$q->closeCursor();
			foreach ($donnees as $row) { $articles[] = new Article($row); }
			return $articles;
		} catch (PDOException $e) {
			print "Error!: " . $e->getMessage() . "<br/>";
			die();
		}
	}
	function getByIdCategorie2($idcategorie, $intlimit) {
		$articles = array();
		try {
			$q = $this->_db->prepare('Select txt_titre as titre, txt_texte as texte, tbl_new.notonhome, ID_New as id, tbl_groupes.dossier as dossier, tbl_new.date, tbl_new.www,tbl_new.ID_categorie as idcategorie From tbl_new, tbl_groupes, tbl_categorie Where tbl_new.ID_categorie = :idcategorie AND tbl_new.ID_categorie = tbl_categorie.ID_categorie AND tbl_categorie.id_groupe = tbl_groupes.id_groupe Order by tbl_new.Num_Click Desc limit 0, :intlimit');
			$q->bindParam('intlimit', $intlimit, PDO::PARAM_INT);
			$q->bindParam('idcategorie', $idcategorie, PDO::PARAM_INT);
			$q->execute();
			$donnees = $q->fetchAll();
			$q->closeCursor();
			foreach ($donnees as $row) { $articles[] = new Article($row); }
			return $articles;
		} catch (PDOException $e) {
			print "Error!: " . $e->getMessage() . "<br/>";
			die();
		}
	}
	function getByIdGroupe($idgroupe,$intstart,$intlimit) {
		$articles = array();
		try {
			$q = $this->_db->prepare('SELECT tbl_new.ID_New as id, tbl_new.url_img as img, tbl_new.txt_texte as texte, tbl_new.notonhome, tbl_new.txt_titre as titre, tbl_categorie.ID_categorie as idcategorie, tbl_categorie.txt_nom as nom, tbl_groupes.dossier as dossier FROM tbl_new, tbl_groupes, tbl_categorie WHERE tbl_new.Vl_Valider = 1 AND tbl_categorie.ID_categorie = tbl_new.ID_categorie AND tbl_categorie.id_groupe = :idgroupe AND tbl_new.ID_categorie = tbl_categorie.ID_categorie AND tbl_categorie.id_groupe = tbl_groupes.id_groupe Order by tbl_new.Num_Click Desc limit :intstart,:intlimit');
			$q->bindParam('intstart', $intstart, PDO::PARAM_INT);
			$q->bindParam('intlimit', $intlimit, PDO::PARAM_INT);
			$q->bindParam('idgroupe', $idgroupe, PDO::PARAM_INT);
			$q->execute();
			$donnees = $q->fetchAll();
			$q->closeCursor();
			foreach ($donnees as $row) { $articles[] = new Article($row); }
			return $articles;
		} catch (PDOException $e) {
			print "Error!: " . $e->getMessage() . "<br/>";
			die();
		}
	}
	function getByIdGroupeLast($idgroupe,$intstart,$intlimit) {
		$articles = array();
		try {
			$q = $this->_db->prepare('SELECT tbl_new.ID_New as id, tbl_new.url_img as img, tbl_new.txt_texte as texte, tbl_new.notonhome, tbl_new.txt_titre as titre, tbl_categorie.ID_categorie as idcategorie, tbl_categorie.txt_nom as nom, tbl_groupes.dossier as dossier FROM tbl_new, tbl_groupes, tbl_categorie WHERE tbl_new.Vl_Valider = 1 AND tbl_categorie.ID_categorie = tbl_new.ID_categorie AND tbl_categorie.id_groupe = :idgroupe AND tbl_new.ID_categorie = tbl_categorie.ID_categorie AND tbl_categorie.id_groupe = tbl_groupes.id_groupe Order by tbl_new.ID_New Desc limit :intstart,:intlimit');
			$q->bindParam('intstart', $intstart, PDO::PARAM_INT);
			$q->bindParam('intlimit', $intlimit, PDO::PARAM_INT);
			$q->bindParam('idgroupe', $idgroupe, PDO::PARAM_INT);
			$q->execute();
			$donnees = $q->fetchAll();
			$q->closeCursor();
			foreach ($donnees as $row) { $articles[] = new Article($row); }
			return $articles;
		} catch (PDOException $e) {
			print "Error!: " . $e->getMessage() . "<br/>";
			die();
		}
	}
	public function getArticle($id) {
		try {
			$q = $this->_db->prepare("Select tbl_new.txt_titre as titre, tbl_new.txt_texte as texte, tbl_new.notonhome,tbl_new.ID_New as id,tbl_new.url_img as img, tbl_new.ID_categorie as idcategorie, tbl_new.date, tbl_new.www, tbl_groupes.dossier
				From tbl_new, tbl_categorie, tbl_groupes
				where Vl_Valider = '1' AND ID_New = :id AND tbl_new.ID_categorie = tbl_categorie.ID_categorie AND tbl_categorie.id_groupe = tbl_groupes.id_groupe
				Order by ID_New Desc Limit 0,1");
			$q->bindParam('id', $id, PDO::PARAM_INT);
			$q->execute();
			$donnees = $q->fetch(PDO::FETCH_ASSOC);
			//var_dump($donnees);
			$q->closeCursor();
			if (isset($donnees) && is_array($donnees)) {
				$article = new Article($donnees);
				return $article;
			} else {
				return new Article();
			}
		} catch (PDOException $e) {
			print "Error!: " . $e->getMessage() . "<br/>";
			die();
		}
	}
	public static function getListArticle($condition, $limit) {
		$sql = "Select txt_titre as titre, txt_texte as texte, notonhome, ID_New as id, url_img as img, tbl_groupes.dossier as dossier From tbl_new, tbl_groupes, tbl_categorie
				Where ".$condition." AND tbl_new.ID_categorie = tbl_categorie.ID_categorie AND tbl_categorie.id_groupe = tbl_groupes.id_groupe
				Order by ID_New Desc ".$limit.";";
		include 'includes-php/conn-inc.php';
		return $req;
	}
	public function getList($intstart, $intlimit) {
		$articles = array();
		try {
			$q = $this->_db->prepare('Select txt_titre as titre, ID_New as id, txt_texte as texte, notonhome, url_img as img, tbl_groupes.dossier as dossier, vl_valider as valider, date, statut From tbl_new, tbl_groupes, tbl_categorie Where Vl_Valider = 1 AND tbl_new.ID_categorie = tbl_categorie.ID_categorie AND tbl_categorie.id_groupe = tbl_groupes.id_groupe AND tbl_new.notonhome = 0
				Order by ID_New Desc limit :intstart,:intlimit');
			$q->bindParam('intstart', $intstart, PDO::PARAM_INT);
			$q->bindParam('intlimit', $intlimit, PDO::PARAM_INT);
			$q->execute();
			$donnees = $q->fetchAll();
			$q->closeCursor();
			foreach ($donnees as $row) { $articles[] = new Article($row); }
			return $articles;
		} catch (PDOException $e) {
			print "Error!: " . $e->getMessage() . "<br/>";
			die();
		}
	}
	public function getByStatut($statut,$intstart, $intlimit) {
		$articles = array();
		try {
			$q = $this->_db->prepare('Select txt_titre as titre, ID_New as id, txt_texte as texte, notonhome, url_img as img, tbl_groupes.dossier as dossier, vl_valider as valider, statut From tbl_new, tbl_groupes, tbl_categorie Where Vl_Valider = :statut AND tbl_new.ID_categorie = tbl_categorie.ID_categorie AND tbl_categorie.id_groupe = tbl_groupes.id_groupe
				Order by ID_New Desc limit :intstart,:intlimit');
			$q->bindParam('intstart', $intstart, PDO::PARAM_INT);
			$q->bindParam('intlimit', $intlimit, PDO::PARAM_INT);
			$q->bindParam('statut', $statut, PDO::PARAM_INT);
			$q->execute();
			$donnees = $q->fetchAll();
			$q->closeCursor();
			foreach ($donnees as $row) { $articles[] = new Article($row); }
			return $articles;
		} catch (PDOException $e) {
			print "Error!: " . $e->getMessage() . "<br/>";
			die();
		}
	}
	public function getAllList($intstart, $intlimit) {
		$articles = array();
		try {
			$q = $this->_db->prepare('Select txt_titre as titre, ID_New as id, txt_texte as texte, notonhome, url_img as img, tbl_groupes.dossier as dossier, vl_valider as valider, statut From tbl_new, tbl_groupes, tbl_categorie Where tbl_new.ID_categorie = tbl_categorie.ID_categorie AND tbl_categorie.id_groupe = tbl_groupes.id_groupe
				Order by ID_New Desc limit :intstart,:intlimit');
			$q->bindParam('intstart', $intstart, PDO::PARAM_INT);
			$q->bindParam('intlimit', $intlimit, PDO::PARAM_INT);
			$q->execute();
			$donnees = $q->fetchAll();
			$q->closeCursor();
			foreach ($donnees as $row) { $articles[] = new Article($row); }
			return $articles;
		} catch (PDOException $e) {
			print "Error!: " . $e->getMessage() . "<br/>";
			die();
		}
	}
	function add($article){
		try {
			//var_dump($article);
			$q = $this->_db->prepare("INSERT INTO `tbl_new` VALUES ('',:titre,:texte,:date,1,:idcategorie,:iddomaine,:www,0,:img,0,:notonhome)");
			$q->bindParam('titre', $article->titre(), PDO::PARAM_STR);
			$q->bindParam('texte', $article->texte(), PDO::PARAM_STR);
			$q->bindParam('date', date('Y-m-d'));
			$q->bindParam('idcategorie', $article->idcategorie(), PDO::PARAM_STR);
			$q->bindParam('iddomaine', $article->iddomaine(), PDO::PARAM_STR);
			$q->bindParam('www', $article->www(), PDO::PARAM_STR);
			$q->bindParam('img', $article->img(), PDO::PARAM_STR);
			$q->bindParam('notonhome', $article->notonhome(), PDO::PARAM_STR);
			$q->execute();
			$q->closeCursor();
		} catch (PDOException $e) {
			print "Error!: " . $e->getMessage() . "<br/>";
			die();
		}
	}
	public function getByListMot($listmots,$intstart, $intlimit){
		$objets = array();
		foreach ($listmots as $mot){
			$list_objets = $this->getByMot($mot,$intstart, $intlimit);
			if ($list_objets){
				$objets = array_merge($objets, $list_objets);
			}
		}
		$objets = array_slice($objets, 0, $intlimit);
		return $objets;
	}
	public function getByMot($mots,$intstart, $intlimit){
		$articles = array();
		$mots = '%'.$mots.'%';
		try {
			$q = $this->_db->prepare('Select txt_titre as titre, ID_New as id, txt_texte as texte, notonhome, url_img as img, tbl_groupes.dossier as dossier, vl_valider as valider, statut From tbl_new, tbl_groupes, tbl_categorie Where txt_titre like :mots AND tbl_new.ID_categorie = tbl_categorie.ID_categorie AND tbl_categorie.id_groupe = tbl_groupes.id_groupe Order by tbl_new.Num_Click Desc limit :intstart,:intlimit');
			$q->bindParam('intstart', $intstart, PDO::PARAM_INT);
			$q->bindParam('intlimit', $intlimit, PDO::PARAM_INT);
			$q->bindParam('mots', $mots, PDO::PARAM_STR);
			$q->execute();
			$donnees = $q->fetchAll();
			$q->closeCursor();
			foreach ($donnees as $row) { $articles[] = new Article($row); }
			//var_dump($donnees);
			return $articles;
		} catch (PDOException $e) {
			print "Error!: " . $e->getMessage() . "<br/>";
			die();
		}
	}
	function update($article){
		try {
			$q = $this->_db->prepare('UPDATE tbl_new Set txt_titre = :titre, txt_texte = :texte, notonhome = :notonhome, ID_categorie = :idcategorie, iddomaine = :iddomaine, www = :www, Vl_Valider = :valider, url_img = :img Where ID_New = :id;');
			$q->bindParam('id', $article->id(), PDO::PARAM_INT);
			$q->bindParam('titre', $article->titre(), PDO::PARAM_STR);
			$q->bindParam('texte', $article->texte(), PDO::PARAM_STR);
			$q->bindParam('www', $article->www(), PDO::PARAM_STR);
			$q->bindParam('idcategorie', $article->idcategorie(), PDO::PARAM_STR);
			$q->bindParam('iddomaine', $article->iddomaine(), PDO::PARAM_STR);
			$q->bindParam('valider', $article->valider(), PDO::PARAM_STR);
			$q->bindParam('img', $article->img(), PDO::PARAM_STR);
			$q->bindParam('notonhome', $article->notonhome(), PDO::PARAM_STR);
			$q->execute();
			$q->closeCursor();
		} catch (PDOException $e) {
			print "Error!: " . $e->getMessage() . "<br/>";
			die();
		}
	}
	function updateNumClick($id){
		try {
			$q = $this->_db->prepare('UPDATE tbl_new Set Num_Click = Num_Click + 1 Where ID_New = :id;');
			$q->bindParam('id', $id, PDO::PARAM_INT);
			$q->execute();
			$q->closeCursor();
		} catch (PDOException $e) {
			print "Error!: " . $e->getMessage() . "<br/>";
			die();
		}
	}
	function updateTexte($id,$texte){
		try {
			$q = $this->_db->prepare('UPDATE tbl_new Set txt_texte = :texte Where ID_New = :id;');
			$q->bindParam('id', $id, PDO::PARAM_INT);
			$q->bindParam('texte', $texte, PDO::PARAM_STR);
			$q->execute();
			$q->closeCursor();
		} catch (PDOException $e) {
			print "Error!: " . $e->getMessage() . "<br/>";
			die();
		}
	}
	public function updatestatut($article) {
			try {
			$q = $this->_db->prepare('UPDATE tbl_new Set vl_valider = :valider, notonhome = :notonhome, statut = :statut Where id_new = :id;');
			$q->bindParam('id', $article->id(), PDO::PARAM_INT);
			$q->bindParam('statut', $article->statut(), PDO::PARAM_INT);
			$q->bindParam('valider', $article->valider(), PDO::PARAM_INT);
			$q->bindParam('notonhome', $article->notonhome(), PDO::PARAM_INT);
			$q->execute();
			$q->closeCursor();
		} catch (PDOException $e) {
			print "Error!: " . $e->getMessage() . "<br/>";
			die();
		}
	}
	public function setDb(PDO $db)
	{
		$this->_db = $db;
	}
}
?>