<?php

use App\Admin\Controllers\NewsAndAnnouncementController;
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
use App\Http\Controllers\Campaings\CampaignsController;
use App\Http\Controllers\CoinbaseCommerceTestController;
use App\Http\Controllers\Cronjobs\EveryDayController;
use App\Http\Controllers\Cronjobs\EveryFiveMinutesController;
use App\Http\Controllers\Cronjobs\EveryMinuteController;
use App\Http\Controllers\Cronjobs\EveryMonthController;
use App\Http\Controllers\CustomAuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FixBalancesController;
use App\Http\Controllers\Pages\AboutController;
use App\Http\Controllers\Pages\ContactPageController;
use App\Http\Controllers\Pages\FaqController;
use App\Http\Controllers\Pages\NewsAndAnnouncementsController;
use App\Http\Controllers\Pages\PaymentProofsController;
use App\Http\Controllers\Pages\PrivacyPolicyController;
use App\Http\Controllers\Pages\TermsController;
use App\Http\Controllers\TestFaucetPayWithdraw;
use App\Models\NewsAndAnnouncement;
use App\Models\Offerwall;
use App\Models\SocialLink;
use Illuminate\Http\RedirectResponse;

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

Route::get('/fix-balances', [FixBalancesController::class, 'index']);


//marketing Campaigns
Route::get('/notik-tracking', [CampaignsController::class, 'notik']);
Route::get('/notik-s2s', [CampaignsController::class, 'notiks2S']);


//offerwalls postbacks routes
Route::get('/postback/admantium', [OfferwallsPostbacksController::class, 'admantium']);
Route::get('/postback/adtowall', [OfferwallsPostbacksController::class, 'adtowall']);
Route::get('/postback/adscendmedia', [OfferwallsPostbacksController::class, 'adscendmedia']);
Route::get('/postback/adswedmedia', [OfferwallsPostbacksController::class, 'adswedmedia']);
Route::get('/postback/ayetstudios', [OfferwallsPostbacksController::class, 'ayetstudios']);
Route::get('/postback/adbreakmedia', [OfferwallsPostbacksController::class, 'adbreakmedia']);
Route::get('/postback/bitlabs', [OfferwallsPostbacksController::class, 'bitlabs']);
Route::post('/postback/bitcotasks', [OfferwallsPostbacksController::class, 'bitcotasks']);
Route::get('/postback/cpxresearch', [OfferwallsPostbacksController::class, 'cpxresearch']);
Route::get('/postback/lootably', [OfferwallsPostbacksController::class, 'lootably']);
Route::post('/postback/offers4crypto', [OfferwallsPostbacksController::class, 'offers4crypto']);
Route::get('/postback/excentiv', [OfferwallsPostbacksController::class, 'excentiv']);
Route::get('/postback/kiwiwall', [OfferwallsPostbacksController::class, 'kiwiwall']);
Route::get('/postback/monlix', [OfferwallsPostbacksController::class, 'monlix']);
Route::get('/postback/notik', [OfferwallsPostbacksController::class, 'notik']);
Route::post('/postback/revlum', [OfferwallsPostbacksController::class, 'revlum']);
Route::get('/postback/sushiads', [OfferwallsPostbacksController::class, 'sushiads']);
Route::get('/postback/timewall', [OfferwallsPostbacksController::class, 'timewall']);
Route::get('/postback/wannads', [OfferwallsPostbacksController::class, 'wannads']);


