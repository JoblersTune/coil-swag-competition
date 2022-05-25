// ***********************************************
// This example commands.js shows you how to
// create various custom commands and overwrite
// existing commands.
//
// For more comprehensive examples of custom
// commands please read more here:
// https://on.cypress.io/custom-commands
// ***********************************************
//

/**
 * Authenticate with WordPress.
 *
 * @param {string} username WordPress user name.
 * @param {string} password WordPress password.
 */
 Cypress.Commands.add( 'logInToWordPress', ( username, password ) => {
	 cy.visit( 'http://wordcamp.local/wp-admin/about.php' );
	cy.request( {
		method: 'POST',
		url: '/wp-login.php',
		form: true,
		body: {
			log: username,
			pwd: password,
		},
	} );

	// Verify by asserting an authentication cookie exists.
	cy.getCookies().then( ( cookies ) => {
		let authCookie = '';

		cookies.forEach( theCookie => {
			if ( theCookie.name.startsWith( 'wordpress_logged_in_' ) ) {
				authCookie = theCookie.name;
			}
		} );

		expect( authCookie ).to.include( 'wordpress_logged_in_' );
	} );

	// TODO add reset ability to clear winner's db option
} );

/**
 * Add email to winners list.
 */
 Cypress.Commands.add( 'addToWinnerList', ( email ) => {
	cy.visit( 'http://wordcamp.local/wp-admin/admin.php?page=coil_swag&tab=coil_winners' );

	cy
		.get( '#coil_contender_email' )
		.type( `{selectall}${ email }` );

	cy
		.get( '#submit' )
		.click();
} );
