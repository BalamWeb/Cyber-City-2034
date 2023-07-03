<?php
/** Gestion de l'action de jeter un item. Cette page est utilis�e UNIQUEMENT par AJAX. des # d'erreur sont retourn�, pas des message. Aucune interface graphique.
*
* @package Member_Action
*/
class Member_Action_Siteadd{
	public static function generatePage(&$tpl, &$db, &$session, &$account, &$perso)
	{	//BUT: D�marrer un template propre � cette page
		
		$actionPa = 3;
		$cout_ouverture = 75;
		
		
		
		//TODO: Valider si le lieu donne acc�s � l'ordinateur + Internet
		
		
		//V�rifier l'�tat du perso
		if(!$perso->isConscient())
			die('01|' . rawurlencode('Votre n\'�tes pas en �tat d\'effectuer cette action.'));
		
		if($perso->getPa() < $actionPa)
			die('02|' . rawurlencode('Vous n\'avez pas assez de PA pour effectuer cette action.'));
		
		if(!preg_match('/^[A-Za-z0-9.-_]+$/', $_POST['url'], $matches))
			die('03|' . rawurlencode('L\'URL dU site est invalide.'));
		
		if(!preg_match('/^([0-9]{4})-([0-9]{4}-[0-9]{4}-[0-9]{4})-([0-9]+)$/', $_POST['no'], $matches))
			die('04|' . rawurlencode('Le # de la carte est invalide.'));
		
		$carte_banque = $matches[1];
		$carte_compte = $matches[2];
		$carte_id = $matches[3];
		
		$query = 'SELECT * 
			FROM ' . DB_PREFIX . 'banque_cartes
			WHERE 	carte_banque= ' . $carte_banque . '
				AND carte_compte="' . $carte_compte . '"
				AND carte_id = ' . $carte_id . '
			LIMIT 1;';
		$result = $db->query($query, __FILE__, __LINE__);
		if (mysql_num_rows($result)==0)
			die('05|' . rawurlencode('Cette carte est innexistante.'));
		
		$carte = mysql_fetch_assoc($result);
		
		if($carte['carte_valid']==0)
			die('06|' . rawurlencode('Cette carte a �t� d�sactiv�e.'));
		
		if($carte['carte_nip'] != $_POST['nip'])
			die('07|' . rawurlencode('NIP erron�.'));
		
		
		//Valider le montant du compte associ� � la carte
		$query = 'SELECT *
					FROM ' . DB_PREFIX . 'banque_comptes
					WHERE	compte_banque= ' . $carte_banque . '
						AND compte_compte="' . $carte_compte . '";';
		$result = $db->query($query, __FILE__, __LINE__);
		if (mysql_num_rows($result)==0)
			die('08|' . rawurlencode('Le compte associ� � la carte a �t� ferm�.'));
		
		$arr = mysql_fetch_assoc($result);
		$compte = new Member_Banquecompte($arr);
		
		if ($compte->getCash() < $cout_ouverture)
			die('09|' . rawurlencode('Compte sans fond.'));
		
		
		//V�rifier si l'URL existe d�j�
		$query = 'SELECT id
					FROM ' . DB_PREFIX . 'sitesweb
					WHERE url="' . $_POST['url'] . '";';
		$result = $db->query($query,__FILE__,__LINE__);
		if (mysql_num_rows($result)>0)
			die('10|' . rawurlencode('Cette URL existe d�j�, veuillez en choisir une autre.'));
			
			
			
			
		//Tout est ok, Cr�er le site !!!!! 
		
		$perso->changePa('-', $actionPa);
		$perso->setPa($db);
		
		$compte->changeCash('-', $cout_ouverture);
		$compte->setCash($db);
		
		$compte->add_bq_hist($db, '', 'WWWA', $cout_ouverture);
		
		$query = 'INSERT INTO ' . DB_PREFIX . 'sitesweb
					(`url`, `titre`, `acces`)
					VALUES
					("' . addslashes($_POST['url']) . '","' . addslashes($_POST['titre']) . '","pub");';
		$db->query($query,__FILE__,__LINE__);
		
		$siteid = mysql_insert_id($db->getConnectionId());
		
		
		//Cr�er l'acc�s admin au site
		
		$query = 'INSERT INTO ' . DB_PREFIX . 'sitesweb_acces
					(`site_id`, `user`, `pass`, `accede`, `poste`, `modifier`, `admin`)
					VALUES
					("' . $siteid . '","' . addslashes($_POST['user']) . '","' . addslashes($_POST['pass']) . '", "1","1","1","1");';
		$db->query($query,__FILE__,__LINE__);
		
		
		
		die('OK|' . $perso->getPa() . '|' . $_POST['url']); //Tout est OK
	}
}
?>