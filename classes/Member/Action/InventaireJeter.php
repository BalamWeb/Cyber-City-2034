<?php
/** Gestion de l'action de jeter un item. Cette page est utilis�e UNIQUEMENT par AJAX. des # d'erreur sont retourn�, pas des message. Aucune interface graphique.
*
* @package Member_Action
*/
class Member_Action_InventaireJeter{
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
		
		
		
		
		//Trouver en inventaire l'item que l'on souhaite jeter
		$i=0; $item = null;
		while( $tmp = $perso->getInventaire($db, $i++))
			if($tmp->getInvId() == $_POST['id'])
				$item = $tmp;
		
		if(empty($item))
			die($_POST['id'] . '|' . rawurlencode('Cet item ne vous appartiend pas. (cheat)'));
		
		
		
		if($item->canRegroupe()){
			if (!isset($_POST['askQte'])){
				//Demander la quantit� � jeter
				$msg = 'Jeter: <input type="text" class="text" id="ask_qte" value="1" /> / ' . $item->getQte() . '<br />';
				$msg .= "<a href=\"#\" onclick=\"submitJeterForm('?popup=1&m=Action_InventaireJeter'," . $_POST['id'] . ", $F('ask_qte'));\">Jeter</a>";
				die($_POST['id'] . '|' . $msg);
				
			}
		}else{
			$_POST['askQte'] = 1;
		}
		
		//D�s�quiper + jeter l'item
		Member_Item::transfererItemPersoVersLieu($db, $item, $perso->getLieu(), addslashes($_POST['askQte']));
				
		$perso->changePa('-', $actionPa);
		$perso->setPa($db);
		
		$perso->refreshInventaire($db); //Recalculer l'inventaire (les PR)
		
		die($_POST['id'] . '|OK|' . $perso->getPa() . '|' . $perso->getPr()); //Tout est OK
		
	}
}
?>