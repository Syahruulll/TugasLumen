<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StuffStockController extends Controller
{
    public function __construct()
    {
        //middleware:membatasi,nama" function yang hanya bisa diakses setelah login
        $this->middleware('auth:api');
    }
    }
