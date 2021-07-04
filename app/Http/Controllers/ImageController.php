<?php

namespace barrilete\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\View\View;
use Image;

class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        return view('images');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Application|RedirectResponse|Redirector
     */
    public function store(Request $request)
    {
        if ($request->hasFile('profile_image')) {
            $filenamewithextension = $request->file('profile_image')->getClientOriginalName();
            $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
            $extension = $request->file('profile_image')->getClientOriginalExtension();
            $filenametostore = $filename.'_'.time().'.'.$extension;
            $request->file('profile_image')->storeAs('public/profile_images', $filenametostore);
            $request->file('profile_image')->storeAs('public/profile_images/thumbnail', $filenametostore);
            $thumbnailpath = public_path('storage/profile_images/thumbnail/'.$filenametostore);
            $img = Image::make($thumbnailpath)->resize(400, 150, function($constraint) {
                $constraint->aspectRatio();
            });
            $img->save($thumbnailpath);

            return redirect('images')->with('success', 'Image uploaded successfully.');
        }

        return redirect('images')->with('error', 'Image is not uploaded.');
    }

    /**
     * Display the specified resource.
     *
     * @param $image
     * @return Response
     */
    public function showImage($image) : Response
    {
        $file = Storage::disk('public_html/img')->get($image);

        return $file->response('jpg');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return void
     */
    public function edit(int $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return void
     */
    public function update(Request $request, int $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy(int $id) : Response
    {
        //
    }
}
