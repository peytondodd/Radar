<?php

namespace App\Http\Controllers\Apis;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\User;
use App\Models\Location;

class DriverController extends Controller
{
	public function updateDriverLocation(Request $request)
	{
		$data = $request->all();
		$user = $request->user();

		$car = Car::where('driver_id',$user->id)->first();
		if($car){
			$checkLocationExists = Location::where('car_id',$car->id)->first();

			if($checkLocationExists){
				$updateLocation = $checkLocationExists->update($data);

				return response()->json([
					'message'=>'You have updated the driver location.',
					],200 );

			}else{
				$data['car_id'] = $car->id;
				$data['type'] = 'Driver';
				$createLocation = Location::create($data);

				return response()->json([
					'message'=>'You have submited the driver location.',
					],200 );
			}
		}else{
			return response()->json([
				'message'=>'There is no car or driver with inputs.',
				],404 );
		}
	}

	public function changeDutyStatus(Request $request)
	{
		$checkStatus = User::where('id',$request->user()->id)->pluck('is_on_duty')->first();

		if($checkStatus == 0){
			$setDutyOn = User::where('id',$request->user()->id)->update([
				'is_on_duty'=>1,
			]);

			return response()->json([
				'message'=>'Now You are on Duty.','status'=>1
				],404 );
		}else{
			$setDutyOff = User::where('id',$request->user()->id)->update([
				'is_on_duty'=>0,
			]);

			return response()->json([
				'message'=>'Now You are off Duty.','status'=>0
				],404 );
		}
	}
}