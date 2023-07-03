<?php
/** Gestion d'une boutique par son propri�taire
*
* @package Member_Action
*/
class Member_Action_BoutiqueAdminBoutiqueVersPerso{
	public static function generatePage(&$tpl, &$db, &$session, &$account, &$perso)
	{	//BUT: D�marrer un template propre � cette page
	
		//Valider si le lieu actuel est une boutique
		if(!$perso->getLieu()->isBoutique())
			return fctErrorMSG('Ce lieu n\'est pas une boutique.');
			
		//D�finir les acc�s d'administration
		if($perso->getLieu()->getProprioid() != $perso->getId())
			return fctErrorMSG('Vous devez �tre propri�taire du lieu pour pouvoir l\'administrer.', '?popup=1&m=Action_Boutique');
		
		//Valider si un item de l'inventaire du perso est s�lectionn�
		if (!isset($_POST['boutique']))
			return fctErrorMSG('Vous devez s�lectionner un item dans votre inventaire.', '?popup=1&m=Action_BoutiqueAdmin');
		
		
		
		
		
		//Trouver les informations concernant l'item de la boutique
		$i=0;
		while( $item = $perso->getLieu()->getBoutiqueInventaire($db, $i++)){
			if($item->getInvId() == $_POST['boutique']){
				$ITEM_BOUTIQUE = $item;
				break;
			}
		}
		if (!isset($ITEM_BOUTIQUE))
			return fctErrorMSG('L\'item s�lectionn� n\'existe pas (ou n\'est pas en boutique).', '?popup=1&m=Action_BoutiqueAdmin');
		

		
			
			
		//V�rifier si la quantit� d'item � transf�r� n'est pas sup�rieur � ce que la boutique pos�de
		$qte_a_transferer = $_POST['btqte_' . $ITEM_BOUTIQUE->getInvId()];
		if ($qte_a_transferer > $ITEM_BOUTIQUE->getQte())
			return fctErrorMSG('Vous ne pouvez pas transf�rer plus que la boutique contient.', '?popup=1&m=Action_BoutiqueAdmin');
		
		
		
		
		Member_Item::transfererItemVersPerso($db, $ITEM_BOUTIQUE, $perso, $qte_a_transferer);
		$perso->refreshInventaire($db);
		
		
		$tpl->set('PAGE', 'Action_BoutiqueAdmin&popup=1');
		return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/redirect.htm',__FILE__,__LINE__);
	}
}
?>
