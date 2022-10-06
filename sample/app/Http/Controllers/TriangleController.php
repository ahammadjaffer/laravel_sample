<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TriangleController extends Controller
{
    public function index(){
        return view('triangles');
    }
}
