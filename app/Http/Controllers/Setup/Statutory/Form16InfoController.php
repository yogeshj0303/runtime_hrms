<?php

namespace App\Http\Controllers\Setup\Statutory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Form16InfoController extends Controller
{
    public function index()
    {
        return view('admin.setup.statutory.form16-info.index');
    }
}
