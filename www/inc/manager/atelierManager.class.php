<?php
class AtelierManager {
	private $_db;
	
	public function __construct($db)
	{
		$this->setDb($db);
	}
	public function getList($intstart = 0,$intlimit = 30){
		$ateliers = array();
		try {
			$q = $this->_db->prepare('Select txt_titre as titre,id_atelier as id, dt_date as date, txt_description as description, url_img as img, url_page as www, mot_cles as mots FROM atelier_atelier Where 1 ORDER BY dt_date ASC Limit :intstart,:intlimit;');
			$q->bindParam('intstart', $intstart, PDO::PARAM_INT);
			$q->bindParam('intlimit', $intlimit, PDO::PARAM_INT);
			$q->execute();
			$donnees = $q->fetchAll();
			$q->closeCursor();
			//var_dump($donnees);
			foreach ($donnees as $row) { $ateliers[] = new atelier($row); }
			return $ateliers;
		} catch (PDOException $e) {
			print "Error!: " . $e->getMessage() . "<br/>";
			die();
		}
	}
	public function getListActive($intstart,$intlimit){
		$ateliers = array();
		try {
			$q = $this->_db->prepare('Select txt_titre as titre,id_atelier as id, dt_date as date, txt_description as description, url_img as img, url_page as www, mot_cles as mots, id_formation as idformateur FROM atelier_atelier Where dt_date <> "0000-00-00" AND dt_date > :date ORDER BY dt_date ASC Limit :intstart,:intlimit;');
			$q->bindParam('intstart', $intstart, PDO::PARAM_INT);
			$q->bindValue('date', date('Y-m-d'), PDO::PARAM_STR);
			$q->bindParam('intlimit', $intlimit, PDO::PARAM_INT);
			$q->execute();
			$donnees = $q->fetchAll();
			$q->closeCursor();
			//var_dump($donnees);
			foreach ($donnees as $row) { $ateliers[] = new atelier($row); }
			return $ateliers;
		} catch (PDOException $e) {
			print "Error!: " . $e->getMessage() . "<br/>";
			die();
		}
	}
	public function getListAtelier($intlimit) {
		$ateliers = array();
		try {
			$q = $this->_db->prepare('Select id_atelier as id, dt_date as date,
					   txt_titre as titre, mot_cles as motcles, url_img as img, url_page as www
				From atelier_atelier
				Where dt_date <> "0000.00.00" AND dt_date > :date
				Order by dt_date ASC limit 0, :intlimit;');
			$q->bindValue('date', date('Y-m-d'), PDO::PARAM_STR);
			$q->bindParam('intlimit', $intlimit, PDO::PARAM_INT);
			$q->execute();
			$donnees = $q->fetchAll();
			$q->closeCursor();
			//var_dump($donnees);
			foreach ($donnees as $row) { $ateliers[] = new atelier($row); }
			return $ateliers;
		} catch (PDOException $e) {
			print "Error!: " . $e->getMessage() . "<br/>";
			die();
		}
	}
	public function getById($id){
		try {
			$q = $this->_db->prepare('Select id_atelier as id, txt_titre as titre, txt_description as description, dt_date as date, mot_cles as mots, url_img as img, url_page as www, idcategorie, id_formation as idformateur From atelier_atelier Where atelier_atelier.id_atelier = :id');
			$q->bindParam('id', $id, PDO::PARAM_INT);
			$q->execute();
			$donnees = $q->fetch(PDO::FETCH_ASSOC);
			$q->closeCursor();
			if ($donnees) {
				$atelier = new atelier($donnees);
				return $atelier;
			} else {
				return new atelier();
			}
		} catch (PDOException $e) {
			print "Error!: " . $e->getMessage() . "<br/>";
			die();
		}
	}
	public function add($atelier){
		//var_dump($atelier);
		try {
			$q = $this->_db->prepare("INSERT INTO `atelier_atelier` ( `id_atelier` , `txt_titre` , `txt_description`, `dt_date`, `mot_cles`, `url_img`, `url_page`, `id_formation`, `idcategorie`) VALUES ('', :titre, :description,:date,:mots,:img,:www,0,:idcategorie);");
			$q->bindParam('titre', $atelier->titre(), PDO::PARAM_STR);
			$q->bindParam('description', $atelier->description(), PDO::PARAM_STR);
			$q->bindParam('idcategorie', $atelier->idcategorie(), PDO::PARAM_INT);
			$q->bindParam('mots', $atelier->mots(), PDO::PARAM_STR);
			$q->bindParam('img', $atelier->img(), PDO::PARAM_STR);
			$q->bindParam('www', $atelier->www(), PDO::PARAM_STR);
			$q->bindParam('date', $atelier->date(), PDO::PARAM_STR);
			$q->execute();
			$q->closeCursor();
		} catch (PDOException $e) {
			print "Error!: " . $e->getMessage() . "<br/>";
			die();
		}
	}
	public function update($atelier){
		try {
			$q = $this->_db->prepare('Update atelier_atelier set idcategorie = :idcategorie, txt_titre = :titre, txt_description = :description, mot_cles = :mots, url_img = :img, url_page = :www, dt_date = :date where id_atelier = :id;');
			$q->bindParam('id', $atelier->id(), PDO::PARAM_INT);
			$q->bindParam('titre', $atelier->titre(), PDO::PARAM_STR);
			$q->bindParam('idcategorie', $atelier->idcategorie(), PDO::PARAM_INT);
			$q->bindParam('description', $atelier->description(), PDO::PARAM_STR);
			$q->bindParam('mots', $atelier->mots(), PDO::PARAM_STR);
			$q->bindParam('img', $atelier->_img, PDO::PARAM_STR);
			$q->bindParam('www', $atelier->_www, PDO::PARAM_STR);
			$q->bindParam('date', $atelier->date(), PDO::PARAM_STR);
			$q->execute();
			$q->closeCursor();
		} catch (PDOException $e) {
			print "Error!: " . $e->getMessage() . "<br/>";
			die();
		}
	}
	public function getNext() {
		try {
			$q = $this->_db->prepare('Select id_atelier as id, txt_titre as titre, url_page as www, dt_date as date,url_img as img, id_formation as idformateur from atelier_atelier WHERE dt_date > :date Order by dt_date ASC LIMIT 0,1;');
			$q->bindValue('date', date("Y-m-d"));
			$q->execute();
			$donnees = $q->fetch(PDO::FETCH_ASSOC);
			//var_dump($donnees);
			$q->closeCursor();
			if ($donnees) {
				$atelier = new atelier($donnees);
				return $atelier;
			} else {
				return new atelier();
			}
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