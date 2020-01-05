<?php

namespace barrilete\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use barrilete\Articles;
use barrilete\Gallery;
use barrilete\Poll;
use Throwable;

class SearchController extends Controller
{
    /**
     * @param Request $request
     * @return Factory|View
     */
    public function search(Request $request)
    {
        $section = $request->input('sec');
        $search = $request->input('query');
        if ($section == 'articulos') {
            $result = Articles::search($search);
        } elseif ($section == 'galerias') {
            $result = Gallery::search($search);
        } elseif ($section == 'encuestas') {
            $result = Poll::search($search);
        } else {
            return view('errors.404');
        }
        $result->appends(['query' => $search, 'sec' => $section]);
        return view('search', compact('result'));
    }

    /**
     * @param Request $request
     * @return Factory|JsonResponse|View
     * @throws Throwable
     */
    public function searchAuth(Request $request)
    {
        if ($request->ajax()) {
            $section = $request->input('sec');
            $search = $request->input('query');
            $author = $request->input('author');
            if ($section == 'articulos') {
                $result = Articles::searchAuth($search, $author);
            } elseif ($section == 'galerias') {
                $result = Gallery::SearchAuth($search, $author);
            } elseif ($section == 'encuestas') {
                $result = Poll::SearchAuth($search, $author);
            } else {
                $result = [];
                return response()->json([
                    'view' => view('auth.search', compact('result'))->render()
                ])->header('Content-Type', 'application/json');
            }
            $result->appends(['query' => $search, 'sec' => $section, 'author' => $author]);
            return response()->json([
                'view' => view('auth.search', compact('result'))->render()
            ])->header('Content-Type', 'application/json');
        }
        return response()->json(['error' => 'Ésta no es una petición Ajax!']);
    }
}
