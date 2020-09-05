<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class User extends Controller
{
    public function getUserList()
	{
		error_log('i am user list');
		return DB::table('users')->get();
	}

	public function insertUser(Request $req)
	{
	
		error_log('CreateUser');

		$name = $req->input('name');
		$role = $req->input('role');
		$mobileNo = $req->input('mobile_no');
		$password = $req->input('password');

		error_log($name);
		error_log($role);
		error_log($mobileNo);
		error_log($password);
			
		$check = $role;

		error_log('Going in If');
		if( $check == 'READER'){
			error_log('i m in reader');
			$user= DB::table('users')
			->insert([
				'name' => $name,
				'role' => $role,
				'mobile_no' => $mobileNo,
				'password' => $password,
			]);
		}else if(  $check == 'PUBLISHER' )
		{
			error_log('i m in publisher');
			$fileName = $req->file('file')->store('Uploads');
			error_log($fileName);
			$user= DB::table('users')
			
			->insert(
			[
				'name' => $name,
				'role' => $role,
				'mobile_no' => $mobileNo,
				'password' => $password,
				'fileName' => $fileName,
			]
			);
		}

	}

	public function deleteUser(Request $req, $userId)
	{
	
		error_log('DeleteUser');
		error_log($userId);
		$data = $req->json()->all();

		$user= DB::table('users')
		->where('id', $userId)
		->where('role', '!=' , 'ADMIN')
		->delete();
		
	   // $data['id']
	}

	public function updateUserStatus(Request $req, $userId, $newStatus)
	{
		error_log($userId);
		error_log($newStatus);

		if($newStatus == "ACTIVE" || $newStatus == "INACTIVE")
		{
			return DB::table('users')
			->where('id',$userId)
			->update([
				'status'=> $newStatus
			]);
		}
	}	

	public function userAuthenticate(Request $req)
	{
		$random = str_random(30);
		error_log($random);

		$data = $req->input();

		$statusArr = DB::table('users')
				->where('mobile_no',$data['mobileNo'])
				->where('password',$data['password'])
				->pluck('status');

		if(count($statusArr) == 1 && $statusArr[0]=='ACTIVE'){

			DB::table('users')
			->where('status','ACTIVE')
			->where('mobile_no',$data['mobileNo'])
			->where('password',$data['password'])
			->update([
				'token' => $random
			]);

			$sql = DB::table('users')
				->where('mobile_no',$data['mobileNo'])
				->where('password',$data['password'])
				->get();

		return $sql;
		}

		return "User Not Active";
	}

	public function viewImage(Request $req, $fileName) {
		error_log('ViewImage');
		return Storage::Response('Uploads/' . $fileName);
	}

	public function getProfileData(Request $req)
	{
		error_log("GetProfileData");
		$userId = $req->header('user_id');
		error_log($userId);

		return $profileData = DB::table('users')
				->where('id',$userId)
				->get();
		
	}
	
	public function updateProfile(Request $req)
	{
		error_log("UpdateProfile");
		return null;
	}
}
