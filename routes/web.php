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

if (file_exists($rpath = base_path('routes').'/common.php')) include_once($rpath);

if (file_exists($rpath = base_path('routes').'/dev.php')) {
    Route::group(['prefix' => 'dev'],function() use ($rpath) {
        include_once($rpath);
    });
}

Auth::routes();

Route::get('/', function () {
    return view('home');
})->name('main.home');

Route::get('login',function() {
	cas()->authenticate();
})->name('main.login');

Route::middleware(['cas.auth'])->group(function() {
	// main.logout
	Route::get('logout',function() {
		session()->forget('auth.account_type');
		return cas()->logout(null,route('main.home'));
	})->name('main.logout');

	Route::get('execute',function() {
		$client = new GuzzleHttp\Client;

		$atomId = config('boomi.atomID');
		$processId = config('boomi.processID');

		$body = <<<XML
<ProcessExecutionRequest processId="{$processId}" atomId="{$atomId}" xmlns="http://api.platform.boomi.com/">;
	<ProcessProperties>
		<ProcessProperty>
			<Name>priority</Name>
			<Value>medium</Value>
		</ProcessProperty>
	</ProcessProperties>
</ProcessExecutionRequest>
XML;

		$response = $client->request('POST','https://api.boomi.com/api/rest/v1/'.config('boomi.accountID').'/executeProcess',[
			'auth' => [
				config('boomi.username'),
				config('boomi.password')
			],
			'headers' => [
				'Content-Type' => 'application/xml'
			],
			'body' => $body
		]);

		$statusCode = $response->getStatusCode();
		$bodyResponse = $response->getBody();
		$allowedUsers = ['MHAN1','KDFROTH'];

		return view('api-response',['allowedUsers'=>$allowedUsers,'statusCode' => $statusCode,'bodyResponse' => $bodyResponse]);

	})->name('boomi.execute');

	Route::middleware(['can:admin'])->group(function() {
		Route::get('admin/text','TextController@index')->name('admin.text');
		Route::post('admin/text','TextController@submit')->name('admin.text-submit');
	});
});


// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
