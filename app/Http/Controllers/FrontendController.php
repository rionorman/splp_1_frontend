<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FrontendController extends Controller
{
    public function beranda()
    {
        $response = Http::get('http://127.0.0.1:8001/api/indexPostAPI');
        $response = (json_decode($response, false));
        $rows = $response->data;
        return view('frontend/beranda', ['rows' => $rows]);
    }

    public function detail($id)
    {
        $response = Http::get('http://127.0.0.1:8001/api/showPostAPI/' . $id);
        $response = (json_decode($response, false));
        $row = $response->data;
        return view('frontend/detail', ['row' => $row]);
    }
}
