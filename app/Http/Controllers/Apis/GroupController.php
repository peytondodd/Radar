<?php

namespace App\Http\Controllers\Apis;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\PusherController;
use App\Http\Requests\Apis\CreateGroup;
use App\Models\Group;
use App\Models\User;
use App\Models\GroupUser;
use App\Transformers\GroupTransformer;
use App\Transformers\UserTransformer;
use Mail;
use DB;

class GroupController extends Controller
{
	public function createGroup(CreateGroup $request)
	{
		$data = $request->all();
		$data['admin_id'] = $request->user()->id;

		if(!empty($request->hasFile('image'))){
			$data['image'] = $this->uploadImage($request->file('image'));
		}

		$createGroup = Group::create($data);

		return response()->json([
			'id' => $createGroup->id,
			'message'=>'The group has been created.',
		],200 );
	}

	public function groupAddUser(Request $request)
	{
		$groupId = $request->input('group_id');
		$checkgroup = Group::where('id',$groupId)->first();
		if($checkgroup){
			$tokens = [];
			foreach ($request->input('users') as $user) {
				$checkUser = User::where('id',$user)->first();
				if($checkgroup->admin_id != $user && $checkUser){
					$confirmation_code = str_random(30);
					$groupUser = new GroupUser;
					$groupUser->user_id = $checkUser->id;
					$groupUser->group_id = $groupId;
					$groupUser->confirmation_code = $confirmation_code;
					$groupUser->save();
					$email = $checkUser->email;
					$mess=[
						'email'=>$email,
						'confirmation_code'=>$confirmation_code,
					];

					try {
						Mail::send('email.groupRequest', $mess, function ($message) use ($email,$confirmation_code)
						{
							$message->subject('Group Request Radar Application');
							$message->to($email, $name=null);
						});
					} catch (\Exception $e) {
					}

					if (!$checkUser->registeration_id->isEmpty()) { // insert user tokens to send to
						$tokens[] = $checkUser->registeration_id()->orderBy('id','DESC')->first()->device_id;
					}
				}
			}

			if ($tokens) { // if there are any device tokens to send notifications to
				$group_admin = $checkgroup->admin;
				$title = "You've been added to a group.";
				$body = ($group_admin ? $group_admin->full_name : '[DELETED]')." invited you to a group,confirmation code has been sent to your email account."; // handeling if there is no admin just to make sure nothing goes wrong
				try {
					$pusher = new PusherController($title,$body,[],$tokens);
				} catch (\Exception $e) {
				}
			}

			return response()->json([
				'message'=>'Group Request has been sent.',
			],200 );
		}else{
			return response()->json([
				'message'=>'No group with this id.',
			],404);
		}
	}

	public function confirm($confirmation_code)
	{
		if( ! $confirmation_code)
		{
			throw new InvalidConfirmationCodeException;
		}

		$groupUser = GroupUser::whereConfirmationCode($confirmation_code)->first();

		if ( ! $groupUser)
		{
			throw new InvalidConfirmationCodeException;
		}

		$groupUser->confirmed = 1;
		$groupUser->confirmation_code = null;
		$groupUser->save();

		echo "You have successfully approved the request to the group.";
	}

	public function viewGroups(Request $request)
	{
		$id = $request->input('user_id');
		if($id){
			$userId = $id;
		}else{
			$userId = $request->user()->id;
		}

		$groupIds = GroupUser::where('user_id',$userId)->where('confirmed',1)->pluck('group_id')->toArray();

		$groups = Group::whereIn('id',$groupIds)->orWhere('admin_id',$userId)->get();

		return response()->json([
			'data'=>fractal()
			->collection($groups)
			->transformWith(new GroupTransformer)
			->serializeWith(new \Spatie\Fractal\ArraySerializer())
			->toArray(),
		],200);
	}

	public function viewUsersGroup(Request $request)
	{
		$authUser = $request->user()->id;

		$id = $request->input('group_id');
		if($id){
			$checkgroup = Group::where('id',$id)->first();
			if($checkgroup){
				$groupUsersIds = GroupUser::where('group_id',$id)->whereNotIn('user_id',[$authUser])->where('confirmed',1)->pluck('user_id')->toArray();
				$groupUsersIds[] = $authUser;
				$users = User::whereIn('id',$groupUsersIds)->get();

				return response()->json([
					'data'=>fractal()
					->collection($users)
					->transformWith(new UserTransformer)
					->serializeWith(new \Spatie\Fractal\ArraySerializer())
					->toArray(),
				],200);
			}else{
				return response()->json([
					'message'=>'No group with this id.',
				],404);
			}
		}else{
			return response()->json([
				'message'=>'No group id is provided.',
			],400);
		}

	}

	public function leave(Request $request,Group $group)
	{
		$user = $request->user();
		if ($group->admin_id === $user->id) {
			$new_admin = $group->users()->first();
			if ($new_admin) {
				$group->admin_id = $new_admin->id;
				$group->save();
				DB::table('group_user')->where('group_id',$group->id)->where('user_id',$new_admin->id)->delete();
			}else{
				$group->delete();
			}
		}else{
			DB::table('group_user')->where('group_id',$group->id)->where('user_id',$user->id)->delete();
			// $member = GroupUser::where('group_id',$group->id)->where('user_id',$user->id)->get();
			// if ($member) {
			// 	$member->delete();
			// }
		}
		return response()->json([
			'status' => 'success',
			'message' => 'User removed successfully.'
		]);
	}
}
