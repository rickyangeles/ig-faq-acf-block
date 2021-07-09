<?php
/**
 * <%= pkg.projectName %>
 *
 * Contains blocks for the specific design of this theme.
 *
 * @link <%= pkg.projectUri %>
 *
 * @package <%= pkg.name %>
 * @since 1.0.0
 *
 * Plugin Name:      IG Block: FAQ
 * Plugin URI:       https://improveandgrow.com/
 * Description:      Custom ACF Blocks to display FAQ in an accordion style with Schema included
 * Version:          1.0.0
 * Author:           Improve & Grow (Ricky Angeles)
 * Author URI:       https://improveandgrow.com/
 * License:          GPL-2.0+
 * License URI:      http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:      <%= pkg.name %>
 * Domain Path:      /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly


define( 'IG_BLOCKS_PLUGIN_VERSION', '1.0.0' );
define( 'IG_BLOCKS_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'IG_BLOCKS_PLUGIN_PATH_URL', plugin_dir_url( __FILE__ ) );


function wds_acf_blocks_acf_json_save_point( $path ) {

	// Update the path.
	$path = get_stylesheet_directory( dirname( __FILE__ ) ) . '/acf-json';

	return $path;
}
add_filter( 'acf/settings/save_json', 'wds_acf_blocks_acf_json_save_point' );

/**
 * Specify the location for loading ACF JSON files.
 *
 * @param string $paths The paths from which we're loading the files.
 * @return string $paths
 * @author Corey Collins
 * @since 1.0
 */
function wds_acf_blocks_acf_json_load_point( $paths ) {

	// Remove original path (optional).
	unset( $paths[0] );

	// Append the new path.
	$paths[] = get_stylesheet_directory( dirname( __FILE__ ) ) . '/acf-json';

	return $paths;
}
add_filter( 'acf/settings/load_json', 'wds_acf_blocks_acf_json_load_point' );


if( function_exists('acf_add_local_field_group') ):

	acf_add_local_field_group(array(
		'key' => 'group_60e457f47873e',
		'title' => 'Block: FAQ Block',
		'fields' => array(
			array(
				'key' => 'field_60e457fbbb394',
				'label' => 'Question/Answer',
				'name' => 'qa',
				'type' => 'repeater',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'collapsed' => '',
				'min' => 0,
				'max' => 0,
				'layout' => 'block',
				'button_label' => '',
				'sub_fields' => array(
					array(
						'key' => 'field_60e4580abb395',
						'label' => 'Question',
						'name' => 'question',
						'type' => 'text',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'maxlength' => '',
					),
					array(
						'key' => 'field_60e45810bb396',
						'label' => 'Asnwer',
						'name' => 'asnwer',
						'type' => 'wysiwyg',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'default_value' => '',
						'tabs' => 'all',
						'toolbar' => 'full',
						'media_upload' => 1,
						'delay' => 0,
					),
				),
			),
			array(
				'key' => 'field_60e45819bb397',
				'label' => 'Enable FAQ Schema',
				'name' => 'enable_faq_schema',
				'type' => 'true_false',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'message' => '',
				'default_value' => 1,
				'ui' => 1,
				'ui_on_text' => '',
				'ui_off_text' => '',
			),
		),
		'location' => array(
			array(
				array(
					'param' => 'block',
					'operator' => '==',
					'value' => 'acf/faq',
				),
			),
		),
		'menu_order' => 0,
		'position' => 'normal',
		'style' => 'default',
		'label_placement' => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen' => '',
		'active' => true,
		'description' => '',
	));
	
	endif;

/**
 * Main class for plugin.
 */
class IG_BLOCKS_Plugin {

	/**
	 * Singleton Class.
	 *
	 * @var object
	 */
	private static $instance = null;

	/**
	 * Creates or returns an instance of this class.
	 */
	public static function get_instance() {
		// If an instance hasn't been created and set to $instance create an instance and set it to $instance.
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Initialize plugin if $instance is null.
	 */
	public function __construct() {

		if ( null === self::$instance ) {
			register_activation_hook( __FILE__, array( $this, 'activate' ) );
			register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );

			// Language file.
			add_action( 'plugins_loaded', array( $this, 'load_plugin_textdomain' ) );

			add_action( 'plugins_loaded', array( $this, 'load_acf_hooks' ) );

			// Add other hooks and filters.
			// * add_action( 'init', array( $this, 'setup_misc' ) );.

			// Register front end scripts so they can be enqueued if needed.
			add_action( 'wp_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

			// Some helper functions that could be placed in class.

			require_once IG_BLOCKS_PLUGIN_PATH . 'includes/helper-functions.php';
			require_once IG_BLOCKS_PLUGIN_PATH . 'includes/image-functions.php';

			self::$instance = $this;

			return self::$instance;

		} else {
			return self::$instance;
		}
	}

	/**
	 * Load blocks if ACF plugin is active.
	 */
	public function load_acf_hooks() {

		if ( function_exists( 'acf_register_block_type' ) ) {

			add_action( 'acf/init', array( $this, 'register_acf_block_types' ) );

			add_filter('acf/settings/load_json', 'my_acf_json_load_point');

			function my_acf_json_load_point( $paths ) {
				// remove original path (optional)
				unset($paths[0]);
				
				// append path
				$paths[] = IG_BLOCKS_PLUGIN_PATH . '/ig-faq-json';
				
				// return
				return $paths;
				
			}
		}
	}


	/**
	 * Register all the blocks.
	 */
	public function register_acf_block_types() {

		// register a faq block.
        acf_register_block_type(array(
            'name'              => 'faq',
            'title'             => __('FAQs'),
            'description'       => __('A content block to display FAQs.'),
            'render_template'   => IG_BLOCKS_PLUGIN_PATH . 'template-parts/blocks/faq/template.php',
            'enqueue_assets' => function(){
                wp_enqueue_script( 'jquery-ui-accordion' );
            },
            'category'          => 'formatting',
            'icon'              => 'admin-comments',
            'keywords'          => array( 'faq', 'question', 'answer', 'starter' ),
            'mode'				=> 'preview',
			'supports'			=> array(
				'align'  => array( 'full' ),
				'mode' => false,
				'jsx' => true
			),
        ));

		// Cannot just disable align as editor will not show it as full if supports:align is false.
		// https://github.com/AdvancedCustomFields/acf/issues/91.

		
	}
	/**
	 * Register scripts so they can be enqueued only when needed.
	 */
	public function enqueue_scripts() {

	}

	/**
	 * Editor/Admin scripts.
	 */
	public function admin_enqueue_scripts() {

		wp_enqueue_script( '<%= pkg.name %>-admin', plugins_url( '/js/admin-scripts.js', __FILE__ ), array( 'jquery' ), IG_BLOCKS_PLUGIN_VERSION, true );

	}

	/**
	 * Activate Plugin
	 */
	public static function activate() {
		// Do nothing.
	} // END public static function activate

	/**
	 * Deactivate the plugin
	 */
	public static function deactivate() {
		// Do nothing.
	} // END public static function deactivate

	/**
	 * Loading textdomain.
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain(
			'<%= pkg.name %>',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);
	}
}

if ( class_exists( 'IG_BLOCKS_Plugin' ) ) {

	IG_BLOCKS_Plugin::get_instance();
}
