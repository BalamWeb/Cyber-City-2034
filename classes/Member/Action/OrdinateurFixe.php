<?php
/** Gestion de l'interface de l'action Parler: Afficher l'interface pour parler.
*
* @package Member_Action
*/
class Member_Action_OrdinateurFixe{
	public static function generatePage(&$tpl, &$db, &$session, &$account, &$perso)
	{	//BUT: D�marrer un template propre � cette page
		
		//TODO: Valider si le lieu donne acc�s � l'ordinateur
		
		
		//Retourner le template compl�t�/rempli
		return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/Action/ordinateur.htm',__FILE__,__LINE__);
	}
}
?>