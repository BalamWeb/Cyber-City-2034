<?php
/** Gestion de l'interface de Coup d'oeil au lieu actuel
*
* @package Member_Action
*/
class Member_Action_Coupdoeil{
	public static function generatePage(&$tpl, &$db, &$session, &$account, &$perso)
	{	//BUT: D�marrer un template propre � cette page
		
		
		//V�rifier l'�tat du perso
		if(!$perso->isConscient())
			return fctErrorMSG('Votre n\'�tes pas en �tat d\'effectuer cette action.');
		
		
		//G�n�rer les informations sur le lieu actuel
		$tpl->set('LIEU_NOM',	$perso->getLieu()->getNom());
		$tpl->set('LIEU_DESC',	nl2br($perso->getLieu()->getDescription()));
		$tpl->set('LIEU_IMG', 	$perso->getLieu()->getImage());
		$tpl->set('id', 		$perso->getId());	//Afin d'�viter de s'auto-renommer.
		
		//G�n�rer la liste des personnages pr�sent dans le lieu actuel
		$i=0; $e=0;
		$arrPersos = array();
		while( $tmp = $perso->getLieu()->getPerso($db, $perso, $i++)){
		
			//Info du perso
			$arrPersos[$e]['perso'] = $tmp;
			
			//Info sur l'arme (Mains nues si aucune)
			$arrPersos[$e]['arme'] = $tmp->getArme($db)->getNom();
			
			//Info TXT sur l'�tat du perso
			if ($tmp->isNormal())
				$arrPersos[$e]['etat'] = "En sant�";
			elseif ($tmp->isAutonome())
				$arrPersos[$e]['etat'] = "L�g�rement bless�";
			elseif($tmp->isConscient())
				$arrPersos[$e]['etat'] = "Bless� gravement";
			elseif($tmp->isVivant())
				$arrPersos[$e]['etat'] = "Inconscient";
			else
				$arrPersos[$e]['etat'] = "Mort";
				
			$e++;
		}
		$tpl->set('PERSOS', $arrPersos);
		
		
		
		//Retourner le template compl�t�/rempli
		return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/Action/coupdoeil.htm',__FILE__,__LINE__);
	}
}

?>
