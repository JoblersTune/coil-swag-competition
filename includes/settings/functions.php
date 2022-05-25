<?php
declare(strict_types=1);
/**
 * Coil Swag settings.
 * Creates and renders the Coil Swag settings panel
 */

namespace CoilSwag\Settings;

use CoilSwag\Rendering;

/* ------------------------------------------------------------------------ *
 * Menu Registration
 * ------------------------------------------------------------------------ */

/**
 * Add Coil settings to the admin navigation menu.
 *
 * @return void
 */
function register_admin_menu() : void {

	add_menu_page(
		'Coil Swag',
		'Coil Swag',
		'manage_options',
		'coil_swag',
		__NAMESPACE__ . '\render_coil_swag_screen',
		'data:image/svg+xml;base64,' . base64_encode( '<svg height="20" viewBox="0 0 20 20" width="20" xmlns="http://www.w3.org/2000/svg"><path clip-rule="evenodd" d="m10 18c4.4183 0 8-3.5817 8-8 0-4.41828-3.5817-8-8-8-4.41828 0-8 3.58172-8 8 0 4.4183 3.58172 8 8 8zm3.1274-5.5636c-.1986-.4734-.4822-.5848-.6997-.5848-.0778 0-.1556.0156-.2045.0253-.0046.0009-.0089.0018-.0129.0026-.0785.0337-.1576.1036-.2553.19-.2791.2466-.7099.6274-1.7113.6824h-.1607c-1.03998 0-2.00434-.5383-2.49598-1.4014-.22691-.4084-.34036-.8632-.34036-1.318 0-.529.15127-1.06731.46327-1.53137.23636-.36197.69963-.94669 1.53163-1.19728.39709-.12066.74691-.16706 1.04944-.16706.9455 0 1.3804.4919 1.3804.85387 0 .1949-.1229.35268-.3593.38053-.0284.00928-.0473.00928-.0756.00928-.0851 0-.1797-.01856-.2553-.06497-.0284-.01856-.0662-.02784-.104-.02784-.2931 0-.6996.50118-.6996.92812 0 .31556.2269.594.8981.594.1121 0 .2309-.0133.3679-.02864.0249-.00279.0504-.00564.0765-.00849.7375-.10209 1.3709-.62184 1.56-1.29937.0284-.08353.0567-.22275.0567-.40837 0-.42694-.1702-1.08591-.9927-1.68919-.5862-.43621-1.2575-.56615-1.8625-.56615-.62404 0-1.1724.13922-1.4844.24131-1.22909.39909-1.92872 1.13231-2.288 1.68919-.46327.69609-.69963 1.50355-.69963 2.31103 0 .6961.17018 1.3829.52 2.0047.74691 1.318 2.19345 2.1347 3.75343 2.1347.0378 0 .078-.0023.1182-.0046s.0804-.0047.1182-.0047c1.0494-.0557 2.8552-.761 2.8552-1.5406 0-.065-.0189-.1393-.0472-.2042z" fill-rule="evenodd" fill="black"/></svg>' )
	);
}

/* ------------------------------------------------------------------------ *
 * Setting Registration
 * ------------------------------------------------------------------------ */

/**
 * Initialize the Coil Swag page by registering the Sections,
 * Fields, and Settings.
 *
 * @return void
 */
function register_admin_content_settings() {

	// Tab 1 - Swag
	register_setting(
		'coil_swag_settings_group',
		'coil_swag_settings_group',
		__NAMESPACE__ . '\coil_swag_settings_group_validation'
	);

	// ==== Welcome Note, Entry Form
	add_settings_section(
		'coil_swag_section',
		false,
		__NAMESPACE__ . '\coil_swag_settings_render_callback',
		'coil_swag_section'
	);

	// Tab 2 - Winners
	register_setting(
		'coil_winners_settings_group',
		'coil_winners_settings_group',
		__NAMESPACE__ . '\coil_winners_settings_group_validation'
	);

	// ==== Displays participants
	add_settings_section(
		'coil_participants_section',
		false,
		__NAMESPACE__ . '\coil_participants_settings_render_callback',
		'coil_participants_section'
	);

	// ==== Enter contenders
	add_settings_section(
		'coil_contenders_section',
		false,
		__NAMESPACE__ . '\coil_contenders_settings_render_callback',
		'coil_contenders_section'
	);

	// ==== Displays winners
	add_settings_section(
		'coil_winners_section',
		false,
		__NAMESPACE__ . '\coil_winners_settings_render_callback',
		'coil_winners_section'
	);
}

/* ------------------------------------------------------------------------ *
 * Section Validation
 * ------------------------------------------------------------------------ */

/**
 * Validates the swag entry settings.
 *
 * @param array $swag_settings
 * @return array
*/
function coil_swag_settings_group_validation( $swag_settings ): array {
	$final_settings = get_option( 'coil_swag_settings_group', false ) ? get_option( 'coil_swag_settings_group' ) : [];

	if ( isset( $swag_settings['coil_entry_email'] ) && isset( $swag_settings['coil_entry_link'] ) ) {
		$email     = sanitize_email( $swag_settings['coil_entry_email'] );
		$link      = esc_url( $swag_settings['coil_entry_link'] );
		$new_entry = [
			'coil_entry_email' => filter_var( $email, FILTER_VALIDATE_EMAIL ),
			'coil_entry_link'  => filter_var( $link, FILTER_VALIDATE_URL ),
		];

		if ( $new_entry['coil_entry_email'] && $new_entry['coil_entry_link'] ) {
			array_push( $final_settings, $new_entry );
		}
	}

	return $final_settings;
}

