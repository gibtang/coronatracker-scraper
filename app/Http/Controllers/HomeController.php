<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    public function get_articles()
    {
        $all_articles = \DB::table('newsapi_n')->orderBy('addedOn', 'DESC')->paginate(10);

        //return view('get_articles')->with('all_articles');
        //dd($all_articles);
        return \View::make('get_articles', ['all_articles' => $all_articles]);//view("get_articles", compact("all_articles"));
    }

    public function search_article(Request $request)
    {   
        $keyword = $request->search;
        $all_articles = \DB::table('newsapi_n')
        ->orWhere('title', 'LIKE', '%'.$keyword.'%')
        //->orWhere('description', 'LIKE', '%'.$keyword.'%')
        //->orWhere('url', 'LIKE', '%'.$keyword.'%')
        ->orderBy('addedOn', 'DESC')->paginate(10);

        return \View::make('get_articles', ['all_articles' => $all_articles]);
    }

    public function edit_article($id)
    {
        $article = \DB::table('newsapi_n')->where('nid', $id)->first();
        return \View::make('edit_article', ['article' => $article]);
    }

    public function update_article(Request $request, $id)
    {
        $article = \DB::table('newsapi_n')->where('nid', $id)
        ->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Article updated');
    }
}
