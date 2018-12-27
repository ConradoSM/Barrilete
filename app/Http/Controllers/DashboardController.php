<?php

namespace barrilete\Http\Controllers;

use Illuminate\Http\Request;
use barrilete\User;
use barrilete\Sections;

class DashboardController extends Controller
{

    public function index()
    {
        return view('auth.dashboard');
    }
    
    //ARTÍCULOS DE LOS USUARIOS
    public function userArticles(Request $request, $id) {
        
        if ($request->ajax()) {
            
            $User = User::find($id);
            $Articles = $User->articles()->paginate(10);
            
            return view('auth.viewArticles', compact('Articles'))
            ->with('status','artículos');
            
        } else return 'Error: ésta no es una petición Ajax!';     
    }
    
    //GALERÍAS DE LOS USUARIOS
    public function userGalleries(Request $request, $id) {
        
        if ($request->ajax()) {
            
            $User = User::find($id);
            $Articles = $User->gallery()->paginate(10);
            
            return view('auth.viewArticles', compact('Articles'))
            ->with('status','galerías');
            
        } else return 'Error: ésta no es una petición Ajax!';     
    }
    
    //ENCUESTAS DE LOS USUARIOS
    public function userPolls(Request $request, $id) {
        
        if ($request->ajax()) {
            
            $User = User::find($id);
            $Articles = $User->poll()->paginate(10);
            
            return view('auth.viewArticles', compact('Articles'))
            ->with('status','encuestas');
            
        } else return 'Error: ésta no es una petición Ajax!';     
    }
    
    //FORMULARIO CARGAR ARTÍCULO
    public function formArticle(Request $request) {
        
        if ($request->ajax()) {
            
        $sections = Sections::select('id','name')->get();
        return view('auth.articles.formArticles', compact('sections'));
        
        } else return 'Error: ésta no es una petición Ajax!';
    }
    
    //FORMULARIO CARGAR GALERÍA
    public function formGallery(Request $request) {
        
        if ($request->ajax()) {
            
        $section = Sections::where('name','galerias')->first();    
        return view('auth.galleries.formGalleries', compact('section'));  
        
        } else return 'Error: ésta no es una petición Ajax!';
    }
}