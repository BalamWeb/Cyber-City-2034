<?php
/** Recyclage des Items, but: g�n�rer un template qui va afficher la liste des objets � recycler
*
* @package Member_Action
*/
class Member_Action_Recycler {
	public static function generatePage(&$tpl, &$db, &$session, &$account, &$perso)
	{
	
		//V�rifier que le lieu dans lequel est le perso permet bien de recycler les items
		$sql = "SELECT *
				FROM cc_lieu_menu
				WHERE lieutech='".$perso->getLieu()->getNomTech()."' && url='Recycler'";
		$result = $db->query($sql,__FILE__,__LINE__,__FUNCTION__,__CLASS__,__METHOD__);	
		if(mysql_num_rows($result) < 1)
			return fctErrorMSG('Vous n\'�tes pas dans un lieu permettant ce type d\'action.', '?popup=1&m=Action_Deplacement');	

		
	
	
		$i=0; $e=0;
		$objets=array();
		while( $item = $perso->getInventaire($db, $i++)){

				$objets[$e++] = $item;
				
			}
			
	$tpl->set('OBJETS', $objets);

	//Retourner le template compl�t�/rempli
	return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/Action/recycler.htm',__FILE__,__LINE__);	
	}
}

?>