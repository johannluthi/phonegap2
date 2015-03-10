<?php
class index {
	public $colorlight;
	public $color;
	public $debutcharge;
	public $tempscharge;
	public $ip;
	public $time;
	public $date;
	public $data = array();
	public $objet;
	public $breadcrumb;
	public $content;
	public $_langue;
	public $menuright = array();
		public function __construct($objet) {
		$this->objet = $objet;
		$this->getIp();
		$this->startTime();
		$this->setMenu();
		$this->setLangue($objet['langue']);
		$this->setData();
		$this->setPage();
		$this->defineColor();
		$this->defineBreadcrumb();
    }
	public function setLangue($langue){
		$this->_langue = $langue;
	}
		public static function replaceText($text) {
		$text = str_replace("?", "", $text);
		$search = array ('@[éèêëÊÉË]@i','@[àâäÂÄ]@i','@[îïÎÏ]@i','@[ûùüÛÜ]@i','@[ôöÔÖ]@i','@[ç]@i','@[ ]@i','@[^a-zA-Z0-9_]@');
		$replace = array ('e','a','i','u','o','c','-',' ');
		$text = preg_replace($search, $replace, $text);
		$text = strtolower($text);
		$search = array(' ', '_', ':',"'",'.',",","é","É","è¨","à ",'<p>','</p>');
		$replace = array('-', '-', '-', '-','','-','e','e','e','a','','');
		$text = str_replace($search, $replace, $text); 
		return $text;
	}	
	public static function replaceTextSearch($text) {
		$text = str_replace("?", "", $text);
		$text = strtolower($text);
		$search = array('"', '_', ':',"'",'.',",",'<p>','</p>');
		$replace = array(' ',' ', ' ', ' ','',' ','','');
		$text = str_replace($search, $replace, $text); 
		return $text;
	}
	public function setMenu() {	
		$this->data['html_h1_small'] = false;
		$this->data['topmenutpl'][0]['include'] = 'logo';
		$this->data['topmenutpl'][1]['include'] = 'menuhorizontaltop';
		$this->data['topmenutpl'][2]['include'] = 'menuhorizontal';
		$this->data['topmenutpl'][3]['include'] = 'breadcrumb';
		$this->data['topmenutpl'][4]['include'] = 'titleh1';
		$this->data['topmenutpl'][5]['include'] = 'google';
		$this->data['topmenutpl'][6]['include'] = 'questfast';
		$this->data['lefttpl'][]['include'] = 'search';
		//$this->data['righttpl'][2]['include'] = 'addthis.right.inc';
		$this->data['righttpl'][3]['include'] = 'emploi-list-offres-5';
		//$this->data['righttpl'][4]['include'] = 'google-carre';
		$this->data['righttpl'][4]['include'] = 'newsletter-ins';
		$this->data['righttpl'][6]['include'] = 'sondage-right';
		$this->data['righttpl'][7]['include'] = 'video-right';
		//$this->data['righttpl'][7]['include'] = 'box-search';
		//$this->data['righttpl'][8]['include'] = 'facebook-page';
		//$this->data['righttpl'][9]['include'] = 'list-forum';
		//$this->data['righttpl'][10]['include'] = 'list-partenaire';
		$this->data['righttpl'][11]['include'] = 'link-site';
		$this->data['righttpl'][12]['include'] = 'box-search-cloud';
		if (isset($this->objet['p'])) {
			if (isset($this->objet['a'])) {
				$this->data['righttpl'][0]['include'] = 'article-comment';
				$this->data['lefttpl'][]['include'] = 'menu-categorie-info';
				$this->data['lefttpl'][]['include'] = 'categorie-by-lien-menu';
				$this->data['lefttpl'][]['include'] = 'categorie-by-cat-lien-menu';
			}
			if (isset($this->objet['g'])) {
				$this->data['lefttpl'][]['include'] = 'categorie-by-cat-lien-menu';
				$this->data['lefttpl'][]['include'] = 'groupe-menu';
			}
			if (!isset($this->objet['a']) & !isset($this->objet['g'])) {
				$this->data['lefttpl'][]['include'] = 'categorie-article-menu';
				$this->data['lefttpl'][]['include'] = 'categorie-lien-menu';
			}
		} elseif (isset($this->objet['c'])) {
			$this->data['righttpl'][0]['include'] = 'institution-participer';
			$this->data['lefttpl'][]['include'] = 'groupe-menu';
		} elseif (isset($this->objet['i'])) {
			$this->data['righttpl'][0]['include'] = 'instition-carte';
			$this->data['righttpl'][1]['include'] = 'institution-participer';
		} elseif (isset($this->objet['e'])) {
				$this->data['lefttpl'][]['include'] = 'emploi-list-offres-5';
				$this->data['righttpl'][3]['include'] = 'blank';
				$this->data['righttpl'][0]['include'] = 'emploi-list-button';
		} elseif (isset($this->objet['o'])) {
			if ($this->objet['o']=='institution'){
				$this->data['righttpl'][0]['include'] = 'institution-participer';
			}
			if ($this->objet['o']=='lien'){
				$this->data['righttpl'][0]['include'] = 'lien-ajouter';
			}
			if ($this->objet['o']=='emploi'){
				$this->data['lefttpl'][]['include'] = 'emploi-list-button';
				$this->data['righttpl'][3]['include'] = 'emploi-list-offres-5';
			}
			if ($this->objet['o']=='question'){
				$this->data['righttpl'][0]['include'] = 'question-comment';
			}
		} else {
			$this->data['lefttpl'][]['include'] = 'groupe-menu-h5';
			
			$this->data['topmenutpl'][1]['include'] = 'menuhorizontaltop-h2';
			$this->data['topmenutpl'][2]['include'] = 'menuhorizontal-h3';
			
			$this->data['righttpl'][6]['include'] = 'sondage-right-h5';
			//$this->data['righttpl'][9]['include'] = 'list-forum-h5';
			//$this->data['righttpl'][10]['include'] = 'list-partenaire-h5';
			$this->data['righttpl'][11]['include'] = 'link-site-h5';
			$this->data['righttpl'][12]['include'] = 'box-search-cloud-h5';
		}
	}
		public function defineBreadcrumb() {
		return true;
	}
		public function setData() {
		$this->data['breadcrumb'] = array();
		$this->data['html_h1'] = '';
		if (isset($this->objet['r'])){
			switch($this->objet['r']){
				case 'result' :
				$this->data['titreh1']="Résultat de recherche";
				$this->data['url'] = 'recherche/';
				$this->data['description'] = "";
				$this->data['no_pub'] = false;
				$this->data['title'] = "Résultat de recherche";
				$this->data['lien_dossier'] = '';
				$this->data['lien_style'] = '../';
				$this->data['titrepage'] = '';
				$this->data['keywords'] = 'Résultat recherche Coaching, Formation coaching, Offre d\'Emploi, Education, cohérence cardiaque, Heartmath, Formation professionnelle, en ligne, L\'Education specialisee, formation, emploi, education';
				break;
				case 'glossaire' :
				$this->data['titreh1']="Glossaire";
				$this->data['url'] = '';
				$this->data['description'] = "";
				$this->data['no_pub'] = false;
				$this->data['title'] = "Glossaire";
				$this->data['lien_dossier'] = '';
				$this->data['lien_style'] = '../';
				$this->data['titrepage'] = '';
				$this->data['keywords'] = 'Glossaire, Coaching, Formation coaching, Offre d\'Emploi, Education, cohérence cardiaque, Heartmath, Formation professionnelle, en ligne, L\'Education specialisee, formation, emploi, education';
				$this->data['head_title'] = 'Glossaire';
				$this->data['html_h1'] = 'Glossaire';
				$this->data['breadcrumb'][] = array('titre'=>'glossaire','url'=>'glossaire/');
				break;
			}
		} elseif (isset($this->objet['c'])){
			switch($this->objet['f']){
				case 'online' :
				$this->data['titreh1']="Cours à distance";
				$this->data['url'] = 'formation-a-distance/';
				$this->data['description'] = "Cours à distance";
				$this->data['no_pub'] = false;
				$this->data['title'] = "Cours à distance";
				$this->data['lien_dossier'] = '';
				$this->data['lien_style'] = '../';
				$this->data['titrepage'] = '';
				$this->data['keywords'] = 'Coaching, Formation coaching, Offre d\'Emploi, Education, cohérence cardiaque, Heartmath, Formation professionnelle, en ligne, L\'Education specialisee, formation, emploi, education';
				break;
				default :
				$this->data['titreh1']="Cours en salle";
				$this->data['url'] = 'formation/';
				$this->data['description'] = "Cours en salle";
				$this->data['no_pub'] = false;
				$this->data['title'] = "Cours en salle";
				$this->data['lien_dossier'] = '';
				$this->data['lien_style'] = '../';
				$this->data['titrepage'] = '';
				$this->data['keywords'] = 'Coaching, Formation coaching, Offre d\'Emploi, Education, cohérence cardiaque, Heartmath, Formation professionnelle, en ligne, L\'Education specialisee, formation, emploi, education';
				break;
			}
		} elseif (isset($this->objet['p'])) {
		switch($this->objet['p']){
			case 'education' :
			$this->data['titreh1']="Educatrices Educateurs Sociaux";
			$this->data['url'] = 'education/education.html';
			$this->data['page'] = "education";
			$this->data['groupe'] = array('titre'=>'Education','idgroupe'=>'1');
			$this->data['description'] = "L'éducation sociale et ses nouveaux défis, Le site Educh.ch présente sous forme d'articles, de liens, de faq et de forum un éhange permanent et actualisé sur cette profession. Informations, Rendez-vous et bibliothèque en ligne sur l'éducation sociale.";
			$this->data['no_pub'] = false;
			$this->data['title'] = "Éducation Educatrices Educateurs Sociaux";
			$this->data['lien_dossier'] = '';
			$this->data['lien_style'] = '../';
			$this->data['titrepage'] = '';
			$this->data['keywords'] = 'Coaching, Formation coaching, Offre d\'Emploi, Education, cohérence cardiaque, Heartmath, Formation professionnelle, en ligne, L\'Education specialisee, formation, emploi, education';
			break;
			case 'prevention' :
			$this->data['titreh1']="Prévention éducation";
			$this->data['url'] = 'prevention/prevention.html';
			$this->data['page'] = "prevention";
			$this->data['groupe'] = array('titre'=>'Prévention','idgroupe'=>'2');
			$this->data['description'] = "Thérapie et prévention dans l'éducation et la formation professionnelle, Articles, infos, liens et conférence en ligne. Le forum qui vous invite à échanger dans la communauté francophone et suisse.";
			$this->data['no_pub'] = false;
			$this->data['title'] = "Prévention";
			$this->data['lien_dossier'] = '';
			$this->data['lien_style'] = '../';
			$this->data['titrepage'] = '';
			$this->data['keywords'] = 'Coaching, Formation coaching, Offre d\'Emploi, Education, cohérence cardiaque, Heartmath, Formation professionnelle, en ligne, L\'Education specialisee, formation, emploi, education';
			break;
			case 'adolescent' :
			$this->data['titreh1']="Adolescent Adolescence";
			$this->data['url'] = 'adolescent/adolescent-adolescence.html';
			$this->data['page'] = "adolescent";
			$this->data['groupe'] = array('titre'=>'Adolescent Adolescence','idgroupe'=>'3');
			$this->data['description'] = "Accueillir, accepter cette période difficile tant pour l'individu que pour le parent, l'éducateur. Educh.ch offrent un regard large sur l'information, les bibliographies, les questions clefs qui animent ce champ particulier de l'éducation.";
			$this->data['no_pub'] = false;
			$this->data['title'] = "adolescent";
			$this->data['lien_dossier'] = '';
			$this->data['lien_style'] = '../';
			$this->data['titrepage'] = '';
			$this->data['keywords'] = 'Coaching, Formation coaching, Offre d\'Emploi, Education, cohérence cardiaque, Heartmath, Formation professionnelle, en ligne, L\'Education specialisee, formation, emploi, education';
			break;
			case 'enfant' :
			$this->data['titreh1']="Educatrice Petite Enfance";
			$this->data['url'] = 'enfant/educatrice-petite-enfance.html';
			$this->data['page'] = "enfant";
			$this->data['groupe'] = array('titre'=>'enfant','idgroupe'=>'4');
			$this->data['description'] = "L'éducation sociale et ses nouveaux défis, Le site Educh.ch présente sous forme d'articles, de liens, de faq et de forum un éhange permanent et actualisé sur cette profession. Informations, Rendez-vous et bibliothèque en ligne sur l'éducation sociale.";
			$this->data['no_pub'] = false;
			$this->data['title'] = "Educatrice Petite Enfance";
			$this->data['lien_dossier'] = '';
			$this->data['lien_style'] = '../';
			$this->data['titrepage'] = '';
			$this->data['keywords'] = 'Coaching, Formation coaching, Offre d\'Emploi, Education, cohérence cardiaque, Heartmath, Formation professionnelle, en ligne, L\'Education specialisee, formation, emploi, education';
			break;
			case 'coaching-parental' :
			$this->data['titreh1']="Coaching Parental";
			$this->data['url'] = 'coaching-parental/parents.html';
			$this->data['page'] = "coaching-parental";
			$this->data['groupe'] = array('titre'=>'Parents Informations et ressources','idgroupe'=>'5');
			$this->data['description'] = "Question - Réponse en direct à l'usage des parents, Liens, bibliographie sur l'éducation et des conseils en ligne toujours actualisés. Venez poser vos questions et recevoir la réponse d'un spécialiste et d'une équipe dynamique de bénévoles.";
			$this->data['no_pub'] = false;
			$this->data['title'] = "Parents Informations et ressources";
			$this->data['lien_dossier'] = '';
			$this->data['lien_style'] = '../';
			$this->data['titrepage'] = '';
			$this->data['keywords'] = 'Coaching, Formation coaching, Offre d\'Emploi, Education, cohérence cardiaque, Heartmath, Formation professionnelle, en ligne, L\'Education specialisee, formation, emploi, education';
			break;
			case 'coaching-scolaire' :
			$this->data['titreh1']="Coaching Scolaire";
			$this->data['url'] = 'coaching-scolaire/ecole-infirmier.html';
			$this->data['page'] = "coaching-scolaire";
			$this->data['groupe'] = array('titre'=>'Ecole Infirmier','idgroupe'=>'6');
			$this->data['description'] = "Professions du domaine Médicale - santé en Suisse romande";
			$this->data['no_pub'] = false;
			$this->data['title'] = "Ecole Infirmier";
			$this->data['lien_dossier'] = '';
			$this->data['lien_style'] = '../';
			$this->data['titrepage'] = '';
			$this->data['keywords'] = 'Coaching, Formation coaching, Offre d\'Emploi, Education, cohérence cardiaque, Heartmath, Formation professionnelle, en ligne, L\'Education specialisee, formation, emploi, education';
			break;
			case 'orientation' :
			$this->data['titreh1']="Orientation Professionnelle";
			$this->data['url'] = 'orientation/orientation-professionnelle.html';
			$this->data['page'] = "orientation";
			$this->data['groupe'] = array('titre'=>'Formation Professionnelle','idgroupe'=>'7');
			$this->data['description'] = "La formation professionnelle aujourd'hui en plein mutation, bilan de compétences, apprentissage et mentorat. Educh.ch vous offre de pistes et répond en direct à vos questions par email sur la formation professionnelle en suisse. Orientation formation dans le domaine social et santé pour la suisse romande Coaching et Formation coaching professionnelle Offre d'Emploi Formation en Education spécialisée et travail social coaching en ligne et online, irts et formation hes orientation et apprentissage heartmath cohérence cardiaque";
			$this->data['no_pub'] = false;
			$this->data['title'] = "Formation Professionnelle";
			$this->data['lien_dossier'] = '';
			$this->data['lien_style'] = '../';
			$this->data['titrepage'] = '';
			$this->data['keywords'] = "Orientation, formation, domaine, social, suisse, romande,  Coaching, Formation coaching, Offre d'Emploi, Education, cohérence cardiaque, Heartmath, Formation professionnelle, en ligne, L' Education specialisee, formation, emploi, education,  a distance, annonces, ecoles,  à distance, offres d', université, universitaire, emploi informatique,  internet,  continue,  professionnelle,  par correspondance,  d'adulte,  ecoles,  gratuite, ecole de ,  en ligne, éducation, emploi, stage, stagiaire, centre de , offres, cfa, emploi en suisse, offres emploi, recherche, site, online, web, school, internet, job, social,  action, info, depression, coaching, école, en ligne, sécurité,  help, job, test, adultedivorce, online education, coaching, distance education,  social, ong, divorce, parent, enfant, online, ecole, recherche, web, internet, eesp, formateur d'adulte";
			break;
			case 'handicap' :
			$this->data['titreh1']="Handicap";
			$this->data['url'] = 'handicap/handicap.html';
			$this->data['page'] = "education";
			$this->data['groupe'] = array('titre'=>'Education','idgroupe'=>'8');
			$this->data['description'] = "L'éducation sociale et ses nouveaux défis, Le site Educh.ch présente sous forme d'articles, de liens, de faq et de forum un éhange permanent et actualisé sur cette profession. Informations, Rendez-vous et bibliothèque en ligne sur l'éducation sociale.";
			$this->data['no_pub'] = false;
			$this->data['title'] = "Éducation Educatrices Educateurs Sociaux";
			$this->data['lien_dossier'] = '';
			$this->data['lien_style'] = '../';
			$this->data['titrepage'] = '';
			$this->data['keywords'] = 'Coaching, Formation coaching, Offre d\'Emploi, Education, cohérence cardiaque, Heartmath, Formation professionnelle, en ligne, L\'Education specialisee, formation, emploi, education';
			break;
			case 'formateur' :
			$this->data['titreh1']="Enseignants Formateur";
			$this->data['url'] = 'formateur/formateur.html';
			$this->data['page'] = "education";
			$this->data['groupe'] = array('titre'=>'Education','idgroupe'=>'9');
			$this->data['description'] = "Formateur d'adultes, enseignant, animateur, entraîneur coach découvrir les différents aspects du métier de formateur, de ses voies de formations de dès objectifs aujourd'hui. Quelles sont les écoles, les compétences, les outils et méthodes de ces professions de l'enseignement.";
			$this->data['no_pub'] = false;
			$this->data['title'] = "Enseignants Formateur";
			$this->data['lien_dossier'] = '';
			$this->data['lien_style'] = '../';
			$this->data['titrepage'] = '';
			$this->data['keywords'] = 'Coaching, Formation coaching, Offre d\'Emploi, Education, cohérence cardiaque, Heartmath, Formation professionnelle, en ligne, L\'Education specialisee, formation, emploi, education';
			break;
			case 'internet' :
			$this->data['titreh1']="Internet";
			$this->data['categorie']="Internet";
			$this->data['url'] = 'internet/internet.html';
			$this->data['page'] = "internet";
			$this->data['groupe'] = array('titre'=>'Internet','idgroupe'=>'10');
			$this->data['description'] = "Articles sur les outils et méthodes Internet";
			$this->data['no_pub'] = false;
			$this->data['title'] = "Formation sur les outils et méthodes Internet";
			$this->data['lien_dossier'] = '';
			$this->data['lien_style'] = '../';
			$this->data['titrepage'] = '';
			$this->data['keywords'] = 'eReputation, eSécurité, Prévention internet, formation internet, informatique, Coaching, Formation coaching, Offre d\'Emploi, Education, cohérence cardiaque, Heartmath, Formation professionnelle, en ligne, L\'Education specialisee, formation, emploi, education';
			break;
			}
		} elseif (isset($this->objet['o'])){
			switch ($this->objet['o']){
				case 'annonce' :
					$objetselected = $this->objet['o'];
					// annonce, emploi, institution, domaine, formation, salaire, metier, sondage, biblio, reponse, question, partenaire, glossaire, recherche
					$this->data['titreh1']=$this->_langue[$objetselected.'_home_titreh1']['txt'];
					$this->data['url'] = $this->_langue[$objetselected.'_home_url']['txt'];
					$this->data['description'] = $this->_langue[$objetselected.'_home_description']['txt'];
					$this->data['no_pub'] = ($this->_langue[$objetselected.'_home_no_pub']['txt'] === 'true');
					$this->data['title'] = $this->_langue[$objetselected.'_home_title']['txt'];
					$this->data['lien_dossier'] = $this->_langue[$objetselected.'_home_lien_dossier']['txt'];
					$this->data['lien_style'] = $this->_langue[$objetselected.'_home_lien_style']['txt'];
					$this->data['titrepage'] = $this->_langue[$objetselected.'_home_titrepage']['txt'];
					$this->data['keywords'] = $this->_langue[$objetselected.'_home_keywords']['txt'];
					$this->data['maincontenttpl'] = $this->_langue[$objetselected.'_home_maincontenttpl']['txt'];
					$this->data['head_title'] = $this->_langue[$objetselected.'_home_head_title']['txt'];
					$this->data['html_h1'] = $this->_langue[$objetselected.'_home_html_h1']['txt'];
					$this->data['breadcrumb'][] = array('titre'=>$this->_langue[$objetselected.'_home_maincontenttpl']['txt'],'url'=>$this->_langue[$objetselected.'_page_url']['txt']);
				break;
				case 'video' :
					$objetselected = $this->objet['o'];
					// annonce, emploi, institution, domaine, formation, salaire, metier, sondage, biblio, reponse, question, partenaire, glossaire, recherche
					$this->data['titreh1']=$this->_langue[$objetselected.'_home_titreh1']['txt'];
					$this->data['url'] = $this->_langue[$objetselected.'_home_url']['txt'];
					$this->data['description'] = $this->_langue[$objetselected.'_home_description']['txt'];
					$this->data['no_pub'] = ($this->_langue[$objetselected.'_home_no_pub']['txt'] === 'true');
					$this->data['title'] = $this->_langue[$objetselected.'_home_title']['txt'];
					$this->data['lien_dossier'] = $this->_langue[$objetselected.'_home_lien_dossier']['txt'];
					$this->data['lien_style'] = $this->_langue[$objetselected.'_home_lien_style']['txt'];
					$this->data['titrepage'] = $this->_langue[$objetselected.'_home_titrepage']['txt'];
					$this->data['keywords'] = $this->_langue[$objetselected.'_home_keywords']['txt'];
					$this->data['maincontenttpl'] = $this->_langue[$objetselected.'_home_maincontenttpl']['txt'];
					$this->data['head_title'] = $this->_langue[$objetselected.'_home_head_title']['txt'];
					$this->data['html_h1'] = $this->_langue[$objetselected.'_home_html_h1']['txt'];
					$this->data['breadcrumb'][] = array('titre'=>$this->_langue[$objetselected.'_home_maincontenttpl']['txt'],'url'=>$this->_langue[$objetselected.'_page_url']['txt']);
				break;
				case 'zone':
					$this->data['titreh1']="Zones";
					$this->data['url'] = 'articles/';
					$this->data['description'] = "Cette page réuni les zones principales de notre portail.";
					$this->data['no_pub'] = false;
					$this->data['title'] = "zone";
					$this->data['lien_dossier'] = '../';
					$this->data['lien_style'] = '../';
					$this->data['titrepage'] = '';
					$this->data['keywords'] = "zone";
					$this->data['maincontenttpl'] = 'articles';
					$this->data['head_title'] = 'zone';
					$this->data['html_h1'] = 'Zone';
					$index->data['breadcrumb'][] = array('titre'=>'articles','url'=>'articles/');
				break;
				case 'articles':
					$this->data['titreh1']="Articles";
					$this->data['url'] = 'articles/';
					$this->data['description'] = "Découvrez nos articles dans le domaine de la formation et du conseil socio-éducatif";
					$this->data['no_pub'] = false;
					$this->data['title'] = "Articles Coaching Formation professionnelle Emploi Education Coaching online Formation et coaching en ligne travail social";
					$this->data['lien_dossier'] = '../';
					$this->data['lien_style'] = '../';
					$this->data['titrepage'] = '';
					$this->data['keywords'] = "Articles, domaine, formation, conseil, socio-éducatif,  Coaching, Formation coaching, Offre d'Emploi, Education, cohérence cardiaque, Heartmath, Formation professionnelle, en ligne, L' Education specialisee, formation, emploi, education";
					$this->data['maincontenttpl'] = 'articles';
					$this->data['head_title'] = 'Articles Coaching Formation professionnelle Emploi Education Coaching online Formation et coaching en ligne travail social';
					$this->data['html_h1'] = 'Articles';
					$this->data['breadcrumb'][] = array('titre'=>'articles','url'=>'articles/');
				break;
				case 'forum':
					$this->data['titreh1']="Forum Coaching Education Formation";
					$this->data['url'] = 'forum/';
					$this->data['description'] = "Découvrez nos forum sur le coaching et la formation coaching professionnelle Offre d'Emploi Formation en Education spécialisée et travail social coaching en ligne et online, irts et formation hes orientation et apprentissage heartmath cohérence cardiaque";
					$this->data['no_pub'] = false;
					$this->data['title'] = "Forum Coaching Education Formation Emploi Education spécialisée Formation professionnelle Coaching offre d'emploi";
					$this->data['lien_dossier'] = '../';
					$this->data['lien_style'] = '../';
					$this->data['titrepage'] = '';
					$this->data['keywords'] = "Coaching, Formation coaching, Offre d'Emploi, Education, cohérence cardiaque, Heartmath, Formation professionnelle, en ligne, L' Education specialisee, formation, emploi, education,  a distance, annonces, ecoles,  à distance, offres d', université, universitaire, emploi informatique,  internet,  continue,  professionnelle,  par correspondance,  d'adulte,  ecoles,  gratuite, ecole de ,  en ligne, éducation, emploi, stage, stagiaire, centre de , offres, cfa, emploi en suisse, offres emploi, recherche, site, online, web, school, internet, job, social,  action, info, depression, coaching, école, en ligne, sécurité,  help, job, test, adultedivorce, online education, coaching, distance education,  social, ong, divorce, parent, enfant, online, ecole, recherche, web, internet, eesp, formateur d'adulte";
					$this->data['maincontenttpl'] = 'forum';
					$this->data['head_title'] = 'Forum Coaching Education Formation Emploi Education spécialisée Formation professionnelle Coaching offre d\'emploi';
					$this->data['html_h1'] = 'Forum Coaching Education Formation';
					$this->data['breadcrumb'][] = array('titre'=>'forum','url'=>'forum/');
					//$this->data['lefttpl'] = array();
				break;
				case 'partenaire':
					$this->data['titreh1']="Partenaire";
					$this->data['url'] = 'partenaire/';
					$this->data['description'] = "Découvrez nos partenaires";
					$this->data['no_pub'] = false;
					$this->data['title'] = "Partenaire";
					$this->data['lien_dossier'] = '../';
					$this->data['lien_style'] = '../';
					$this->data['titrepage'] = '';
					$this->data['keywords'] = "Partenaire";
					$this->data['maincontenttpl'] = 'partenaire';
					$this->data['head_title'] = 'Partenaires';
					$this->data['html_h1'] = 'Partenaires';
					$this->data['breadcrumb'][] = array('titre'=>'partenaire','url'=>'partenaire/');
				break;
				case 'emploi':
					$this->data['titreh1']="Emploi Travail Social";
					$this->data['url'] = 'emploi/';
					$this->data['description'] = "Découvrez les dernières offres d'emploi en suisse pour assistant social, éducateur spécialisé, animateur, stagiaire dans le domaine de la formation, conseiller socio-éducatif, et bien plus encore.";
					$this->data['no_pub'] = false;
					$this->data['title'] = "Offres d'emploi";
					$this->data['lien_dossier'] = '../';
					$this->data['lien_style'] = '../';
					$this->data['titrepage'] = '';
					$this->data['keywords'] = "Offres, Suisse, Salaire, demande, d'emploi, domaine, formation, conseil, socio-éducatif,  Coaching, Formation coaching, Offre d'Emploi, Education, cohérence cardiaque, Heartmath, Formation professionnelle, en ligne, L' Education specialisee, formation, emploi, education";
					$this->data['maincontenttpl'] = 'emploi';
					$this->data['head_title'] = 'Offres d\'emploi en Suisse : annonces (job) travail social';
					$this->data['html_h1'] = 'Offres d\'emploi en Suisse';
					$this->data['breadcrumb'][] = array('titre'=>'emploi','url'=>'emploi/');
				break;
				case 'lien':
					$this->data['titreh1']="Annuaire";
					$this->data['url'] = 'lien/';
					$this->data['description'] = "Découvrez les sites internet des associations, institutions et organisation de la suisse romande";
					$this->data['no_pub'] = false;
					$this->data['title'] = "Annuaire";
					$this->data['lien_dossier'] = '../';
					$this->data['lien_style'] = '../';
					$this->data['titrepage'] = '';
					$this->data['keywords'] = "Annuaire, liens";
					$this->data['maincontenttpl'] = 'annuaire';
					$this->data['head_title'] = 'Annuaire';
					$this->data['html_h1'] = 'Annuaire';
					$this->data['breadcrumb'][] = array('titre'=>'annuaire','url'=>'lien/');
				break;
				case 'questions':
					$this->data['titreh1']="Questions";
					$this->data['url'] = 'questions/';
					$this->data['description'] = "Question, Réponse";
					$this->data['no_pub'] = false;
					$this->data['title'] = "Questions";
					$this->data['lien_dossier'] = '../';
					$this->data['lien_style'] = '../';
					$this->data['titrepage'] = '';
					$this->data['keywords'] = "Question, Réponse";
					$this->data['maincontenttpl'] = 'questions';
					$this->data['head_title'] = 'Questions';
					$this->data['html_h1'] = 'Questions';
					$this->data['breadcrumb'][] = array('titre'=>'questions','url'=>'questions/');
				break;
				case 'question':
					$this->data['titreh1']="Questions";
					$this->data['url'] = 'question/';
					$this->data['description'] = "Question, Réponse";
					$this->data['no_pub'] = false;
					$this->data['title'] = "Questions";
					$this->data['lien_dossier'] = '../';
					$this->data['lien_style'] = '../';
					$this->data['titrepage'] = '';
					$this->data['keywords'] = "Question, Réponse";
					$this->data['maincontenttpl'] = 'question';
					$this->data['head_title'] = 'Question';
					$this->data['html_h1'] = 'Question';
					$this->data['breadcrumb'] = array();
				break;
				case 'reponse':
					$this->data['titreh1']="Réponse";
					$this->data['url'] = 'reponse/';
					$this->data['description'] = "Réponse";
					$this->data['no_pub'] = false;
					$this->data['title'] = "Réponse";
					$this->data['lien_dossier'] = '../';
					$this->data['lien_style'] = '../';
					$this->data['titrepage'] = '';
					$this->data['keywords'] = "Réponse";
					$this->data['maincontenttpl'] = 'reponse';
					$this->data['head_title'] = 'Réponse';
					$this->data['html_h1'] = 'Réponse';
					$this->data['breadcrumb'][] = array('titre'=>'réponse','url'=>'reponse/');
				break;
				case 'biblio':
					$this->data['titreh1']="Bibliothèque";
					$this->data['url'] = 'biblio/';
					$this->data['description'] = "Découvrez les livres du domaine de la formation et du conseil socio-éducatif Coaching et Formation coaching professionnelle Offre d'Emploi Formation en Education spécialisée et travail social coaching en ligne et online, irts et formation hes orientation et apprentissage heartmath cohérence cardiaque";
					$this->data['no_pub'] = false;
					$this->data['title'] = "Acheter un Livre Formation Coaching Emploi Education spécialisée Formation professionnelle Coaching offre d emploi";
					$this->data['lien_dossier'] = '../';
					$this->data['lien_style'] = '../';
					$this->data['titrepage'] = '';
					$this->data['keywords'] = "Livres, domaine, formation, conseil, socio-éducatif,  Coaching, Formation coaching, Offre d'Emploi, Education, cohérence cardiaque, Heartmath, Formation professionnelle, en ligne, L' Education specialisee, formation, emploi, education,  a distance, annonces, ecoles,  à distance, offres d', université, universitaire, emploi informatique,  internet,  continue,  professionnelle,  par correspondance,  d'adulte,  ecoles,  gratuite, ecole de ,  en ligne, éducation, emploi, stage, stagiaire, centre de , offres, cfa, emploi en suisse, offres emploi, recherche, site, online, web, school, internet, job, social,  action, info, depression, coaching, école, en ligne, sécurité,  help, job, test, adultedivorce, online education, coaching, distance education,  social, ong, divorce, parent, enfant, online, ecole, recherche, web, internet, eesp, formateur d'adulte";
					$this->data['maincontenttpl'] = 'bibliotheque';
					$this->data['head_title'] = 'Bibliothéque';
					$this->data['html_h1'] = 'Bibliothéque';
					$this->data['breadcrumb'][] = array('titre'=>'bibliothèque','url'=>'biblio/');
				break;
				case 'sondage':
					$this->data['titreh1']="Les sondages";
					$this->data['url'] = 'sondage/';
					$this->data['description'] = "Découvrer les sondages réalisés par educh.ch";
					$this->data['no_pub'] = false;
					$this->data['title'] = "Sondage";
					$this->data['lien_dossier'] = '../';
					$this->data['lien_style'] = '../';
					$this->data['titrepage'] = '';
					$this->data['keywords'] = "Sondage";
					$this->data['maincontenttpl'] = 'sondage';
					$this->data['head_title'] = 'Sondage';
					$this->data['html_h1'] = 'Sondage';
					$this->data['breadcrumb'][] = array('titre'=>'sondage','url'=>'sondage/');
				break;
				case 'metier':
					$this->data['titreh1']="Métier";
					$this->data['url'] = 'metier/';
					$this->data['description'] = "Découvrez les métiers et les professions du domaine social santé et éducation en Suisse romande";
					$this->data['no_pub'] = false;
					$this->data['title'] = "Métier";
					$this->data['lien_dossier'] = '../';
					$this->data['lien_style'] = '../';
					$this->data['titrepage'] = '';
					$this->data['keywords'] = "Métier, professions, emploi santé social formations professionnalisantes du domaine social en Suisse romande";
					$this->data['maincontenttpl'] = 'metier';
					$this->data['head_title'] = 'Métier professions santé sociale suisse';
					$this->data['html_h1'] = 'Métier santé social du domaine social en Suisse romande';
					$this->data['breadcrumb'][] = array('titre'=>'metier','url'=>'orientation/');
				break;
				case 'salaire':
					$this->data['titreh1']="Salaire";
					$this->data['url'] = 'salaire/';
					$this->data['description'] = "Découvrez les salaires des professions du domaine social santé et éducation en Suisse romande";
					$this->data['no_pub'] = false;
					$this->data['title'] = "Salaires";
					$this->data['lien_dossier'] = '../';
					$this->data['lien_style'] = '../';
					$this->data['titrepage'] = '';
					$this->data['keywords'] = "Salaire, Montant, Argent, professions, emploi santé social formations professionnalisantes du domaine social en Suisse romande";
					$this->data['maincontenttpl'] = 'salaire';
					$this->data['head_title'] = 'Salaire des professions santé sociale suisse';
					$this->data['html_h1'] = 'Salaire en Suisse';
					$this->data['breadcrumb'][] = array('titre'=>'salaire','url'=>'salaire/');
				break;
				case 'formation':
					$this->data['titreh1']="Formation";
					$this->data['url'] = 'formation/';
					$this->data['description'] = "Découvrez les formations professionnelles du domaine social en Suisse";
					$this->data['no_pub'] = false;
					$this->data['title'] = "Formation";
					$this->data['lien_dossier'] = '../';
					$this->data['lien_style'] = '../';
					$this->data['titrepage'] = '';
					$this->data['keywords'] = "Formation santé social formations professionnalisantes du domaine social en Suisse romande";
					$this->data['maincontenttpl'] = 'formation';
					$this->data['head_title'] = 'Les formations sociales';
					$this->data['html_h1'] = 'Formation santé social du domaine social en Suisse romande';
					$this->data['breadcrumb'][] = array('titre'=>'formation','url'=>'formation/');
				break;
				case 'domaine':
					$this->data['titreh1']="Les domaines professionnels";
					$this->data['url'] = 'domaine-professionnel/';
					$this->data['description'] = "Découvrez les domaines professionnels dont la santé, le social, la formation, l'éducation en Suisse romande";
					$this->data['no_pub'] = false;
					$this->data['title'] = "Les domaines professionnels";
					$this->data['lien_dossier'] = '../';
					$this->data['lien_style'] = '../';
					$this->data['titrepage'] = '';
					$this->data['keywords'] = "Domaine professionnel santé social formations du domaine social en Suisse romande";
					$this->data['maincontenttpl'] = 'domaine';
					$this->data['head_title'] = 'Domaine professionnel formation sociale';
					$this->data['html_h1'] = 'Domaine professionnel santé social formations professionnalisantes du domaine social en Suisse romande';
					$this->data['breadcrumb'][] = array('titre'=>'domaine','url'=>'domaine-professionnel/');
				break;
				case 'institution':
					$this->data['titreh1']="Institution";
					$this->data['url'] = 'institution/';
					$this->data['description'] = "Découvrez les adresses des institutions suisses dans les domaines : santé, social, éducation et de la formation et du conseil socio-pédagogique";
					$this->data['no_pub'] = false;
					$this->data['title'] = "Institution";
					$this->data['lien_dossier'] = '../';
					$this->data['lien_style'] = '../';
					$this->data['titrepage'] = '';
					$this->data['keywords'] = "institutions adresse sociale Suisse Romande Vaud Genève Valais Neuchatel Jura Berne Fribourg";
					$this->data['maincontenttpl'] = 'institution';
					$this->data['head_title'] = 'Les adresses des institutions Sociale Suisse Romande';
					$this->data['html_h1'] = 'Les adresses des institutions Sociale Suisse Romande';
					$this->data['breadcrumb'][] = array('titre'=>'institution','url'=>'institution/');
				break;
			}
		} elseif (isset($this->objet['e'])){
			$this->data['titreh1']="Emploi";
			$this->data['url'] = 'emploi/';
			$this->data['description'] = "Offres et demande d'emploi dans le domaine de la formation et du conseil socio-éducatif";
			$this->data['no_pub'] = false;
			$this->data['title'] = "Offres d'emploi et demande d'emploi Coaching Emploi Travail Social Offres Demande Recherche Emploi Formation";
			$this->data['lien_dossier'] = '';
			$this->data['lien_style'] = '';
			$this->data['titrepage'] = '';
			$this->data['keywords'] = "Offres, demande, d'emploi, domaine, formation, conseil, socio-éducatif,  Coaching, Formation coaching, Offre d'Emploi, Education, cohérence cardiaque, Heartmath, Formation professionnelle, en ligne, L' Education specialisee, formation, emploi, education";
			$this->data['maincontenttpl'] = 'emploi';
		} else {
			$this->data['titreh1']="";
			$this->data['url'] = '';
			$this->data['page'] = "";
			$this->data['description'] = "Portail d'offres d'emploi, de formation coaching et des institutions suisses. Educh.ch diffuse des offres d'emploi dans les domaines : travail social, éducation spécialisée, santé et coaching. L'insitution de formation propose des ateliers pour devenir coach individuel. Vous trouverez, aussi, les institutions, les formations et les métiers pour les orientations social, éducation et santé. Découvrez heartmath et la cohérence cardiaque";
			$this->data['no_pub'] = false;
			$this->data['title'] = "Formation professionnelle coaching, Offres d'emploi et institutions suisse le tout dans le domaine éducation, travail social et santé";
			$this->data['lien_dossier'] = '';
			$this->data['lien_style'] = '';
			$this->data['titrepage'] = '';
			$this->data['keywords'] = " Coaching, Formation coaching, Offre d'Emploi, Education, cohérence cardiaque, Heartmath, Formation professionnelle, en ligne, L\'Education specialisee, formation, emploi, education,  a distance, annonces, ecoles,  à distance, offres d', université, universitaire, emploi informatique,  internet,  continue,  professionnelle,  par correspondance,  d'adulte,  ecoles,  gratuite, ecole de ,  en ligne, éducation, emploi, stage, stagiaire, centre de , offres, cfa, emploi en suisse, offres emploi, recherche, site, online, web, school, internet, job, social,  action, info, depression, coaching, école, en ligne, sécurité,  help, job, test, adultedivorce, online education, coaching, distance education,  social, ong, divorce, parent, enfant, online, ecole, recherche, web, internet, eesp, formateur d'adulte";
			/** no data **/
			$this->data['maincontenttpl'] = 'default';
		}
		return true;
	}
	public function setPage() {
		if (isset($this->objet['p'])){
			if (isset($this->objet['g'])){
				$this->data['maincontenttpl'] = 'categorie';
			} elseif (isset($this->objet['a'])){
				$this->data['maincontenttpl'] = 'article';
			} else {
				$this->data['maincontenttpl'] = 'groupe';
			}
		}
		if (isset($this->objet['f']) && isset($this->objet['c'])){
			$this->data['maincontent'] = 'cours-inc.php';
		}
		if (isset($this->objet['o'])){
			switch ($this->objet['o']) {
			// Vérifier et purger ces lignes (voir-ci dessus les mêmes attributions
				case 'zone': $this->data['maincontenttpl'] = 'zone'; break;
				case 'article': $this->data['maincontenttpl'] = 'articles'; break;
				case 'partenaire': $this->data['maincontenttpl'] = 'partenaire'; break;
				case 'sondage': $this->data['maincontenttpl'] = 'sondage'; break;
				case 'biblio': $this->data['maincontenttpl'] = 'bibliotheque'; break;
				case 'lien': $this->data['maincontenttpl'] = 'lien'; break; 
				case 'orientation': $this->data['maincontenttpl'] = 'orientation'; break;
				case 'questions': $this->data['maincontenttpl'] = 'questions'; break;
				case 'question': $this->data['maincontenttpl'] = 'question'; break;
				case 'reponse': $this->data['maincontenttpl'] = 'reponse'; break;
				case 'emploi': $this->data['maincontenttpl'] = 'emploi'; break;
			}
		}
		if (isset($this->objet['r'])){
			if ($this->objet['r']=='result'){ $this->data['maincontenttpl'] = 'recherche'; }
			if ($this->objet['r']=='glossaire'){ $this->data['maincontenttpl'] = 'glossaire'; }
		}
	}
		public function genEndTime() {
		// On récupère la date de fin d'exécution du script
		$temps = microtime();
		$temps = explode(' ', $temps);
		$fin = $temps[1] + $temps[0];
		$this->tempscharge = round(($fin - $this->debutcharge),2);
		return true;
	}
   
