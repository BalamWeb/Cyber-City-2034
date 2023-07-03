<?php
/** Gestion de l'interface de l'inventaire du personnage
*
* @package Member_Action
*/

function compare($a, $b){	//Fonction de comparaison servant au tri du tableua pour afficher les items en inventaire par groupe de "type"
   return strcmp($a->getType(), $b->getType());
}

class Member_Action_Inventaire{
	public static function generatePage(&$tpl, &$db, &$session, &$account, &$perso)
	{	//BUT: D�marrer un template propre � cette page
		
		
		//V�rifier l'�tat du perso
		if(!$perso->isConscient())
			return fctErrorMSG('Votre n\'�tes pas en �tat d\'effectuer cette action.');
		
		
		
		
		//Lister tout les items que le perso poss�de (section de gauche)
		$i=0; $e=0;
		while( $item = $perso->getInventaire($db, $i++))
			$arrItem[$e++] = $item;
		
		if (!empty($arrItem)){
			usort($arrItem, "compare");
			$tpl->set('INVENTAIRE',$arrItem);
		}
		
		
		
		//AFFICHER LES IMAGES APPROPRI�ES SUR LE MANEQUIN (section de droite)
		/*
		//Tableau des positions possible. La cl�=le nom de la position, La valeur=le nom de la classe de l'item qui va y �tre plac�.
		$arrEquipPos = array(
						'dos'  => 'Member_ItemSac',
						'arme' => 'Member_ItemArme', 
						'tete' => 'Member_ItemDefenseTete',
						'main' => 'Member_ItemDefenseMain',
						'torse'=> 'Member_ItemDefenseTorse',
						'jambe'=> 'Member_ItemDefenseJambe',
						'pied' => 'Member_ItemDefensePied',
						'bras' => 'Member_ItemDefenseBras'
						);
		
		
		//Innitialiser les valeurs par d�faut
		$EQUIP = array();
		$arrEquipPosKeys = array_keys($arrEquipPos);
		foreach($arrEquipPosKeys as $key){
			$EQUIP[$key]['id'] = 0;
			$EQUIP[$key]['img'] ='blank.gif';
		}
		
		//Charger les items �quip� � la bonne position dans la liste
		$i=0;
		while( $item = $perso->getInventaire($db, $i++)){
			foreach($arrEquipPosKeys as $key){
				if(is_a($item, $arrEquipPos[$key]) && $item->isEquip()){
					$EQUIP[$key]['id'] = $item->getId();
					$EQUIP[$key]['img']= $item->getImage();
				}
			}
		}
		$tpl->set('EQUIP',$EQUIP);
		*/
		
		
		
		//Retourner le template compl�t�/rempli
		return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/Action/inventaire.htm',__FILE__,__LINE__);
	}
}
?>
