<?php

namespace App\Http\Controllers;

use App\Models\{
    Casino,
    Category,
    Post,
    User
};

class DashboardController extends Controller
{
    public function index()
    {
        $data['category'] = Category::count();
        $data['post'] = Post::count();
        $data['casino'] = Casino::count();
        $data['user'] = User::count();

        return view('dashboard', $data);
    }
}