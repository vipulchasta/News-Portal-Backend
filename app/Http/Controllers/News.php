<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\File;
use Illuminate\Support\Facades\File as LaraFile;
use Illuminate\Support\Facades\Storage;

class News extends Controller
{
    public function getAllPublishedNews() {

		$rs = DB::table('news')
				->select('news.id as id',
						'user.name as publisherName',
						'news.title as title',
						'news.content as content',
						'user.id as publisherId',
						'news.fileName as fileName',
						'news.adminApproval as adminApproval',
						'news.publisherApproval as publisherApproval',
						'news.countView as countView',
						'news.time as time',)
				->join('user','news.uploaderId','=','user.id')
				->get();

		return $rs;
	}

	public function getMyPublishedNews(Request $req) {

		error_log('message');
		$userId = $req->header('user_id');
		error_log($userId);
		
		$rs = DB::table('news')
				->select('news.id as id',
						'news.publisherId as publisherId',
						'news.title as title',
						'news.fileName as fileName',
						'news.adminApproval as adminApproval',
						'news.publisherApproval as publisherApproval',
						'news.countView as countView')
				->where('news.publisherId', $userId)
				->get();

		return $rs;
	}

	public function getAllActiveNews(Request $req) {

		error_log('message');
		
		$rs = DB::table('news')
				->select('news.id as id',
						'news.publisherId as publisherId',
						'news.title as title',
						'news.fileName as fileName',
						'news.adminApproval as adminApproval',
						'news.publisherApproval as publisherApproval',
						'news.countView as countView')
				->where('news.adminApproval', 1)
				->where('news.publisherApproval', 1)
				->get();

		return $rs;
	}

	public function insertNews(Request $req) {
	
		error_log('InsertNews');
		$fileName = $req->file('file')->store('Uploads');
		echo $fileName;


		$newsTitle = $req->input('title');
		$newsContent = $req->input('content');

		$newsPublisherId = $req->header('user_id');

		$news = DB::table('news')
		
		->insert(
		[
			'publisherId' => $newsPublisherId,
			'title' => $newsTitle,
			'content' => $newsContent,
			'fileName' => $fileName
		]
		);
	}

	public function deleteNews(Request $req, $newsId) {
		error_log('DeleteNews');
		error_log($newsId);

		$fileName = DB::table('news')->where('id', $newsId)->pluck('fileName');

		error_log($fileName[0]);

		error_log("Removing"); 
		//unlink(storage_path('app/'.$filename[0]));
		Storage::delete($fileName[0]);
		error_log("Deleted"); 

		$user= DB::table('news')
		->where('id', $newsId)
		->delete();
	}

	public function downloadNews(Request $req, $fileName) {
		error_log('DownloadNews');
		$newsTitle = $req->input('title');
		error_log($fileName);
		error_log($newsTitle);

		$increment = DB::table('news')
		->where('fileName', 'Uploads/'.$fileName)
		->increment('countDownload', 1);

		return Storage::download('Uploads/' . $fileName , $newsTitle . '.png');
	}

	public function searchNews(Request $req) {
		error_log('SearchNews1');
		error_log($req);
		$data = $req->input('searchString');
		error_log('SearchNews2');

		error_log($data);
		error_log('SearchNews3');

		error_log('before query');
		$wantedNews = DB::table('news')
		->where('title', 'LIKE' , '%'.$data.'%')
		->where('news.adminApproval', 1)
		->where('news.publisherApproval', 1)
		->get();
		error_log('after query');
		error_log('after wanted called');

		return $wantedNews;
	}

	public function viewNews(Request $req, $fileName) {
		error_log('ViewNews');

		$increment = DB::table('news')
		->where('fileName', 'Uploads/'.$fileName)
		->increment('countView', 1);

		return Storage::Response('Uploads/' . $fileName);
	}

	public function updateNewsAdminStatus(Request $req, $newsId, $adminStatus) {
		error_log($newsId);
		error_log($adminStatus);

		if($adminStatus == 1 || $adminStatus == 0)
		{
			return DB::table('news')
			->where('id',$newsId)
			->update([
				'adminApproval'=> $adminStatus
			]);
		}
	}	

	public function updateNewsPublisherStatus(Request $req, $newsId, $publisherStatus) {
		error_log($newsId);
		error_log($publisherStatus);

		$userId = $req->header('user_id');

		if($publisherStatus == 1 || $publisherStatus == 0)
		{
			return DB::table('news')
			->where('id',$newsId)
			->where('publisherId', $userId)
			->update([
				'publisherApproval'=> $publisherStatus
			]);
		}
	}
}
