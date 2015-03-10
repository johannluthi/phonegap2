<?php
class offre {
	public $_id;
	public $_titre;
	public $_email;
	public $_date;
	public $_region;
	public $_adresse;
	public $_ville;
	public $_idville;
	public $_idcanton;
	public $_iddomaine;
	public $_idmetier;
	public $_idgroupe;
	public $_npa;
	public $_rue;
	public $_pourcent;
	public $_description;
	public $_source;
	public $_www;
	public $_nom;
	public $_url;
	public $_valider;
	public $_affichage;
	
	public function hydrate(array $donnees)
	{ foreach ($donnees as $key => $value) { $method = 'set'.ucfirst($key); if (method_exists($this, $method)) { $this->$method($value); } }
	  $this->setUrl();
	  $this->setAffichage(); }

	public function __construct(array $donnees = array()) { $this->hydrate($donnees); }
	public function id() { return $this->_id; }
	public function titre() { return $this->_titre; }
	public function email() { return $this->_email; }	
	public function date() { return $this->_date; }		
	public function dateformat() { return date("d.m.Y", strtotime($this->_date)); }	
	public function region() { return $this->_region; }	
	public function adresse() { return $this->_adresse; }	
	public function ville() { return $this->_ville; }		
	public function idville() { return $this->_idville; }		
	public function idcanton() { return $this->_idcanton; }		
	public function iddomaine() { return $this->_iddomaine; }	
	public function idmetier() { return $this->_idmetier; }		
	public function idgroupe() { return $this->_idgroupe; }	
	public function npa() { return $this->_npa; }	
	public function rue() { return $this->_rue; }		
	public function pourcent() { return str_replace('%','',$this->_pourcent); }		
	public function description() { return $this->_description; }	
	public function nl2brdescription() { return nl2br($this->_description); }		
	public function source() { return $this->_source; }		
	public function www() { return str_replace('http://','',$this->_www); }		
	public function nom() { return $this->_nom; }	
	public function url() { return str_replace('http://','',$this->_url); }	
	public function valider() { return $this->_valider; }	
	public function affichage() { return $this->_affichage; }
	public function titreformat() { return index::replaceTextSearch($this->_titre); }
	
	public function setAffichage() {
		$lastWeek = time() - (21 * 24 * 60 * 60);
		$datelimit = date('Y-m-d', $lastWeek);
		if ($this->_date < $datelimit){
			$this->_affichage = false;	
		} else {
			$this->_affichage = true;
		}
	}
	
	public function setId($id) { $id = (int) $id; if ($id > 0) { $this->_id = $id; } }
	public function setTitre($titre) { if (is_string($titre)) { $this->_titre = $titre; } }
	public function setEmail($email) { if (is_string($email)) { $this->_email = $email; } }
	public function setDate($date) { if (is_string($date)) { $this->_date = $date; } }
	public function setRegion($region) { if (is_string($region)) { $this->_region = $region; } }
	public function setAdresse($adresse) { if (is_string($adresse)) { $this->_adresse = $adresse; } }
	public function setVille($ville) { if (is_string($ville)) { $this->_ville = $ville; } }
	public function setIdville($idville) { if (is_string($idville)) { $this->_idville = $idville; } }
	public function setIdcanton($idcanton) { if (is_string($idcanton)) { $this->_idcanton = $idcanton; } }
	public function setIddomaine($iddomaine) { if (is_string($iddomaine)) { $this->_iddomaine = $iddomaine; } }
	public function setIdmetier($idmetier) { if (is_string($idmetier)) { $this->_idmetier = $idmetier; } }
	public function setIdgroupe($idgroupe) { if (is_string($idgroupe)) { $this->_idgroupe = $idgroupe; } }
	public function setNpa($npa) { if (is_numeric($npa)) { $this->_npa = $npa; } }
	public function setRue($rue) { if (is_string($rue)) { $this->_rue = $rue; } }
	public function setPourcent($pourcent) { if (is_string($pourcent)) { $this->_pourcent = $pourcent; } }
	public function setDescription($description) { if (is_string($description)) { $this->_description = $description; } }
	public function setSource($source) { if (is_string($source)) { $this->_source = $source; } }
	public function setWww($www) { if (is_string($www)) { $this->_www = $www; } }
	public function setNom($nom) { if (is_string($nom)) { $this->_nom = $nom; } }
	public function setValider($valider) { if (is_numeric($valider)) { $this->_valider = $valider; } }
	public function setUrl() { $this->_url = 'emploi/'.index::replaceText(substr($this->_titre,0,100)).'-e'.$this->_id.'.html'; }
}
?>