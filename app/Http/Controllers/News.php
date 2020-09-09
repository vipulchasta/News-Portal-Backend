<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\File;
use Illuminate\Support\Facades\File as LaraFile;
use Illuminate\Support\Facades\Storage;

class News extends Controller {

    public function getAllPublishedNews() {
		error_log('News::getAllPublishedNews');

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
		error_log('News::getMyPublishedNews');
		$userId = $req->header('user_id');
		
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
		error_log('News::getAllActiveNews');
		
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
		error_log('News::insertNews');
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
		error_log('News::deleteNews');
		$fileName = DB::table('news')->where('id', $newsId)->pluck('fileName');
		Storage::delete($fileName[0]);
		
		$user= DB::table('news')
					->where('id', $newsId)
					->delete();
	}

	public function downloadNews(Request $req, $fileName) {
		error_log('News::downloadNews');
		$newsTitle = $req->input('title');

		$increment = DB::table('news')
							->where('fileName', 'Uploads/'.$fileName)
							->increment('countDownload', 1);

		return Storage::download('Uploads/' . $fileName , $newsTitle . '.png');
	}

	public function searchNews(Request $req) {
		error_log('News::searchNews');
		$data = $req->input('searchString');

		$wantedNews = DB::table('news')
							->where('title', 'LIKE' , '%'.$data.'%')
							->where('news.adminApproval', 1)
							->where('news.publisherApproval', 1)
							->get();

		return $wantedNews;
	}

	public function viewNews(Request $req, $fileName) {
		error_log('News::viewNews');

		$increment = DB::table('news')
							->where('fileName', 'Uploads/'.$fileName)
							->increment('countView', 1);

		return Storage::Response('Uploads/' . $fileName);
	}

	public function updateNewsAdminStatus(Request $req, $newsId, $adminStatus) {
		error_log('News::updateNewsAdminStatus');

		if($adminStatus == 1 || $adminStatus == 0) {
			return DB::table('news')
							->where('id',$newsId)
							->update([
								'adminApproval'=> $adminStatus
							]);
		}
	}	

	public function updateNewsPublisherStatus(Request $req, $newsId, $publisherStatus) {
		error_log('News::updateNewsPublisherStatus');
		$userId = $req->header('user_id');

		if($publisherStatus == 1 || $publisherStatus == 0) {
			return DB::table('news')
							->where('id',$newsId)
							->where('publisherId', $userId)
							->update([
								'publisherApproval'=> $publisherStatus
							]);
		}
	}
}
