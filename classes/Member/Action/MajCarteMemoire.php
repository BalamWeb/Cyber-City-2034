<?php
/**Editer le contenu d'une carte memoire
*
* @package Member_Action
*/

//RESTE A FAIRE:
//voir pour le cryptage Aller :\
class Member_Action_MajCarteMemoire {
	public static function generatePage(&$tpl, &$db, &$session, &$account, &$perso)
	{

	//decomposition de la var cr�e pour savoir type et ip (ordi ou cartemem) + mise en var
	$memoireT = explode('-', $_POST['idtype']);	
	$type_mem = $memoireT[0];
	$idMemoire = $memoireT[1];
	//attribution du type d'apreil
	if($type_mem == "lect"){
	$typeMemoire = "Member_ItemOrdinateur";
	}elseif($type_mem == "cm"){
	$typeMemoire = "Member_ItemCarteMemoire";}
	else{
	//erreur
	}

		//r�cup du lecteur
		$i=0;
		while( $item = $perso->getInventaire($db, $i++)){

			if(is_a($item, 'Member_ItemOrdinateur')){
				if($item->getInvId() == $_POST['lecteur'])
				
				$lecteur = $item;}}
				
		
			//r�cup contenu des cartes m�moires ou lecteurs
			$i=0;
			while( $item = $perso->getInventaire($db, $i++)){
			echo $item."<br>";
				if(is_a($item, $typeMemoire)){
					if($item->getInvId() == $idMemoire)
					$memoire = $item;
				}}
				
		//verif de tous les psot (numeric +  validit� clef + existance d'un lecteur)
		if((!is_numeric($_POST['clef'])) AND ($_POST['clef'] != '' )){
			return fctErrorMSG('Ceci n\'est pas une clef de cryptage num�rique', '?popup=1&m=Action_AfficherCartesMemoire');
		}elseif(( $_POST['clef_crypt'] != md5($memoire->getKey()) ) AND ($memoire->getKey() != NULL) AND ($memoire->getKey() != 0)){
			return fctErrorMSG('Le contenu de cette m�moire n\'a pas pu �tre d�crypt� avec cette clef', '?popup=1&m=Action_AfficherCartesMemoire');
		}elseif($memoire == NULL){
			return fctErrorMSG('Erreur avec ce lecteur de cartes', '?popup=1&m=Action_AfficherCartesMemoire');}

				
		$nombre_carac = strlen($_POST['edit']);//memoire du texte
		if($nombre_carac > $memoire->getMemorySizeMax()){
			return fctErrorMSG('le contenu que vous avez tap� est trop grand'.$nombre_carac.'/'.$memoire->getMemorySizeMax(), '?popup=1&m=Action_AfficherCartesMemoire');
		}
		$carac_restant = $nombre_carac;//Pour enregistrer la m�moire actuellement utilis�e

		if($_POST['change_clef'] == NULL){
			if((!is_numeric($_POST['clef'])) AND ($_POST['clef'] != NULL)){return fctErrorMSG('Ceci n\'est pas une clef de cryptage num�rique', '?popup=1&m=Action_AfficherCartesMemoire');
			}else{
				$clef_save = $_POST['clef'];}
		}if($_POST['change_clef'] == 'on')
			$clef_save = $memoire->getKey();
		//fin v�rifs
		
		//$msg_text =	replace_specials($_POST['edit'],'add',false,false);
		$content = $_POST['edit'];
		

		echo $lecteur->majMem($db,$memoire,$content,$clef_save);
	

	//Retourner le template compl�t�/rempli
	return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/Action/majCarteMemoire.htm',__FILE__,__LINE__);	
	}
}

?>	