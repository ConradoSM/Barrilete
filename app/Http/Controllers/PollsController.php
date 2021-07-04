<?php

namespace barrilete\Http\Controllers;

use barrilete\User;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
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
use Throwable;

class PollsController extends Controller
{
    /**
     * Show Poll In Frontend
     * @param $id
     * @return Factory|View|void
     */
    public function poll($id)
    {
        $article = Poll::poll($id);
        if ($article && $article->valid_from <= Carbon::now()) {
            $ipRequest = Request()->getClientIp();
            $ip = PollIp::where('poll_id',$id)
            ->where('ip',$ipRequest)
            ->first();
            $morePolls = Poll::morePolls($id);
            $poll_options = $article->option;
            $totalVotes = $poll_options->sum('votes');

            if ($article->valid_to <= Carbon::now()) {
                return view('poll', compact('article','poll_options','totalVotes','morePolls'))
                    ->with('status', 'La encuesta ha cerrado.');
            }

            if ($ip) {
                return view('poll', compact('article','poll_options','totalVotes','morePolls'))
                    ->with('status', 'Ya has votado!');
            }

            return view('poll', compact('article','poll_options','morePolls'))->with('status', false);
        }

        return abort(404);
    }

    /**
     * Set Poll Vote
     * @param Request $request
     * @return Application|Redirector|RedirectResponse|void
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

        return abort(404);
    }

    /**
     * Show Poll In Admin
     * @param $id
     * @return Factory|JsonResponse|View
     * @throws Throwable
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
     * Create Poll
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
        $poll->valid_from = $request->valid_from;
        $poll->valid_to = $request->valid_to;
        $poll->save();

        return view('auth.polls.formOptionsPolls', compact('poll'));
    }

    /**
     * Create Options
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
     * More Options Form
     * @param $id
     * @return Factory|JsonResponse|View
     * @throws Throwable
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
     * Delete Poll
     * @param Request $request
     * @param $id
     * @return JsonResponse
     * @throws Throwable
     */
    public function delete(Request $request, $id) : JsonResponse
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
     * Publish Poll
     * @param Request $request
     * @param $id
     * @return Factory|JsonResponse|View
     * @throws Throwable
     */
    public function publishPoll(Request $request, $id)
    {
        if ($request->ajax()) {
            if (Auth::user()->authorizeRoles(User::ADMIN_USER_ROLE)) {
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
     * Poll Form
     * @param $id
     * @return Factory|JsonResponse|View
     * @throws Throwable
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
     * Update Poll
     * @param pollRequest $request
     * @return Factory|View|JsonResponse
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
            $poll->valid_from = $request->valid_from;
            $poll->valid_to = $request->valid_to;
            $poll->save();
            $options = $poll->option->first() ? $poll->option : [];

            return view('auth.polls.formPollUpdate', compact('poll','options'))
                ->with('success', 'Se actualizó la encuesta.');
        }

        return response()->json(['error' => 'La encuesta no existe.'],404);
    }

    /**
     * Update Options Poll
     * @param Request $request
     * @return Factory|View|JsonResponse
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
     * Delete Options Poll
     * @param Request $request
     * @param $id
     * @return JsonResponse
     * @throws Throwable
     */
    public function deleteOption(Request $request, $id) : JsonResponse
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
     * Unpublished Polls
     * @param Request $request
     * @return Factory|JsonResponse|View
     * @throws Throwable
     */
    public function unpublishedPolls(Request $request)
    {
        if ($request->ajax()) {
            if (Auth::user()->authorizeRoles(User::ADMIN_USER_ROLE)) {
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
