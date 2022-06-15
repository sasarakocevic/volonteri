<?php

namespace App\Http\Controllers;

use App\Models\Donacija;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class DonacijeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Donacija::all();
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
        return Donacija::find($id);
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

        $email = "";
        if (auth('sanctum')->check()) {
            if (!$this->isLoggedIn($donacija->donator)) {
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

        if (!$this->isLoggedIn($donacija->donator)) {
            return response()->json(['error' => 'nemate pravo brisanja' . $donacija->donator], 403);
        }

        $donacija->delete($id);
        return response()->json(['success' => 'uspjesno obrisana donacija'], 200);
    }

    public function isLoggedIn($email)
    {
        if (!auth('sanctum')->check()) return false;

        $user = User::find(auth('sanctum')->user()->getAuthIdentifier());
        return $email == $user->email;

    }
}
