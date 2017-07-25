<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//all auth api
	Route::post('/auth/signup',[

		'uses'=>'Apis\AuthController@signUp'
	]);


	Route::post('/auth/signin',[

		'uses'=>'Apis\AuthController@signIn'
	]);

	Route::post('/auth/social/signup',[

		'uses'=>'Apis\AuthController@socialSignUp'
	]);

	Route::post('/auth/social/signin',[

		'uses'=>'Apis\AuthController@socialSignIn'
	]);

	Route::post('/auth/forget/password',[

		'uses'=>'Apis\AuthController@forgetPassword'
	]);

	Route::group(['middleware'=>['JWT.auth']],function (){

	
		Route::post('/auth/registration/id',[

			'uses'=>'Apis\AuthController@registrationId'
		]);


		Route::post('/set/car/registration/code',[

			'uses'=>'Apis\CarController@setRegistrationCode'
		]);

		Route::post('/submit/radar',[

			'uses'=>'Apis\RadarController@SubmitRadar'
		]);

		Route::post('/nearby/radars',[

			'uses'=>'Apis\RadarController@nearbyRadars'
		]);

		Route::post('/get/radar',[

			'uses'=>'Apis\RadarController@getRadar'
		]);


});

