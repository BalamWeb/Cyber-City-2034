<?php
/** Effectuer un retrait bancaire ( AJAX ONLY )
*
* @package Member_Action
*/
class Member_Action_BanqueRetrait{
	public static function generatePage(&$tpl, &$db, &$session, &$account, &$perso)
	{	//BUT: D�marrer un template propre � cette page
		$coutPa = 1;
		
		
		//V�rifier l'�tat du perso
		if(!$perso->isAutonome())
			die('00|' . rawurlencode('Votre n\'�tes pas en �tat d\'effectuer cette action.'));
		
		//V�rifier les PA du perso
		if($perso->getPa() < $coutPa)
			die('01|' . rawurlencode('Vous n\'avez pas assez de PA pour effectuer cette action.'));
		
		
		//Instancier la banque
		$query = 'SELECT *
		           FROM ' . DB_PREFIX . 'banque
		           WHERE banque_lieu="' . $perso->getLieu()->getNomTech() . '";';
		$result=$db->query($query,__FILE__,__LINE__);
		if (mysql_num_rows($result)==0)
			die('02|' . rawurlencode('Cette banque n\'existe pas (' . $perso->getLieu()->getNomTech() . ').'));
		$arr = mysql_fetch_assoc($result);
		$banque = new Member_Banque($arr);
		
		
		//Instancier le compte
		$query = 'SELECT *
					FROM ' . DB_PREFIX . 'banque_comptes
					WHERE	compte_id=' . $_POST['id'] . '
						AND compte_idperso=' . $perso->getId() . ';';
		$result = $db->query($query,__FILE__,__LINE__);
		if (mysql_num_rows($result)==0)
			die('03|' . rawurlencode('Ce compte n\'existe pas (' . $_POST['id'] . ').'));
		$arr=mysql_fetch_assoc($result);
		$compte = $banque->getCompte($arr['compte_compte'], $db, $arr);
		
		
		//Valider si le montant est possible
		$_POST['montant'] = str_replace(',','.',$_POST['montant']);
		if ($_POST['montant']<=0 && $_POST['montant']>$compte->getCash())
			die('04|' . rawurlencode('Vous ne pouvez pas retirer plus que vous avez ou un montant vide.'));
		
		//Effectuer le transfert d'argent
		$compte->changeCash('-', $_POST['montant']);
		$compte->setCash($db);
		
		$perso->changeCash('+', $_POST['montant']);
		$perso->setCash($db);
		
		//Retirer les PA
		$perso->changePa('-', $coutPa);
		$perso->setPa($db);
		
		//Ajouter la transaction � l'historique
		$compte->add_bq_hist($db, '', 'RETR', $_POST['montant'], 0);
		
		//Confirmer les modifications avec les informations sur les changements
		die($_POST['id'] . '|OK|' . $compte->getCash() . '|' . $perso->getCash() . '|' . $perso->getPa());
	}
}
?>