<?php
/** Gestion d'un laboratoire de drogue
*
* @package Member_Action
*/
class Member_Action_LaboDrogue2{
	public static function generatePage(&$tpl, &$db, &$session, &$account, &$perso)
	{	//BUT: D�marrer un template propre � cette page
		
		
		//V�rifier l'�tat du perso
		if(!$perso->isAutonome())
			return fctErrorMSG('Votre n\'�tes pas en �tat d\'effectuer cette action.', '?popup=1&m=Action_LaboDrogue');
		
		if ($perso->getPa() < 50)
			return fctErrorMSG('Vous n\'avez pas assez de PA.', '?popup=1&m=Action_LaboDrogue');
		
		//Valider si le lieu actuel est un labo
		if(!$perso->getLieu()->isLaboDrogue($db))
			return fctErrorMSG('Ce lieu n\'est pas un laboratoire de drogue.', '?popup=1&m=Action_LaboDrogue');
		
		
		
		
		//LISTER TOUTES LES DROGUES QUE LE PERSO A S�LECTIONN� (et poss�de sur lui)
		$i=0; $drogues = array();
		while( $item = $perso->getInventaire($db, $i++))
			if($item instanceof Member_ItemDrogueSubstance)
				if(isset($_POST['drqte_' . $item->getInvId()]) && $_POST['drqte_' . $item->getInvId()]>0)
					$drogues[count($drogues)] = array(
												'item'	=> $item,
												'qte'	=> $_POST['drqte_' . $item->getInvId()]		//Qte en % du comprim� que l'on souhaite produire
											);
				
		
		//Valider les quantit�s (par type et totale)
		$qteTotal = 0;
		foreach($drogues as $drogue){
			if(!is_numeric($drogue['qte']))
				return fctErrorMSG('Les quantit�s doivent �tre des chiffres entiers', '?popup=1&m=Action_LaboDrogue');
			
			if($drogue['qte']>$drogue['item']->getQte())
				return fctErrorMSG('Vous ne pouvez pas utiliser plus que vous poss�dez', '?popup=1&m=Action_LaboDrogue');
			
			$drogue['qte'] = floor($drogue['qte']);
			
			$qteTotal+= $drogue['qte'];
		}
		if ($qteTotal<10)
			return fctErrorMSG('Une pr�paration demande une quantit� minimale de 10.', '?popup=1&m=Action_LaboDrogue');
		
		
		//�tablir le % de chaque substance par rapport au total
		for($i=0; $i<count($drogues); $i++)
			$drogues[$i]['perc'] = round(100/$qteTotal * $drogues[$i]['qte']);
		
		
		
		//D�terminer la quantit� de perte lors de la pr�paration (�vaporation par exemple)
		if($qteTotal>500)
			$pertePerc = rand(5,10);
		elseif($qteTotal<500)
			$pertePerc = rand(8,15);
		elseif($qteTotal<100)
			$pertePerc = rand(10,25);
		elseif($qteTotal<50)
			$pertePerc = rand(20,40);
		elseif($qteTotal<25)
			$pertePerc = rand(30,60);
		
		//Mettre � jour la quantit� totale � produire en fonction du taux de perte
		$qteTotal -= round($qteTotal * $pertePerc / 100);
		
		
		
		
		//Retirer la quantit� d'item de l'inventaire
		foreach($drogues as $drogue){
			if($drogue['item']->getQte()==$drogue['qte']){
				$query = 'DELETE FROM ' . DB_PREFIX . 'item_inv
							WHERE	inv_persoid=' . $perso->getId() . '
								AND inv_id=' . $drogue['item']->getInvId() . '
							LIMIT 1;';
				$db->query($query, __FILE__, __LINE__);
			}else{
				$query = 'UPDATE ' . DB_PREFIX . 'item_inv
							SET		inv_qte=' . ($drogue['item']->getQte() - $drogue['qte']) . '
							WHERE	inv_persoid=' . $perso->getId() . '
								AND inv_id=' . $drogue['item']->getInvId() . '
							LIMIT 1;';
				$db->query($query, __FILE__, __LINE__);
			}
		}
		
		
		//Cr�er les drogues
		$taux = $perso->getChancesReussite($db, 'CHIM');
		$de = rand(1,100);
		
		
		if ($de<=$taux){
			//R�ussite de la production
			
			$msg = "Vous r�ussissez votre production avec succ�s.";
			$msg .= "\n" . $perso->setComp($db, array('CHIM' => rand(1,3) ));
			$msg .= "\n" . $perso->setStat($db, array('INT' => '+01', 'DEX' => '+01', 'PER' => '-02' ));
			

			
			$duree		= 0;
			$shockPa	= 0;
			$shockPv	= 0;
			$boostPa	= 0;
			$boostPv	= 0;
			$percAgi	= 0;
			$percDex	= 0;
			$percPer	= 0;
			$percFor	= 0;
			$percInt	= 0;
			foreach($drogues as $drogue){
				$duree		+= $drogue->getDuree();
				$shockPa	+= $drogue->getShockPa();
				$shockPv	+= $drogue->getShockPv();
				$boostPa	+= $drogue->getBoostPa();
				$boostPv	+= $drogue->getBoostPv();
				$percAgi	+= $drogue->getPercStatAgi();
				$percDex	+= $drogue->getPercStatDex();
				$percPer	+= $drogue->getPercStatPer();
				$percFor	+= $drogue->getPercStatFor();
				$percInt	+= $drogue->getPercStatInt();
			}
			$tot = count($drogues);
			$duree		/= $tot;
			$shockPa	/= $tot;
			$shockPv	/= $tot;
			$boostPa	/= $tot;
			$boostPv	/= $tot;
			$percAgi	/= $tot;
			$percDex	/= $tot;
			$percPer	/= $tot;
			$percFor	/= $tot;
			$percInt	/= $tot;
			
			
			//Cr�er les comprim�s/doses de drogue
			$query = 'INSERT INTO ' . DB_PREFIX . 'item_inv
						(
							`inv_dbid`,				`inv_qte`,				`inv_persoid`,
							`inv_duree`,			`inv_shock_pa`,			`inv_shock_pv`,		`inv_boost_pa`,		`inv_boost_pv`,
							`inv_perc_stat_agi`,	`inv_perc_stat_dex`,	`inv_perc_stat_per`,
							`inv_perc_stat_for`,	`inv_perc_stat_int`
						)
						VALUES
						(
							1,						' . $qteTotal . ',		' . $perso->getId() . ',
							' . $duree . ',			' . $shockPa . ',		' . $shockPv . ',	' . $boostPa . ',	' . $boostPv . ',
							' . $percAgi . ',		' . $percDex . ',		' . $percPer . ',
							' . $percFor . ',		' . $percInt . '
						);';
			$db->query($query, __FILE__, __LINE__);
			//$drogue_id = mysql_insert_id($db->getConnectionId());
			
		}else{
			//�chec de la production
			
			$msg = "Vous temptez une production de drogue, mais malheureusement le m�lange �choue.";
			$msg .= "\n" . $perso->setComp($db, array('CHIM' => rand(1,3) ));
			$msg .= "\n" . $perso->setStat($db, array('INT' => '+01', 'DEX' => '+01', 'PER' => '-02' ));
		}
		
		//Copier le message dans les HE
		//echo $msg . "(de = {$de}/{$taux})";
		Member_He::add($db, 'System', $perso->getId(), 'labodrogue', $msg);
		
		//Rafraichir le HE
		return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/herefresh.htm',__FILE__,__LINE__);
	}
}
?>