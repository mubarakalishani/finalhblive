<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('home');
    $router->resource('users', UserController::class);
    $router->resource('task-categories', TaskCategoryController::class);
    $router->resource('faucet-claims', FaucetClaimController::class);
    $router->resource('offers-and-surveys-logs', OffersAndSurveysLogsController::class);
    $router->resource('tasks', TaskController::class);
    $router->resource('task-required-proofs', TaskRequiredProofs::class);
    $router->resource('task-steps', TaskStepsController::class);
    $router->resource('offerwalls-settings', OfferwallSettingsController::class);
    $router->resource('settings', Settingscontroller::class);
    $router->resource('available-countries', AvailableCountryController::class);
    $router->resource('admin-task-decline-reasons', TaskDeclineReasonController::class);
    $router->resource('ptc-logs', PtcLogController::class);
    $router->resource('submitted-task-proofs', SubmittedTaskProofController::class);
    $router->resource('ptc-ads', PtcAdController::class);
    $router->resource('ptc-ad-packages', PtcAdPackageController::class);
    $router->resource('short-l-inks', ShortLInkController::class);
    $router->resource('short-links-histories', ShortLinksHistoryController::class);
    $router->resource('available-rejection-reasons', AvailableRejectionReasonController::class);
    $router->resource('cheat-logs', CheatLogController::class);
    $router->resource('ip-logs', IpLogController::class);
    $router->resource('deposit-method-settings', DepositMethodSettingController::class);
    $router->resource('payout-gateways', PayoutGatewayController::class);
    $router->resource('withdrawal-histories', WithdrawalHistoryController::class);
    $router->resource('support-departments', SupportDepartmentController::class);
    $router->resource('faqs', FaqController::class);
    $router->resource('offerwalls', OfferwallController::class);
    $router->resource('faucet-settings', FaucetSettingController::class);
    $router->resource('pages', PagesController::class);
    $router->resource('deposit-methods', DepositMethodController::class);
    $router->resource('social-links', SocialLinksController::class);
    $router->resource('support-tickets', SupportTicketController::class);
    $router->resource('deposits', DepositController::class);
    $router->resource('news-and-announcements', NewsAndAnnouncementController::class);









});
