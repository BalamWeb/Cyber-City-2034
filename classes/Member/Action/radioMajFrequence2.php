<?php
/** Gestion des radio: Mettre � jour la fr�quence de la radio
*
* @package Member_Action
*/
class Member_Action_RadioMajFrequence2 {
	public static function generatePage(&$tpl, &$db, &$session, &$account, &$perso)
	{
		$i=0;
		while( $item = $perso->getInventaire($db, $i++)){

			if(is_a($item, 'Member_ItemRadio') && ($item->getInvId() == $_POST['idradio'])){
				$radio = $item;
				}
			}
			
	if(!is_numeric($_POST['frequence'])){return fctErrorMSG('Cette fr�quence n\'est pas num�rique', '?popup=1&m=Action_RadioMajFrequence');}
	
	echo $radio->majFrequence($db,$_POST['frequence']);
	

	//Retourner le template compl�t�/rempli
	return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/Action/radioMajFrequence2.htm',__FILE__,__LINE__);	
	}
}