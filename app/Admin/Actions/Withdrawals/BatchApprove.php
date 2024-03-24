<?php

namespace App\Admin\Actions\Withdrawals;

use App\Models\User;
use App\Models\WithdrawalHistory;
use Illuminate\Http\Request;
use OpenAdmin\Admin\Actions\Action;

class BatchApprove extends Action
{
    protected $selector = '.batch-approve';

    public function handle(Request $request)
    {
        $keys = explode(',', $request->input('_key'));

        // store each selected ad in ads
        $withdrawalRequests = WithdrawalHistory::whereIn('id', $keys)->get();

        //Pause each task by admin i.e put status to 2
        foreach ($withdrawalRequests as $withdrawalRequest) {
            if($withdrawalRequest->status == 2){
                $user = User::find($withdrawalRequest->user_id);
                $user->decrement('balance', $withdrawalRequest->amount_no_fee);
                $user->update([
                    'total_withdrawn' => $withdrawalRequest->amount_no_fee
                ]);
                $withdrawalRequest->update(['status' => 1]);
            }
            else {
                $withdrawalRequest->update(['status' => 1]);
                $user = User::find($withdrawalRequest->user_id);
                $user->update([
                    'total_withdrawn' => $withdrawalRequest->amount_no_fee
                ]);
            }
            
        }
        return $this->response()->success('Selected requests Approved Successfully')->refresh();
    }

    public function html()
    {
        return "<a class='batch-approve btn btn-sm btn-success show-on-rows-selected d-none me-1 mt-1 mb-1'>Approve</a>";
    }
}