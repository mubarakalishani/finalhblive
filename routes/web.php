<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\IpExampleController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OfferwallsPostbacksController;
use App\Http\Controllers\Advertiser\CreateTaskController;
use App\Http\Controllers\Advertiser\TaskFullPageController;
use App\Http\Controllers\Worker\TaskSubmitController;
use App\Http\Controllers\Worker\PtcAdController;
use App\Http\Controllers\Worker\WorkerFaucetController;
use App\Http\Controllers\Advertiser\ReviewTasksController;
use App\Http\Controllers\Worker\WorkerShortLinkController;


use App\Livewire\ReviewTasks;

use App\Http\Controllers\Admin\AdminTaskCategoryController;
use App\Http\Controllers\Advertiser\DepositController;
use App\Http\Controllers\CoinbaseCommerceTestController;
use App\Http\Controllers\CustomAuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Pages\ContactPageController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/




Route::get('/ip', [IpExampleController::class, 'index']);

Route::get('/create-task', function () {
    return view('advertiser.create_task');
});

Route::get('/advertiser/task', function () {
    return view('advertiser.task_full_page');
});




//offerwalls postbacks routes
Route::get('/postbacks/adscendmedia', [OfferwallsPostbacksController::class, 'adscendmedia']);
Route::get('/postbacks/ayetstudios', [OfferwallsPostbacksController::class, 'ayetstudios']);
Route::get('/postbacks/adbreakmedia', [OfferwallsPostbacksController::class, 'adbreakmedia']);
Route::get('/postbacks/bitlabs', [OfferwallsPostbacksController::class, 'bitlabs']);
Route::get('/postbacks/bitcotasks', [OfferwallsPostbacksController::class, 'bitcotasks']);
Route::get('/postbacks/cpxresearch', [OfferwallsPostbacksController::class, 'cpxresearch']);
Route::get('/postbacks/lootably', [OfferwallsPostbacksController::class, 'lootably']);
Route::get('/postbacks/offers4crypto', [OfferwallsPostbacksController::class, 'offers4crypto']);
Route::get('/postbacks/excentiv', [OfferwallsPostbacksController::class, 'excentiv']);
Route::get('/postbacks/kiwiwall', [OfferwallsPostbacksController::class, 'kiwiwall']);
Route::get('/postbacks/notik', [OfferwallsPostbacksController::class, 'notik']);
Route::get('/postbacks/revlum', [OfferwallsPostbacksController::class, 'revlum']);
Route::get('/postbacks/timewall', [OfferwallsPostbacksController::class, 'timewall']);
Route::get('/postbacks/wannads', [OfferwallsPostbacksController::class, 'wannads']);


