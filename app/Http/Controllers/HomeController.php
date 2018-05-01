<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Modalidade;
use App\Campus;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }
    public function inscricoes()
    {
        $modalidades = Modalidade::all();
        if(\Auth::user()->admin){
            $campi = Campus::all();
        }else{
            $campi = Campus::where('id',\Auth::user()->campus_id)->get();
        }
        return view('inscricoes', compact('modalidades','campi'));
    }
    public function inscricoes_modaliade(Request $request)
    {
        $validatedData = $request->validate([
            'campus' => 'required|numeric',
            'modalidade' => 'required|numeric',
        ]);
        if(!\Auth::user()->admin && \Auth::user()->campus_id != $request->campus){
            abort(403);
        }
        $modalidade = Modalidade::find($request->modalidade);
        return view('inscricoes_modalidade', compact('modalidade'));
    }
    public function relacao()
    {
        return view('home');
    }
}
