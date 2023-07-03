<?php
/**Ouvrir une carte m�moire pour voir son contenu
*
* @package Member_Action
*/

//RESTE A FAIRE:
//voir pour le cryptage retour :\
class Member_Action_OuvrirCarteMemoire {
	public static function generatePage(&$tpl, &$db, &$session, &$account, &$perso)
	{
	

	if($_POST['memoire'] == NULL ){	return fctErrorMSG('Vous n\'avez pas s�lectionn� de m�moire � lire', '?popup=1&m=Action_AfficherCartesMemoire');}
	elseif($_POST['lecteur'] == NULL){	return fctErrorMSG('Vous n\'avez pas s�lectionn� de lecteur', '?popup=1&m=Action_AfficherCartesMemoire');}

	//decomposition de la var cr�e pour savoir type et ip (ordi ou cartemem) + mise en var
	$memoire = explode('-', $_POST['memoire']);	
	$type_mem = $memoire[0];
	$idMemoire = $memoire[1];
	//attribution du type d'apreil
	if($type_mem == "lect"){
	$typeMemoire = "Member_ItemOrdinateur";
	}elseif($type_mem == "cm"){
	$typeMemoire = "Member_ItemCarteMemoire";}
	else{
	//erreur
	}

	//Si aucune clef n'est soumise
	if($_POST['clef'] == NULL){
		$_POST['clef'] = 0;
	}

		//r�cup des cartes lecteurs
		$i=0;
		while( $item = $perso->getInventaire($db, $i++)){

			if(is_a($item, 'Member_ItemTelephone')){
				if($item->getInvId() == $_POST['lecteur'])
				
				$lecteur = $item;}}
		
			//r�cup contenu des cartes m�moires ou lecteurs
			$i=0;
			while( $item = $perso->getInventaire($db, $i++)){

				if(is_a($item, $typeMemoire)){
					if($item->getInvId() == $idMemoire)
					$memoire = $item;
				}}
	
		//verif de tous les psot (numeric +  validit� clef + existance d'un lecteur)
		if((!is_numeric($_POST['clef'])) AND ($_POST['clef'] != '' )){
			return fctErrorMSG('Ceci n\'est pas une clef de cryptage num�rique', '?popup=1&m=Action_AfficherCartesMemoire');
		}elseif(($_POST['clef'] != $memoire->getKey()) AND ($memoire->getKey() != NULL) AND ($memoire->getKey() != 0)){
			return fctErrorMSG('Le contenu de cette m�moire n\'a pas pu �tre d�crypt� avec cette clef', '?popup=1&m=Action_AfficherCartesMemoire');
		}elseif($memoire == NULL){return fctErrorMSG('Erreur avec ce lecteur de cartes', '?popup=1&m=Action_AfficherCartesMemoire');}
			//cr�ation d'une var pour dire au  htm si on peut �diter ou pas
			$write_auto = $memoire->getMcWrite();

			if( ($memoire->getMemorySize() == 0) or ($memoire->getMemorySize() == NULL) ){
				$write_auto = 0;
			}
			//CRYPTAGE RETOUR
			//$donnees_post_cm['inv_memoiretext'] = replace_specials($donnees_post_cm['inv_memoiretext'],'strip'); 

			
			if($memoire->getDbParam() != NULL){
				$contenuMemoire = '<img src="'.$memoire->getDbParam().'"/><br />'.$memoire->getMemory();
			}else{
				$contenuMemoire = $memoire->getMemory();
			}
			

			$contenuMemoire = nl2br($contenuMemoire);
			$var_idtype = $_POST['memoire'];
			$clef_crypt = md5($_POST['clef']);

			$lecteur = $_POST['lecteur'];
	
			$tpl->set("memoire",$memoire);	
			$tpl->set("idtype",$var_idtype);
			$tpl->set("write_auto",$write_auto);
			$tpl->set("clef_crypt",$clef_crypt);			
			$tpl->set("lecteur",$lecteur);
			$tpl->set("contenuMemoire",$contenuMemoire);

			
	//Retourner le template compl�t�/rempli
	return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/Action/ouvrirCarteMemoire.htm',__FILE__,__LINE__);	
	}
}

?>	
	