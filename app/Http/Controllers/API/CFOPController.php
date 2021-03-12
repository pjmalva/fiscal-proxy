<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\SearchInFile;
use Illuminate\Http\Request;

class CFOPController extends Controller
{
    public function search(Request $request)
    {
        $search = new SearchInFile('cfop');
        $items = $search->find($request->q, $request->c??10);
        return response()->json($items);
    }
}
