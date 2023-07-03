<?php
/** Gestion de l'interface d'un guichet automatique: Effectuer un retrait
*
* @package Member_Action
*/
class Member_Action_Guichet3Retrait{
	public static function generatePage(&$tpl, &$db, &$session, &$account, &$perso)
	{	//BUT: D�marrer un template propre � cette page
	
	
		//V�rifier l'�tat du perso
		if(!$perso->isAutonome())
			die('00|' . rawurlencode('Votre n\'�tes pas en �tat d\'effectuer cette action.'));
			
		//V�rifier si une carte � �t� s�lectionn�e
		if (!isset($_POST['carteid']))
			die('01|' . rawurlencode('Aucune carte s�lectionn�e.'));
		$tpl->set('CARD_ID',	$_POST['carteid']	);
		
		
		if (!isset($_POST['nip']))
			die('02|' . rawurlencode('Aucun NIP sp�cifi�e.'));
		$tpl->set('NIP',		$_POST['nip']		);
		
		
		
		//Cr�er la carte + compte
		$query = 'SELECT * 
					FROM ' . DB_PREFIX . 'banque_cartes
					LEFT JOIN ' . DB_PREFIX . 'banque_comptes ON (compte_banque = carte_banque AND compte_compte = carte_compte)
					WHERE carte_id = ' . $_POST['carteid'] . '
					LIMIT 1;';
		$result = $db->query($query, __FILE__, __LINE__);
		if (mysql_num_rows($result)==0)
			die('03|' . rawurlencode('Cette carte � �t� d�sactiv�e.'));
		$arr = mysql_fetch_assoc($result);
		
		$compte = new Member_Banquecompte($arr);
		$carte = $compte->getCarte($_POST['carteid'], $db, $arr);
		$tpl->set('COMPTE', $compte->getNoBanque() . '-' . $compte->getNoCompte());
		
		//Valider si la carte est active ou non
		if(!$carte->isValid())
			die('03|' . rawurlencode('Cette carte est actuellement d�sactiv�e.'));
		
		
		
		
		
		
		
		
		
		//Valider le montant d'argent � retirer
		$_POST['montant'] = str_replace(',','.',$_POST['montant']);
		$montant = round($_POST['montant'],2);
		if(!is_numeric($montant) || $montant<=0)
			die('04|' . rawurlencode('Montant invalide.'));
		
		if ($compte->getCash() < $montant)
			die('05|' . rawurlencode('Vous ne pouvez pas retirer plus que le solde de votre compte.'));
		
		//Retirer l'argent du compte
		$compte->changeCash('-', $montant);
		$compte->setCash($db);
		
		//Ajouter l'argent au perso
		$perso->changeCash('+', $montant);
		$perso->setCash($db);
		
		$perso->changePa('-', 1);
		$perso->setPa($db);
		
		
		//Ajouter la transaction � l'historique
		$compte->add_bq_hist($db, '', 'RGUI', $montant, 0);
		
		
		//Confirmer les modifications avec les informations sur les changements
		die($_POST['id'] . '|OK|' . $compte->getCash() . '|' . $perso->getCash() . '|' . $perso->getPa());
	}
}
?>
