<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;


class Series extends Model
{
    use HasFactory;
    protected $fillable = ['nome', 'cover'];
    protected $appends = ['links'];

    public function seasons()
    {
        return $this->hasMany(Season::class, 'series_id');
    }

    //aoc lamar pega todos os episodios de todas as temporadas 
    public function episodes() {
        return $this->hasManyThrough(Episode::class, Season::class);
    }

    //Escopo global ordenação
    protected static function booted()
    {
        self::addGlobalScope('ordered', function (Builder $queryBuilder) {
            $queryBuilder->orderBy('nome');
        });
    }

    //Colocar links dentro de data para episodios
    public function links():Attribute{
        return new Attribute(
            get: fn () => [
                [
                    'rel' => 'self',
                    'url' => "/api/series/{$this->id}"
                ],
                [
                    'rel' => 'seasons',
                    'url' => "/api/series/{$this->id}/seasons"
                ],
                [
                    'rel' => 'episodes',
                    'url' => "/api/series/{$this->id}/episodes"
                ],
            ]
        );
    }


}
