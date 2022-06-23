<?php

namespace App\Http\Controllers;

use App\Models\Donacija;
use App\Models\Slika;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\JsonResponse;

class SlikeController extends Controller
{
    public function store(Request $request, $donacija_id)
    {
        $request->validate([
            'image.*' => 'mimes:jpeg,png,jpg',
        ]);
        if ($request->hasFile('image')) {

            $donacija = Donacija::find($donacija_id);
            if ($donacija == null) {
                return response()->json(['error' => 'nema donacije'], 404);
            }
            if ($loggedInEmail = AuthController::getLoggedInEmail()) {
                if ($donacija->donator != $loggedInEmail) {
                    return response()->json(['error' => 'nemate pravo dodavanja slike'], 403);
                }
            } elseif ($donacija->donator != $request->get("donator")) {
                return response()->json(['error' => 'nemate pravo dodavanja slikeaa'], 403);
            }

            $putanja = $request->file('image')->storePublicly("donacije/$donacija_id", 'public');
            $slika = Slika::create(['putanja' => $putanja, 'donacija_id' => $donacija_id]);

            return response()->json($slika);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $donacija_id
     * @param int $slika_id
     * @return JsonResponse
     */
    public function destroy($donacija_id, $slika_id)
    {
        $slika = Slika::find($slika_id);
        if ($slika == null) {
            return response()->json(['error' => 'nema slike'], 404);
        }
        $donacija = Donacija::find($donacija_id);
        if ($donacija == null) {
            return response()->json(['error' => 'nema donacije'], 404);
        }
        if ($donacija->id != $slika->donacija_id) {
            return response()->json(['error' => 'nemate pravo brisanja'], 403);
        }

        // samo logovani korisnik moze brisati
        if ($donacija->donator != AuthController::getLoggedInEmail()) {
            return response()->json(['error' => 'nemate pravo brisanja'], 403);
        }

        Storage::delete('public/'.$slika->pravaPutanjaDoFajla());
        $slika->delete();

        return response()->json(['success' => 'uspjesno obrisana slika'], 200);
    }
}
