<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FaqController extends Controller
{
    public function index()
    {
        return view('faq.index', [
            'faqs' => Faq::where('is_active', true)->get()
        ]);
    }

    public function adminIndex()
    {
        abort_unless(Auth::user()?->isAdmin(), 403);

        return view('admin.faq.index', [
            'faqs' => Faq::all()
        ]);
    }

    public function store(Request $request)
    {
        abort_unless(Auth::user()?->isAdmin(), 403);

        $request->validate([
            'question' => 'required|string',
            'answer' => 'required|string',
            'category' => 'nullable|string'
        ]);

        Faq::create([
            'question' => $request->question,
            'answer' => $request->answer,
            'category' => $request->category,
            'is_active' => true
        ]);

        return back();
    }

    public function update(Request $request, Faq $faq)
    {
        abort_unless(Auth::user()?->isAdmin(), 403);

        $request->validate([
            'question' => 'required|string',
            'answer' => 'required|string',
            'category' => 'nullable|string'
        ]);

        $faq->update($request->only(['question','answer','category']));
        return back();
    }


    public function destroy(Faq $faq)
    {
        abort_unless(Auth::user()?->isAdmin(), 403);

        $faq->delete();
        return back();
    }

    public function toggle(Faq $faq)
    {
        abort_unless(Auth::user()?->isAdmin(), 403);

        $faq->update([
            'is_active' => ! $faq->is_active
        ]);

        return back();
    }
}
