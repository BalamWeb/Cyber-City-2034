<?php
/** Gestion de l'interface pour cr�er un site sur Domnet
*
* @package Member_Action
*/
class Member_Action_NavigateurAddsite{
	public static function generatePage(&$tpl, &$db, &$session, &$account, &$perso)
	{	//BUT: D�marrer un template propre � cette page
		
		
		
		
		//Retourner le template compl�t�/rempli
		return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/Action/navigateurDomnetAddsite.htm',__FILE__,__LINE__);
	}
}
?>