<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ApiClient;

class AuthController extends Controller
{
    protected $apiClient;

    public function __construct(ApiClient $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        
        // CORRECCIÓN: Ahora usamos postForm en lugar de post
        $response = $this->apiClient->postForm('/auth/login', [
            'username' => $request->email,
            'password' => $request->password,
            'scope' => 'admin'
        ]);

        if (isset($response['error'])) {
            return back()->withErrors(['error' => 'Credenciales inválidas']);
        }

        session(['api_token' => $response['access_token']]);

        // Obtener datos del usuario (opcional)
        $userInfo = $this->apiClient->get('/users/me');
        if (!isset($userInfo['error'])) {
            session(['admin_user' => $userInfo]);
        }

        return redirect()->route('admin.dashboard');
    }

    public function logout()
    {
        session()->forget('api_token');
        session()->forget('admin_user');
        return redirect()->route('login');
    }
}