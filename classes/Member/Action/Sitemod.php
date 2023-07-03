<?php
/** Gestion de l'action de jeter un item. Cette page est utilis�e UNIQUEMENT par AJAX. des # d'erreur sont retourn�, pas des message. Aucune interface graphique.
*
* @package Member_Action
*/
class Member_Action_Sitemod{
	public static function generatePage(&$tpl, &$db, &$session, &$account, &$perso)
	{	//BUT: D�marrer un template propre � cette page
		
		$actionPa = 3;
		
		
		
		//TODO: Valider si le lieu donne acc�s � l'ordinateur + Internet
		
		
		//V�rifier l'�tat du perso
		if(!$perso->isConscient())
			die('01|' . rawurlencode('Votre n\'�tes pas en �tat d\'effectuer cette action.'));
		
		if($perso->getPa() < $actionPa)
			die('02|' . rawurlencode('Vous n\'avez pas assez de PA pour effectuer cette action.'));
		
		if (!preg_match('/^[A-Za-z0-9.-_]*$/', $_POST['url'], $matches))
			return fctErrorMsg('L\'URL du site est invalide.');
		
		
		//V�rifier si l'URL existe 
		$site = Member_Siteweb::loadSite ($db, $_POST['url']);
		if (!$site)
			die('10|' . rawurlencode('Cette URL n\'existe pas.'));
		
		//V�rifier si l'acc�s est valide
		$acces = $site->checkAcces($db, $_POST['user'], $_POST['pass']);
		
		if(!$acces)
			die('11|' . rawurlencode('Vous ne poss�dez pas les autorisations n�c�saires (1).'));
			
		if($acces['admin'] !=1)
			die('12|' . rawurlencode('Vous ne poss�dez pas les autorisations n�c�saires (2).'));
		
		
		
			
			
		//Tout est ok, modifier le site !!!!! 
		
		$perso->changePa('-', $actionPa);
		$perso->setPa($db);
		
		
		//Valider si la page d'accueil demand�e appartiend au site
		$query = 'SELECT id
					FROM ' . DB_PREFIX . 'sitesweb_pages
					WHERE	site_id = ' . $site->getId() . '
						AND id= ' . addslashes($_POST['accueil']) . ';';
		$result = $db->query($query, __FILE__, __LINE__);
		if (mysql_num_rows($result)==0)
			$_POST['accueil'] = 0;
		
		$query = 'UPDATE ' . DB_PREFIX . 'sitesweb
					SET	`url`	="' . addslashes($_POST['new_url']) . '",
						`titre`	="' . addslashes($_POST['titre']) . '",
						`acces` ="' . addslashes($_POST['acces']) . '",
						`first_page` =' . addslashes($_POST['accueil']) . '
					WHERE url="' . addslashes($_POST['url']) . '";';
		$db->query($query,__FILE__,__LINE__);
		
		
		die('OK|' . $perso->getPa() . '|' . $_POST['new_url']); //Tout est OK
	}
}
?>