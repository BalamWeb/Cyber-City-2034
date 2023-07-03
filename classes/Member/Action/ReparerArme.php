<?php
/** Gestion de l'interface de r�paration des armes
*
* @package Member_Action
*/
class Member_Action_ReparerArme{
	public static function generatePage(&$tpl, &$db, &$session, &$account, &$perso)
	{	//BUT: D�marrer un template propre � cette page
	
		//V�rifier l'�tat du perso
		if(!$perso->isAutonome())
			return fctErrorMSG('Votre n\'�tes pas en �tat d\'effectuer cette action.');
		
		
		// Liste des armes d�fectueuses en inventaire
		$i=0; $arrItems = array();
		while( $item = $perso->getInventaire($db, $i++))
			if($item instanceof Member_ItemArme)
				if ($item->getResistance() < $item->getResistanceMax())
					$arrItems[count($arrItems)]['item'] = $item;

		
		//�tablir le cout de r�paration en PA + Cash pour chaque item
		for($i=0; $i<count($arrItems); $i++){
								
			//Calculer le % de r�ussite
			switch($arrItems[$i]['item']->getTypeTech()){
				case 'arme_lancee':

					break;
				
				case 'arme_feu':
					$chanceReussite =	(
										  $perso->getChancesReussite($db,'ARMF') * 1
										+ $perso->getChancesReussite($db,'FORG') * 2
										+ $perso->getChancesReussite($db,'MECA') * 1
										) /4;
					$chanceReussite = round(($chanceReussite + ($arrItems[$i]['item']->getPercDommage() + $arrItems[$i]['item']->getPercComplexite())/2) /2);
					break;
				
				case 'arme_blanche':
					$chanceReussite =	(
										  $perso->getChancesReussite($db,'ARMB') * 1
										+ $perso->getChancesReussite($db,'FORG') * 2
										+ $perso->getChancesReussite($db,'ARMU') * 1
										) /4;
					$chanceReussite = round(($chanceReussite + ($arrItems[$i]['item']->getPercDommage() + $arrItems[$i]['item']->getPercComplexite())/2) /2);
					break;
			}
			
			//Calculer le cout $ de la r�paration
			$coutCash 	= round(($arrItems[$i]['item']->getPercDommage() / 20) * ($arrItems[$i]['item']->getResistanceMax() - $arrItems[$i]['item']->getResistance()),2);
			$coutPa		= floor((100-$chanceReussite)/10 * $arrItems[$i]['item']->getPercDommage()/10);
			
			
			//Ajouter les nouvelles donn�es au tableau
			$arrItems[$i]['coutCash']	= $coutCash;
			$arrItems[$i]['coutPa']		= $coutPa;
			$arrItems[$i]['complex']	= round(($arrItems[$i]['item']->getPercDommage() + $arrItems[$i]['item']->getPercComplexite())/20);
		}	
		
		$tpl->set('ITEMS', $arrItems);
		
		
		return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/Action/reparerArme.htm',__FILE__,__LINE__);
	}
}
?>