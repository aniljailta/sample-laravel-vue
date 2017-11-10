/* Add here all your JS customizations */


/**
  * @OVERRIDE
  * Lock Screen - Build Template
  *
  */
LockScreen.buildTemplate = function( userinfo ) {
	return [
		'<section id="LockScreenInline" class="body-sign body-locked body-locked-inline">',
			'<div class="center-sign">',
				'<div class="panel panel-sign">',
					'<div class="panel-body">',
						'<form action="/auth/unlock" method="post">',
							'<div class="current-user text-center">',
								'<img id="LockUserPicture" src="{{picture}}" class="img-circle user-image" />',
								'<h2 id="LockUserName" class="user-name text-dark m-none">{{username}}</h2>',
								'<p  id="LockUserEmail" class="user-email m-none">{{email}}</p>',
							'</div>',
							'<div class="form-group mb-lg">',
								'<div class="input-group input-group-icon">',
									'<input id="password" name="password" type="password" class="form-control input-lg" placeholder="Password" autofocus />',
									'<span class="input-group-addon">',
										'<span class="icon icon-lg">',
											'<i class="fa fa-lock"></i>',
										'</span>',
									'</span>',
								'</div>',
							'</div>',

							'<div class="row">',
								'<div class="col-xs-6">',
									'<p class="mt-xs mb-none">',
										'<a href="/auth/logout">Logout</a>',
									'</p>',
								'</div>',
								'<div class="col-xs-6 text-right">',
									'<button type="submit" class="btn btn-primary">Unlock</button>',
								'</div>',
							'</div>',
						'</form>',
					'</div>',
				'</div>',
			'</div>',
		'</section>'
	]
	.join( '' )
	.replace( /\{\{picture\}\}/, userinfo.picture )
	.replace( /\{\{username\}\}/, userinfo.username )
	.replace( /\{\{email\}\}/, userinfo.email );
}
