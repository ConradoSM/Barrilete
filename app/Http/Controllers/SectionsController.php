<?php

namespace barrilete\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use barrilete\Articles;
use barrilete\Gallery;
use barrilete\Sections;

class SectionsController extends Controller {
    
    //VER SECCIONES
    public function searchSection($name) {

        $section = Sections::searchSection($name);
        
        if ($section) {
            
            if ($section->name == 'galerias') {
                
                $galleries = Gallery::galleries();
                
                if ($galleries) {
                    
                    return view('galleries', compact('galleries'));
                    
                } else return view('errors.section-error');
                
            } else $articles = $section->articles;

            if ($articles) {

                return view('section', compact('articles'));
                
            } else return view('errors.article-error');
            
        } else return view('errors.section-error');
    }
    
    //ADMINISTRAR SECCIONES: LISTAR
    public function index(Request $request) {
        if (Auth::user()->is_admin) {
        
            if ($request->ajax()) {
                
                $sections = Sections::all()->sortByDesc('id');
                return view('auth.sections.index', compact('sections'));
                
            } else return response()->json(['Error' => 'Ésta no es una petición Ajax!']);           
        } else return response()->json(['Error' => 'No eres administrador del sistema.']);
    }
    
    //ADMINISTRAR SECCIONES: NUEVA
    public function newSection(Request $request) {
        if (Auth::user()->is_admin) {
            
            if ($request->ajax()) {
                
                return view('auth.sections.form');
                
            } else return response()->json(['Error' => 'Ésta no es una petición Ajax!']);
        } else return response()->json(['Error' => 'No eres administrador del sistema.']);
    }
    
    //ADMINISTRAR SECCIONES: CREAR
    public function create(Request $request) {
        
        if (Auth::user()->is_admin) {
            
            if ($request->ajax()) {
                
                $section = new Sections;
                $section->name = strtolower($request->name);
                $section->prio = $request->prio;
                $section->save();
                
                if ($section) {
                    
                    return redirect()->route('sectionsIndex')->with('success','La sección se ha creado correctamente.');  
                    
                } else return redirect()->route('sectionsIndex')->with('error','Ha ocurrido un problema al tratar de crear la sección.');               
            } else return response()->json(['Error' => 'Ésta no es una petición Ajax!']);          
        } else return response()->json(['Error' => 'No eres administrador del sistema.']);
    }
    
    //ADMINISTRAR SECCIONES: EDITAR
    public function edit(Request $request, $id) {
        
        if (Auth::user()->is_admin) {
            
            if ($request->ajax()) {
                
                $section = Sections::find($id);
                
                if ($section) {
                    
                    return view('auth.sections.form', compact('section'));
                    
                } else return redirect()->route('sectionsIndex')->with('error','La sección no existe');               
            } else return response()->json(['Error' => 'Ésta no es una petición Ajax!']);           
        } else return response()->json(['Error' => 'No eres administrador del sistema.']);       
    }
    
    //ADMINISTRAR SECCIONES: ACTUALIZAR
    public function update(Request $request, $id) {
        
        if (Auth::user()->is_admin) {
        
            $section = Sections::find($id);

            if ($section) {

                $section->name = strtolower($request->name);
                $section->prio = $request->prio;
                $section->save();
                return redirect()->route('sectionsIndex')->with('success','La sección se ha actualizado.');

            } else return response()->json(['Error' => 'La sección no existe.']);           
        } else return response()->json(['Error' => 'No eres administrador del sistema.']);
    }
    
    //ADMINISTRAR SECCIONES: BORRAR
    public function delete(Request $request, $id) {
        
        if (Auth::user()->is_admin) {
            
            if ($request->ajax()) {
        
                $section = Sections::find($id);

                if ($section) {

                    $section->delete();
                    return redirect()->route('sectionsIndex')->with('success','La sección se ha eliminado.');

                } else return response()->json(['Error' => 'La sección no existe.']);
            } else return response()->json(['Error' => 'Ésta no es una petición Ajax!']);
        } else return response()->json(['Error' => 'No eres administrador del sistema.']);
    }
}