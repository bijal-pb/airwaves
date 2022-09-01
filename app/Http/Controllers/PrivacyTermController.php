<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PrivacyTerm;

class PrivacyTermController extends Controller
{
    public function privacy()
    {
        $p = PrivacyTerm::find(1);
        $privacy = $p->privacy;
        return view('privacy-term.privacy', compact('privacy'));
    }
    public function term()
    {
        $p = PrivacyTerm::find(1);
        $term = $p->term;
        return view('privacy-term.term', compact('term'));
    }
    public function index(){
        $privacy = PrivacyTerm::find(1);
        return view('admin.privacy-term.privacy', compact('privacy'));
    }
    public function update(Request $request)
    {
        $p = PrivacyTerm::find(1);
        $p->privacy = $request->privacy;
        $p->term = $request->term;
        $p->save();
        return redirect()->route('privacy.index')->with('message','Privacy updated Successfully');
    }
}
