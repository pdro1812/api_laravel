<?php

namespace App\Http\Controllers;
use App\Models\Users;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ListUsersController extends Controller
{
    // Obtendo todos os users do mais novo para o mais antigo
    public function index()
    {

        $users = Users::orderBy('created_at', 'desc')->get();
        return response()->json($users);    
    }

    //mostrar apenas um users filtrando pelo id
    public function show($id)
    {
        $user = Users::find($id);
        if ($user) {
            return response()->json($user);
        } else {
            return response()->json(['message' => 'User not found'], 404);
        }
    }

    //deletar um user filtrando pelo id
    public function destroy($id)
    {
        $user = Users::find($id);
        if ($user) {
            $user->delete();
            return response()->json(['message' => 'User deleted successfully']);
        } else {
            return response()->json(['message' => 'User not found'], 404);
        }
    }

    
}
