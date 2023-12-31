<?php
/** Gestion des cartes associ�s � un compte
*
* @package Member_Action
*/
class Member_Action_BanqueCarteDel{
	public static function generatePage(&$tpl, &$db, &$session, &$account, &$perso)
	{	//BUT: D�marrer un template propre � cette page
	
		//V�rifier les param�tres requis
		if(!isset($_POST['compte']))
			return fctErrorMSG('Ce compte est invalide (aucun compte).');
		
		if (!isset($_POST['c_check']))
			return fctErrorMSG('Vous devez cocher la case pour confirmer que vous voulez bien fermer ce compte.','?popup=1&m=Action_Banque');
		
		//Valider le # du compte (TODO: REGEX !!!!)
		if(strlen($_POST['compte'])!=19)
			return fctErrorMSG('Ce compte est invalide (no invalide).');
		
		$banque_no = substr($_POST['compte'],0,4);
		$compte_no = substr($_POST['compte'],5,14);
		$tpl->set('COMPTE', $_POST['compte']);
		
		//Instancier le compte afin d'y faire des op�rations.
		$query = 'SELECT *
					FROM ' . DB_PREFIX . 'banque_comptes
					WHERE	compte_banque="' . $banque_no . '"
						AND compte_compte="' . $compte_no . '";';
		$result = $db->query($query,__FILE__,__LINE__);
		$arr=mysql_fetch_assoc($result);
		$compte = new Member_BanqueCompte($arr);
				
				
		//V�rifier si le compte appartiend bien au perso
		if ($compte->getIdPerso() != $perso->getId())
			return fctErrorMSG('Ce compte ne vous appartiend pas.');
		
		

		
		//Effectuer le transfert d'argent (Transf�rer l'argent du compte vers le perso)
		$perso->changeCash('+', $compte->getCash());
		$perso->setCash($db);
		
		//Effacer le compte
		$query = 'DELETE FROM ' . DB_PREFIX . 'banque_comptes
					WHERE	compte_id=' . $_POST['id'] . '
						AND compte_idperso=' . $perso->getId() . '
					LIMIT 1;';
		$db->query($query,__FILE__,__LINE__);
		
		//Retirer les PA
		$perso->changePa('-', 1);
		$perso->setPa($db);
		
		
		
		
		
		
		
		//Ajouter l'ouverture � l'historique
		$compte->add_bq_hist($db,'','FRMT', $compte->getCash(), 0, "FERM�");
		
		$tpl->set('PAGE', 'Action_Banque&popup=1');
		return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/redirect.htm',__FILE__,__LINE__);
	}
}
?>