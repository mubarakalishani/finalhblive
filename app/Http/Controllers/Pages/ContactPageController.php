<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\SupportDepartment;
use App\Models\SupportTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContactPageController extends Controller
{
    public function index(Request $request){
        $departments = SupportDepartment::all();
        return view('pages.contact', ['departments' => $departments]);
    }

    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'name' => 'required|string|max:50',
            'email' => 'required|string|max:100',
            'department' => 'required|string|max:100',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:1000|min:20',
        ]);

        SupportTicket::create([
            'user_id' => auth()->check() ? auth()->user()->id : null,
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'department_id' => SupportDepartment::where('name', $request->input('department'))->value('id'),
            'subject' => $request->input('subject'),
            'message' =>  $request->input('message'),
        ]);

        return back()->with('success', 'Your Query is Submitted Successfully! We will get back to you asap');
    }
}
