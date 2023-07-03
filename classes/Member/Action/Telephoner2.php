<?php
/** Gestion de l'interface de l'action T�l�phoner: Envoyer le message
*
* @package Member_Action
*/
class Member_Action_Telephoner2 { 
	public static function generatePage(&$tpl, &$db, &$session, &$account, &$perso)
	{ 
		//V�rifications du post
		if(!isset($_POST['telephone']))
			return fctErrorMSG('Vous n\'avez pas s�lectionn� de t�l�phone.', '?popup=1&m=Action_Telephoner');
			// ^^ VOILA !!! C'est comme ca qu'on utilise la nouvelle m�thode :) pas compliqu� non ? Je te laisse t'ammuser � faire les autres :) :)
			
		if(empty($_POST['numero_destinataire']))
			return fctErrorMSG('Vous n\'avez pas compos� de num�ro de t�l�phone', '?popup=1&m=Action_Telephoner');
			
		if(empty($_POST['message']))
			return fctErrorMSG('Vous n\'avez pas entr� de message', '?popup=1&m=Action_Telephoner');
		
		
		//r�cup des info  du l'appeleur
		$i=0;
		while( $item = $perso->getInventaire($db, $i++)){

			if(is_a($item, 'Member_ItemTelephone')){
				if($item->getInvId() == $_POST['telephone'])
				
				$telephoneAppeleur = $item;
			}
		}	
			
		echo $telephoneAppeleur->envoyerMessage($db,$_POST['numero_destinataire'],$_POST['message'],$_POST['anonyme']);

		
		
	
	
	
	
	
	
	
	
	
	
	
	}
}