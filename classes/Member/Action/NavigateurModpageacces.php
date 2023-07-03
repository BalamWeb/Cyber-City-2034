<?php
/** Gestion de l'interface pour modifier une page sur Domnet
*
* @package Member_Action
*/
class Member_Action_NavigateurModpageacces{
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
		
		
		//Trouver le site qui contient la page
		$query = 'SELECT s.url
					FROM ' . DB_PREFIX . 'sitesweb_pages as p
					LEFT JOIN ' . DB_PREFIX . 'sitesweb as s ON (s.id=p.site_id)
					WHERE p.id=' . $url_param . ';';
		$result = $db->query($query,__FILE__,__LINE__);
		if(mysql_num_rows($result)==0)
			return fctErrorMSG('Page innexistante');
		
		$site_url= mysql_result($result,0);
		
		//V�rifier si l'URL existe 
		$site = Member_Siteweb::loadSite ($db, $site_url);
		if (!$site)
			return fctErrorMsg('Cette URL n\'existe pas.');
		
		//V�rifier si l'acc�s est valide
		$acces = $site->checkAcces($db, $_POST['user'], $_POST['pass']);
		
		if(!$acces)
			return fctErrorMsg('Vous ne poss�dez pas les autorisations n�c�saires (1).');
			
		if(!$acces->isAdmin())
			return fctErrorMsg('Vous ne poss�dez pas les autorisations n�c�saires (2).');
		
		
		if(isset($_POST['save'])){
			if($_POST['adddel']=='true'){
				$query = 'INSERT INTO ' . DB_PREFIX . 'sitesweb_pages_acces
							(`user_id`,`page_id`)
							VALUES
							(' . addslashes($_POST['userid']) . ', ' . $url_param . ');';
				$db->query($query,__FILE__,__LINE__);
			}else{
				$query = 'DELETE FROM ' . DB_PREFIX . 'sitesweb_pages_acces
							WHERE	`user_id` = ' . addslashes($_POST['userid']) . '
								AND `page_id` = ' . $url_param . ';';
				$db->query($query,__FILE__,__LINE__);
			}
			die ('OK');
		}
		
		
		
		//Charger les acc�s � la page
		$query = 'SELECT id, user_id
					FROM ' . DB_PREFIX . 'sitesweb_pages_acces
					WHERE page_id=' . $url_param . ';';
		$result = $db->query($query, __FILE__, __LINE__);
		
		$arrPageAx = array();
		while($arrPageAx[count($arrPageAx)] = mysql_fetch_assoc($result))
			;
			
		$i=0; $arrAcces = array();
		while( $ax = $site->getAcces($db, $i++)){
			$arrAcces[$i]['obj'] = $ax;
			$arrAcces[$i]['ax'] = false;
			foreach($arrPageAx as $pax){
				if($pax['user_id'] == $ax->getId()){	
					$arrAcces[$i]['ax'] = true;
					break 2;
				}
			}
		}
		
		$tpl->set('ACCES', $arrAcces);
		
		
		
		$page = new Member_SitewebPage($arr);
		$tpl->set('PAGE', $page);
		
		
		//Retourner le template compl�t�/rempli
		return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/Action/navigateurDomnetModpageacces.htm',__FILE__,__LINE__);
	}
}
?>