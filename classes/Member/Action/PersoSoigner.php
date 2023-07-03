<?php
/** Soigner un personnage (blessures superficielles): But g�n�rer un template des personnes bless�es l�ger � soigner
*
* @package Member_Action
*/
class Member_Action_PersoSoigner {
	public static function generatePage(&$tpl, &$db, &$session, &$account, &$perso)
	{

		$i=0;$e=0;
		$persoSoignable = array();
		while($arrPerso = $perso->getLieu()->getPerso($db, $perso, $i++)){
			if(($arrPerso->getPv() < $arrPerso->getPvMax()))
			{
				$persoSoignable[$e++]['perso'] = $arrPerso;
			//Info TXT sur l'�tat du perso
			if ($arrPerso->isNormal())
				$persoSoignable[$e-1]['etat'] = "En sant�";
			elseif ($arrPerso->isAutonome())
				$persoSoignable[$e-1]['etat'] = "L�g�rement bless�";
			elseif($arrPerso->isConscient())
				$persoSoignable[$e-1]['etat'] = "Bless� gravement";
			elseif($arrPerso->isVivant())
				$persoSoignable[$e-1]['etat'] = "Inconscient";
			else
				$persoSoignable[$e-1]['etat'] = "Mort";
			}
		}
		
		$i=0; $e=0;
		$trousses=array();
		while( $item = $perso->getInventaire($db, $i++)){
			if(is_a($item, 'Member_ItemTrousse')){
				if($item->getResistance() > 0)
					$trousses[$e++] = $item;
			}	
		}		

	$tpl->set('PERSO_SOIGNABLE', $persoSoignable);
	$tpl->set('TROUSSES', $trousses);
	
	//Retourner le template compl�t�/rempli
	return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/Action/persoSoigner.htm',__FILE__,__LINE__);	
	}
}

?>