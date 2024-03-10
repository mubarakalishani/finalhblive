<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use App\Models\TaskCategory;
use App\Models\CategoryReward;
use App\Models\AvailableCountry;
use App\Models\Deposit;
use App\Models\FaucetClaim;
use App\Models\OffersAndSurveysLog;
use App\Models\PtcAd;
use App\Models\PtcLog;
use App\Models\ShortLinksHistory;
use App\Models\WithdrawalHistory;
use OpenAdmin\Admin\Admin;
use OpenAdmin\Grid\Tools;
use OpenAdmin\Grid\Displayers\Actions;
use OpenAdmin\Grid\Tools\BatchActions;
use OpenAdmin\Admin\Layout\Content;

class AdminTaskCategoryController extends Controller
{
    public function addRewards($id)
    {
        $category = TaskCategory::find($id);
        $countries = AvailableCountry::all();

        $admin = app(Admin::class);

        return $admin->content(function (Content $content) use ($category, $countries) {
            // Create a form for adding rewards for each country
            $content->body(view('admin.add_rewards_form', compact('category', 'countries')));
        });
    }

    // Handle the form submission
    public function storeRewards(Request $request, $id)
    {
        // Validate and store rewards for each country
        // ...
        dd($request->all());
        // Redirect back or wherever appropriate
        // return redirect()->back();
    }

    public function showStats()
    {
        $withdrawals = WithdrawalHistory::all();
        $deposits = Deposit::where('status', 'completed')->get();
        $offers = OffersAndSurveysLog::whereIn('status', [0,1])->get();
        $shortlinks = ShortLinksHistory::all();
        $pendingPtcAd = PtcAd::where('status', 0)->get();
        $ptcEarnings = PtcLog::all();
        $faucet = FaucetClaim::all();

        $admin = app(Admin::class);

        return $admin->content(function (Content $content) use ($withdrawals, $deposits, $offers, $shortlinks, $pendingPtcAd, $ptcEarnings, $faucet) {
            // Create a form for adding rewards for each country
            $content->body(view('admin.stats', compact('withdrawals', 'deposits', 'offers', 'shortlinks', 'pendingPtcAd', 'ptcEarnings', 'faucet')));
        });
    }
}