/*===============================================After Login Routes, with middleware auth required====================================== */
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    //'verified',
])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
  //===========Advertiser Routes==========
    Route::get('/advertiser/deposit', function () {
        return view('advertiser.deposit');
    });
    Route::get('/advertiser/transfer', function () {
        return view('advertiser.deposit.main-balance-to-advertiser');
    });
    Route::get('/pay/coinbase', [DepositController::class, 'createCoinbasePayLink']);
    Route::get('/pay/perfectmoney', [DepositController::class, 'createPerfectMoneyPayLink']);
    Route::get('/advertiser/create-new-task', [CreateTaskController::class, 'index']);
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
        $offerwalls =  Offerwall::where('status', 1)->orderBy('order', 'ASC')->get();
        foreach ($offerwalls as $offerwall) {
           $iframeUrl = $offerwall->iframe_url;
           $url = str_replace('[userid]', auth()->user()->unique_user_id, $iframeUrl);
           $offerwall->url = $url;
        }
        return view('worker.all-offers', ['offerwalls' => $offerwalls]);
    });
    Route::get('/views', function () {
        return view('worker.ptc.allptc-ads');
    });
    //worker histories routes
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

    //auth profile settings
    Route::get('/profile/security', function () {
        return view('worker.profile.profile-security');
    });

    Route::get('/profile/payout-methods', function () {
        return view('worker.profile.payment-methods');
    });


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

Route::get('/test-coinbase-commerce', [CoinbaseCommerceTestController::class, 'charge']);


Route::post('/faucetpay/callback', [DepositController::class, 'faucetpaySuccessCallback'])->middleware('web');
Route::post('/webhook/coinbase', [DepositController::class, 'handleCoinbaseWebhook'])->middleware('web');
Route::post('/webhook/perfectmoney', [DepositController::class, 'handlePerfectMoneyWebhook'])->middleware('web');

Route::get('/ads', function () {
    return view('ads');
});











Route::get('/contact', [ContactPageController::class, 'index']);
Route::post('/contact', [ContactPageController::class, 'store'])->name('contact.submit');
Route::get('/about', [AboutController::class, 'index'])->name('about.index');
Route::get('/terms', [TermsController::class, 'index'])->name('terms-and-conditions.index');
Route::get('/faq', [FaqController::class, 'index'])->name('faq.index');
Route::get('/payment-proofs', [PaymentProofsController::class, 'index'])->name('payout-proofs.index');
Route::get('/guides-and-announcements', [NewsAndAnnouncementsController::class, 'index'])->name('news.index');
Route::get('/guides-and-announcements/{id}', [NewsAndAnnouncementsController::class, 'show'])->name('news.show');
Route::get('/privacy-policy', [App\Http\Controllers\Pages\PrivacyPolicyController::class, 'index'])->name('privacy-policy.index');



Route::get('/jobs/submitted/{taskId}', [TaskSubmitController::class, 'showSbumittedProof']);
Route::post('/jobs/submitted/{taskId}/file-dispute', [TaskSubmitController::class, 'fileDispute'])->name('worker.file_dispute');
Route::post('/jobs/submitted/{taskId}/file-admin-appeal', [TaskSubmitController::class, 'fileAdminAppeal'])->name('worker.file_admin_appeal');

Route::post('/jobs/submitted/{taskId}/submit-revised-task', [TaskSubmitController::class, 'submitRevisedTask'])->name('worker.submit_revised_task');




Route::get('/views/youtube', [PtcAdController::class, 'showYoutube'])->name('worker.views.youtube.show');




Route::get('/cronjob/everyminute', [EveryMinuteController::class, 'index']);
Route::get('/cronjob/everyfiveminutes', [EveryFiveMinutesController::class, 'index']);
Route::get('/cronjob/everyday', [EveryDayController::class, 'runCronJob']);
Route::get('/cronjob/everymonth', [EveryMonthController::class, 'index']);


Route::get('/social', function () {
    $socialName = request()->query('name');

    // Fetch the link from the database based on the social name
    $socialLink = SocialLink::where('name', $socialName)->value('url');

    // If the social link is found, perform the redirection; otherwise, redirect to the default URL
    if ($socialLink) {
        return new RedirectResponse($socialLink);
    } else {
        return new RedirectResponse('/');
    }
});


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
Route::get('/stats', [AdminTaskCategoryController::class, 'showStats'])->name('admin.showstats');
Route::post('/admin/task-categories/{id}/store-rewards', [AdminTaskCategoryController::class, 'storeRewards'])->name('admin.task-categories.store-rewards');

