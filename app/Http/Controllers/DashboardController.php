<?php

namespace barrilete\Http\Controllers;

use Illuminate\Http\Request;
use barrilete\User;
use barrilete\Sections;
use barrilete\Articles;
use barrilete\Gallery;

class DashboardController extends Controller
{

    public function index() {
        
        return view('auth.dashboard');
    }
    
    //ARTÍCULOS DE LOS USUARIOS
    public function userArticles(Request $request, $id) {
        
        if ($request->ajax()) {
            
            $User = User::find($id);
            $Articles = $User->articles()->orderBy('id','DESC')->paginate(10);
            
            return view('auth.viewArticles', compact('Articles'))
            ->with('status','artículos');
            
        } else return response()->json(['Error' => 'Ésta no es una petición Ajax!']);     
    }
    
    //GALERÍAS DE LOS USUARIOS
    public function userGalleries(Request $request, $id) {
        
        if ($request->ajax()) {
            
            $User = User::find($id);
            $Articles = $User->gallery()->orderBy('id','DESC')->paginate(10);
            
            return view('auth.viewArticles', compact('Articles'))
            ->with('status','galerías');
            
        } else return response()->json(['Error' => 'Ésta no es una petición Ajax!']);     
    }
    
    //ENCUESTAS DE LOS USUARIOS
    public function userPolls(Request $request, $id) {
        
        if ($request->ajax()) {
            
            $User = User::find($id);
            $Articles = $User->poll()->orderBy('id','DESC')->paginate(10);
            
            return view('auth.viewArticles', compact('Articles'))
            ->with('status','encuestas');
            
        } else return response()->json(['Error' => 'Ésta no es una petición Ajax!']);     
    }
    
    //FORMULARIO CARGAR ARTÍCULO
    public function formArticle(Request $request) {
        
        if ($request->ajax()) {
            
            if ($request->id) {               
                $article = Articles::find($request->id);
                $sections = Sections::select('id','name')->where('name', '!=', $article->section->name)->where('name','!=','Encuestas')->get();
                return view('auth.articles.formArticles', compact('article','sections'));
                
            } else               
                $sections = Sections::select('id','name')->where('name','!=','Encuestas')->get();
                return view('auth.articles.formArticles', compact('sections'));
        
        } else return response()->json(['Error' => 'Ésta no es una petición Ajax!']);
    }
    
    //FORMULARIO CARGAR GALERÍA
    public function formGallery(Request $request) {
        
        if ($request->ajax()) {
            
            $section = Sections::where('name','galerias')->first();    
            return view('auth.galleries.formGalleries', compact('section'));  
        
        } else return response()->json(['Error' => 'Ésta no es una petición Ajax!']);
    }
    
    //FORMULARIO ACTUALIZAR GALERÍA
    public function formUpdateGallery(Request $request, $id) {
        
        if ($request->ajax()) {
           
            $gallery = Gallery::find($id);
            
            if ($gallery) {
                
                $photos = $gallery->photos;
                return view('auth.galleries.formGalleryUpdate', compact('gallery','photos'));
            
            } else return response()->json(['Error' => 'La galería no existe.']);                      
        
        } else return response()->json(['Error' => 'Ésta no es una petición Ajax!']);
    }
    
    //FORMULARIO CARGAR ENCUESTA
    public function formPoll(Request $request) {
        
        if ($request->ajax()) {
            
            $section = Sections::where('name','encuestas')->first();
            return view('auth.polls.formPoll', compact('section'));  
        
        } else return response()->json(['Error' => 'Ésta no es una petición Ajax!']);
    }
}