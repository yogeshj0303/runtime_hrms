<?php

namespace App\Http\Controllers\Setup\Statutory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Tds24QInfoController extends Controller
{
    public function index()
    {
        return view('admin.setup.statutory.tds-24q.index');
    }
}
