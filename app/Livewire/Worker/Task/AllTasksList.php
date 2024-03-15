<?php

namespace App\Livewire\Worker\Task;
use Illuminate\Support\Carbon;
use Livewire\Component;
use App\Models\Task;
use App\Models\TaskCategory;
use App\Models\User;

class AllTasksList extends Component
{
    public $availableTasks;
    public $sortField = 'id';
    public $sortDirection = 'desc';
    public $search;
    public $perPage = 10;
    public $categories;
    public $subCategories;
    public $selectedCategory;
    public $selectedSubcategories = [];


    public function mount()
    {
        $userCountry = auth()->user()->country;
        $this->categories = TaskCategory::whereNull('parent_id')->get();
        // Retrieve tasks with targeted countries for the user's country, status 1, and employer's deposit balance greater than 0
        $this->availableTasks = Task::with(['targetedCountries' => function ($query) use ($userCountry) {
                $query->where('country', $userCountry);
        }])
        ->whereDoesntHave('submittedProofs', function ($query) {
            $query->where('worker_id', auth()->user()->id);
        })
        ->where('status', 1)
        ->whereHas('employer', function ($query) {
            $query->where('deposit_balance', '>', 0);
        })
        // Filter the tasks by the user's country and the amount_per_task column
        ->whereHas('targetedCountries', function ($query) use ($userCountry) {
            $query->where('country', $userCountry);
        })
        ->orderBy($this->sortField, $this->sortDirection)
        ->take($this->perPage)->get();
        $this->addTaskCaps();    
    }

    public function selectCategory($id){
        $this->selectedCategory = TaskCategory::find($id);
        $this->subCategories = TaskCategory::whereNotNull('parent_id')->where('parent_id', $id)->get();
        $this->loadSelectedCategory();
    }

