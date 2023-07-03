<?php
/** Gestion de l'interface de l'action T�l�phoner depuis une cabine t�l�phonique
*
* @package Member_Action
*/
class Member_Action_TelephonerCabine { 
	public static function generatePage(&$tpl, &$db, &$session, &$account, &$perso)
	{
		if(isset($_POST['numero_destinataire'])){
			
			if(empty($_POST['numero_destinataire']))	{	
				return fctErrorMSG('Aucun num�ro de t�l�phone n\'a �t� entr�.','?popup=1&m=Action_TelephonerCabine');
			}elseif(empty($_POST['message'])){
				return fctErrorMSG('Aucun message n\'a �t� r�dig�.','?popup=1&m=Action_TelephonerCabine');
			}
			
			echo self::cabineTelephoner($db,$perso,$_POST['numero_destinataire'],$_POST['message']); 	
		}
		
		//Retourner le template compl�t�/rempli
		return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/Action/telephonerCabine.htm',__FILE__,__LINE__);
	}
	
	
	/**Permet de t�l�phoner depuis une cabine t�l�phonique
	* @param object &$db Connexion � la base de donn�e � utiliser, normalement: $db
	* @param object $perso Objet du perso utilisant le tel
	* @param char $numero_destinataire Num�ro du destinataire
	* @param char $message Message � envoyer
	*/
	public static function cabineTelephoner(&$db,
									 &$perso,
									 &$numero_destinataire,
									 &$message){
								 

		//R�cup des info du t�l�phone de l'appel�
		$query = "SELECT * 
		FROM " . DB_PREFIX . "item_inv 
		LEFT JOIN " . DB_PREFIX . "item_db ON (db_id = inv_dbid) 
		WHERE db_type='telephone' 
		AND inv_notel='".$numero_destinataire."';";
		
		$result = $db->query($query,__FILE__,__LINE__,__FUNCTION__,__CLASS__,__METHOD__);
		$arr = mysql_fetch_assoc($result);
		$tel_distant = new Member_ItemTelephone ($arr);
		
		//Si le t�l�phone n'existe pas
		if(empty($tel_distant))
		{
			return fctErrorMSG('Num�ro pas attribu�.', '?popup=1&m=Action_TelephonerCabine');
		}

		$affichage_dest = $tel_distant->getTypeAffichage();
		$lieu_perso = $perso->getLieu()->getNom();
		
		//Ent�te selon le type d'affichage que poss�de l'utilisateur
		if($affichage_dest == 2){
			$entete_mess_dest = 'Appel provenant d\'une cabine t�l�phonique ('.$lieu_perso.'). Appel re�u sur le t�l�phone #'.$numero_destinataire;
		}
		else
		{
			$entete_mess_dest = 'Votre t�l�phone ne poss�de pas d\'afficheur. Appel re�u sur le t�l�phone #'.$numero_destinataire;			
		}
		
		
		$id_destinataire = $tel_distant->getIdProprio();
		$id_expediteur = $perso->getId();
		$cout_appel = 2;
		$cash_exp = $perso->getCash();
		
		//Assez d'argent dans le portefeuille pour passer un appel?
		if(($cash_exp < $cout_appel)){
			return fctErrorMSG('Vous n\'avez pas assez d\'argent pour passer cet appel.', '?popup=1&m=Action_TelephonerCabine');	
		}
		//T�l�phone Poss�d� par personne
		if($id_destinataire == NULL)
		{
			return fctErrorMSG('Ca sonne occup�. R�essayer plus tard.', '?popup=1&m=Action_TelephonerCabine');
		}
		
		
		//retrait du cash du portefeuille du gars
		$query_retrait_argent_exp = "UPDATE  ".DB_PREFIX."perso SET cash=cash-".$cout_appel." WHERE id='".$id_expediteur."'   ;"; 
		$db->query($query_retrait_argent_exp ,__FILE__,__LINE__,__FUNCTION__,__CLASS__,__METHOD__); //requete sql
		
		//mise de l'argent sur le compte commun
		$query_deplacement_argent = "UPDATE ".DB_PREFIX."banque_comptes SET compte_cash=compte_cash+".$cout_appel." WHERE compte_id=1 ;"; // on le met dans le fond commun
		$db->query($query_deplacement_argent ,__FILE__,__LINE__,__FUNCTION__,__CLASS__,__METHOD__); //requete sql		
		
		//mise du message dans les HE des personnes impliqu�es
		$id_mess_he_dest = Member_He::add($db, "Telephone", $id_destinataire, 'Telephone', $entete_mess_dest. "[sep]" . $message);
		$id_mess_he_exp = Member_He::add($db, "Copie/Telephone", $id_expediteur, "Copie/Telephone", $message);		
		
		//log MJ
		$query_mess_log_dest = "INSERT INTO ".DB_PREFIX."log_telephone VALUES('".$id_mess_he_exp."','".$id_mess_he_dest."','".time()."','Cabine, lieu: ".$lieu_perso."','".$id_expediteur."', '".$numero_destinataire."','".$id_destinataire."') ;";
		$db->query($query_mess_log_dest ,__FILE__,__LINE__,__FUNCTION__,__CLASS__,__METHOD__); //requete sql	
		
	}
}
?>