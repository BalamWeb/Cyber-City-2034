<?php
/** Gestion de l'interface des d�placement
*
* @package Member_Action
*/
class Member_Action_Deplacement{
	public static function generatePage(&$tpl, &$db, &$session, &$account, &$perso)
	{	//BUT: D�marrer un template propre � cette page
		
		
		//V�rifier l'�tat du perso
		if(!$perso->isAutonome())
			return fctErrorMSG('Votre n\'�tes pas en �tat d\'effectuer cette action.');
			
		
		//G�n�rer la liste des lieux connexes
		$i=0;
		$arrLieux = array();
		while($lien = $perso->getLieu()->getLink($db, $i, $perso->getId()))
			$arrLieux[$i++] = $lien;
		$tpl->set('LIEUX', $arrLieux);
		
		
		//G�n�rer la liste des personnages (� qui nous pourrions tenir la porte)
		$i=0; $e=0;
		$arrPersos = array();
		while( $tmp = $perso->getLieu()->getPerso($db, $perso, $i++))
			if($tmp->getId() != $perso->getId())
				$arrPersos[$e++] = $tmp;
		$tpl->set('PERSOS', $arrPersos);
		
		
		//Retourner le template compl�t�/rempli
		return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/Action/deplacement.htm',__FILE__,__LINE__);
	}
}

?>