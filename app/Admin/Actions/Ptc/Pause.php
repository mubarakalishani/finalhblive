<?php

namespace App\Admin\Actions\Ptc;

use Illuminate\Http\Request;
use OpenAdmin\Admin\Actions\Action;
use App\Models\PtcAd;

class Pause extends Action
{
    protected $selector = '.pause';

    public function handle(Request $request)
    {
        $keys = explode(',', $request->input('_key'));

        // store each selected ad in ads
        $ads = PtcAd::whereIn('id', $keys)->get();

        //Pause each task by admin i.e put status to 2
        foreach ($ads as $ad) {
            $ad->update(['status' => 5]);
        }
        return $this->response()->success('Selected Ad Approved Successfully')->refresh();
    }

    public function html()
    {
        return "<a class='pause btn btn-sm btn-secondary show-on-rows-selected d-none me-1 mt-1 mb-1'>Pause</a>";
    }
}