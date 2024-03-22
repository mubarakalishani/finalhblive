<?php

namespace App\Http\Controllers\Cronjobs;

use App\Http\Controllers\Controller;
use App\Models\Statistic;
use Illuminate\Http\Request;

class EveryMonthController extends Controller
{
    public function index(){
        $this->resetStats();    
    }
    protected function resetStats(){
        $statistic = Statistic::latest()->firstOrCreate([]);
        $statistic->update([
            'tasks_last_month' => $statistic->tasks_this_month,
            'offers_last_month' => $statistic->offers_this_month,
            'shortlinks_last_month' => $statistic->shortlinks_this_month,
            'ptc_last_month' => $statistic->ptc_this_month,
            'faucet_last_month' => $statistic->faucet_this_month,
            'tasks_this_month' => 0,
            'offers_this_month' => 0,
            'shortlinks_this_month' => 0,
            'ptc_this_month' => 0,
            'faucet_this_month' => 0,
        ]);
        
    }
}
