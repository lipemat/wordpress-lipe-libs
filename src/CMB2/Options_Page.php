<?php

namespace Lipe\Lib\CMB2;

/**
 * Options_Page
 *
 * @author  Mat Lipe
 * @since   7/27/2017
 *
 * @package Lipe\Lib\CMB2
 */
class Options_Page extends Box {

	/**
	 * This parameter is for options-page metaboxes only and defaults to 'admin_menu',
	 * to register your options-page at the network level:
	 *
	 * @example 'network_admin_menu'
	 *
	 * @var string
	 */
	public $admin_menu_hook;

	/**
	 * This parameter is for options-page metaboxes only,
	 * and is sent along to add_menu_page()/add_submenu_page()
	 * to define the capability required to view the options page.
	 *
	 * @example 'edit_posts'
	 *
	 * @var string
	 */
	public $capability = 'manage_options';

	/**
	 * This parameter is for options-page metaboxes only
	 * and allows overriding the options page form output.
	 *
	 * @example 'my_callback_function_to_display_output'
	 *
	 * @var callable
	 */
	public $display_cb;

	/**
	 * This parameter is for options-page metaboxes only,
	 * and is sent along to add_menu_page() to define the menu icon.
	 * Only applicable if parent_slug is left empty.
	 *
	 * @example 'dashicons-chart-pie'
	 *
	 * @var string
	 */
	public $icon_url;

	/**
	 * This parameter is for options-page metaboxes only,
	 * and is sent along to add_menu_page()/add_submenu_page() to define the menu title.
	 *
	 * @example 'Site Options
	 *
	 * @var string
	 */
	public $menu_title;

	/**
	 * This parameter is for options-page metaboxes only,
	 * and is sent along to add_submenu_page() to define the parent-menu item slug.
	 *
	 * @exampl 'tools.php'
	 * @var string
	 */
	public $parent_slug;

	/**
	 * This parameter is for options-page metaboxes only,
	 * and is sent along to add_menu_page() to define the menu position.
	 * Only applicable if parent_slug is left empty.
	 *
	 * @example 1
	 *
	 * @var int
	 */
	public $position;

	/**
	 * This parameter is for options-page metaboxes only and
	 * defines the text for the options page save button. defaults to 'Save'.
	 *
	 * @example 'Save Settings'
	 *
	 * @var string
	 */
	public $save_button;


	/**
	 * Options Page constructor.
	 *
	 * @param  string $id
	 * @param  string $title
	 */
	public function __construct( $id, $title ) {
		$this->show_on = [
			'key'   => 'options-page',
			'value' => [ $this->id ],
		];
		parent::__construct( $id, [ 'options-page' ], $title );

		add_action( 'admin_menu', [ $this, 'add_options_page' ] );
	}


	public function add_options_page() {
		if( empty( $this->parent_slug ) ){
			$options_page = add_menu_page( $this->title, $this->title, $this->capability, $this->id, [
				$this,
				'admin_page_display',
			], $this->icon_url, $this->position );
		} else {
			$options_page = add_submenu_page( $this->parent_slug, $this->title, $this->title, $this->capability, $this->id, [
				$this,
				'admin_page_display',
			] );
		}

		add_action( "admin_print_styles-{$options_page}", [ 'CMB2_hookup', 'enqueue_cmb_css' ] );

	}


	public function admin_page_display() {
		?>
        <div class="wrap cmb2-options-page <?php echo $this->id; ?>">
            <h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
			<?php cmb2_metabox_form( $this->id, $this->id ); ?>
        </div>
		<?php
	}
}