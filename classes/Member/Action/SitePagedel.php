<?php
/** Gestion de l'action de jeter un item. Cette page est utilis�e UNIQUEMENT par AJAX. des # d'erreur sont retourn�, pas des message. Aucune interface graphique.
*
* @package Member_Action
*/
class Member_Action_Sitepagedel{
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
		
		if(!preg_match('/^([^\/]+)(?:[\/]([0-9]+))?(?:[?](.+))?/', $_POST['url'], $matches))
			die('03|' . rawurlencode('L\'URL du site est invalide.'));
		
		if(count($matches)<=2)
			die('04|' . rawurlencode('L\'id du site est manquant ou invalide.'));
		
		
		//V�rifier si l'URL existe 
		$site = Member_Siteweb::loadSite ($db, $matches[1]);
		if (!$site)
			die('10|' . rawurlencode('Cette URL n\'existe pas.'));
		
		//V�rifier si l'acc�s est valide
		$acces = $site->checkAcces($db, $_POST['user'], $_POST['pass']);
		
		if(!$acces)
			die('11|' . rawurlencode('Vous ne poss�dez pas les autorisations n�c�saires (1).'));
			
		if($acces['modifier'] !=1 && $acces['admin'] !=1)
			die('12|' . rawurlencode('Vous ne poss�dez pas les autorisations n�c�saires (2).'));
		
		//V�rifier que la page appartient au site
		$i=0; $e=0; $found = false;
		while( $page = $site->getPage($db, $i++))
			if($page->getId() == $matches[2])
				$found=true;
		if (!$found)
			die('12|' . rawurlencode('Cette page n\'appartiend pas � ce site.'));
		
		
		//Tout est ok, Effacer la page :(
		$perso->changePa('-', $actionPa);
		$perso->setPa($db);
		
		
		$query = 'DELETE FROM ' . DB_PREFIX . 'sitesweb_pages_acces
					WHERE page_id=' . $matches[2] . ';';
		$db->query($query,__FILE__,__LINE__);
		
		$query = 'DELETE FROM ' . DB_PREFIX . 'sitesweb_pages
					WHERE id=' . $matches[2] . ';';
		$db->query($query,__FILE__,__LINE__);
		
		
		
		
		
		die('OK|' . $perso->getPa()); //Tout est OK
	}
}
?>