	public function defineColor() {
	if (isset($this->objet['p'])){
		switch ($this->objet['p']){
			// Nécessite une requête sql pour ces données !
					  case "education":
					  $this->colorlight = "#EAA2A2";
					  $this->color = "#C60000";
					  break;
					  case "prevention":
					  $this->colorlight = "#FFA2A2";
					  $this->color = "#FF0000";
					  break;
					  case "orientation":
					  $this->colorlight = "#FFC274";
					  $this->color = "#FF9000";
					  break;
					  case "adolescent":
					  $this->colorlight = "#8B9AB8";
					  $this->color = "#002063";
					  break;
					  case "coaching-scolaire":
					  $this->colorlight = "#8BDCB0";
					  $this->color = "#0071C6";
					  break;
					  case "coaching-parental":
					  $this->colorlight = "#8BDCFB";
					  $this->color = "#00B2F7";
					  break;
					  case "enfant":
					  $this->colorlight = "#88dd77";
					  $this->color = "#00B252";
					  break;
					  case "handicap":
					  $this->colorlight = "#BFA1D6";
					  $this->color = "#7330A5";
					  break;
					  case "formateur":
					  $this->colorlight = "#BBE391";
					  $this->color = "#94D352";
					  break;
					  case "internet":
					  $this->colorlight = "#8B9AB8";
					  $this->color = "#002063";
					  break;
					  default:
					  $this->colorlight = "#EAA2A2";
					  $this->color = "#990000";
					  break;
				  }
		return true;
		}
	}
	public function getIp(){
		if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} elseif(isset($_SERVER['HTTP_CLIENT_IP'])) {
				$ip  = $_SERVER['HTTP_CLIENT_IP'];
		} else {
				$ip = $_SERVER['REMOTE_ADDR'];
		}
		$this->ip = $ip;
		return true;
	}
	public function startTime() {
		// On récupère la date au lancement de la page
		$temps = microtime();
		$temps = explode(' ', $temps);
		$this->debutcharge = $temps[1] + $temps[0];
		return true;
	}
	public function setMeta() {
			$list = explode(" ",$this->data['description']);
			$mots = '';
			foreach ($list as $mot){
				if (strlen($mot) > 5){
					$mots = $mots.", ".$mot;
				}
			}
			$this->data['keywords'] = $mots.", ".$this->data['keywords'];
			$this->data['keywords'] = str_replace(',,',',',$this->data['keywords']);
			$this->data['meta']['description'] = '<meta name="Description" content="'.$this->data['description'].'"/>';
			$this->data['meta']['keywords'] = '<meta name="Keywords" content="'.$this->data['keywords'].'"/>';
	}
	public function curPageURL() {
	 $pageURL = 'http';
	 $pageURL .= "://";
	 if ($_SERVER["SERVER_PORT"] != "80") {
	  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	 } else {
	  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	 }
	 return $pageURL;
	}
	public static function getListAlpha() {
		$listAlpha = array();
		foreach (range('A', 'Z') as $char) {
			$listAlpha[] = array('char'=>$char);
		}
		return $listAlpha;
	}
}?>