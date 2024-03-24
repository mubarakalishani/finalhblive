<?php

namespace App\Livewire\Advertise\Ptc;

use App\Models\Log;
use App\Models\PtcAd;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class PtcCampaignHistory extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $sortField = 'id';
    public $sortDirection = 'desc';
    public $search = '';
    public $status = '';
    public $perPage = 10;
    public $adId;

    public $editBudget = false;
    public $editClicks = false;
    public $budgetToAdd = 0;
    public $clicksToAdd = 0;

    protected function rules(){
        return [
            'budgetToAdd' => 'required|numeric',
        ];
    } 

    public function showEditBudget($id){
        $this->editBudget = true;
        $this->editClicks = false;
        $this->adId = $id;
    }

    public function showEditClicks($id){
        $this->editBudget = false;
        $this->editClicks = true;
        $this->adId = $id;
    }

    public function updatedClicksToAdd(){
        $ad = PtcAd::find($this->adId);
        if ($this->clicksToAdd > 0) {
            $this->budgetToAdd = $this->clicksToAdd * $ad->reward_per_view;
        }
        else{
            $this->clicksToAdd = 0;
            $this->budgetToAdd = $this->clicksToAdd * $ad->reward_per_view;
        }
        
    }

    public function submitUpdatedBudget(){
        $ad = PtcAd::find($this->adId);
        $user = User::find(auth()->user()->id);
        if (auth()->user()->deposit_balance < $this->budgetToAdd) {
            return redirect(url('/advertiser/ptc-campaigns-list'))->with('error', 'Your advertising balance is less than the amount you entered');
        }
        else{
            if (auth()->user()->deposit_balance > 0 && $this->budgetToAdd > 0) {
                $this->validate();
                $user->deductAdvertiserBalance(abs($this->budgetToAdd));
                $ad->increment('ad_balance', $this->budgetToAdd);
                $ad->increment('views_needed', $this->budgetToAdd/$ad->reward_per_view);
                Log::create([
                    'user_id' => auth()->user()->id,
                    'description' => 'modified and added budget to the ptc ad '.$this->adId.' with $'.$this->budgetToAdd,
                ]);
                return redirect(url('/advertiser/ptc-campaigns-list'))->with('success', 'The campaign budget is successfully Updated');   
            }
            else{
                return redirect(url('/advertiser/ptc-campaigns-list'))->with('error', 'something went wrong, either your advertising balance is insufficient or you have entered negative numbers'); 
            }
            
        }
    }

    public function pauseResume($adId){
        $ptcAd = PtcAd::find($adId);
        if($ptcAd->status == 1){
            $ptcAd->update(['status' => 3]);
        }elseif($ptcAd->status == 3){
            $ptcAd->update(['status' => 1]);
        }
    }

    public function stopCampaign($id){
        $ptcAd = PtcAd::find($id);
        $employer = User::find($ptcAd->employer_id);
        $ptcAd->update([
            'status' => 7
        ]);
        $employer->increment('deposit_balance', $ptcAd->ad_balance);


    }

    public function render()
    {
        return view('livewire.advertise.ptc.ptc-campaign-history', [
            "ptcAds" => PtcAd::search($this->search)
            ->where('employer_id', auth()->user()->id)
            ->when($this->status !== '',function($query){
                $query->where('status',$this->status);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage)
        ]);
    }
}
