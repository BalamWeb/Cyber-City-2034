<?php
/** Gestion de l'interface d'une boutique
*
* @package Member_Action
*/
class Member_Action_Donnercash2{
	public static function generatePage(&$tpl, &$db, &$session, &$account, &$perso)
	{	//BUT: D�marrer un template propre � cette page
	
		//D�claration des variables pour cette action
		$pacost = 2; //PA par item
		
		//V�rifier l'�tat du perso
		if(!$perso->isConscient())
			return fctErrorMSG('Votre n\'�tes pas en �tat d\'effectuer cette action.','?popup=1&m=Action_Donnercash');
		
		
		//Cr�er le template
		if($perso->getPa()<$pacost)
			return fctErrorMSG('Vous n\'avez pas assez de PA.','?popup=1&m=Action_Donnercash');
		
		//Cash sp�cifi� ?
		if(!isset($_POST['cash']))
			return fctErrorMSG('Aucun montant.','?popup=1&m=Action_Donnercash');
			
		//V�rifier la validit� du montant
		if(!is_numeric($_POST['cash']) || $_POST['cash']<1)
			return fctErrorMSG('Montant invalide','?popup=1&m=Action_Donnercash');
		
		if($_POST['cash']>$perso->getCash())
			return fctErrorMSG('Vous ne pouvez pas transf�rer plus que vous poss�dez.','?popup=1&m=Action_Donnercash');
		
		
		
		
		
		//V�rifier si le perso � qui donner l'argent est pr�sent dans le bon lieu
		$found = false;
		$i=0;
		while($toPerso = $perso->getLieu()->getPerso($db, $perso, $i++)){
			if($toPerso->getId() == $_POST['toPersoId']){
				$found=true;
				break;
			}
		}
		if(!$found)
			return fctErrorMSG('Ce personnage n\'est pas dans le lieu ou vous vous trouvez actuellement.','?popup=1&m=Action_Donnercash');
		

		//Tranf�rer l'item du perso actuel vers l'autre perso.
		$perso->changePa('-', $pacost);
		$perso->setPa($db);
		
		$perso->changeCash('-', $_POST['cash']);
		$perso->setCash($db);
		
		$toPerso->changeCash('+', $_POST['cash']);
		$toPerso->setCash($db);
		
		
		Member_He::add($db, $perso->getId(), $toPerso->getId(), 'donner', "Montant d'argent transf�r�: " . fctDollarFormat($_POST['cash']));
		
		
		//Rafraichir le HE
		return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/herefresh.htm',__FILE__,__LINE__);
	}
}
?>