/**
 * Validates the swag winners entries.
 *
 * @param array $winner_emails
 * @return array
*/
function coil_winners_settings_group_validation( $winner_emails ): array {
	$final_settings = get_option( 'coil_winners_settings_group', false ) ? get_option( 'coil_winners_settings_group' ) : [];

	if ( isset( $winner_emails['coil_entry_email'] ) ) {
		$email     = sanitize_email( $winner_emails['coil_entry_email'] );
		$new_entry = [
			'coil_entry_email' => filter_var( $email, FILTER_VALIDATE_EMAIL ),
		];

		if ( $new_entry['coil_entry_email'] ) {
			array_push( $final_settings, $new_entry );
		}
	}

	return $final_settings;
}

/* ------------------------------------------------------------------------ *
 * Settings Rendering
 * ------------------------------------------------------------------------ */

/**
 * Renders the output of the Competition Entry tab.
 *
 * @return void
*/
function coil_swag_settings_render_callback() {
	?>
	<div class="tab-styling">
		<?php

		Rendering\render_settings_section_heading(
			'Coil Swag Competition'
		);

		printf(
			'<p>%s</p>',
			'Participants send an email containing a valid URL for a web monetized site. Capture the email of the sender and the URL that was sent to enter them into the competition.'
		);

		// Swag Entry Section
		Rendering\render_text_input_field( 'coil_entry_email', 'coil_swag_settings_group[coil_entry_email]', '', 'example@mail.com', 'Email', 'Enter the email address associated with the submitted URL' );
		Rendering\render_text_input_field( 'coil_entry_link', 'coil_swag_settings_group[coil_entry_link]', '', 'https://example.com', 'URL', 'Enter a valid URL for a web monetized site' );

		?>
	</div>
	<?php
}

/**
 * Renders a list of all participants.
 *
 * @return void
*/
function coil_participants_settings_render_callback() {
	Rendering\render_settings_section_heading(
		'Coil Swag Participants'
	);

	$entries = get_option( 'coil_swag_settings_group', [] );
	if ( count( $entries ) > 0 ) {
		echo( '<ol>' );
		foreach ( $entries as $entry ) {
			printf(
				'<li> Email: <span class="email">%s</span> Link: <span class="site">%s</span></li>',
				$entry['coil_entry_email'],
				$entry['coil_entry_link']
			);
		}
		echo( '</ol>' );
	}
}

/**
 * Allows entry of valid contender's emails.
 *
 * @return void
*/
function coil_contenders_settings_render_callback() {
	Rendering\render_settings_section_heading(
		'Enter Coil Swag Contender Emails'
	);

	// Swag Entry Section
	Rendering\render_text_input_field( 'coil_contender_email', 'coil_winners_settings_group[coil_entry_email]', '', 'example@mail.com', 'Email', 'Enter the email address associated with the submitted URL' );

}

/**
 * Renders the output of the Announce Winners tab.
 *
 * @return void
*/
function coil_winners_settings_render_callback() {
	Rendering\render_settings_section_heading(
		'Coil Swag Winners'
	);

	$winners = get_winners( 2 );

	if ( count( $winners ) > 0 ) {
		echo( '<ol>' );
		foreach ( $winners as $participant ) {
			echo( '<li>' . $participant . '</li>' );
		}
		echo( '</ol>' );
	}
}

/**
 * Render the Coil Swag setting screen
 *
 * @return void
*/
function render_coil_swag_screen() : void {

	?>
	<div class="wrap coil plugin-header">
		<?php $active_tab = isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : 'coil_competition'; ?>
		<h2 class="nav-tab-wrapper">
			<a href="<?php echo esc_url( '?page=coil_swag&tab=coil_competition' ); ?>" id="coil-swag-settings" class="nav-tab <?php echo $active_tab === 'coil-competition' ? esc_attr( 'nav-tab-active' ) : ''; ?>">Competition Entry</a>
			<a href="<?php echo esc_url( '?page=coil_swag&tab=coil_winners' ); ?>" id="coil-swag-winners" class="nav-tab <?php echo $active_tab === 'coil-winners' ? esc_attr( 'nav-tab-active' ) : ''; ?>">Announce Winners</a>
		</h2>
	</div>
	<div class="wrap coil plugin-settings">

		<?php settings_errors(); ?>

		<form action="options.php" method="post">
			<?php
			switch ( $active_tab ) {
				case 'coil_competition':
					settings_fields( 'coil_swag_settings_group' );
					do_settings_sections( 'coil_swag_section' );
					submit_button();
					break;
				case 'coil_winners':
					settings_fields( 'coil_winners_settings_group' );
					do_settings_sections( 'coil_participants_section' );
					do_settings_sections( 'coil_contenders_section' );
					submit_button();
					//do_settings_sections( 'coil_winners_section' );
					break;
			}
			?>
		</form>
	</div>
	<?php
}

/**
 * Queries the database to see if there are any valid entries.
 * Returns up to $max_winners winners.
 *
 * @param int
 * @return array
*/
function get_winners( $max_winners ) {
	$entries            = get_option( 'coil_swag_settings_group', [] );
	$participant_emails = [];
	$contender_emails   = [];
	foreach ( $entries as $entry ) {
		array_push( $participant_emails, $entry['coil_entry_email'] );
	}

	$participant_emails = array_unique( $participant_emails );

	$num_valid_entries = count( $participant_emails );

	if ( $num_valid_entries <= $max_winners ) {
		return $participant_emails;
	}

	$winner_indices = [];

	while ( count( $winner_indices ) < $max_winners ) {
		$index = wp_rand( 0, $num_valid_entries - 1 );
		if ( ! in_array( $index, $winner_indices, true ) ) {
			array_push( $winner_indices, $index );
			array_push( $contender_emails, $participant_emails[ $index ] );
		}
	}

	return $contender_emails;
}
