<?php
/** Gestion de l'action d'�quiper un item. Cette page est utilis�e UNIQUEMENT par AJAX. des # d'erreur sont retourn�, pas des message. Aucune interface graphique.
*
* @package Member_Action
*/
class Member_Action_InventaireEquiper{
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
		
		
		//Tableau des positions possible. La cl�=le nom de la position, La valeur=le nom de la classe de l'item qui va y �tre plac�.
		$arrEquipPos = array(
						'dos'  => 'Member_ItemSac',
						'arme' => 'Member_ItemArme', 
						'tete' => 'Member_ItemDefenseTete',
						'main' => 'Member_ItemDefenseMain',
						'torse'=> 'Member_ItemDefenseTorse',
						'jambe'=> 'Member_ItemDefenseJambe',
						'pied' => 'Member_ItemDefensePied',
						'bras' => 'Member_ItemDefenseBras'
						);
		$typeValid = false;
		foreach($arrEquipPos as $className)
			if(is_a($item, $className))
				$typeValid=true;
		if(!$typeValid)
			die($_POST['id'] . '|' . rawurlencode('Ce type d\'item ne peut pas �tre �quip�.'));
		
		
		//V�rifier si un objet est d�j� �quip� � cette emplacement
		$i=0;
		while( $tmp = $perso->getInventaire($db, $i++))
			if(is_a($tmp, get_class($item)) && $tmp->isEquip())
				die($_POST['id'] . '|' . rawurlencode('Vous avez d�j� un item �quip�. Vous devez ranger l\'item �quip� avant d\'en �quiper un nouveau.'));
		
		
		
		
		//�quiper l'item
		$query = 'UPDATE '.DB_PREFIX.'item_inv
					SET inv_equip="1"
					WHERE inv_persoid=' . $perso->getId() . ' AND inv_id=' . $_POST['id'] . ';';
		$db->query($query,__FILE__,__LINE__);
		
		$perso->changePa('-', $actionPa);
		$perso->setPa($db);
		
		die($_POST['id'] . '|OK|' . $perso->getPa()); //Tout est OK
	}
}
?>