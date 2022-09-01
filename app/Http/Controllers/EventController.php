<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Group;



class EventController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.event.index');
    }

    public function detail($id)
    {
        $events = Event::with(['group'])->where('id',$id)->first();
        // $events = Event::find($id);
        return view('admin.event.detail',compact('events'));
    }
   
}
