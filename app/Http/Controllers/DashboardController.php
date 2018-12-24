<?php

namespace barrilete\Http\Controllers;

use Illuminate\Http\Request;
use barrilete\User;

class DashboardController extends Controller
{

    public function index()
    {
        return view('auth.dashboard');
    }
    
    public function userArticles(Request $request, $id) {
        
        if ($request->ajax()) {
            
            $User = User::find($id);
            $Articles = $User->articles()->get();
            
            return view('auth.articles.viewArticles', compact('Articles'))
            ->with('status','artículos');
            
        } else { echo 'Error: ésta no es una petición Ajax!'; }      
    }
        
    public function userGalleries(Request $request, $id) {
        
        if ($request->ajax()) {
            
            $User = User::find($id);
            $Articles = $User->gallery()->get();
            
            return view('auth.articles.viewArticles', compact('Articles'))
            ->with('status','galerías');;
            
        } else { echo 'Error: ésta no es una petición Ajax!'; }      
    }

    public function userPolls(Request $request, $id) {
        
        if ($request->ajax()) {
            
            $User = User::find($id);
            $Articles = $User->poll()->get();
            
            return view('auth.articles.viewArticles', compact('Articles'))
            ->with('status','encuestas');;
            
        } else { echo 'Error: ésta no es una petición Ajax!'; }      
    }

    public function formArticle() {

        return view('auth.articles.formArticles');
    }
}