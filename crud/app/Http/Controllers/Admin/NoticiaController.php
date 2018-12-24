<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Noticia;
use DB;

class NoticiaController extends Controller
{
    public function index(){
        $noti = DB::table ('noticias')
        ->join ('users', 'noticias.user_id', '=', 'users.id')
        ->select('noticias.id','users.name','noticias.titulo', 'noticias.subtitulo',  'noticias.imagem','noticias.created_at')
        ->orderByRaw('noticias.created_at DESC')
        ->get();

        return view('admin.noticias.index', compact('noti'));      
    }

    public function mynews(){

        $noti = DB::table ('noticias')
        ->join ('users', 'noticias.user_id', '=', 'users.id')
        ->select('noticias.id','users.name','noticias.titulo', 'noticias.subtitulo')
        ->get();

        // $registros = Noticia::all();

        return view('admin.noticias.mynews', compact('noti'));
    }

    public function adicionar(){
        
        return view('admin.noticias.adicionar');
    }

    public function salvar(Request $req){
        $dados = $req->all();
        
        if($req->hasFile('imagem')){
            $imagem = $req->file('imagem');
            $num = rand(1111,9999);
            $dir = "img/noticias";
            $ex = $imagem->guessClientExtension();
            $nomeImagem = "imagem_".$num.".".$ex;
            $imagem->move($dir,$nomeImagem);
            $dados['imagem'] = $dir."/".$nomeImagem;
        }

        Noticia::create($dados);

        \Session::flash('mensagem_sucesso','Notícia cadastrada com sucesso!');

        return redirect()->route('admin.minhas.noticias');
    }

    public function editar($id){
        $registro = Noticia::find($id);

        return view ('admin.noticias.editar', compact('registro'));
    }

    public function atualizar(Request $req, $id){
        $dados = $req->all();
        
        if($req->hasFile('imagem')){
            $imagem = $req->file('imagem');
            $num = rand(1111,9999);
            $dir = "img/noticias";
            $ex = $imagem->guessClientExtension();
            $nomeImagem = "imagem_".$num.".".$ex;
            $imagem->move($dir,$nomeImagem);
            $dados['imagem'] = $dir."/".$nomeImagem;
        }

        Noticia::find($id)->update($dados);

        \Session::flash('mensagem_sucesso','Suas foram atua com sucesso!');

        return redirect()->route('admin.minhas.noticias');
    }

    public function deletar($id){
        Noticia::find($id)->delete();

        \Session::flash('mensagem_sucesso','Notícia excluída com sucesso!');

        return redirect()->route('admin.minhas.noticias');
    }
}
