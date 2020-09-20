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
     * Search Content
     * @param Request $request
     * @return Factory|View
     */
    public function search(Request $request)
    {
        $section = $request->input('sec');
        $search = $request->input('query');
        $result = '';

        if ($section == 'articulos') {
            $result = Articles::search($search)->paginate(10);
        }
        if ($section == 'galerias') {
            $result = Gallery::search($search)->paginate(10);
        }
        if ($section == 'encuestas') {
            $result = Poll::search($search)->paginate(10);
        }
        $result->appends(['query' => $search, 'sec' => $section]);

        return view('search', compact('result'));
    }

    /**
     * Autocomplete
     * @param Request $request
     * @return JsonResponse|void
     * @throws Throwable
     */
    public function autocomplete(Request $request)
    {
        if($request->ajax()) {
            $query = $request->input('query');

            $results = array_values([
                'articles' => Articles::search($query)->get(),
                'galleries' => Gallery::search($query)->get(),
                'polls' => Poll::search($query)->get()
            ]);

            $response = [];
            foreach ($results as $key => $values) {
                foreach ($values as $result) {
                    $sectionName = $result->section->name;
                    $url = '';
                    if ($sectionName !== 'galerias' && $sectionName !== 'encuestas') {
                        $url = route('article', [
                            'id' => $result->id,
                            'section' => str_slug($sectionName),
                            'title' => str_slug($result->first()->title, '-')
                        ]);
                    }
                    if ($sectionName == 'galerias') {
                        $url = route('gallery', [
                            'id' => $result->id,
                            'title' => str_slug($result->first()->title, '-')
                        ]);
                    }
                    if ($sectionName == 'encuestas') {
                        $url = route('poll', [
                            'id' => $result->id,
                            'title' => str_slug($result->first()->title, '-')
                        ]);
                    }
                    $data = [
                        'title' => $result->title,
                        'url' => $url
                    ];
                    if (array_key_exists($sectionName, $response)) {
                        array_push($response[$sectionName], $data);
                    } else {
                        $response[$sectionName][$key] = $data;
                    }
                }
            }

            return response()->json([view('autocomplete', compact('response'))->render()]);
        }

        return abort(404);
    }

    /**
     * Search Content in Dashboard
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
