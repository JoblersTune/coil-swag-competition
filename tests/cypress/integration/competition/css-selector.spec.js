/**
 * CSS selector tests.
*/

describe( 'Plugin Settings Panel', function() {
	beforeEach( () => {
		cy.logInToWordPress( 'admin', 'password' );
	} );

	it( 'check the compitition entry tab exists', function() {
		cy.visit( '/wp-admin/admin.php?page=coil_swag' );

		cy
			.get( '#coil_entry_email' )
			.should( 'exist' );
	} );
	it( 'check the winners tab exists', function() {
		cy.visit( '/wp-admin/admin.php?page=coil_swag&tab=coil_winners' );

		cy
			.get( 'ol' )
			.should( 'exist' );
	} );
} );
