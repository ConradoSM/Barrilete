<?php

namespace barrilete\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class contenidoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $titularesIndex = DB::table('contenido')
                        ->select('id','titulo','copete','foto','seccion','video')
                        ->where('publicar',1)
                        ->orderBy('id','DESC')
                        ->limit(15)
                        ->get();
       
       $galeriasIndex = DB::table('momentos')
                        ->select('momentos.id','momentos.titulo','fotos.foto')
                        ->join('fotos','momentos.id','fotos.idmomento')
                        ->where('momentos.publicar',1)
                        ->limit(1)
                        ->groupBy('momentos.id')
                        ->orderBy('momentos.id','DESC')
                        ->get();
       
       $pollsIndex = DB::table('encuesta')
                    ->select('id','titulo','fecha')
                    ->where('publicar',1)
                    ->orderBy('id','DESC')
                    ->limit(3)
                    ->get();
       
       return view('home')
       ->with(compact('titularesIndex'))
       ->with(compact('galeriasIndex'))
       ->with(compact('pollsIndex'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, $seccion)
    {   
        $article = DB::table('contenido')
                ->where('id',$id)
                ->where('seccion',$seccion)
                ->limit(1);
        
        if($article->exists()) {
            DB::table('contenido')
                    ->where('id',$id)
                    ->update(['visitas' => DB::raw('visitas + 1')]);
            
            $moreArticles = DB::table('contenido')
                    ->select('id','titulo','foto','seccion')
                    ->where('id','!=',$id)
                    ->where('publicar',1)
                    ->where('seccion',$seccion)
                    ->orderBy('id','DESC')
                    ->limit(8)
                    ->get();
            
            $article = $article->get();
            
            return view('article')
            ->with(compact('article'))
            ->with(compact('moreArticles'));

            } else { return view('article_error'); }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function sec($seccion)
    {
        $section = DB::table('contenido')
                ->select('id','titulo','copete','foto','video','seccion')
                ->where([['seccion',$seccion],['publicar',1],])
                ->orderBy('id','DESC')
                ->limit(15);
        
        if($section->exists()) {
            $section = $section->get();
            return view('section', compact('section'));

            } else { return view('article_error'); }
    }
    
    
    public function galleries()
    {
       $galleries = DB::table('momentos')
                    ->select('momentos.id','momentos.titulo','momentos.copete','fotos.foto')
                    ->join('fotos','momentos.id','fotos.idmomento')
                    ->where('momentos.publicar',1)
                    ->limit(15)
                    ->groupBy('momentos.id')
                    ->orderBy('momentos.id','DESC');
       if($galleries->exists()) {
           
           $galleries = $galleries->get();
           return view('galleries', compact('galleries'));
       } else { return view('article_error'); }    
    }
    
    
    public function gallery($id)
    {
       $gallery = DB::table('momentos')
                    ->where('id',$id)
                    ->where('publicar',1)
                    ->limit(1);
       if($gallery->exists()) {
        
        DB::table('momentos')
                ->where('id',$id)
                ->update(['visitas' => DB::raw('visitas + 1')]);
        
        $fotos = DB::table('fotos')
                ->where('idmomento',$id)
                ->get();
           
        $gallery = $gallery->get();
        
           return view('gallery')
            ->with(compact('gallery'))
            ->with(compact('fotos'));
           
       } else { return view('article_error'); }    
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
