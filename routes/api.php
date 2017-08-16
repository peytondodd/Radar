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

		Route::post('/auth/view/profile',[

			'uses'=>'Apis\UserController@viewProfile'
		]);

		Route::post('/create/group',[

			'uses'=>'Apis\GroupController@createGroup'
		]);

		Route::post('/view/groups',[

			'uses'=>'Apis\GroupController@viewGroups'
		]);

		Route::post('/view/group/users',[

			'uses'=>'Apis\GroupController@viewUsersGroup'
		]);

		Route::post('/group/add/user',[

			'uses'=>'Apis\GroupController@groupAddUser'
		]);

		Route::post('/auth/edit/profile',[

			'uses'=>'Apis\UserController@editProfile'
		]);

		Route::post('/auth/change/password',[

			'uses'=>'Apis\UserController@changePassword'
		]);


		Route::post('/set/car/registration/code',[

			'uses'=>'Apis\CarController@setRegistrationCode'
		]);

		Route::post('/view/car/details',[

			'uses'=>'Apis\CarController@viewCarDetails'
		]);

		Route::post('/view/all/cars',[

			'uses'=>'Apis\CarController@viewAllCars'
		]);

		Route::post('/update/car/details',[

			'uses'=>'Apis\CarController@updateCarDetails'
		]);

		Route::post('/submit/radar',[

			'uses'=>'Apis\RadarController@SubmitRadar'
		]);

		Route::post('/submit/lagna',[

			'uses'=>'Apis\LagnaController@SubmitLagna'
		]);

		Route::post('/nearby/radars',[

			'uses'=>'Apis\RadarController@nearbyRadars'
		]);

		Route::post('/nearby/lagnas',[

			'uses'=>'Apis\LagnaController@nearbyLagnas'
		]);

		Route::post('/get/radar',[

			'uses'=>'Apis\RadarController@getRadar'
		]);

		Route::post('/update/driver/location',[

			'uses'=>'Apis\DriverController@updateDriverLocation'
		]);


		Route::post('/change/duty/driver',[

			'uses'=>'Apis\DriverController@changeDutyStatus'
		]);


});

