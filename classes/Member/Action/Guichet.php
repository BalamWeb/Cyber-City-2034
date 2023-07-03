<?php
/** Gestion de l'interface d'un guichet automatique: S�lectionner une carte
*
* @package Member_Action
*/
class Member_Action_Guichet{
	public static function generatePage(&$tpl, &$db, &$session, &$account, &$perso)
	{	//BUT: D�marrer un template propre � cette page
	
	
		//V�rifier l'�tat du perso
		if(!$perso->isAutonome())
			return fctErrorMSG('Votre n\'�tes pas en �tat d\'effectuer cette action.');
		
		//Afficher la liste des cartes de guichet
		$i=0; $e=0;
		while( $item = $perso->getInventaire($db, $i++))
			if($item instanceof Member_ItemCartebanque)
				$carteEnPossessionDuPerso[$e++]  = $item;
		if (isset($carteEnPossessionDuPerso))
			$tpl->set('LIST_CARTE', $carteEnPossessionDuPerso);
		
		
		//Retourner le template compl�t�/rempli
		return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/Action/guichet.htm',__FILE__,__LINE__);
	}
}
?>
