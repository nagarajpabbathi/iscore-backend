<?php

namespace App\Http\Controllers;

use App\Models\Casino;
use Illuminate\Http\Request;

class CasinoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $casinos = Casino::when(isset($request->name), function ($q) use ($request) {
            $q->where('title', 'like', "%{$request->name}%");
            $q->Orwhere('banner_title', 'like', "%{$request->name}%");
        })
            ->when(isset($request->status), function ($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->when(isset($request->rating), function ($q) use ($request) {
                $q->where('rating', $request->rating);
            })
            ->when(isset($request->top_rated), function ($q) use ($request) {
                $q->where('top_rated', $request->top_rated);
            })
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('casino.list', compact('casinos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('casino.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->validate([
            'banner_title' => 'required|max:250',
            'title' => 'required|unique:casinos',
            'description' => 'required',
            'rating' => 'required',
            'url' => 'required|url',
            'img' => 'required|image',
            'status' => 'required|in:0,1',
            'top_rated' => 'nullable',
        ]);

        $input['top_rated'] = isset($input['top_rated']) ? 1 : 0;

        $input['img'] = uploadImage($input['img'], 'casino');

        Casino::create($input);

        return redirect()
            ->route('casino')
            ->with('casino.success', 'Casino created successfully!');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Casino  $casino
     * @return \Illuminate\Http\Response
     */
    public function edit(Casino $casino)
    {
        return view('casino.edit', compact('casino'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Casino  $casino
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Casino $casino)
    {
        $input = $request->validate([
            'banner_title' => 'required|max:250',
            'title' => 'required|unique:casinos,title,' . $casino->id,
            'description' => 'required',
            'rating' => 'required',
            'url' => 'required|url',
            'img' => 'nullable|image',
            'status' => 'required|in:0,1',
            'top_rated' => 'nullable',
        ]);


        $input['top_rated'] = isset($input['top_rated']) ? 1 : 0;

        if (isset($input['img'])) {

            $input['img'] = uploadImage($input['img'], 'casino');

            deleteImage($casino->img);
        }

        $casino->update($input);

        return redirect()
            ->route('casino')
            ->with('casino.success', 'Casino updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Casino  $casino
     * @return \Illuminate\Http\Response
     */
    public function destroy(Casino $casino)
    {
        $casino->delete();

        return redirect()
            ->route('casino')
            ->with('casino.error', 'Casino deleted successfully!');
    }
    // API
    public function casinoList(Request $request)
    {
        $casinos['top_rated_list'] = Casino::select(
            'id',
            'title',
            'banner_title',
            'description',
            'rating',
            'url',
            'img'
        )
            ->where('top_rated', 1)
            ->where('status', 1)
            ->orderBy('id', 'desc')
            ->get();

        $casinos['trending_of_month'] = Casino::select(
            'id',
            'title',
            'banner_title',
            'description',
            'rating',
            'url',
            'img'
        )
            ->where('top_rated', 0)
            ->where('status', 1)
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'data'      => $casinos,
            'message'   => 'Casino List!',
            'response'  => true
        ], 200);
    }

    public function casinoDetails($id)
    {
        $casinos = Casino::select(
            'id',
            'title',
            'banner_title',
            'description',
            'rating',
            'url',
            'img'
        )
            ->where('id', $id)
            ->where('status', 1)
            ->first(10);

        return response()->json([
            'data'      => $casinos,
            'message'   => 'Casino List!',
            'response'  => true
        ], 200);
    }
}
