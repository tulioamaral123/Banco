<?php

namespace App\Http\Controllers;

use App\Models\Transacao;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // SPA - Vue Router gerencia as rotas
        // Dados serão carregados via API
        return view('dashboard');
    }
}
