<?php

namespace App\Http\Controllers\Api;

use App\Models\Series;
use App\Http\Controllers\Controller;
use App\Repositories\SeriesRepository;
use Illuminate\Http\Request;
use App\Http\Requests\SeriesFormRequest;


class SeriesController extends Controller
{
  public function __construct(private SeriesRepository $seriesRepository)
  {

  }

  //Busca todas as séries ou filtra pelo nome
  //http://127.0.0.1:8000/api/series?nome=homem%20aranha
  public function index(Request $request)
  {
    // dd($request->has('nome'));
    $query = Series::query();
    if ($request->has('nome')) {
      $query->where('nome', $request->nome);
    }
    return $query->paginate(3);
  }

  //Cria uma séria
  public function store(SeriesFormRequest $request)
  {
    return response()->json($this->seriesRepository->add($request), 201);
  }

  //busca uma unica serie - http://127.0.0.1:8000/api/series/20
  public function show(Series $series)
  {
    return $series;
  }

  //atualiza uma serie
  public function update(Series $series, SeriesFormRequest $request)
  {
    $series->fill($request->all());
    $series->save();
    return $series;
  }

  //apaga uma serie
  public function destroy(int $series)
  {
    Series::destroy($series);
    return response()->noContent();
  }


}
