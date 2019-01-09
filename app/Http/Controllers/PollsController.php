<?php

namespace barrilete\Http\Controllers;

use Illuminate\Http\Request;
use barrilete\Http\Requests\pollRequest;
use Auth;
use barrilete\Poll;
use barrilete\PollOptions;
use barrilete\PollIp;

class PollsController extends Controller {

    //MOSTRAR ENCUESTA
    public function poll($id) {

        $poll = Poll::poll($id);
        $morePolls = Poll::morePolls($id);

        if ($poll) {

            $ip = PollIp::ip($id)->first();

            if (!$ip) {

                $poll = $poll->first();
                $poll_options = $poll->option;
                $morePolls = $morePolls->get();

                return view('poll', compact('poll','poll_options','morePolls'))
                ->with('status', false);

            } else

                $poll = $poll->first();
                $poll_options = $poll->option;
                $totalVotes = $poll_options->sum('votes');
                $morePolls = $morePolls->get();

                return view('poll', compact('poll','poll_options','totalVotes','morePolls'))
                ->with('status', 'Ya has votado!');
            
        } else return view('errors.article-error');
        
    }

    //VOTOS DE LA ENCUESTA
    public function pollVote(Request $request) {

        $idOpcion = $request->input('id_opcion');
        $poll_id = $request->input('id_encuesta');
        $pollTitle = $request->input('titulo_encuesta');
        $ip = $request->input('ip');

        $optionPoll = PollOptions::options($idOpcion);

        if ($optionPoll) {

            $optionPoll->increment('votes', 1);

            PollIp::create([
                'poll_id' => $poll_id,
                'ip' => $ip
            ]);

            return redirect('poll/'.$poll_id.'/'. $pollTitle);
            
        } else return view('errors.article-error');
    }
    
    //MOSTRAR ENCUESTA
    public function previewPoll($id) {

        $poll = Poll::find($id);

        if ($poll) {

            $poll_options = $poll->option;
            return view('auth.polls.previewPoll', compact('poll','poll_options'));
                
        } else return response()->json(['Error' => 'La encuesta no existe.']);
    }
    
    //CREAR ENCUESTA
    public function createPoll(pollRequest $request) {
            
        $article = new Poll;
        $article -> user_id = $request['user_id'];
        $article -> title = $request['title'];
        $article -> date = $request['date'];
        $article -> section_id = $request['section_id'];
        $article -> author = $request['author'];
        $article -> article_desc = $request['article_desc'];
        $article -> save();
        $poll = Poll::find($article->id);

        return view('auth.polls.formOptionsPolls', compact('poll'));       
    }
    
    //GUARDAR OPCIONES
    public function createOptions(Request $request) {
                
        $poll_id = $request['poll_id'];
        $inputOptions = $request->get('option');
                  
            foreach ($inputOptions as $key => $val) {            
                
                /**GUARDAR EN BASE DE DATOS**/
                $PollOption = new PollOptions;
                $PollOption->poll_id = $poll_id;
                $PollOption->option = $inputOptions[$key];
                $PollOption->save();
            }
        
        $poll = Poll::find($poll_id);
        $poll_options = $poll->option;
        
        return view('auth.polls.previewPoll', compact('poll', 'poll_options'))
        ->with(['Exito' => 'La encuesta se ha creado correctamente.']);      
    }

    //FORMULARIO AGREGAR MAS OPCIONES
    public function addMorePollOption(Request $request) {

        $poll = Poll::find($request->id);

        if ($poll) {

            return view('auth.polls.formOptionsPolls', compact('poll'));

        } else return response()->json(['Error' => 'La encuesta no existe.']);
    }

    //BORRAR ENCUESTA
    public function deletePoll(Request $request, $id) {

        if ($request->ajax()) {

            $poll = Poll::find($id);

            if ($poll) {

                $poll->delete();
                return response()->json(['Exito' => 'La encuesta se ha eliminado correctamente del sistema.']);

            } else return response()->json(['Error' => 'La encuesta no existe.']);

        } else return response()->json(['Error' => 'Ésta no es una petición Ajax!']);
    } 
    
    //PUBLICAR ENCUESTA
    public function publishPoll(Request $request, $id) {
        
        if ($request->ajax()) {
            
            if (Auth::user()->is_admin) {
                
                $poll = Poll::find($id);
                $poll->status = 'PUBLISHED';
                $poll->save();
                $poll_options = $poll->option;
                
                return view('auth.polls.previewPoll', compact('poll','poll_options'))
                ->with(['Exito' => 'La encuesta se ha publicado correctamente.']);
                
            } else return response()->json(['Error' => 'Debes ser administrador del sitio para publicar contenido.']);
            
        } else return response()->json(['Error' => 'Ésta no es una petición Ajax!']);       
    }
    
    //FORMULARIO ACTUALIZAR ENCUESTA
    public function formUpdatePoll($id) {
        
        $poll = Poll::find($id);
        
        if ($poll) {
            
            $options = $poll->option;
            return view('auth.polls.formPollUpdate', compact('poll','options'));
            
        } else return response()->json(['Error' => 'La encuesta no existe.']);
    }
    
    //ACTUALIZAR ENCUESTA
    public function updatePoll(pollRequest $request) {
        
        $poll = Poll::find($request->id);
        
        if ($poll) {
            
            $poll->title = $request->title;
            $poll->article_desc = $request->article_desc;
            $poll->date = $request->date;
            $poll->section_id = $request->section_id;
            $poll->author = $request->author;
            $poll->status = 'DRAFT';
            $poll->save();
            
            return response()->json([
                
                'Exito' => 'La encuesta se actualizó correctamente.',
                'title' => $poll->title,
                'article_desc' => $poll->article_desc
            ]);
        } else return response()->json(['Error' => 'La encuesta no existe.']);        
    }
    
    //ACTUALIZAR OPCIONES DE LA ENCUESTA
    public function updatePollOption(Request $request) {
        
        $pollOption = PollOptions::find($request->id);
        
        if ($pollOption) {
            
            $pollOption->option = $request->option;
            $pollOption->save();
            
            return response()->json([
                'Exito' => 'La opción de la encuesta se actualizó correctamente.',
                'option' => $pollOption->option
            ]);
            
        } else return response()->json(['Error' => 'La opción de la encuesta no existe.']);
    }

    //BORAR OPCIONES DE LA ENCUESTA
    public function deletePollOption(Request $request) {
        
        $pollOption = PollOptions::find($request->id);
        
        if ($pollOption) {
            
            $pollOption->option = $request->option;
            $pollOption->delete();
            
            return response()->json([
                'Exito' => 'La opción de la encuesta se borró correctamente.',
                'option' => $pollOption->option
            ]);
            
        } else return response()->json(['Error' => 'La opción de la encuesta no existe.']);
    } 
}