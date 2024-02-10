<?php

namespace App\Http\Controllers\Advertiser;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Task;
use App\Models\TaskCategory;
use App\Models\TaskRequiredProof;
use App\Models\TaskStep;
use App\Models\TaskTargetedCountry;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


class CreateTaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (auth()->user()->deposit_balance < 0) {
            return redirect(url('/advertiser/deposit'))->with('error', 'Your Advertising Balance is not enough, kindly deposit first');
        }
        return view('advertiser.task.create-campaign');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'title' => 'required|string|max:50',
            'rating_time' => 'required|numeric',
            'step.*' => 'required|string|max:255',
            'requiredProof.*' => 'required|string|max:255',
            'proofType.*' => 'required|string|max:255',
            'includedCountries.*' => 'required|string|max:255',
            'category' => 'required|numeric',
            'subCategory' => 'required|numeric',
        ]);

        $taskBalance = 0.00;
        $task = new Task;
        $task->title = $request->input('title');
        $task->employer_id = Auth::user()->id;
        $task->worker_level = $request->input('worker_level');
        $task->task_balance = $taskBalance;
        $task->hold_time = 0;
        $task->status = 0;
        $task->category = $request->input('category');
        $task->sub_category = $request->input('subCategory');
        $task->rating_time = $request->input('rating_time');
        $task->max_budget = $request->input('dailyBudget');
        $task->daily_budget = $request->input('dailyBudget');
        $task->weekly_budget = $request->input('weeklyBudget');
        $task->hourly_budget = $request->input('hourlyBudget');
        $task->submission_per_day = $request->input('submissionPerDay');
        $task->submission_per_hour = $request->input('submissionPerHour');
        $task->submission_per_week = $request->input('submissionPerWeek');




        //store required proofs
        $inputData = $request->input('requiredProofs');
        $proofs = [];
        $proofCount = 1;
        foreach ($inputData as $item) {
            $proofs[] = [
                // 'proof_no' => $proofCount,
                'proof_text' => $item['input'],
                'proof_type' => $item['type'],
            ];
            $proofCount++;
        }
        

        //store targeted/included countries
        $includedCountries = $request->input('includedCountries');
        $targetedCountries = [];

        foreach ($includedCountries as $country => $amount) {
            $targetedCountries[] = [
                'country' => strval($country),
                'amount_per_task' => $amount,
            ];
        }
        

        //store task steps/instructions
        $inputSteps = $request->input('step');
        $steps = [];

        foreach ($inputSteps as $index => $stepDetails) {
            $steps[] = [
                'step_no' => $index + 1,
                'step_details' => strval($stepDetails ),
            ];
        }
        

        $task->save();
        $task->requiredProofs()->createMany($proofs);
        $task->targetedCountries()->createMany($targetedCountries);
        $task->stepDetails()->createMany($steps);

        return redirect(url('/advertiser/campaigns'))->with('success', 'Your Campaign is Successfully Created and Pending Approval');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
