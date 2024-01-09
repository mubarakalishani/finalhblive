<?php

namespace App\Admin\Actions\Ptc;

use Illuminate\Http\Request;
use OpenAdmin\Admin\Actions\Action;
use App\Models\PtcAd;

class Approve extends Action
{
    protected $selector = '.approve';

    public function handle(Request $request)
    {
        $keys = explode(',', $request->input('_key'));

        // store each selected ad in ads
        $ads = PtcAd::whereIn('id', $keys)->get();

        //Pause each task by admin i.e put status to 2
        foreach ($ads as $ad) {
            if($ad->status == 2){

            }
            else {
                $ad->update(['status' => 1]);
            }
            
        }
        return $this->response()->success('Selected Ad Approved Successfully')->refresh();
    }

    public function html()
    {
        return "<a class='approve btn btn-sm btn-success show-on-rows-selected d-none me-1 mt-1 mb-1'>Approve</a>";
    }
}