/*===============================================After Login Routes, with middleware auth required====================================== */
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
  //===========Advertiser Routes==========
    Route::get('/advertiser/deposit', function () {
        return view('advertiser.deposit');
    });
    Route::get('/advertiser/create-new-task', function () {
        return view('advertiser.task.create-campaign');
    });
    Route::post('/advertiser/create-new-task', [CreateTaskController::class, 'store'])->name('advertiser.create_task');
    Route::get('/advertiser/campaigns', function () {
        return view('advertiser.task.campaign-list');
    });
    Route::get('/advertiser/campaign/{taskId}', [TaskFullPageController::class, 'showCampaignDetails']);
    Route::get('/advertiser/disputes', function () {
        return view('advertiser.task.disputes');
    });
    Route::get('/advertiser/create-new-ptc-campaign', function () {
        return view('advertiser.ptc.create-new-ptc');
    });
    Route::get('/advertiser/ptc-campaigns-list', function () {
        return view('advertiser.ptc.ptc-campaign-history');
    });
  //===============Worker Routes============
    Route::get('/jobs', function () {
        return view('worker.all-jobs');
    });
    Route::get('/offers', function () {
        return view('worker.all-offers');
    });
    Route::get('/views', function () {
        return view('worker.ptc.allptc-ads');
    });
    //worker histories routes
    Route::get('/history', function () {
        return view('worker.history.overall');
    });
    Route::get('/history/jobs', function () {
        return view('worker.history.jobs');
    });
    Route::get('/history/offers-and-surveys', function () {
        return view('worker.history.offerwalls');
    });
    //ended histories
    Route::get('/withdraw', function () {
        return view('worker.withdraw');
    });
    Route::get('/referral', function () {
        return view('worker.referral');
    });
    Route::get('/faucet', [WorkerFaucetController::class, 'index'])->name('worker.faucet');
    Route::post('/faucet', [WorkerFaucetController::class, 'store'])->name('worker.claim_faucet');
    Route::get('/views/iframe', [PtcAdController::class, 'showIframe'])->name('worker.views.iframe.show');
    Route::get('/views/iframe/{uniqueId}', [PtcAdController::class, 'show'])->name('ptc.iframe');
    Route::post('/views/iframe/{uniqueId}', [PtcAdController::class, 'iframeSubmit'])->name('worker.ptc_iframe.submit');
    Route::get('/views/window', [PtcAdController::class, 'showWindow'])->name('worker.views.window.show');
    Route::post('/views/window', [PtcAdController::class, 'windowSubmit'])->name('worker.ptc.window.submit');
    Route::get('/shortlinks', [WorkerShortLinkController::class, 'index'])->name('worker.shortlinks');
    Route::get('/shortlink/{uniqueId}', [WorkerShortLinkController::class, 'show'])->name('shortlink.show');
    Route::get('/shortlink/back/{secretKey}', [WorkerShortLinkController::class, 'verifyAndUpdate'])->name('shortlink.verifyandupdate');
    Route::get('/jobs/{taskId}', [TaskSubmitController::class, 'showTask']);
    Route::post('/worker/submit-task/{taskId}', [TaskSubmitController::class, 'store'])->name('worker.submit_task');


});


/*===============================================Before Login/Accessible by public Routes====================================== */
Route::get('/', [HomeController::class, 'index']);
Route::get('/logout', function(){
          Auth::logout();
          return redirect('/login');
});

Route::get('/auth/google', function () {
    return Socialite::driver('google')->redirect();
});

Route::get('/login/google/callback', [CustomAuthController::class, 'handleGoogleCallback']);

Route::get('/test-coinbase-commerce', [CoinbaseCommerceTestController::class, 'index']);


Route::get('/faucetpay/callback', [DepositController::class, 'faucetpaySuccessCallback']);












Route::get('/contact', [ContactPageController::class, 'index']);
Route::post('/contact', [ContactPageController::class, 'store'])->name('contact.submit');



Route::post('/jobs/{taskId}', [TaskSubmitController::class, 'store'])->name('worker.submit_task');
Route::get('/jobs/submitted/{taskId}', [TaskSubmitController::class, 'showSbumittedProof']);
Route::post('/jobs/submitted/{taskId}/file-dispute', [TaskSubmitController::class, 'fileDispute'])->name('worker.file_dispute');

Route::post('/jobs/submitted/{taskId}/submit-revised-task', [TaskSubmitController::class, 'submitRevisedTask'])->name('worker.submit_revised_task');




Route::get('/views/youtube', [PtcAdController::class, 'showYoutube'])->name('worker.views.youtube.show');








// Route::middleware([
//     'auth:sanctum',
//     config('jetstream.auth_session'),
//     'verified',
// ])->group(function () {
//     Route::get('/dashboard', function () {
//         return view('dashboard');
//     })->name('dashboard');
// });




Route::get('/admin/task-categories/{id}/add-rewards', [AdminTaskCategoryController::class, 'addRewards'])->name('admin.task-categories.add-rewards');
Route::post('/admin/task-categories/{id}/store-rewards', [AdminTaskCategoryController::class, 'storeRewards'])->name('admin.task-categories.store-rewards');

