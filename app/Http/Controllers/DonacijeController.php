<?php

namespace App\Http\Controllers;

use App\Models\Donacija;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class DonacijeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $donacijeQuery = Donacija::with('slike');

        // vrati samo svoje ako je ulogovan i ako ima ?mojeDonacije
        if (auth('sanctum')->check() && $request->has('mojeDonacije')){
            $donacijeQuery->where('donator',User::find(auth('sanctum')->user()->getAuthIdentifier())->email);
        }

        return $donacijeQuery->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            "naslov" => "required|string|min:5|max:255",
            "lokacija" => "required|string|min:5|max:255",
            "opis" => "required|string|min:20|max:2000",
            "status" => ["required", Rule::in(Donacija::$statusi)],
        ]);

        if (auth('sanctum')->check()) {
            $this->validate($request, [
                "donator" => "prohibited",
            ]);
            $user = User::find(auth('sanctum')->user()->getAuthIdentifier());
            $request->merge([
                'donator' => $user->email,
            ]);
        } else {
            $this->validate($request, [
                "donator" => "required|email",
            ]);
        }

        return Donacija::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show($id)
    {
        return Donacija::with('slike')->find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            "naslov" => "required|string|min:5|max:255",
            "lokacija" => "required|string|min:5|max:255",
            "opis" => "required|string|min:20|max:2000",
            "status" => ["required", Rule::in(Donacija::$statusi)],
        ]);

        $donacija = Donacija::find($id);
        if ($donacija == null) {
            return response()->json(['error' => 'nema donacije'], 404);
        }

        if ($loggedInEmail = AuthController::getLoggedInEmail()) {
            if ($donacija->donator != $loggedInEmail) {
                return response()->json(['error' => 'nemate pravo promjene'], 403);
            }
        } elseif ($donacija->donator != $request->get("donator")) {
            return response()->json(['error' => 'nemate pravo promjene'], 403);
        }

        $donacija->update($request->all());
        return $donacija;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $donacija = Donacija::find($id);
        if ($donacija == null) {
            return response()->json(['error' => 'nema donacije'], 404);
        }

        // samo logovani korisnik moze brisati
        if ($donacija->donator != AuthController::getLoggedInEmail()) {
            return response()->json(['error' => 'nemate pravo brisanja'], 403);
        }

        $donacija->delete();

        // izbrisi slike
        Storage::deleteDirectory("public/donacije/$id");

        return response()->json(['success' => 'uspjesno obrisana donacija'], 200);
    }


}
