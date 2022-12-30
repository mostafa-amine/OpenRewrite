<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RewriterController extends Controller
{
    public function index()
    {
        return view('rewrite');
    }
}
