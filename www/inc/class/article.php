<?php
class article {
	public $_id;
	public $_titre;
	public $_texte;
	public $_img;
	public $_imgurl;
	public $_idcategorie;
	public $_iddomaine;
	public $_date;
	public $_www;
	public $_dossier;
	public $_idauthor;
	public $_view;
	public $_author;
	public $_commentaires = array();
	public $_articles = array();
	public $_valider;
	public $_notonhome;
	// 	Statut : 0 = nothing; 1 = favorite (unique, affiché en page d'accueil)
		public $_statut;
	public function hydrate(array $donnees)
	{
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
		$this->setUrl();
		$this->setImgURL();
		$this->setIdauthor();
	}
	public function __construct(array $donnees = array()) {
	  $this->hydrate($donnees);
	}
	public function id() { return $this->_id; }
	public function idauthor() { return $this->_idauthor; }
	public function author() { return $this->_author; }
	public function titre() { return $this->_titre; }
	public function texte() { return $this->_texte; }
	public function img() { return $this->_img; }
	public function imgurl() { return $this->_imgurl; }
	public function idcategorie() { return $this->_idcategorie; }
	public function iddomaine() { return $this->_iddomaine; }
	public function date() { return$this->_date; }
	public function www() { return $this->_www; }
	public function dossier() { return $this->_dossier; }
	public function url() { return $this->_url; }
	public function statut() { return $this->_statut; }
	public function valider() { return $this->_valider; }
	public function commentaires() { return $this->_commentaires; }
	public function notonhome() { return $this->_notonhome; }
	
	public function setId($id)
	{
		$id = (int) $id;
		if ($id > 0) { $this->_id = $id; }
	}
	public function setStatut($statut)
	{ $statut = (int) $statut; if ($statut > 0) { $this->_statut = $statut; } }
	public function setValider($valider)
	{ $valider = (int) $valider; if ($valider > 0) { $this->_valider = $valider; } }
	public function setTitre($titre) { $this->_titre = $titre; }
	public function setTexte($texte)
	{ if (is_string($texte)) { $this->_texte = $texte; } }
	public function setIdcategorie($idcategorie)
	{ $idcategorie = (int) $idcategorie;
	  if ($idcategorie > 0) { $this->_idcategorie = $idcategorie; }
	}
	public function setIddomaine($iddomaine)
	{ $iddomaine = (int) $iddomaine;
	  if ($iddomaine > 0) { $this->_iddomaine = $iddomaine; }
	}
	public function setDate($date)
	{ $this->_date = substr($date,-2).'.'.substr($date,5,2).'.'.substr($date,0,4); }
	public function setWww($www)
	{ if (is_string($www)) {
		$www = str_replace('http:', '', $www);
		$www = str_replace('//', '', $www);
		$this->_www = $www;
	} }
	public function setDossier($dossier)
	{ if (is_string($dossier)) { $this->_dossier = $dossier; } }
	public function setImg($img) {
		$this->_img = $img;
	}
	public function setImgURL() {
		if (!isset($this->_img) || $this->_img == ''){
			$this->_imgurl = "article/img_small/article_".$this->_id.".gif";
		} else {
			$this->_imgurl = "article/img_small/".$this->_img;
		}
	}
	public function setUrl() {
		$this->_url = $this->dossier()."/".index::replaceText($this->titre())."-a".$this->id().".html";}
	public function setIdauthor() { 
		$this->_idauthor = 3;
	}
	public function setAuthor($author) { $this->_author = $author; }
	public function setCommentaire($commentaires) { $this->_commentaires = $commentaires; }
	public function setNotonhome($notonhome) { $this->_notonhome = $notonhome; }
}
?>