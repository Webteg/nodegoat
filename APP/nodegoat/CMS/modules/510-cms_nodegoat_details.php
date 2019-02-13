<?php

/**
 * nodegoat - web-based data management, network analysis & visualisation environment.
 * Copyright (C) 2019 LAB1100.
 * 
 * nodegoat runs on 1100CC (http://lab1100.com/1100cc).
 *
 * See http://nodegoat.net/release for the latest version of nodegoat and its license.
 */

DB::setTable('TABLE_NODEGOAT_DETAILS', DB::$database_home.'.def_nodegoat_details');

DB::setTable('TABLE_USER_DETAILS' , DB::$database_home.'.user_details');
DB::setTable('TABLE_USER_PREFERENCES' , DB::$database_home.'.user_preferences');

define('NODEGOAT_CLEARANCE_DEMO', 1);
define('NODEGOAT_CLEARANCE_INTERACT', 2);
define('NODEGOAT_CLEARANCE_UNDER_REVIEW', 3);
define('NODEGOAT_CLEARANCE_USER', 4);
define('NODEGOAT_CLEARANCE_ADMIN', 5);

define('DIR_CACHE_SCENARIOS', DIR_ROOT_CACHE.SITE_NAME.'/scenarios/');

class cms_nodegoat_details extends base_module {

	public static function moduleProperties() {
		static::$label = getLabel('ttl_details');
		static::$parent_label = 'nodegoat';
	}
	
	public function contents() {
		
		$return .= '<div class="section"><h1>'.self::$label.'</h1>
		<div class="nodegoat_details">';

			$arr = self::getDetails();
			
			$return .= '<div class="tabs">
				<ul>
					<li><a href="#">'.getLabel('lbl_settings').'</a></li>
				</ul>
				
				<div>
					<form id="f:cms_nodegoat_details:update-0">
						
						<div class="options">
							<fieldset><ul>
								<li>
									<label>'.getLabel('lbl_view_limit').'</label>
									<span><input type="text" name="details[limit_view]" value="'.$arr['limit_view'].'" /></span>
								</li>
							</ul></fieldset>
						</div>
						
						<input type="submit" value="'.getLabel('lbl_save').'" />
					
					</form>
				</div>
								
		</div></div>';
		
		return $return;
	}
		
	public static function css() {
	
		$return = '.nodegoat_details input[type=submit] { display: block; margin: 15px auto 0px auto; }';
		
		return $return;
	}
	
	public static function js() {
	
		$return = "";
		
		return $return;
	}

	public function commands($method, $id, $value = "") {
		
		// INTERACT
						
		// DATATABLE
							
		// QUERY
		
		if ($method == 'update') {
		
			$arr_sql_fields = DBFunctions::arrEscape(array_keys($_POST['details']));
			$str_values = implode("','", DBFunctions::arrEscape($_POST['details']));

			$res = DB::query("INSERT INTO ".DB::getTable('TABLE_NODEGOAT_DETAILS')."
				(".implode(',', $arr_sql_fields).")
					VALUES
				('".$str_values."')
				".DBFunctions::onConflict('unique_row', $arr_sql_fields)."
			");
	
			$this->msg = true;				
		}
	}
	
	public static function getDetails() {
					
		$cache = self::getCache('details');
		if ($cache) {
			return $cache;
		}
					
		$arr = [];

		$res = DB::query("SELECT * FROM ".DB::getTable('TABLE_NODEGOAT_DETAILS')."");
		$arr = $res->fetchAssoc();
		
		self::setCache('details', $arr);
		
		return $arr;
	}
	
	public static function getClearanceLevels() {
					
		$arr = [
			['id' => NODEGOAT_CLEARANCE_DEMO, 'label' => getLabel('lbl_demo')],
			['id' => NODEGOAT_CLEARANCE_INTERACT, 'label' => getLabel('lbl_clearance_interact')],
			['id' => NODEGOAT_CLEARANCE_UNDER_REVIEW, 'label' => getLabel('lbl_clearance_under_review')],
			['id' => NODEGOAT_CLEARANCE_USER, 'label' => getLabel('lbl_clearance_user')],
			['id' => NODEGOAT_CLEARANCE_ADMIN, 'label' => getLabel('lbl_clearance_admin')]
		];

		return $arr;
	}
}
