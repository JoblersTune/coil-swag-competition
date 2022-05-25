/**
 * CSS selector tests.
*/

let email = '';
let site_url = '';

describe( 'Plugin Settings Panel', function() {
	beforeEach( () => {
		cy.logInToWordPress( 'admin', 'password' );
	} );

	it( 'check the compitition entry tab exists', function() {
		// get aray, while, catch is continue
		cy.visit( '/wp-admin/admin.php?page=coil_swag' );

		const site = 'https://www.freecodecamp.org/';
		const mail = 'new1@mail.com';

		cy.origin( 'https://coil.com/', () => {
			cy.visit( 'https://coil.com/' );
			cy.get( 'head meta[name="monetization"]' ).should( 'exist' );
		} );

		cy.logInToWordPress( 'admin', 'password' );
		cy.visit( '/wp-admin/admin.php?page=coil_swag' );

		cy
			.get( '#coil_entry_email' )
			.type( `{selectall}${ mail }` );

		cy
			.get( '#coil_entry_link' )
			.type( `{selectall}${ site }` );

		cy
			.get( '#submit' )
			.click();
	} );
	it.only( 'check the winners tab exists', function() {
		cy.visit( '/wp-admin/admin.php?page=coil_swag&tab=coil_winners' );

		cy.get( 'ol>li' ).each( ( $el ) => {
			cy.wrap($el).then( ( $item ) => {
				email = $item.children( '.email' ).text();
				site_url = $item.children( '.site' ).text();
				cy.origin( site_url, () => {
					cy.visit( '/' );
					cy.get( 'head' )
						.then(($head) => {
							if ( $head.find( 'meta[name="monetization"]' ).length > 0 ) {
								return true;
							} else {
								return false;
							}
						});
				}).then(($result) => {
					if ($result === true) {
						cy.visit( '/wp-admin/admin.php?page=coil_swag&tab=coil_winners' );
						cy
							.get( '#coil_contender_email' )
							.type( `{selectall}${ email }` );

						cy
							.get( '#submit' )
							.click();
					}
				});
			} );
		} );
	} );
} );
