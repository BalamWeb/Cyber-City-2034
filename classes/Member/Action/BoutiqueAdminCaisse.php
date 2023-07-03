<?php
/** Gestion d'une boutique par son propri�taire (AJAX)
*
* @package Member_Action
*/
class Member_Action_BoutiqueAdminCaisse{
	public static function generatePage(&$tpl, &$db, &$session, &$account, &$perso)
	{	//BUT: D�marrer un template propre � cette page
	
		//D�finir les acc�s d'administration
		if($perso->getLieu()->getProprioid() != $perso->getId())
			die('01|Vous devez �tre propri�taire du lieu pour pouvoir l\'administrer.');
		
		//V�rifier l'�tat du perso
		if(!$perso->isAutonome())
			die('02|Votre n\'�tes pas en �tat d\'effectuer cette action.');
		
		//Valider les PA
		if($perso->getPa() < 1)
			die('09|Vous n\'avez pas assez de PA.');
		
		//Valider si le lieu actuel est une boutique
		if(!$perso->getLieu()->isBoutique())
			die('03|Ce lieu n\'est pas une boutique.');
			
		//Valider si le bouton � bel et bien �t� press�
		if (!isset($_POST['oper_type']))
			die('04|Et si tu allais te pendre ?');
		
		//Valider si le montant est valide
		if (!isset($_POST['montant']))
			die('05|Et si tu allais te tirer en bas d\'un pont ?');
		
		
		$_POST['montant'] = str_replace(',','.',$_POST['montant']);
		
		if (!is_numeric($_POST['montant']))
			die('06|Vous devez entrer un nombre num�rique.');
		
		if ($_POST['montant']<0)
			die('07|Le montant doit �tre positif.');
		
		
		
		if($_POST['oper_type']=='depot'){
			//D�poser dans la caisse
			
			//Valider si les fond � transf�rer sont disponibles
			if($perso->getCash() < $_POST['montant'])
				die('08|Vous n\'avez pas une telle somme sur vous.');
			
			$perso->changePa('-', 1);
			$perso->setPa($db);
			
			$perso->changeCash('-', $_POST['montant']);
			$perso->setCash($db);
			
			$perso->getLieu()->changeBoutiqueCash($db, '+', $_POST['montant']);
			
			die('OK|' . $perso->getLieu()->getBoutiqueCash() . '|' . $perso->getCash() . '|' . $perso->getPa());
		}
		
		if($_POST['oper_type']=='retrait'){
			//Retirer dans la caisse
			
			if($perso->getLieu()->getBoutiqueCash() < $_POST['montant'])
				die('10|Vous n\'avez pas une telle somme en caisse.');
			
			$perso->changePa('-', 1);
			$perso->setPa($db);
			
			$perso->changeCash('+', $_POST['montant']);
			$perso->setPa($db);
			
			$perso->getLieu()->changeBoutiqueCash($db, '-', $_POST['montant']);
			
			die('OK|' . $perso->getLieu()->getBoutiqueCash() . '|' . $perso->getCash() . '|' . $perso->getPa());
		}
		
		die('99|Sans changement.');
	}
}
?>