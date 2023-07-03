<?php
/** Gestion de l'action de jeter un item. Cette page est utilis�e UNIQUEMENT par AJAX. des # d'erreur sont retourn�, pas des message. Aucune interface graphique.
*
* @package Member_Action
*/
class Member_Action_Sitepageadd{
	public static function generatePage(&$tpl, &$db, &$session, &$account, &$perso)
	{	//BUT: D�marrer un template propre � cette page
		
		$actionPa = 3;
		//$cout_ouverture = 5;
		
		
		
		//TODO: Valider si le lieu donne acc�s � l'ordinateur + Internet
		
		
		//V�rifier l'�tat du perso
		if(!$perso->isConscient())
			die('01|' . rawurlencode('Votre n\'�tes pas en �tat d\'effectuer cette action.'));
		
		if($perso->getPa() < $actionPa)
			die('02|' . rawurlencode('Vous n\'avez pas assez de PA pour effectuer cette action.'));
		
		if(!preg_match('/^[A-Za-z0-9.-_]+$/', $_POST['url'], $matches))
			die('03|' . rawurlencode('L\'URL du site est invalide.'));
		
		//if(!preg_match('/^([0-9]{4})-([0-9]{4}-[0-9]{4}-[0-9]{4})-([0-9]+)$/', $_POST['no'], $matches))
		//	die('04|' . rawurlencode('Le # de la carte est invalide.'));
		
		//$carte_banque = $matches[1];
		//$carte_compte = $matches[2];
		//$carte_id = $matches[3];
		
		//$query = 'SELECT * 
		//	FROM ' . DB_PREFIX . 'banque_cartes
		//	WHERE 	carte_banque= ' . $carte_banque . '
		//		AND carte_compte="' . $carte_compte . '"
		//		AND carte_id = ' . $carte_id . '
		//	LIMIT 1;';
		//$result = $db->query($query, __FILE__, __LINE__);
		//if (mysql_num_rows($result)==0)
		//	die('05|' . rawurlencode('Cette carte est innexistante.'));
		
		//$carte = mysql_fetch_assoc($result);
		
		//if($carte['carte_valid']==0)
		//	die('06|' . rawurlencode('Cette carte a �t� d�sactiv�e.'));
		
		//if($carte['carte_nip'] != $_POST['nip'])
		//	die('07|' . rawurlencode('NIP erron�.'));
		
		
		//Valider le montant du compte associ� � la carte
		//$query = 'SELECT *
		//			FROM ' . DB_PREFIX . 'banque_comptes
		//			WHERE	compte_banque= ' . $carte_banque . '
		//				AND compte_compte="' . $carte_compte . '";';
		//$result = $db->query($query, __FILE__, __LINE__);
		//if (mysql_num_rows($result)==0)
		//	die('08|' . rawurlencode('Le compte associ� � la carte a �t� ferm�.'));
		
		//$arr = mysql_fetch_assoc($result);
		//$compte = new Member_Banquecompte($arr);
		
		//if ($compte->getCash() < $cout_ouverture)
		//	die('09|' . rawurlencode('Compte sans fond.'));
		
		
		//V�rifier si l'URL existe 
		$site = Member_Siteweb::loadSite ($db, $_POST['url']);
		if (!$site)
			die('10|' . rawurlencode('Cette URL n\'existe pas.'));
		
		//V�rifier si l'acc�s est valide
		$acces = $site->checkAcces($db, $_POST['user'], $_POST['pass']);
		
		if(!$acces)
			die('11|' . rawurlencode('Vous ne poss�dez pas les autorisations n�c�saires (1).'));
			
		if($acces['poste'] !=1 && $acces['admin'] !=1)
			die('12|' . rawurlencode('Vous ne poss�dez pas les autorisations n�c�saires (2).'));
		
		
		//Tout est ok, Cr�er la page !!!!! 
		
		$perso->changePa('-', $actionPa);
		$perso->setPa($db);
		//$compte->changeCash('-', $cout_ouverture);
		//$compte->setCash($db);
		//$compte->add_bq_hist($db, '', 'SDPD', $prixTotal);
		
		
		$query = 'INSERT INTO ' . DB_PREFIX . 'sitesweb_pages
					(`site_id`, `titre`, `content`, `acces`, `showIndex`)
					VALUES
					(' . $site->getId() . ',"' . addslashes($_POST['titre']) . '","' . addslashes($_POST['content']) . '", "' . addslashes($_POST['acces']) . '","' . addslashes($_POST['showIndex']) . '");';
		$db->query($query,__FILE__,__LINE__);
		
		
		
		$pageid = mysql_insert_id($db->getConnectionId());
		
		
		//Si la page est priv� et que la personne qui cr�e est pas admin, lui donner droit d'acc�s
		if($_POST['acces'] == 'priv' && $acces['admin'] !=1){
			$query = 'INSERT INTO ' . DB_PREFIX . 'sitesweb_pages_acces
					(`page_id`, `user_id`)
					VALUES
					(' . $pageid . ',' . $acces['id'] . ');';
			$db->query($query,__FILE__,__LINE__);
		}
		
		

		
		
		
		die('OK|' . $perso->getPa() . '|' . $_POST['url'] . '/' . $pageid); //Tout est OK
	}
}
?>