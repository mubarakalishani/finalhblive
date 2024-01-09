<?php

namespace App\Admin\Actions;

use Illuminate\Database\Eloquent\Collection;
use OpenAdmin\Admin\Actions\BatchAction;

class BatchReplicate extends BatchAction
{
    public $name = 'Batch copy';

    public function handle(Collection $collection)
    {
        foreach ($collection as $model) {
            dd($collection);
        }

        // return $this->response()->success('Success message...')->refresh();
    }

}