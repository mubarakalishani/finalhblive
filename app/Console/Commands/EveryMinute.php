<?php

namespace App\Console\Commands;

use App\Models\Log;
use App\Models\OffersAndSurveysLog;
use App\Models\Offerwall;
use App\Models\User;
use App\Models\WithdrawalHistory;
use Carbon\Carbon;
use Illuminate\Console\Command;

class EveryMinute extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:every-minute';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::create([
            'user_id' => 1,
            'description' => 'the cronjob rann at '.now()
        ]);
        $pendingWithdrawals = WithdrawalHistory::where('status' , 0)->get();
        foreach ($pendingWithdrawals as $withdrawal) {
            if ( $withdrawal->user->balance < 0 ) {
                $user = User::find($withdrawal->user->id);
                $user->addWorkerBalance($withdrawal->amount_no_fee);
                Log::create([
                    'user_id' => $withdrawal->user->id,
                    'description' => 'withdrawal # '.$withdrawal->id.' is returned blc<0'
                ]);

                $withdrawal->update(['status' => 2]);
            }
        }

        $offersPendingCleared = OffersAndSurveysLog::where(function ($query) {
            $query->whereRaw('NOW() > DATE_ADD(created_at, INTERVAL hold_time MINUTE)');
        })->where('status', 1)->get();

        foreach ($offersPendingCleared as $offer) {
                $offerUser = User::find($offer->worker->id);
                $offerUser->addWorkerBalance($offer->reward);
                $offerUser->increment('diamond_level_balance', $offer->added_expert_level);
                Log::create([
                    'user_id' => $offer->worker->id,
                    'description' => 'reward '.$offer->reward.' added from '.$offer->provider_name
                ]);

                $offer->update(['status' => 0]);
        }
    }
}
