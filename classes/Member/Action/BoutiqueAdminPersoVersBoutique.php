<?php
/** Gestion d'une boutique par son propri�taire
*
* @package Member_Action
*/
class Member_Action_BoutiqueAdminPersoVersBoutique{
	public static function generatePage(&$tpl, &$db, &$session, &$account, &$perso)
	{	//BUT: D�marrer un template propre � cette page
	
		//Valider si le lieu actuel est une boutique
		if(!$perso->getLieu()->isBoutique())
			return fctErrorMSG('Ce lieu n\'est pas une boutique.');
			
		//D�finir les acc�s d'administration
		if($perso->getLieu()->getProprioid() != $perso->getId())
			return fctErrorMSG('Vous devez �tre propri�taire du lieu pour pouvoir l\'administrer.', '?popup=1&m=Action_Boutique');
		
		//Valider si un item de l'inventaire du perso est s�lectionn�
		if (!isset($_POST['perso']))
			return fctErrorMSG('Vous devez s�lectionner un item dans votre inventaire.', '?popup=1&m=Action_BoutiqueAdmin');
		
		
		
		
		
		//Trouver les informations concernant l'item du perso
		$i=0;
		while( $item = $perso->getInventaire($db, $i++)){
			if($item->getInvId() == $_POST['perso']){
				$ITEM  = $item;
				break;
			}
		}
		if (!isset($ITEM))
			return fctErrorMSG('L\'item s�lectionn� n\'existe pas (ou ne vous appartiend pas).', '?popup=1&m=Action_BoutiqueAdmin');
		
		
		
		
		
		//V�rifier si la quantit� d'item � transf�r� n'est pas sup�rieur � ce que le perso pos�de
		$qte_a_transferer = $_POST['psqte_' . $ITEM->getInvId()];
		if ($qte_a_transferer > $ITEM->getQte())
			return fctErrorMSG('Vous ne pouvez pas transf�rer plus que le personnage poss�de.', '?popup=1&m=Action_BoutiqueAdmin');
		
		
		
		
		Member_Item::transfererItemVersBoutique($db, $ITEM, $perso->getLieu(), $qte_a_transferer);
		$perso->refreshInventaire($db);
		
		
		$tpl->set('PAGE', 'Action_BoutiqueAdmin&popup=1');
		return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/redirect.htm',__FILE__,__LINE__);
	}
}
?>