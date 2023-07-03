<?php
/** Gestion de l'action d'�quiper un item. Cette page est utilis�e UNIQUEMENT par AJAX. des # d'erreur sont retourn�, pas des message. Aucune interface graphique.
*
* @package Member_Action
*/
class Member_Action_InventaireRanger{
	public static function generatePage(&$tpl, &$db, &$session, &$account, &$perso)
	{	//BUT: D�marrer un template propre � cette page
	
		$actionPa = 3;
		
		if (!isset($_POST['id']) || (isset($_POST['id']) && !is_numeric($_POST['id'])))
			die('00|' . rawurlencode('Vous devez s�lectionner un item pour effectuer cette action.'));
			
		//V�rifier l'�tat du perso
		if(!$perso->isConscient())
			die($_POST['id'] . '|' . rawurlencode('Votre n\'�tes pas en �tat d\'effectuer cette action.'));
		
		if($perso->getPa() < $actionPa)
			die($_POST['id'] . '|' . rawurlencode('Vous n\'avez pas assez de PA pour effectuer cette action.'));
		
		
		
		
		//Trouver en inventaire l'item que l'on souhaite �quiper
		$i=0; $item = null;
		while( $tmp = $perso->getInventaire($db, $i++))
			if($tmp->getInvId() == $_POST['id'])
				$item = $tmp;
		
		if(empty($item))
			die($_POST['id'] . '|' . rawurlencode('Cet item ne vous appartiend pas. (cheat)'));
		
		

		
		//Ranger l'item
		$query = 'UPDATE '.DB_PREFIX.'item_inv
					SET inv_equip="0"
					WHERE inv_persoid=' . $perso->getId() . ' AND inv_id=' . $_POST['id'] . ';';
		$db->query($query,__FILE__,__LINE__);
		
		$perso->changePa('-', $actionPa);
		$perso->setPa($db);
		
		die($_POST['id'] . '|OK|' . $perso->getPa()); //Tout est OK
	}
}
?>