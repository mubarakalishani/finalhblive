<?php

namespace App\Http\Controllers\Worker;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Task;
use App\Models\TaskCategory;
use App\Models\TaskTargetedCountry;
use App\Models\SubmittedTaskProof;
use App\Models\TextProof;
use App\Models\ImageProof;
use App\Models\TaskDispute;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TaskSubmitController extends Controller
{
    public function showTask($taskId)
    {
        $task = Task::findOrFail($taskId); // Assuming you have a Task model
        if ($task->status != 1) {
            return redirect(url('/jobs'))->with('error', 'the task you are trying to open is not active');
        }
        
        //check if the user's country is in the targetedCountries or not
        $countryExists = TaskTargetedCountry::where('country', auth()->user()->country)->where('task_id', $task->id)->exists();
        if(!$countryExists){
           return redirect(url('/jobs'))->with('error', 'You are not allowed to attempt that Job, try other jobs');
        }

        //check if the user has already submitted proof for the task
        $proofExists = SubmittedTaskProof::where('task_id', $taskId)->where('worker_id', auth()->user()->id)->whereIn('status', [0,1,2,4,5])->exists();
        if($proofExists){
            return redirect(url('/jobs'))->with('error', 'you have already submitted proof for this task');
        }

        return view('worker.tasks.job-detail', ['task' => $task]);
    }

    public function showSbumittedProof($taskId){
        $task = Task::findOrFail($taskId); // Assuming you have a Task model

        //check if the user has already submitted proof for the task, only give access to the page if exists
        $proofExists = SubmittedTaskProof::where('task_id', $taskId)->where('worker_id', auth()->user()->id)->exists();
        if(!$proofExists){
            return redirect(url('/jobs'))->with('error', 'Access Denied! You are not allowed to visit this page Directly');
        }

        $proof = SubmittedTaskProof::where('task_id', $taskId)->where('worker_id', auth()->user()->id)->first();

        return view('worker.tasks.submitted-proof-details', ['task' => $task, 'proof' => $proof]);
    }
    /*============================function to store the proof normally that the User will File=========================================  */

    public function store(Request $request, $taskId)
    {
        // Validation rules for image proofs and text proofs
        $rules = [
            'image_proofs.*' => 'image|mimes:jpeg,png,jpg,gif|max:5048', // Assuming maximum file size is 2MB
            'text_proofs.*' => 'string',
        ];

        // Custom error messages
        $messages = [
            'image_proofs.*.image' => 'The proof must be an image file.',
            'image_proofs.*.mimes' => 'The proof must be a file of type: jpeg, png, jpg, gif.',
            'image_proofs.*.max' => 'The proof must not be larger than 5MB.',
            'text_proofs.*.string' => 'The proof must be a string.',
        ];

        // Validate the request data
        $validatedData = $request->validate($rules, $messages);


        //check if the user has already submitted proof for the task
        $proofExists = SubmittedTaskProof::where('task_id', $taskId)->where('worker_id', auth()->user()->id)->whereIn('status', [0,1,2,4,5])->exists();
        if($proofExists){
            return back()->with('error', 'you have already submitted proof for this task');
        }
        $task = Task::findOrFail($taskId);
        //check if the user's country is in the targetedCountries or not
        $countryExists = TaskTargetedCountry::where('country', auth()->user()->country)->where('task_id', $task->id)->exists();
        if(!$countryExists){
           return redirect(url('/jobs'))->with('error', 'You are not allowed to attempt that Job, try other jobs');
        }

        //if task is not active, ine, status !=0 , terminate
        if ($task->status != 1) {
            return redirect(url('/jobs'))->with('error', 'the task you are trying to open is not active');
        }

        

        $advertiserId = $task->employer_id;
        $advertiser = User::find($advertiserId);

        //check if the advertisers deposit_balance is greater than 0 or not
        $resubmitAsked = SubmittedTaskProof::where('task_id', $taskId)->where('worker_id', auth()->user()->id)->where('status', 3)->exists();
        if($advertiser->deposit_balance < 0 && !$resubmitAsked)
        {
            return redirect(url('/jobs'))->with('error', 'the task you are trying to open is not active');
        }

        $workerId = auth()->user()->id;
        $userCountry = auth()->user()->country;
        $reward = TaskTargetedCountry::where('task_id', $taskId)->where('country', $userCountry)->value('amount_per_task');
        $advertiser->deductAdvertiserBalance(abs($reward));

        $submittedProof = new SubmittedTaskProof;
        $submittedProof->task_id = $taskId;
        $submittedProof->worker_id = $workerId;
        $submittedProof->status = 0;
        $submittedProof->amount = $reward;
        $submittedProof->save();
        
        $submittedProofId = $submittedProof->id; // Retrieve the ID of the submitted proof

        if ($request->has('text_proofs')) {
            // The input exists in the request
            $inputTextProofs = $request->input('text_proofs');
            foreach ($inputTextProofs as $proofNo => $proofText) {
                $textProof = new TextProof;
                $textProof->task_id = $taskId;
                $textProof->submitted_proof_id = $submittedProofId;
                $textProof->proof_no = $proofNo;
                $textProof->proof_text = $proofText;
                $textProof->save();
            }
        }

        if ($request->has('image_proofs')) {
            // The input exists in the request
            $imageProofs = $request->file('image_proofs', []);
            foreach ($imageProofs as $proofNo => $imageProof) {
                //customize the file name
                $fileName = 'proof_' . $proofNo . '_task_' . $taskId . '_worker_' . $workerId . '_' . time() . '.' . $imageProof->getClientOriginalExtension();
                // Store $imageProof with the customized file name
                $imageProof->move('storage/proofs/', $fileName);
                
                
                $imageProof = new ImageProof;
                $imageProof->task_id = $taskId;
                $imageProof->submitted_proof_id = $submittedProofId;
                $imageProof->proof_no = $proofNo;
                $imageProof->url = $fileName;
                $imageProof->save();
            }
        }
        return redirect(url('/jobs'))->with('success', 'The proof Is Successfully Submitted and pending the employer approval.');
    }


    /*============================function to store the Dispute that the User will File=========================================  */
    public function fileDispute(Request $request, $taskId){
        $request->validate([
            'description' => 'required|min:10',
            'proofId' => 'required',
        ]);

        $task = Task::find($taskId);
        $employerId = $task->employer_id;

        TaskDispute::create([
            'status' => 0,
            'worker_id' => auth()->user()->id,
            'task_id' => $taskId,
            'proof_id' => $request->input('proofId'),
            'description' => $request->input('description'),
            'employer_id' => $employerId
        ]);
        $proof = SubmittedTaskProof::find($request->input('proofId'));
        $proof->update(['status' => 5]);
        return back()->with('success', 'Your Dispute Is Successfully Filed');
    }

    /*============================function to store the Revise Task that the User will File=========================================  */
    public function submitRevisedTask(Request $request, $taskId){
        $proofId = $request->input('proof_id');
        
        $proof = SubmittedTaskProof::find($proofId);

        $proof->delete();
        //check if the user has already submitted proof for the task
        $proofExists = SubmittedTaskProof::where('task_id', $taskId)->where('worker_id', auth()->user()->id)->exists();
        if($proofExists){
            return back()->with('error', 'you have already submitted proof for this task');
        }
        $task = Task::findOrFail($taskId);


        $workerId = auth()->user()->id;
        $userCountry = auth()->user()->country;
        $reward = TaskTargetedCountry::where('task_id', $taskId)->where('country', $userCountry)->value('amount_per_task');

        $submittedProof = new SubmittedTaskProof;
        $submittedProof->task_id = $taskId;
        $submittedProof->worker_id = $workerId;
        $submittedProof->status = 0;
        $submittedProof->amount = $reward;
        $submittedProof->save();
        
        $submittedProofId = $submittedProof->id; // Retrieve the ID of the submitted proof

        if ($request->has('text_proofs')) {
            // The input exists in the request
            $inputTextProofs = $request->input('text_proofs');
            foreach ($inputTextProofs as $proofNo => $proofText) {
                $textProof = new TextProof;
                $textProof->task_id = $taskId;
                $textProof->submitted_proof_id = $submittedProofId;
                $textProof->proof_no = $proofNo;
                $textProof->proof_text = $proofText;
                $textProof->save();
            }
        }

        if ($request->has('image_proofs')) {
            // The input exists in the request
            $imageProofs = $request->file('image_proofs', []);
            foreach ($imageProofs as $proofNo => $imageProof) {
                //customize the file name
                $fileName = 'proof_' . $proofNo . '_task_' . $taskId . '_worker_' . $workerId . '_' . time() . '.' . $imageProof->getClientOriginalExtension();
                // Store $imageProof with the customized file name
                $imageProof->move('storage/proofs/', $fileName);
                
                
                $imageProof = new ImageProof;
                $imageProof->task_id = $taskId;
                $imageProof->submitted_proof_id = $submittedProofId;
                $imageProof->proof_no = $proofNo;
                $imageProof->url = $fileName;
                $imageProof->save();
            }
        }
        return redirect(url('/jobs'))->with('success', 'The proof Is Successfully Submitted and pending the employer approval.');

    }

}
