<?php

namespace App\Admin\Actions\Ptc;

use App\Models\Log;
use Illuminate\Http\Request;
use OpenAdmin\Admin\Actions\Action;
use App\Models\PtcAd;
use App\Models\User;

class Reject extends Action
{
    protected $selector = '.reject';

    public function handle(Request $request)
    {
        $keys = explode(',', $request->input('_key'));

        // store each selected ads in ads
        $ads = PtcAd::whereIn('id', $keys)->get();

        //Pause each task by admin i.e put status to 2
        foreach ($ads as $ad) {
            if ($ad->status != 2) {
                $employerId = $ad->employer_id;
                $user = User::find($ad->employer_id);
                $ad->update(['status' => 2]);
                $adBalance = $ad->ad_balance;
                $ad->decrement('ad_balance', abs($adBalance));
                $user->increment('deposit_balance' , $adBalance);
                Log::create([
                    'user_id' => $employerId,
                    'description' => 'admin rejected Ptc ad id '.$ad->id.'and remaining adbalance '.$adBalance.' added back to employer'
                ]); 
            }
        }
        return $this->response()->success('Selected Ad Rejected Successfully')->refresh();
    }

    public function html()
    {
        return "<a class='reject btn btn-sm btn-danger show-on-rows-selected d-none me-1 mt-1 mb-1'>Reject</a>";
    }
}