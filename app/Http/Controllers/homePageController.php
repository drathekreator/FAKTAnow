<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class homePageController extends Controller
{
    //
    public function index(){
        return view('homepage');
    }
}
