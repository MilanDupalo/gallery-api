<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditGalleryRequest;
use App\Http\Requests\GalleryRequest;
use App\Models\Gallery;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GalleriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $term = $request->query('term');
        $galleries = Gallery::search_by_title($term)->with('images', 'user', 'comments')->paginate(10);

        return response()->json($galleries);

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
    public function store(GalleryRequest $request)
    {
        $data = $request->validated();

        $gallery = new Gallery;
        $gallery->title = $data['title'];
        $gallery->description = $data['description'];
        $gallery->user()->associate(Auth::user());
        $gallery->save();

        $image = new Image;
        $image->imageURl = $data['imageURL'];
        $image->gallery()->associate($gallery);
        $image->save();

        return response()->json($gallery);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Gallery  $gallery
     * @return \Illuminate\Http\Response
     */
    public function show(Gallery $gallery)
    {
            $gallery->load('images', 'user', 'comments.user');
            return response()->json($gallery);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Gallery  $gallery
     * @return \Illuminate\Http\Response
     */
    public function edit(Gallery $gallery)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Gallery  $gallery
     * @return \Illuminate\Http\Response
     */
    public function update(EditGalleryRequest $request, Gallery $gallery, Image $images)
    {
        $data = $request->validated();

        info($data);
        $gallery->update($data);

        $image = $data['imageURL'];


        $image = Image::where('gallery_id', $gallery->id)->update(array('imageURL' => $image));

        return response()->json($gallery);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Gallery  $gallery
     * @return \Illuminate\Http\Response
     */
    public function destroy(Gallery $gallery)
    {
        $gallery->delete();
        return response()->noContent();
    }

    public function getMyGalleries($user_id)
    {
        $galleries = Gallery::with('images')->where('user_id',$user_id)->get();
        return response()->json($galleries);
    }
}
