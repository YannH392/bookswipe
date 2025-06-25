<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function store(Request $request)
    {
        $uuid = $request->input('uuid');

        // Si l'UUID est fourni, on le retrouve ou on le crée
        if ($uuid) {
            $user = User::firstOrCreate(['id' => $uuid]);
        } else {
            // Sinon, on en génère un nouveau
            $user = User::create(['id' => (string) Str::uuid()]);
        }

        return response()->json(['uuid' => $user->id]);
    }
}
