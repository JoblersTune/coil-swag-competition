let email = '';
let site_url = '';

describe( 'Plugin Settings Panel', function() {
	beforeEach( () => {
		cy.logInToWordPress( 'admin', 'password' );
	} );

	it( 'enters valid email submissions', function() {
		cy.visit( '/wp-admin/admin.php?page=coil_swag&tab=coil_contenders' );

		cy.get( 'ol>li' ).each( ( $el ) => {
			cy.wrap($el).then( ( $item ) => {
				email = $item.children( '.email' ).text();
				site_url = $item.children( '.site' ).text();
				cy.origin( site_url, () => {
					cy.visit( '/' );
					cy.get( 'head' )
						.then(($head) => {
							if ( $head.find( 'meta[name="monetization"]' ).length > 0 ) {
								cy.log("The site is monetized").then(()=>{return true;});
							} else {
								cy.log("The site is not monetized").then(()=>{return false;});
							}
						});
				}).then(($result) => {
					if ($result === true) {
						cy.visit( '/wp-admin/admin.php?page=coil_swag&tab=coil_contenders' );
						cy
							.get( '#coil_contender_email' )
							.type( `{selectall}${ email }` );

						cy
							.get( '#submit' )
							.click();
					} else {
						cy.visit( '/wp-admin/admin.php?page=coil_swag&tab=coil_contenders' );
					}
				});
			} );
		} );
	} );

	it( 'displays winners', function() {
		cy.visit( '/wp-admin/admin.php?page=coil_swag&tab=coil_winners' );
		cy.get('ol').should('exist');
		cy.get('ol').screenshot();
	});
} );
