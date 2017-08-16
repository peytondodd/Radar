<?php

namespace App\Transformers;

use App\Models\Car;

use League\Fractal\TransformerAbstract;


class CarTransformer extends TransformerAbstract
{



	public function transform(Car $car)
	{
		

		$data = [
			'id'=>$car->id,
			'model'=>$car->model,
			'plate_number'=>$car->plate_number,
			'maintenance_date'=>$car->maintenance_date,
			'mile_age'=>$car->mile_age,
			'registration_code'=>$car->registration_code,
			
		];

		return $data;
	}



}