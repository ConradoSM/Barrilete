<?php

namespace barrilete\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Redirector;
use Illuminate\View\View;
use barrilete\Http\Requests\pollRequest;
use barrilete\Poll;
use barrilete\PollOptions;
use barrilete\PollIp;

class PollsController extends Controller
{
    /**
     * MOSTRAR ENCUESTA
     * @param $id
     * @return Factory|View
     */
    public function poll($id)
    {
        $poll = Poll::poll($id);
        if ($poll) {
            $ipRequest = Request()->getClientIp();
            $ip = PollIp::where('poll_id',$id)
            ->where('ip',$ipRequest)
            ->first();
            if (!$ip) {
                $poll_options = $poll->option;
                $morePolls = Poll::morePolls($id);
                return view('poll', compact('poll','poll_options','morePolls'))->with('status', false);

            }
            $poll_options = $poll->option;
            $totalVotes = $poll_options->sum('votes');
            $morePolls = Poll::morePolls($id);
            return view('poll', compact('poll','poll_options','totalVotes','morePolls'))
            ->with('status', 'Ya has votado!');
        }
        return view('errors.404');
    }

    /**
     * VOTOS DE LA ENCUESTA
     * @param Request $request
     * @return Factory|RedirectResponse|Redirector|View
     */
    public function pollVote(Request $request)
    {
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
        }
        return view('errors.404');
    }

    /**
     * MOSTRAR ENCUESTA
     * @param $id
     * @return Factory|JsonResponse|View
     * @throws \Throwable
     */
    public function preview($id)
    {
        $poll = Poll::findOrFail($id);
        if ($poll) {
            $poll_options = $poll->option;
            return response()->json([
                'view' => view('auth.polls.previewPoll', compact('poll','poll_options'))->render()
            ])->header('Content-Type', 'application/json');
        }
        return response()->json(['error' => 'La encuesta no existe.'],404);
    }

    /**
     * CREAR ENCUESTA
     * @param pollRequest $request
     * @return Factory|View
     */
    public function createPoll(pollRequest $request)
    {
        $poll = new Poll;
        $poll->user_id = $request->user_id;
        $poll->title = $request->title;
        $poll->section_id = $request->section_id;
        $poll->author = $request->author;
        $poll->article_desc = $request->article_desc;
        $poll->save();
        return view('auth.polls.formOptionsPolls', compact('poll'));
    }

    /**
     * GUARDAR OPCIONES
     * @param Request $request
     * @return Factory|JsonResponse|View
     */
    public function createOptions(Request $request)
    {
        $poll_id = $request->poll_id;
        $poll = Poll::find($poll_id);
        if ($poll) {
            $inputOptions = $request->option;
                foreach ($inputOptions as $key => $val) {
                    /**GUARDAR EN BASE DE DATOS**/
                    $PollOption = new PollOptions;
                    $PollOption->poll_id = $poll_id;
                    $PollOption->option = $inputOptions[$key];
                    $PollOption->save();
                }
            $poll_options = $poll->option;
            return view('auth.polls.previewPoll', compact('poll', 'poll_options'))
            ->with(['success' => 'La encuesta se ha creado correctamente.']);
        }
        return response()->json(['error' => 'La encuesta no existe.']);
    }

    /**
     * FORMULARIO AGREGAR MAS OPCIONES
     * @param $id
     * @return Factory|JsonResponse|View
     * @throws \Throwable
     */
    public function addOptions($id)
    {
        $poll = Poll::find($id);
        if ($poll) {
            return response()->json([
                'view' => view('auth.polls.formOptionsPolls', compact('poll'))->render()
            ])->header('Content-Type', 'application/json');
        }
        return response()->json(['error' => 'La encuesta no existe.'],404);
    }

    /**
     * BORRAR ENCUESTA
     * @param Request $request
     * @param $id
     * @return JsonResponse
     * @throws \Throwable
     */
    public function delete(Request $request, $id)
    {
        if ($request->ajax()) {
            $user = Auth::user();
            $poll = Poll::find($id);
            if ($poll) {
                $poll->delete();
                $articles = $user->poll()->orderBy('id','DESC')->paginate(10);
                return response()->json([
                    'view' => view('auth.viewArticles', compact('articles'))
                        ->with('status','encuestas')
                        ->with('success', 'La encuesta se ha borrado.')
                        ->render()
                ])->header('Content-Type', 'application/json');
            }
            return response()->json(['error' => 'La encuesta no existe.'],404);
        }
        return response()->json(['error' => 'Ésta no es una petición Ajax!']);
    }

    /**
     * PUBLICAR ENCUESTA
     * @param Request $request
     * @param $id
     * @return Factory|JsonResponse|View
     * @throws \Throwable
     */
    public function publishPoll(Request $request, $id)
    {
        if ($request->ajax()) {
            if (Auth::user()->is_admin) {
                $poll = Poll::find($id);
                if ($poll) {
                    $poll_options = $poll->option->first() ? $poll->option : null;
                    if ($poll_options) {
                        $poll->status = 'PUBLISHED';
                        $poll->save();
                        return response()->json([
                            'view' => view('auth.polls.previewPoll', compact('poll', 'poll_options'))
                                ->with(['success' => 'La encuesta se ha publicado correctamente.'])->render()
                        ])->header('Content-Type', 'application/json');
                    }
                    return response()->json(['error' => 'La encuesta no se publicó, porque no hay opciones relacionadas.'],403);
                }
                return response()->json(['error' => 'La encuesta no existe.'],404);
            }
            return response()->json(['error' => 'Debes ser administrador del sitio para publicar contenido.'],401);
        }
        return response()->json(['error' => 'Ésta no es una petición Ajax!']);
    }

    /**
     * FORMULARIO ACTUALIZAR ENCUESTA
     * @param $id
     * @return Factory|JsonResponse|View
     * @throws \Throwable
     */
    public function formUpdatePoll($id)
    {
        $poll = Poll::find($id);
        if ($poll) {
            $options = $poll->option->first() ? $poll->option : [];
            return response()->json([
                'view' => view('auth.polls.formPollUpdate', compact('poll','options'))->render()
            ])->header('Content-Type', 'application/json');
        }
        return response()->json(['error' => 'La encuesta no existe.'],404);
    }

    /**
     * ACTUALIZAR ENCUESTA
     * @param pollRequest $request
     * @return JsonResponse
     */
    public function update(pollRequest $request)
    {
        $poll = Poll::find($request->id);
        if ($poll) {
            $poll->title = $request->title;
            $poll->article_desc = $request->article_desc;
            $poll->section_id = $request->section_id;
            $poll->author = $request->author;
            $poll->status = 'DRAFT';
            $poll->save();
            $options = $poll->option->first() ? $poll->option : [];
            return view('auth.polls.formPollUpdate', compact('poll','options'))
                ->with('success', 'Se actualizó la encuesta.');
        }
        return response()->json(['error' => 'La encuesta no existe.'],404);
    }

    /**
     * ACTUALIZAR OPCIONES DE LA ENCUESTA
     * @param Request $request
     * @return JsonResponse
     */
    public function updateOption(Request $request)
    {
        $option = PollOptions::find($request->id);
        if ($option) {
            $option->option = $request->option;
            $option->save();
            $poll = $option->poll;
            $poll->status = 'DRAFT';
            $poll->save();
            $options = $poll->option->first() ? $poll->option : [];
            return view('auth.polls.formPollUpdate', compact('poll','options'))
                ->with('success', 'Se actualizó la opción de la encuesta.');
        }
        return response()->json(['error' => 'La opción de la encuesta no existe.'],404);
    }

    /**
     * BORRAR OPCIONES DE LA ENCUESTA
     * @param Request $request
     * @param $id
     * @return JsonResponse
     * @throws \Throwable
     */
    public function deleteOption(Request $request, $id)
    {
        $pollOption = PollOptions::find($id);
        if ($pollOption) {
            $pollOption->option = $request->option;
            $pollOption->delete();
            $poll = $pollOption->poll;
            $poll->status = 'DRAFT';
            $poll->save();
            $options = $poll->option->first() ? $poll->option : [];
            return response()->json([
                'view' => view('auth.polls.formPollUpdate', compact('poll','options'))
                    ->with('success','Se ha borrado la opción.')->render()
            ])->header('Content-Type', 'application/json');
        }
        return response()->json(['error' => 'La opción de la encuesta no existe.'],404);
    }

    /**
     * ENCUESTAS SIN PUBLICAR
     * @param Request $request
     * @return Factory|JsonResponse|View
     * @throws \Throwable
     */
    public function unpublishedPolls(Request $request)
    {
        if ($request->ajax()) {
            if (Auth::user()->is_admin) {
                $articles = Poll::unpublished();
                return response()->json([
                    'view' => view('auth.viewArticles', compact('articles'))->with('status','encuestas')->render()
                ])->header('Content-Type', 'application/json');
            }
            return response()->json(['error' => 'Tu no eres administrador del sistema.']);
        }
        return response()->json(['error' => 'Ésta no es una petición Ajax!']);
    }
}
