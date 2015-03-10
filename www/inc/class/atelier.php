<?php
class atelier {
	public $_id;
	public $_titre;
	public $_description;
	public $_idcategorie;
	public $_idformateur;
	public $_img;
	public $_www;
	public $_mots;
	public $_date;
	public $_url;
	public $_dateformated;
	public function hydrate(array $donnees)
	{
		//var_dump($donnees);
		foreach ($donnees as $key => $value) {
			// On récupère le nom du setter correspondant à l'attribut.
			$method = 'set'.ucfirst($key);	
			// Si le setter correspondant existe.
			if (method_exists($this, $method))
			{
			  // On appelle le setter.
			  $this->$method($value);
			}
		}
		$this->setDateformated();
		$this->setUrl();
	}
	public function __construct(array $donnees = array()) {
	  $this->hydrate($donnees);
	}	
	// Get
	public function id() { return $this->_id; }
	public function titre() { return $this->_titre; }
	public function description() { return $this->_description; }
	public function idcategorie() { return $this->_idcategorie; }
	public function idformateur() { return $this->_idformateur; }
	public function img() { return 'imgs/'.$this->_img; }
	public function www() { return $this->_www; }
	public function mots() { return $this->_mots; }
	public function date() { return $this->_date; }
	public function url() { return $this->_url; }
	public function dateformated() { return $this->_dateformated; }
	// Set
	public function setId($id) 
	{ if (is_numeric($id)) { $this->_id = $id; } }
	public function setIdcategorie($idcategorie) 
	{ if (is_numeric($idcategorie)) { $this->_idcategorie = $idcategorie; } }
	public function setTitre($titre) 
	{ if (is_string($titre)) { $this->_titre = $titre; } }
	public function setDescription($description) 
	{ if (is_string($description)) { $this->_description = $description; } }
	public function setImg($img) 
	{ if (is_string($img)) { if ($img<>''){$this->_img = $img;} } }
	public function setWww($www) 
	{ if (is_string($www)) { $this->_www = $www; } }
	public function setIdformateur($idformateur) 
	{ if (is_numeric($idformateur)) { $this->_idformateur = $idformateur; } }
	public function setMots($mots) 
	{ if (is_string($mots)) { $this->_mots = $mots; } }
	public function setDate($date) 
	{  $this->_date = $date; }
	public function setUrl() {
		$this->_url = index::replaceText($this->www())."-a".$this->id().".html";}
	public function setDateformated() 
	{  $this->_dateformated = substr($this->_date,-2).".".substr($this->_date,5,2).".".substr($this->_date,0,4); }
}
?>