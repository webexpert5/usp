<?php
/**
 * Plugin Name: WPDB PHP Sessions
 * Description: Use $wpdb to store $_SESSION data.
 */

class RM_WPDB_Session_Handler {
	public static $instance = null;
	public static $config = null;

	public $wpdb = null;
	public $table = 'rm_sessions';
	public $version = 1;

	/**
	 * Open a session.
	 */
	public function open() {
		return true;
	}
        

	/**
	 * Close a session.
	 */
	public function close() {
		return true;
	}

	/**
	 * Read session data.
	 *
	 * @param sting $id Session id.
	 * @return mixed Session data or null.
	 */
	public function read( $id ) {
		if ( ! $this->wpdb )
			return false;
                
                return $this->wpdb->get_var( $this->wpdb->prepare("SELECT `data` FROM {$this->wpdb->prefix}{$this->table} WHERE id = %s", $id ) );
	}

	/**
	 * Write a session.
	 *
	 * @param string $id Session id.
	 * @param string $data Session data (serialized for session storage).
	 */
	public function write( $id, $data ) {
		if ( ! $this->wpdb )
			return false;

		return (bool)$this->wpdb->query( $this->wpdb->prepare( "REPLACE INTO {$this->wpdb->prefix}{$this->table} VALUES ( %s, %s, %d );", $id, $data, time() ) );
	}

	/**
	 * Destroy a session.
	 *
	 * @param string $id Session id.
	 */
	public function destroy( $id ) {
		if ( ! $this->wpdb )
			return false;

		return (bool) $this->wpdb->query( $this->wpdb->prepare( "DELETE FROM {$this->wpdb->prefix}{$this->table} WHERE `id` = %s;", $id ) );
	}

	/**
	 * Garbage collection.
	 */
	public function gc( $max ) {
		return true;
	}
	
	/**
	 * Cron-powered garbage collection.
	 */
	public function cron_gc() {
		global $wpdb;
		$wpdb->query( $wpdb->prepare( "DELETE FROM {$this->wpdb->prefix}{$this->table} WHERE `timestamp` < %d;", time() - HOUR_IN_SECONDS * 24 ) );
	}

	/**
	 * If we have a global configuration, try and read it.
	 *
	 * @param array $defaults The default settings.
	 */
	public static function maybe_user_config( $defaults ) {
		if ( ! function_exists( 'pj_user_config' ) )
			return $defaults;

		$pj_user_config = pj_user_config();
		if ( empty( $pj_user_config['wpdb_sessions'] ) || ! is_array( $pj_user_config['wpdb_sessions'] ) )
			return $defaults;

		return wp_parse_args( $pj_user_config['wpdb_sessions'], $defaults );
	}

	/**
	 * Runs at the end of this script.
	 */
	public static function init() {     
		self::$config = self::maybe_user_config( array(
			'enable' => true,
		) );
                
		// Enable this plugin via a pj user config.
		if (! self::$config['enable'] )
			return null;

		if ( ! self::$instance ) { 
			self::$instance = new RM_WPDB_Session_Handler;
			self::$instance->wpdb = $GLOBALS['wpdb']; 
			RM_Table_Tech::create_session_table();
                        
			 session_set_save_handler(
				array( self::$instance, 'open' ),
				array( self::$instance, 'close' ),
				array( self::$instance, 'read' ),
				array( self::$instance, 'write' ),
				array( self::$instance, 'destroy' ),
				array( self::$instance, 'gc' )
			);
			register_shutdown_function( 'session_write_close' );

			if ( ! wp_next_scheduled( 'pj_wpdb_sessions_gc' ) )
				wp_schedule_event( time(), 'hourly', 'pj_wpdb_sessions_gc' );

			add_action( 'pj_wpdb_sessions_gc', array( self::$instance, 'cron_gc' ) );
		}

		return self::$instance;
	}

	// No outsiders.
	private function __construct() {}
}

RM_WPDB_Session_Handler::init();
