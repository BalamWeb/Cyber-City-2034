<?php
/** Gestion d'une boutique par son propri�taire
*
* @package Member_Action
*/
class Member_Action_CasierInv2{
	public static function generatePage(&$tpl, &$db, &$session, &$account, &$perso)
	{	//BUT: D�marrer un template propre � cette page
		
		
		if(!isset($_POST['id_casier']))
			return fctErrorMSG('Aucun casier s�lectionn�.', '?popup=1&m=Action_CasiersListe');
		
		
		//TROUVER LE CASIER
		$i=0;
		$found=false;
		while( $casier = $perso->getLieu()->getCasiers($db, $i++)){
			if($casier->getId()==$_POST['id_casier']){
				$found = true;
				break;
			}
		}
		
		if(!$found)
			return fctErrorMSG('Le casier #' . $_POST['id_casier'] . ' est introuvable.', '?popup=1&m=Action_CasiersListe');
		
		
		
		
		//PROTECTION: v�rifier la protection du casier
		if($casier->getProtection() != NULL){
		
			//Protection par digipass
			if($casier->getProtection() == 'pass'){
				
				if(!isset($_POST['pass']) || $_POST['pass'] != $casier->getPass())
					return fctErrorMSG('Acc�s par mot de passe invalide. (Tentative de hack 1)');
			
			//Protection par clef
			}elseif($casier->getProtection() == 'clef'){
				$accesOk = false;
				
				//Trouver la cl�
				$i=0; $e=0;
				$arrClefs = array();
				while( $item = $perso->getInventaire($db, $i++)){
					if($item instanceof Member_ItemClef){
						$arrClefs[$e++] = $item;
						
						if(isset($_POST['clef']) && $item->getId() == $_POST['clef']){
							if($item->getCode() == $casier->getPass()){
								$accesOk = true;
								break;
							}
						}
					}
				}
					
				if(!$accesOk){
					return fctErrorMSG('Acc�s par mot de passe invalide. (Tentative de hack 2)');
				}
				
			
			}else{
				 fctBugReport($db, 'Une protection d\'un casier n\'est pas prise en charge par le syst�me.', array('CasierId:' . $casier->getId(), 'Protection:' . $casier->getProtection()), __FILE__, __LINE__);
			}
		}
		
		
		
		//LISTER L'INVENTAIRE DU CASIER une premi�re fois pour valider les PR
		$i=0; $e=0;
		$itemsPr=0;
		$arr = array();
		while($item = $casier->getItems($db, $i++)){
			if(isset($_POST[$item->getInvId() . '_qte']) && $_POST[$item->getInvId() . '_qte']>0){
				 $itemsPr+=$item->getPr();
			}
		}
		
		if($perso->getPr()+$itemsPr > $perso->getPrMax())
			return fctErrorMSG('Vous n\'avez pas assez de PR de disponible pour transf�rer la s�lection.', '?popup=1&m=Action_CasiersInv', array('id_casier'=>$_POST['id_casier']));
		
		//Une seconde fois pour transf�rer
		$i=0; $e=0;
		$msg = 'Vous rammassez les items suivants du casier \'' . $casier->getNom() . '\': ';
		while($item = $casier->getItems($db, $i++)){
			if(isset($_POST[$item->getInvId() . '_qte']) && $_POST[$item->getInvId() . '_qte']>0){
				if ($e>0)
					$msg .= ', ';
				$msg .= $item->getNom();
				
				Member_Item::transfererItemVersPerso($db, $item, $perso, $_POST[$item->getInvId() . '_qte']);
			}
		}
		$msg .= '.';
		
		
		Member_He::add($db, 0, $perso->getId(), 'casier', $msg);
		
		//Rafraichir le HE
		return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/herefresh.htm',__FILE__,__LINE__);
	}
}
?>