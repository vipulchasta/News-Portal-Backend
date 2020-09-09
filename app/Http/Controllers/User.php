<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class User extends Controller {

    public function getUserList() {
		error_log('User::getUserList');
		return DB::table('users')->get();
	}

	public function insertUser(Request $req) {
		error_log('User::insertUser');

		$name = $req->input('name');
		$role = $req->input('role');
		$mobileNo = $req->input('mobileNo');
		$password = $req->input('password');

		$check = $role;

		if( $check == 'READER') {
			$user= DB::table('users')
			->insert([
				'name' => $name,
				'role' => $role,
				'mobileNo' => $mobileNo,
				'password' => $password,
			]);
		} else if(  $check == 'PUBLISHER' ) {
			$fileName = $req->file('file')->store('Uploads');
			$user= DB::table('users')
			
			->insert(
			[
				'name' => $name,
				'role' => $role,
				'mobileNo' => $mobileNo,
				'password' => $password,
				'fileName' => $fileName,
			]
			);
		}

	}

	public function deleteUser(Request $req, $userId) {
		error_log('User::deleteUser');
		$data = $req->json()->all();

		$user= DB::table('users')
		->where('id', $userId)
		->where('role', '!=' , 'ADMIN')
		->delete();
		
	   // $data['id']
	}

	public function updateUserStatus(Request $req, $userId, $newStatus) {
		error_log('User::updateUserStatus');

		if($newStatus == "ACTIVE" || $newStatus == "INACTIVE") {
			return DB::table('users')
			->where('id',$userId)
			->update([
				'status'=> $newStatus
			]);
		}
	}	

	public function userAuthenticate(Request $req) {
		error_log('User::userAuthenticate');
		$random = str_random(30);
		$data = $req->input();

		error_log('Mobile No: ' . $data['mobileNo'].' Password: ' . $data['password']);

		$statusArr = DB::table('users')
				->where('mobileNo', $data['mobileNo'])
				->where('password', $data['password'])
				->pluck('status');

		if(count($statusArr) != 1) {
			error_log('Invalid User Details');
			return "Invalid User Details";
		}

		if($statusArr[0]=='ACTIVE') {
			DB::table('users')
				->where('status','ACTIVE')
				->where('mobileNo', $data['mobileNo'])
				->where('password', $data['password'])
				->update([
					'token' => $random
				]);

			$sql = DB::table('users')
				->where('mobileNo', $data['mobileNo'])
				->where('password', $data['password'])
				->get();

			error_log('Token Generated: ' . $random);
			return $sql;
		} else {
			error_log('Inactive User Account');
			return "Inactive User Account";
		}

	}

	public function viewImage(Request $req, $fileName) {
		error_log('User::viewImage');
		return Storage::Response('Uploads/' . $fileName);
	}

	public function getProfileData(Request $req) {
		error_log('User::getProfileData');
		$userId = $req->header('user_id');

		return $profileData = DB::table('users')
				->where('id',$userId)
				->get();
		
	}
	
	public function updateProfile(Request $req) {
		error_log('User::updateProfile');
		return null;
	}
}
