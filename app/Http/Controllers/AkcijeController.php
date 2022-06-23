<?php

namespace App\Http\Controllers;

use App\Models\Akcija;
use App\Models\Donacija;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AkcijeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        if (!AuthController::isLoggedInAdmin()) {
            return response()->json(['error' => 'samo admin ima prvao'], 403);
        }

        return Akcija::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        if (!AuthController::isLoggedInAdmin()) {
            return response()->json(['error' => 'samo admin ima prvao'], 403);
        }

        $this->validate($request, [
            "naslov" => "required|string|min:5|max:255",
            "opis" => "required|string|min:20|max:2000",
            "vrijeme" => "required|date_format:Y-m-d H:i:s|after:1 days",
            "pozeljan_broj_volontera" => "required|integer|min:0",
            "status" => ["required", Rule::in(Akcija::$inizijalni_statusi)],
            "izvjestaj" => "prohibited",
        ]);

        return Akcija::create($request->all());

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        if (!AuthController::isLoggedInAdmin()) {
            return response()->json(['error' => 'samo admin ima prvao'], 403);
        }

        return Akcija::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        if (!AuthController::isLoggedInAdmin()) {
            return response()->json(['error' => 'samo admin ima prvao'], 403);
        }

        $akcija = Akcija::find($id);
        if ($akcija == null) {
            return response()->json(['error' => 'nema akcije'], 404);
        }

        $this->validate($request, [
            "naslov" => "required|string|min:5|max:255",
            "opis" => "required|string|min:20|max:2000",
            "vrijeme" => "required|date_format:Y-m-d H:i:s|after:1 days",
            "pozeljan_broj_volontera" => "required|integer|min:0",
            "status" => ["required", Rule::in(Akcija::$statusi)],
            "izvjestaj" => Rule::prohibitedIf($request->get('status') != Akcija::ZAVRSENA),
        ]);

        $akcija->update($request->all());
        return $akcija;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        if (!AuthController::isLoggedInAdmin()) {
            return response()->json(['error' => 'samo admin ima prvao'], 403);
        }

        $akcija = Akcija::find($id);
        if ($akcija == null) {
            return response()->json(['error' => 'nema akcije'], 404);
        }

        $akcija->delete();

        return response()->json(['success' => 'uspjesno obrisana akcija'], 200);
    }
}
