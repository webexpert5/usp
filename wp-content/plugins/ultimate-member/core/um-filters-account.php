<?php

	/**
	 * Account default tabs
	 * @param  array $tabs 
	 * @return array $tabs
	 * @uses  um_account_page_default_tabs_hook
	 */
	add_filter('um_account_page_default_tabs_hook', 'um_account_page_default_tabs_hook' );
	function um_account_page_default_tabs_hook( $tabs ) {
		global $ultimatemember;
		
		foreach ($tabs as $k => $arr ) {
			foreach( $arr as $id => $info ) {
				
				$output = $ultimatemember->account->get_tab_output( $id );
				if ( !$output ) {
					unset( $tabs[$k][$id] );
				}
				
				if ( $id == 'delete' ) {
					if ( !um_user('can_delete_profile') && !um_user('can_delete_everyone') ) {

						unset( $tabs[$k][$id] );
					}
				}
				
			}
		}
		
		return $tabs;
	
	}

	/**
	 * Account secure fields
	 * @param  array $fields 
	 * @param  string $tab_key 
	 * @return array       
	 * @uses  um_account_secure_fields
	 */
	add_filter('um_account_secure_fields','um_account_secure_fields', 10, 2);
	function um_account_secure_fields( $fields, $tab_key ){
		global $ultimatemember;
		$secure = apply_filters('um_account_secure_fields__enabled', true );

		if( ! $secure ) return $fields;

		
		if( isset( $ultimatemember->account->register_fields ) && ! isset( $ultimatemember->account->register_fields[ $tab_key ] ) ){
			$ultimatemember->account->register_fields[ $tab_key ] = $fields;
		}

		

		return $fields;
	}