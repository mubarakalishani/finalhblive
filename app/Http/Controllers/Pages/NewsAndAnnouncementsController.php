<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\NewsAndAnnouncement;
use Illuminate\Http\Request;

class NewsAndAnnouncementsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $latestNews = NewsAndAnnouncement::orderBy('id', 'DESC')->latest()->first();
        $news = NewsAndAnnouncement::orderBy('id', 'DESC')
        ->where('id', '<>', $latestNews->id)
        ->get();
        return view('pages.news.news', [
            'latestNews' => $latestNews,
            'news' => $news
        ]);
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
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $newsToShow = NewsAndAnnouncement::findOrFail($id);
        $news = NewsAndAnnouncement::where('id', '<>', $id)->orderBy('id', 'DESC')->get();
        return view('pages.news.news-full-page', [
            'newsToShow' => $newsToShow,
            'news' => $news
        ]);

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
