<?php
/** Gestion de l'interface d'une boutique
*
* @package Member_Action
*/
class Member_Action_Donneritem2{
	public static function generatePage(&$tpl, &$db, &$session, &$account, &$perso)
	{	//BUT: D�marrer un template propre � cette page
	
		//D�claration des variables pour cette action
		$pacost = 2; //PA par item
		
		//V�rifier l'�tat du perso
		if(!$perso->isConscient())
			return fctErrorMSG('Votre n\'�tes pas en �tat d\'effectuer cette action.','?popup=1&m=Action_Donneritem');
		
		
		//Valider les PA
		if($perso->getPa()<$pacost)
			return fctErrorMSG('Vous n\'avez pas assez de PA.','?popup=1&m=Action_Donneritem');
		
		//Item s�lectionn� ?
		if(!isset($_POST['itemId']))
			return fctErrorMSG('Vous devez s�lectionner un item � donner.','?popup=1&m=Action_Donneritem');
			
		//V�rifier la quantit�
		if(!isset($_POST['qte_' . $_POST['itemId']]))
			return fctErrorMSG('Quantit� invalide (1).','?popup=1&m=Action_Donneritem');
		$qte=  $_POST['qte_' . $_POST['itemId']];
		
		if(!is_numeric($qte) || $qte<1)
			return fctErrorMSG('Quantit� invalide (2).','?popup=1&m=Action_Donneritem');
		
		
		
		
		//Lister l'inventaire du perso actuel
		$i=0; $found=false;
		while( $item = $perso->getInventaire($db, $i++)){
			if($item->getInvId() == $_POST['itemId']){
				$found=true;
				break;
			}
		}
		if (!$found)
			return fctErrorMSG('L\'item que vous souhaitez transf�rer n\'est pas dans votre inventaire actuellement.','?popup=1&m=Action_Donneritem');
		
		
		//V�rifier si le perso � qui donner l'item est pr�sent dans le bon lieu
		$found = false;
		$i=0;
		while($toPerso = $perso->getLieu()->getPerso($db, $perso, $i++)){
			if($toPerso->getId() == $_POST['toPersoId']){
				$found=true;
				break;
			}
		}
		if(!$found)
			return fctErrorMSG('Ce personnage n\'est pas dans le lieu ou vous vous trouvez actuellement.','?popup=1&m=Action_Donneritem');
		
		if(($toPerso->getPrMax() - $toPerso->getPr()) < $item->getPr()*$qte)
			return fctErrorMSG('Ce personnage n\'a pas suffisamment de PR de libre pour accepter cette transaction.','?popup=1&m=Action_Donneritem');
		
		
		//Tranf�rer l'item du perso actuel vers l'autre perso.
		Member_Item::transfererItemVersPerso($db, $item, $toPerso, $qte);
		$perso->refreshInventaire($db);
		
		
		Member_He::add($db, $perso->getId(), $toPerso->getId(), 'donner', "Objet(s) transf�r�(s): " . $item->getNom() . "\nQuantit�: " . $item->getQte());
		$perso->changePa('-', $pacost);
		$perso->setPa($db);
		
		
		//Afficher la page pr�c�dente
		return Member_Action_Donneritem::generatePage($tpl,$db,$session,$account,$perso);
	}
}
?>