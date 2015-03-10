<?php
class OffreManager {
	private $_db;
		public function __construct($db)
	{
		$this->setDb($db);
	}
	public function getPreview($id){
		try {
			$q = $this->_db->prepare('Select id_offre as id from tbl_offre where id_offre > :id and vl_valider = 1 Order by id_offre ASC Limit 0,1');
			$q->bindParam('id', $id, PDO::PARAM_INT);
			$q->execute();
			$donnees = $q->fetch(PDO::FETCH_ASSOC);
			$q->closeCursor();
			if ($donnees['id']) {
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
			$q = $this->_db->prepare('Select id_offre as id from tbl_offre where id_offre < :id and vl_valider = 1  Order by id_offre DESC Limit 0,1');
			$q->bindParam('id', $id, PDO::PARAM_INT);
			$q->execute();
			$donnees = $q->fetch(PDO::FETCH_ASSOC);
			$q->closeCursor();
			if ($donnees['id']) {
				return $donnees['id'];
			} else {
				return false;
			}
		} catch (PDOException $e) {
			print "Error!: " . $e->getMessage() . "<br/>";
			die();
		}
	}
	function add($offre){
		try {
			$q = $this->_db->prepare("INSERT INTO  `educh`.`tbl_offre` 
			(`id_offre` ,`pourcent` ,`txt_titre` ,`txt_email` ,`txt_desc` ,`txt_adr` ,`vl_valider` ,`date` ,`region` ,`www` ,`Rue_No` ,`Ville` ,`NPA` ,`source` ,`nom`,`idville`,`iddomaine`,`idcanton`,`idmetier`) VALUES 
			(NULL ,  :pourcent,  :titre,  :email,  :description,  :adresse,  :valider,  :date,  :region,  :www,  :rue,  :ville,  :npa,  :source, :nom, :idville, :iddomaine, :idcanton,:idmetier);");
			$q->bindValue('pourcent', $offre->pourcent(), PDO::PARAM_STR);
			$q->bindValue('titre', $offre->titre(), PDO::PARAM_STR);
			$q->bindValue('email', $offre->email(), PDO::PARAM_STR);
			$q->bindValue('description', $offre->description(), PDO::PARAM_STR);
			$q->bindValue('adresse', $offre->adresse(), PDO::PARAM_STR);
			$q->bindValue('valider', $offre->valider(), PDO::PARAM_INT);
			$q->bindValue('date', date('Y-m-d'), PDO::PARAM_STR);
			$q->bindValue('region', $offre->region(), PDO::PARAM_STR);
			$q->bindValue('www', $offre->www(), PDO::PARAM_STR);
			$q->bindValue('rue', $offre->rue(), PDO::PARAM_STR);
			$q->bindValue('ville', $offre->ville(), PDO::PARAM_INT);
			$q->bindValue('idville', $offre->idville(), PDO::PARAM_INT);
			$q->bindValue('iddomaine', $offre->iddomaine(), PDO::PARAM_INT);
			$q->bindValue('idcanton', $offre->idcanton(), PDO::PARAM_INT);
			$q->bindValue('idmetier', $offre->idmetier(), PDO::PARAM_INT);
			$q->bindValue('source', $offre->source(), PDO::PARAM_STR);
			$q->bindValue('npa', $offre->npa(), PDO::PARAM_STR);
			$q->bindValue('nom', $offre->nom(), PDO::PARAM_STR);
			$q->execute();
			$q->closeCursor();
		} catch (PDOException $e) {
			print "Error!: " . $e->getMessage() . "<br/>";
			die();
		}
	}
	public function getById($id){
		try {
			$q = $this->_db->prepare('Select id_offre as id, Ville as ville, nom as nom,NPA as npa, Rue_No as rue, txt_titre as titre, pourcent as pourcent, txt_email as email, txt_desc as description, txt_adr as adresse, date, region, www, source, iddomaine, idcanton, idville, idmetier, idgroupe from tbl_offre where id_offre = :id Limit 0,1');
			$q->bindParam('id', $id, PDO::PARAM_INT);
			$q->execute();
			$donnees = $q->fetch(PDO::FETCH_ASSOC);
			$q->closeCursor();
			if ($donnees) {
				$offre = new Offre($donnees);
				return $offre;
			} else {
				return new Offre();
			}
		} catch (PDOException $e) {
			print "Error!: " . $e->getMessage() . "<br/>";
			die();
		}
	}
	public function getByIdAndStatut($id, $statut){
		try {
			$q = $this->_db->prepare('Select id_offre as id, Ville as ville, nom as nom,NPA as npa, Rue_No as rue, txt_titre as titre, pourcent as pourcent, txt_email as email, txt_desc as description, txt_adr as adresse, date, region, www, source, iddomaine, idcanton, idville, idmetier, idgroupe from tbl_offre where vl_valider = :statut AND id_offre = :id Limit 0,1');
			$q->bindParam('id', $id, PDO::PARAM_INT);
			$q->bindParam('statut', $statut, PDO::PARAM_INT);
			$q->execute();
			$donnees = $q->fetch(PDO::FETCH_ASSOC);
			$q->closeCursor();
			if ($donnees) {
				$offre = new Offre($donnees);
				return $offre;
			} else {
				return new Offre();
			}
		} catch (PDOException $e) {
			print "Error!: " . $e->getMessage() . "<br/>";
			die();
		}
	}
	public function getByStatut($statut, $intstart,$intlimit){
		$offres = array();
		try {
			$q = $this->_db->prepare('Select id_offre as id, txt_titre as titre, pourcent, txt_email as email, txt_desc as description, txt_adr as adresse, www, region, Rue_No as rue, Ville as ville,NPA as npa,source,nom, iddomaine, idcanton, idville, idmetier, idgroupe From tbl_offre Where vl_valider = :statut AND id_offre NOT IN (Select id_offre From tbl_offre_membre); limit :intstart,:intlimit');
			$q->bindParam('statut', $statut, PDO::PARAM_INT);
			$q->bindParam('intstart', $intstart, PDO::PARAM_INT);
			$q->bindParam('intlimit', $intlimit, PDO::PARAM_INT);
			$q->execute();
			$donnees = $q->fetchAll();
			$q->closeCursor();
			foreach ($donnees as $row) { $offres[] = new Offre($row); }
			return $offres;
		} catch (PDOException $e) {
			print "Error!: " . $e->getMessage() . "<br/>";
			die();
		}
	}
	public function getListByIddomaine($iddomaine, $intstart,$intlimit){
		$offres = array();
		try {
			$q = $this->_db->prepare('Select id_offre as id, txt_titre as titre, pourcent, txt_email as email, txt_desc as description, txt_adr as adresse, www, region, Rue_No as rue, Ville as ville,NPA as npa,source,nom, iddomaine, idcanton, idville, idmetier, idgroupe From tbl_offre Where vl_valider = 1 AND `date` > :date AND iddomaine = :iddomaine order by id_offre desc limit :intstart,:intlimit;');
			$q->bindParam('iddomaine', $iddomaine, PDO::PARAM_INT);
			$date = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-21, date("Y")));
			$q->bindParam('date', $date, PDO::PARAM_STR);
			$q->bindParam('intstart', $intstart, PDO::PARAM_INT);
			$q->bindParam('intlimit', $intlimit, PDO::PARAM_INT);
			$q->execute();
			$donnees = $q->fetchAll();
			$q->closeCursor();
			foreach ($donnees as $row) { $offres[] = new Offre($row); }
			return $offres;
		} catch (PDOException $e) {
			print "Error!: " . $e->getMessage() . "<br/>";
			die();
		}
	}
	public function getListByIdmetier($idmetier, $intstart,$intlimit){
		$offres = array();
		try {
			$q = $this->_db->prepare('Select id_offre as id, txt_titre as titre, pourcent, txt_email as email, txt_desc as description, txt_adr as adresse, www, region, Rue_No as rue, Ville as ville,NPA as npa,source,nom, iddomaine, idcanton, idville, idmetier, idgroupe From tbl_offre Where vl_valider = 1 AND `date` > :date AND idmetier = :idmetier order by id_offre desc limit :intstart,:intlimit;');
			$q->bindParam('idmetier', $idmetier, PDO::PARAM_INT);
			$date = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-21, date("Y")));
			$q->bindParam('date', $date, PDO::PARAM_STR);
			$q->bindParam('intstart', $intstart, PDO::PARAM_INT);
			$q->bindParam('intlimit', $intlimit, PDO::PARAM_INT);
			$q->execute();
			$donnees = $q->fetchAll();
			$q->closeCursor();
			foreach ($donnees as $row) { $offres[] = new Offre($row); }
			return $offres;
		} catch (PDOException $e) {
			print "Error!: " . $e->getMessage() . "<br/>";
			die();
		}
	}
	public function getListAsc($intstart,$intlimit){
		$offres = array();
		try {
			$q = $this->_db->prepare('Select txt_titre as titre, id_offre as id, region, iddomaine, idcanton, idville, idmetier, idgroupe from tbl_offre where vl_valider = 1 order by id_offre ASC limit :intstart,:intlimit');
			$q->bindParam('intstart', $intstart, PDO::PARAM_INT);
			$q->bindParam('intlimit', $intlimit, PDO::PARAM_INT);
			$q->execute();
			$donnees = $q->fetchAll();
			$q->closeCursor();
			foreach ($donnees as $row) { $offres[] = new Offre($row); }
			return $offres;
		} catch (PDOException $e) {
			print "Error!: " . $e->getMessage() . "<br/>";
			die();
		}
	}
	public function getList($intstart,$intlimit){
		$offres = array();
		try {
			$q = $this->_db->prepare('Select txt_titre as titre, id_offre as id, region, iddomaine, idcanton, idville, idmetier, idgroupe from tbl_offre where vl_valider = 1 order by id_offre DESC limit :intstart,:intlimit');
			$q->bindParam('intstart', $intstart, PDO::PARAM_INT);
			$q->bindParam('intlimit', $intlimit, PDO::PARAM_INT);
			$q->execute();
			$donnees = $q->fetchAll();
			$q->closeCursor();
			foreach ($donnees as $row) { $offres[] = new Offre($row); }
			return $offres;
		} catch (PDOException $e) {
			print "Error!: " . $e->getMessage() . "<br/>";
			die();
		}
	}
	public function getNext($id, $intstart, $intlimit){
		$offres = array();
		try {
			$q = $this->_db->prepare('Select txt_titre as titre, id_offre as id, region, iddomaine, idcanton, idville, idmetier, idgroupe  from tbl_offre where vl_valider = 1 and id_offre < :id order by id_offre DESC limit :intstart,:intlimit');
			$q->bindParam('id', $id, PDO::PARAM_INT);
			$q->bindParam('intstart', $intstart, PDO::PARAM_INT);
			$q->bindParam('intlimit', $intlimit, PDO::PARAM_INT);
			$q->execute();
			$donnees = $q->fetchAll();
			$q->closeCursor();
			foreach ($donnees as $row) { $offres[] = new Offre($row); }
			return $offres;
		} catch (PDOException $e) {
			print "Error!: " . $e->getMessage() . "<br/>";
			die();
		}
	}
	public function getByListMot($listmots,$intstart, $intlimit){
		$objets = array();
		if (is_array($listmots)){
			foreach ($listmots as $mot){
				$list_objets = $this->getByMot($mot,$intstart, $intlimit);
				if ($list_objets){
					$objets = array_merge($objets, $list_objets);
				}
			}
			$objets = array_slice($objets, 0, $intlimit);
		}
		return $objets;
	}
	public function getByMot($mots,$intstart, $intlimit){
		$i = 0;
		$mots = '%'.$mots.'%';
		$offres = array();
		try {
			$q = $this->_db->prepare('Select id_offre as id, txt_titre as titre, pourcent, txt_email as email, txt_desc as description, txt_adr as adresse, www, region, Rue_No as rue, Ville as ville,NPA as npa,source,nom, vl_valider as valider, iddomaine, idcanton, idville, idmetier, idgroupe  From tbl_offre where vl_valider = 1 AND txt_titre like :mots AND `date` > :date order by id_offre DESC limit :intstart,:intlimit');
			$q->bindParam('intstart', $intstart, PDO::PARAM_INT);
			$date = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-21, date("Y")));
			$q->bindParam('date', $date, PDO::PARAM_STR);
			$q->bindParam('intlimit', $intlimit, PDO::PARAM_INT);
			$q->bindParam('mots', $mots, PDO::PARAM_STR);
			$q->execute();
			$donnees = $q->fetchAll();
			$q->closeCursor();
			foreach ($donnees as $row) { $offres[] = new Offre($row); }
			return $offres;
		} catch (PDOException $e) {
			print "Error!: " . $e->getMessage() . "<br/>";
			die();
		}
	}
		public function getDomaineNum(){
		try {
			$q = $this->_db->prepare('Select distinct(iddomaine) as id, count(id_offre) as num from tbl_offre where vl_valider = 1 AND iddomaine > 0 AND `date` > :date Group by iddomaine;');
			$date = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-21, date("Y")));
			$q->bindParam('date', $date, PDO::PARAM_STR);
			$q->execute();
			$donnees = $q->fetchAll();
			$q->closeCursor();
			return $donnees;
		} catch (PDOException $e) {
			print "Error!: " . $e->getMessage() . "<br/>";
			die();
		}
	}
	public function getMetierNum(){
		try {
			$q = $this->_db->prepare('Select distinct(idmetier) as id, count(id_offre) as num from tbl_offre where vl_valider = 1 AND idmetier > 0 AND `date` > :date Group by idmetier;');
			$date = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-21, date("Y")));
			$q->bindParam('date', $date, PDO::PARAM_STR);
			$q->execute();
			$donnees = $q->fetchAll();
			$q->closeCursor();
			return $donnees;
		} catch (PDOException $e) {
			print "Error!: " . $e->getMessage() . "<br/>";
			die();
		}
	}
	public function getVilleNum(){
		try {
			$q = $this->_db->prepare('Select distinct(idville) as id, count(id_offre) as num from tbl_offre where vl_valider = 1 AND idville > 0 AND `date` > :date Group by idville;');
			$date = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-21, date("Y")));
			$q->bindParam('date', $date, PDO::PARAM_STR);
			$q->execute();
			$donnees = $q->fetchAll();
			$q->closeCursor();
			return $donnees;
		} catch (PDOException $e) {
			print "Error!: " . $e->getMessage() . "<br/>";
			die();
		}
	}
	public function getByVille($ville,$intstart, $intlimit){
		$offres = array();
		$searchville = '%'.$ville->titre().'%';
		try {
			$q = $this->_db->prepare('Select id_offre as id, txt_titre as titre, pourcent, txt_email as email, txt_desc as description, txt_adr as adresse, www, region, Rue_No as rue, Ville as ville,NPA as npa,source,nom, vl_valider as valider, iddomaine, idcanton, idville, idmetier, idgroupe  From tbl_offre where vl_valider = 1 AND (region like :ville OR txt_adr like :ville OR Ville like :ville) AND `date` > :date order by id_offre desc limit :intstart,:intlimit;');
			$q->bindParam('intstart', $intstart, PDO::PARAM_INT);
			$date = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-21, date("Y")));
			$q->bindParam('date', $date, PDO::PARAM_STR);
			$q->bindParam('intlimit', $intlimit, PDO::PARAM_INT);
			$q->bindParam('ville', $searchville, PDO::PARAM_STR);
			$q->execute();
			$donnees = $q->fetchAll();
			$q->closeCursor();
			foreach ($donnees as $row) { $offres[] = new Offre($row); }
			return $offres;
		} catch (PDOException $e) {
			print "Error!: " . $e->getMessage() . "<br/>";
			die();
		}
	}
	public function getNumber() {
		try {
			$q = $this->_db->prepare("Select count(id_offre) as num from tbl_offre Where vl_valider = 1 order by id_offre DESC;");
			$q->execute();
			$donnees = $q->fetch(PDO::FETCH_ASSOC);
			$q->closeCursor();
			if ($donnees) {
				return $donnees['num'];
			} else {
				return null;
			}
		} catch (PDOException $e) {
			print "Error!: " . $e->getMessage() . "<br/>";
			die();
		}	
	}
	function updateTexte($id,$texte){
		try {
			$q = $this->_db->prepare('UPDATE tbl_offre Set txt_desc = :texte Where id_offre = :id;');
			$q->bindParam('id', $id, PDO::PARAM_INT);
			$q->bindParam('texte', $texte, PDO::PARAM_STR);
			$q->execute();
			$q->errorinfo();
			$q->closeCursor();
		} catch (PDOException $e) {
			print "Error!: " . $e->getMessage() . "<br/>";
			die();
		}
	}
	function update($offre){
		//var_dump($offre);
		try {
			$q = $this->_db->prepare('Update tbl_offre SET txt_titre = :titre,region = :region,pourcent = :pourcent,txt_email = :email,txt_desc = :description,txt_adr = :adresse,Rue_No = :rue,Ville = :ville,NPA = :npa,www = :www, vl_valider=:valider, idville=:idville,idcanton=:idcanton,iddomaine=:iddomaine,idmetier=:idmetier, idgroupe = :idgroupe Where id_offre=:id;');
			$q->bindParam('id', $offre->id(), PDO::PARAM_INT);
			$q->bindParam('titre', $offre->titre(), PDO::PARAM_STR);
			$q->bindParam('email', $offre->email(), PDO::PARAM_STR);
			$q->bindParam('region', $offre->region(), PDO::PARAM_STR);
			$q->bindParam('pourcent', $offre->pourcent(), PDO::PARAM_STR);
			$q->bindParam('description', $offre->description(), PDO::PARAM_STR);
			$q->bindParam('adresse', $offre->adresse(), PDO::PARAM_STR);
			$q->bindParam('rue', $offre->rue(), PDO::PARAM_STR);
			$q->bindParam('ville', $offre->ville(), PDO::PARAM_STR);
			$q->bindParam('idville', $offre->idville(), PDO::PARAM_STR);
			$q->bindParam('idmetier', $offre->idmetier(), PDO::PARAM_STR);
			$q->bindParam('idgroupe', $offre->idgroupe(), PDO::PARAM_STR);
			$q->bindParam('idcanton', $offre->idcanton(), PDO::PARAM_STR);
			$q->bindParam('iddomaine', $offre->iddomaine(), PDO::PARAM_STR);
			$q->bindParam('npa', $offre->npa(), PDO::PARAM_STR);
			$q->bindParam('www', $offre->www(), PDO::PARAM_STR);
			$q->bindParam('valider', $offre->valider(), PDO::PARAM_INT);
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
}?>