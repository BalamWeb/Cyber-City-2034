<?php
/** Gestion des drogues (drogues produite à partir de substances)
 *
 * @package Member
 * @subpackage Item
 */
class Member_ItemDrogueDrogue extends Member_ItemDrogue
{
		
	function __construct (&$arr)
	{
		parent::__construct($arr);
		
		$this->duree			= $arr['db_duree'];
		$this->boostPa			= $arr['db_boost_pa'];
		$this->shockPa			= $arr['db_shock_pa'];
		$this->boostPv			= $arr['db_boost_pv'];
		$this->shockPv			= $arr['db_shock_pv'];
		$this->percStatAgi		= $arr['db_perc_stat_agi'];
		$this->percStatDex		= $arr['db_perc_stat_dex'];
		$this->percStatPer		= $arr['db_perc_stat_per'];
		$this->percStatFor		= $arr['db_perc_stat_for'];
		$this->percStatInt		= $arr['db_perc_stat_int'];
	}
	
	
	
	
	/** Retourne le groupe affichable de l'item (pour l'affichage)
	 * @return string
	 */	
	public function getGroup()			{ return 'Consommable'; }
	
	/** Retourne le groupe (nom technique) de l'item (pour l'affichage)
	 * @return string
	 */	
	public function getGroupTech()		{ return 'consommable'; }
	
	/** Retourne le type affichable de l'item (pour l'affichage)
	 * @return string
	 */	
	public function getType()			{ return 'Drogue'; }
	
	/** Retourne le type (nom technique) de l'item (pour l'affichage)
	 * @return string
	 */	
	public function getTypeTech()		{ return 'drogue_drogue'; }
	
}


