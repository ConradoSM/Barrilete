<?php

namespace barrilete\Http\Controllers;

use Illuminate\Http\Request;
use barrilete\Poll;
use barrilete\PollOptions;
use barrilete\PollIp;

class PollsController extends Controller {

    /** MOSTRAR ENCUESTA**/

    public function poll($id) {

        $poll = Poll::poll($id);
        $morePolls = Poll::morePolls($id);

        if ($poll->exists()) {

            $ip = PollIp::ip($id)->first();

            if (!$ip) {

                $poll = $poll->first();
                $poll_options = $poll->option;
                $morePolls = $morePolls->get();

                view('poll', compact('poll','poll_options','morePolls'))
                        ->with('status', false);
            } else {

                $poll = $poll->first();
                $poll_options = $poll->option;
                $totalVotes = $poll_options->sum('votes');
                $morePolls = $morePolls->get();

                return view('poll', compact('poll','poll_options','totalVotes','morePolls'))
                        ->with('status', 'Ya has votado!');
            }
        } else {
            return view('errors.article-error');
        }
    }

    /** VOTOS DE LA ENCUESTA**/

    public function pollVote(Request $request) {

        $idOpcion = $request->input('id_opcion');
        $poll_id = $request->input('id_encuesta');
        $pollTitle = $request->input('titulo_encuesta');
        $ip = $request->input('ip');

        $optionPoll = PollOptions::options($idOpcion);

        if ($optionPoll->exists()) {

            $optionPoll->increment('votes', 1);

            PollIp::create([
                'poll_id' => $poll_id,
                'ip' => $ip
            ]);

            return redirect('poll/' . $poll_id . '/' . $pollTitle);
            
        } else
            
            return view('errors.article-error');
    }
    
    /** MOSTRAR ENCUESTA**/

    public function previewPoll($id) {

        $poll = Poll::poll($id);

        if ($poll->exists()) {

                $poll = $poll->first();
                $poll_options = $poll->option;

                return view('auth.polls.previewPoll', compact('poll','poll_options'));
                
        } else {
                return view('auth.articles.previewArticleError');
        }
    }
}
