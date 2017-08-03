@extends('dashboard.app')
@section('content')
<div class="col-md-12">
	<div class="panel-heading">
	</div>
	<div class="panel panel-default">
		<div class="panel-body">
			<div class="table-responsive">
				<table class="table">
					<thead>
						<th>#</th>
						<th>LOCATION</th>
						<!-- <th></th> -->
						<th style="padding: 0px;">
							<div style="margin:10px;" class="pull-left">OPTIONS</div>
							<a href="{{ route('dashboard.radar.create') }}" class="btn btn-primary pull-right" target="_blank" style="margin-top: 3px;">
								Add new
							</a>
						</th>
					</thead>
					<tbody>
						@foreach($resources as $resource)
						<tr>
							<td>
                                {{ $counter_offset + $loop->iteration }}
                            </td>
							<td>
								<a href="{{ route('dashboard.location.simpleMap', [$resource->location->latitude,$resource->location->longitude]) }}" target="_blank">
									view on map
								</a>
							</td>
							<td>
								<a href="{{ route('dashboard.radar.show', $resource->id) }}" class="btn btn-info pull-left" style="margin-right:5px;"><i class="fa fa-eye"></i></a>
								<a href="{{ route('dashboard.radar.edit', $resource->id) }}" class="btn btn-primary pull-left" style="margin-right:5px;"><i class="fa fa-edit"></i></a>
								{{ Form::open(['route' => ['dashboard.radar.destroy', $resource->id] , 'method' => 'DELETE']) }}
									<button class="btn btn-danger pull-left" type="submit"><i class="fa fa-trash"></i></button>
								{{ Form::close() }}
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
		<div class="panel-footer">
			<div class="text-center">
				{{ $resources->links() }}
			</div>
		</div>
	</div>
</div>
@endsection