    public function loadSelectedCategory(){
        $this->selectedSubcategories = [];
        $userCountry = auth()->user()->country;
        $categoryId = $this->selectedCategory->id;
        if ($this->sortField == 'reward') {
            // Retrieve tasks with targeted countries for the user's country, status 1, and employer's deposit balance greater than 0
            $this->availableTasks = Task::with(['targetedCountries' => function ($query) use ($userCountry) {
                    $query->where('country', $userCountry);
            }])
            ->whereDoesntHave('submittedProofs', function ($query) {
                $query->where('worker_id', auth()->user()->id);
            })
            ->where('status', 1)
            ->where('category', $categoryId)
            ->where('title', 'like', '%' . $this->search . '%')
            ->whereHas('employer', function ($query) {
                $query->where('deposit_balance', '>', 0);
            })
            // Filter the tasks by the user's country and the amount_per_task column
            ->whereHas('targetedCountries', function ($query) use ($userCountry) {
                $query->where('country', $userCountry);
            })
            // Order by the amount_per_task column in targetedCountries relationship
            ->orderBy(function ($query) use ($userCountry) {
                $query->select('amount_per_task')
                    ->from('task_targeted_countries')
                    ->whereColumn('task_targeted_countries.task_id', 'tasks.id')
                    ->where('task_targeted_countries.country', $userCountry);
            }, $this->sortDirection)
            ->take($this->perPage)->get();
        }
        else
        {
            // Retrieve tasks with targeted countries for the user's country, status 1, and employer's deposit balance greater than 0
            $this->availableTasks = Task::with(['targetedCountries' => function ($query) use ($userCountry) {
                    $query->where('country', $userCountry);
            }])
            ->whereDoesntHave('submittedProofs', function ($query) {
                $query->where('worker_id', auth()->user()->id);
            })
            ->where('status', 1)
            ->where('category', $categoryId)
            ->where('title', 'like', '%' . $this->search . '%')
            ->whereHas('employer', function ($query) {
                $query->where('deposit_balance', '>', 0);
            })
            // Filter the tasks by the user's country and the amount_per_task column
            ->whereHas('targetedCountries', function ($query) use ($userCountry) {
                $query->where('country', $userCountry);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->take($this->perPage)->get(); 
        }
        $this->addTaskCaps();
    }

    public function loadSelectedSubCategoriesTasks(){
        $userCountry = auth()->user()->country;
        if($this->selectedSubcategories){
            if ($this->sortField == 'reward') {
                // Retrieve tasks with targeted countries for the user's country, status 1, and employer's deposit balance greater than 0
                $this->availableTasks = Task::with(['targetedCountries' => function ($query) use ($userCountry) {
                        $query->where('country', $userCountry);
                }])
                ->whereDoesntHave('submittedProofs', function ($query) {
                    $query->where('worker_id', auth()->user()->id);
                })
                ->where('status', 1)
                ->whereIn('sub_category', $this->selectedSubcategories)
                ->where('title', 'like', '%' . $this->search . '%')
                ->whereHas('employer', function ($query) {
                    $query->where('deposit_balance', '>', 0);
                })
                // Filter the tasks by the user's country and the amount_per_task column
                ->whereHas('targetedCountries', function ($query) use ($userCountry) {
                    $query->where('country', $userCountry);
                })
                // Order by the amount_per_task column in targetedCountries relationship
                ->orderBy(function ($query) use ($userCountry) {
                    $query->select('amount_per_task')
                        ->from('task_targeted_countries')
                        ->whereColumn('task_targeted_countries.task_id', 'tasks.id')
                        ->where('task_targeted_countries.country', $userCountry);
                }, $this->sortDirection)
                ->take($this->perPage)->get();
            }
            else
            {
                // Retrieve tasks with targeted countries for the user's country, status 1, and employer's deposit balance greater than 0
                $this->availableTasks = Task::with(['targetedCountries' => function ($query) use ($userCountry) {
                        $query->where('country', $userCountry);
                }])
                ->whereDoesntHave('submittedProofs', function ($query) {
                    $query->where('worker_id', auth()->user()->id);
                })
                ->where('status', 1)
                ->whereIn('sub_category', $this->selectedSubcategories)
                ->where('title', 'like', '%' . $this->search . '%')
                ->whereHas('employer', function ($query) {
                    $query->where('deposit_balance', '>', 0);
                })
                // Filter the tasks by the user's country and the amount_per_task column
                ->whereHas('targetedCountries', function ($query) use ($userCountry) {
                    $query->where('country', $userCountry);
                })
                ->orderBy($this->sortField, $this->sortDirection)
                ->take($this->perPage)->get(); 
            }
            $this->addTaskCaps();
        }else{
            $this->loadSelectedCategory();
        }
    }


    public function sort($sortField, $sortDirection){
        $this->sortField = $sortField;
        $this->sortDirection = $sortDirection;
        if($sortField == 'reward'){
            $this->sortByRewardAmount();
        }
        else{
            $this->loadSortedTasks();
        }
        
    }

    public function loadMore()
    {
        $this->perPage += 10;
        
        
        if($this->selectedSubcategories)
        {
            $this->loadSelectedSubCategoriesTasks();

        }elseif($this->selectedCategory)
        {
            $this->loadSelectedCategory();
        }
        elseif($this->search)
        {
            $this->updatedSearch();
        }
        else
        {
            if ($this->sortField == 'reward') {
                $this->sortByRewardAmount();
            }
            else{
                $this->loadSortedTasks();
            }
        }
    }

    //special sort for the reward harami
    public function sortByRewardAmount(){
        $userCountry = auth()->user()->country;

        // Retrieve tasks with targeted countries for the user's country, status 1, and employer's deposit balance greater than 0
        $this->availableTasks = Task::with(['targetedCountries' => function ($query) use ($userCountry) {
                $query->where('country', $userCountry);
        }])
        ->whereDoesntHave('submittedProofs', function ($query) {
            $query->where('worker_id', auth()->user()->id);
        })
        ->where('status', 1)
        ->whereHas('employer', function ($query) {
            $query->where('deposit_balance', '>', 0);
        })
        // Filter the tasks by the user's country and the amount_per_task column
        ->whereHas('targetedCountries', function ($query) use ($userCountry) {
            $query->where('country', $userCountry);
        })
        // Order by the amount_per_task column in targetedCountries relationship
        ->orderBy(function ($query) use ($userCountry) {
            $query->select('amount_per_task')
                ->from('task_targeted_countries')
                ->whereColumn('task_targeted_countries.task_id', 'tasks.id')
                ->where('task_targeted_countries.country', $userCountry);
        }, $this->sortDirection)
        ->take($this->perPage)->get(); 
        $this->addTaskCaps();
    }


    public function loadSortedTasks()
    {
        $userCountry = auth()->user()->country;

        // Retrieve tasks with targeted countries for the user's country, status 1, and employer's deposit balance greater than 0
        $this->availableTasks = Task::with(['targetedCountries' => function ($query) use ($userCountry) {
                $query->where('country', $userCountry);
        }])
        ->whereDoesntHave('submittedProofs', function ($query) {
            $query->where('worker_id', auth()->user()->id);
        })
        ->where('status', 1)
        ->whereHas('employer', function ($query) {
            $query->where('deposit_balance', '>', 0);
        })
        // Filter the tasks by the user's country and the amount_per_task column
        ->whereHas('targetedCountries', function ($query) use ($userCountry) {
            $query->where('country', $userCountry);
        })
        ->orderBy($this->sortField, $this->sortDirection)
        ->take($this->perPage)->get();
        $this->addTaskCaps();

    }


    public function updatedSearch()
    {
        $userCountry = auth()->user()->country;

        if ($this->sortField == 'reward') {
            // Retrieve tasks with targeted countries for the user's country, status 1, and employer's deposit balance greater than 0
            $this->availableTasks = Task::with(['targetedCountries' => function ($query) use ($userCountry) {
                    $query->where('country', $userCountry);
            }])
            ->whereDoesntHave('submittedProofs', function ($query) {
                $query->where('worker_id', auth()->user()->id);
            })
            ->where('status', 1)
            ->where('title', 'like', '%' . $this->search . '%')
            ->whereHas('employer', function ($query) {
                $query->where('deposit_balance', '>', 0);
            })
            // Filter the tasks by the user's country and the amount_per_task column
            ->whereHas('targetedCountries', function ($query) use ($userCountry) {
                $query->where('country', $userCountry);
            })
            // Order by the amount_per_task column in targetedCountries relationship
            ->orderBy(function ($query) use ($userCountry) {
                $query->select('amount_per_task')
                    ->from('task_targeted_countries')
                    ->whereColumn('task_targeted_countries.task_id', 'tasks.id')
                    ->where('task_targeted_countries.country', $userCountry);
            }, $this->sortDirection)
            ->take($this->perPage)->get();
        }
        else{
            // Retrieve tasks with targeted countries for the user's country, status 1, and employer's deposit balance greater than 0
            $this->availableTasks = Task::with(['targetedCountries' => function ($query) use ($userCountry) {
                    $query->where('country', $userCountry);
            }])
            ->whereDoesntHave('submittedProofs', function ($query) {
                $query->where('worker_id', auth()->user()->id);
            })
            ->where('status', 1)
            ->where('title', 'like', '%' . $this->search . '%')
            ->whereHas('employer', function ($query) {
                $query->where('deposit_balance', '>', 0);
            })
            // Filter the tasks by the user's country and the amount_per_task column
            ->whereHas('targetedCountries', function ($query) use ($userCountry) {
                $query->where('country', $userCountry);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->take($this->perPage)->get(); 
        }
        $this->addTaskCaps();
    }

    public function addTaskCaps(){
        foreach ($this->availableTasks as $task) {
            $totalSubmissions = $task->submittedProofs()
                ->whereIn('status', [0,1,3,4,5])
                ->count();

            $submissionsInLastHour = $task->submittedProofs()
                ->where('created_at', '>=', Carbon::now()->subHour())
                ->count();

            $submissionsInLastDay = $task->submittedProofs()
                ->where('created_at', '>=', Carbon::now()->subDay())
                ->count(); 

            $submissionsInLastweek = $task->submittedProofs()
                ->where('created_at', '>=', Carbon::now()->subDays(7))
                ->count();
                
            $amountLastHour = $task->submittedProofs()
                ->where('created_at', '>=', Carbon::now()->subHour())
                ->sum('amount');
                
            $amountLastDay = $task->submittedProofs()
                ->where('created_at', '>=', Carbon::now()->subDay())
                ->sum('amount');
                
            $amountLaskWeek = $task->submittedProofs()
                ->where('created_at', '>=', Carbon::now()->subDays(7))
                ->sum('amount');
            $isTotalSubmissionExceeded = 0;
            $isSubmissionPerHourExceeded = 0;
            $isSubmissionPerDayExceeded = 0;
            $isSubmissionPerWeekExceeded = 0;
            $isBudgetPerDayExceeded = 0;
            $isBudgetPerHourExceeded = 0; 
            $isBudgetPerWeekExceeded = 0;
            if ($task->submission_per_hour !=0 && $submissionsInLastHour >= $task->submission_per_hour) {
                $isSubmissionPerHourExceeded = 1;
            }

            if ($task->submission_per_day !=0 && $submissionsInLastDay >= $task->submission_per_day) {
                $isSubmissionPerDayExceeded = 1;
            }

            if ($task->submission_per_week !=0 && $submissionsInLastweek >= $task->submission_per_week) {
                $isSubmissionPerWeekExceeded = 1;
            }
            
            if ($task->hourly_budget !=0 && $amountLastHour >= $task->hourly_budget) {
                $isBudgetPerHourExceeded = 1;
            }
            
            if ($task->daily_budget !=0 && $amountLastDay >= $task->daily_budget) {
                $isBudgetPerDayExceeded = 1;
            }

            if ($task->weekly_budget !=0 && $amountLaskWeek >= $task->weekly_budget) {
                $isBudgetPerWeekExceeded = 1;
            }

            if ($task->maximum_submissions !=0 && $totalSubmissions >= $task->maximum_submissions) {
                $isTotalSubmissionExceeded = 1; 
            }


        
            $task->hourly_submit_exceed = $isSubmissionPerHourExceeded;
            $task->daily_submit_exceed = $isSubmissionPerDayExceeded;
            $task->weekly_submit_exceed = $isSubmissionPerWeekExceeded;
            $task->hourly_budget_exceed = $isBudgetPerHourExceeded;
            $task->daily_budget_exceed = $isBudgetPerDayExceeded;
            $task->weekly_budget_exceed = $isBudgetPerWeekExceeded;
            $task->total_submissions_exceed = $isTotalSubmissionExceeded;
        }
    }

    public function render()
    {
        return view('livewire.worker.task.all-tasks-list');
    }

}
