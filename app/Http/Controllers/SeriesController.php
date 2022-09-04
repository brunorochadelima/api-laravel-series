<?php

namespace App\Http\Controllers;

use App\Http\Requests\SeriesFormRequest;
use App\Mail\SeriesCreated;
use App\Models\Series;
use App\Models\User;
use App\Repositories\SeriesRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;


class SeriesController extends Controller
{
    public function __construct(private SeriesRepository $repository)
    {
        $this->middleware('auth')->except('index');
    }

    public function index(Request $request)
    {
        $series = Series::all();
        $mensagemSucesso = session('mensagem.sucesso');

        return view('series.index')->with('series', $series)
            ->with('mensagemSucesso', $mensagemSucesso);
    }

    public function create()
    {
        return view('series.create');
    }

    public function store(SeriesFormRequest $request)
    {
        // dd($request->file('cover'));
       
        // nome do arquivo
        $fileName = $nomeimagem = $request->file('cover')->getClientOriginalName();
        $fileNameSemEspaco = str_replace(' ', '_', $fileName);
        $extension = $nomeimagem = $request->file('cover')->getClientOriginalExtension();

        //pega arquivo salva na pasta series cover como o nome do arquivo na pasta public
        $coverPath = $request->file('cover')->storeAs('series_cover', $fileNameSemEspaco.'_'.time().'.'.$extension, 'public');

        //adiciona no request o coverPath pois ele não foi adicionado antes na interface SeriesFormRequest
        $request->coverPath = $coverPath;
        

        $serie = $this->repository->add($request);
        \App\Events\SeriesCreated::dispatch(
            $serie->nome,
            $serie->id,
            $request->seasonsQty,
            $request->episodesPerSeason,
        );

        return to_route('series.index')
            ->with('mensagem.sucesso', "Série '{$serie->nome}' adicionada com sucesso");
    }

    public function destroy(Series $series)
    {
        $series->delete();
        
       \App\Jobs\DeleteSeriesCover::dispatch($series->cover); // apaga a imagem cover

        return to_route('series.index')
            ->with('mensagem.sucesso', "Série '{$series->nome}' removida com sucesso");
    }

    public function edit(Series $series)
    {
        return view('series.edit')->with('serie', $series);
    }

    public function update(Series $series, SeriesFormRequest $request)
    {
        $series->fill($request->all());
        $series->save();

        return to_route('series.index')
            ->with('mensagem.sucesso', "Série '{$series->nome}' atualizada com sucesso");
    }
}
