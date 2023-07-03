<?php
/** Gestion de l'interface pour modifier une page sur Domnet
*
* @package Member_Action
*/
class Member_Action_NavigateurAddpage{
	public static function generatePage(&$tpl, &$db, &$session, &$account, &$perso)
	{	//BUT: D�marrer un template propre � cette page
		
		
		//
		if(!isset($_POST['url']))
			return fctErrorMSG('url innexistante');
		
		
		//S�parer le site de la page (dans l'URL)
		preg_match('/^([^\/]+)(?:[\/]([a-z0-9]+))?(?:[?](.+))?/', $_POST['url'], $matches);
		if (count($matches)<=3)
			return fctErrorMSG('url invalide');
		
		$url_site = $matches[1];
		$url_page = $matches[2];
		$url_param= $matches[3]; //Id de la page a modifier
		
		
		//V�rifier si l'URL existe 
		$site = Member_Siteweb::loadSite ($db, $url_param);
		if (!$site)
			return fctErrorMSG('Cette URL n\'existe pas.');
		
		//V�rifier si l'acc�s est valide
		$acces = $site->checkAcces($db, $_POST['user'], $_POST['pass']);
		
		if(!$acces)
			return fctErrorMSG('Vous ne poss�dez pas les autorisations n�c�saires (1).');
			
		if(!$acces->getPoste() && !$acces->getAdmin())
			return fctErrorMSG('Vous ne poss�dez pas les autorisations n�c�saires (2).');
		
		
		
		
		//Retourner le template compl�t�/rempli
		return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/Action/navigateurDomnetAddpage.htm',__FILE__,__LINE__);
	}
}
?>