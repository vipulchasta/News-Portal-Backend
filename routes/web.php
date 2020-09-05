<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});




/*
 * Admin RESTful APIs
*/

Route::post('/User','User@insertUser')->middleware('authInterceptorAdmin');
Route::get('/Users','User@getUserList')->middleware('authInterceptorAdmin');
Route::put('/User/{userId}/{newStatus}','User@updateUserStatus')->middleware('authInterceptorAdmin');
Route::delete('/User/{userId}','User@deleteUser')->middleware('authInterceptorAdmin');

Route::get('/News/AllPublished','News@getAllPublishedNews')->middleware('authInterceptorAdmin');
Route::put('/NewsAdminApproval/{newsId}/{newsStatus}','News@updateNewsAdminStatus')->middleware('authInterceptorAdmin');
Route::get('/Download/{fileName}','News@downloadNews')->middleware('authInterceptorrAdmin');

Route::get('/ActiveNews','News@getAllActiveNews')->middleware('authInterceptorAdmin'); /* Activated by admin as well as by publisher */
Route::get('/ViewImg/{fileName}','User@viewImage')->middleware('authInterceptorFileAccess');

/*
 * Publisher RESTful APIs
*/
Route::get('/News/MyPublished','News@getMyPublishedNews')->middleware('authInterceptorPublisher');
Route::get('/ActiveNews','News@getAllActiveNews')->middleware('authInterceptorPublisher');
Route::post('/News','News@insertNews')->middleware('authInterceptorPublisher');
Route::delete('/News/{newsId}','News@deleteNews')->middleware('authInterceptorPublisher');
Route::put('/NewsPublisherApproval/{newsId}/{newsStatus}','News@updateNewsPublisherStatus')->middleware('authInterceptorPublisher');
Route::get('/Download/{fileName}','News@downloadNews')->middleware('authInterceptorrPublisher');
Route::get('/View/{fileName}','News@viewNews')->middleware('authInterceptorrPublisher');

/*
 * User RESTful APIs
*/
Route::get('/ActiveNews','News@getAllActiveNews')->middleware('authInterceptorReader'); /* Activated by admin as well as by publisher */
Route::get('/Download/{fileName}','News@downloadNews')->middleware('authInterceptorFileAccess');
Route::get('/View/{fileName}','News@viewNews')->middleware('authInterceptorFileAccess');
Route::get('/SearchNews','News@searchNews')->middleware('authInterceptorReader');

Route::get('/GetProfileData','User@getProfileData')->middleware('authInterceptorReader');
Route::post('/UpdateProfile','User@updateProfile')->middleware('authInterceptorReader');

/*
 * Guest RESTful APIs
*/
Route::post('/User','User@insertUser');
Route::post('/Authentication/Authenticate','User@userAuthenticate');



