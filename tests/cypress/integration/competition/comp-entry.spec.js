let email = '';
let site_url = '';

describe( 'Plugin Settings Panel', function() {
	beforeEach( () => {
		cy.logInToWordPress( 'admin', 'password' );
	} );

	it( 'enters participant emails and URLs', function() {
		cy.visit( '/wp-admin/admin.php?page=coil_swag&tab=coil_competition' );
		enterParticipant('in1@MediaList.com', 'https://www.freecodecamp.org/');
		enterParticipant('in5@MediaList.com', 'https://www.freecodecamp.org/');
		enterParticipant('out1@MediaList.com', 'https://www.codecademy.com/');
		enterParticipant('in2@MediaList.com', 'https://www.freecodecamp.org/');
		enterParticipant('in3@MediaList.com', 'https://www.freecodecamp.org/');
		enterParticipant('out3@MediaList.com', 'https://www.codecademy.com/');
		enterParticipant('in4@MediaList.com', 'https://www.freecodecamp.org/');

		cy.visit( '/wp-admin/admin.php?page=coil_swag&tab=coil_contenders' );
		cy.get('ol').should('exist');
		cy.get('ol').screenshot();
	});

	it( 'enters valid email submissions', function() {
		cy.visit( '/wp-admin/admin.php?page=coil_swag&tab=coil_contenders' );

		cy.get( 'ol>li' ).each( ( $el ) => {
			cy.wrap($el).then( ( $item ) => {
				email = $item.children( '.email' ).text();
				site_url = $item.children( '.site' ).text();
				cy.log("site_url = " + site_url);
				cy.origin( site_url, () => {
					cy.url().then(($url) => {
						cy.visit($url);
					});
					cy.get( 'head' )
						.then(($head) => {
							if ( $head == '' ) {
								cy.log("The site is not accessible").then(()=>{return false;});
							} else if ( $head.find( 'meta[name="monetization"]' ).length > 0 ) {
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

function enterParticipant(email, site) {
	cy.get('#coil_entry_email').type( `{selectall}${ email }` );
	cy.get('#coil_entry_link').type( `{selectall}${ site }` );
	cy
		.get( '#submit' )
		.click();
}
