<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MovieController extends Controller
{   
    private $key_tmdb;
    private $endpoint_tmdb;
    private $lang_tmdb;
    public $genero_list;
    public $filmes_list;

    public function __construct(){
        $this->key_tmdb = '4ec327e462149c3710d63be84b81cf4f';
        $this->endpoint_tmdb = 'https://api.themoviedb.org/3/';
        $this->lang_tmdb = 'pt-BR';

        $endpoint = $this->endpoint_tmdb;
        $endpoint .= 'genre/movie/list';
        $endpoint .= '?api_key='.$this->key_tmdb;
        $endpoint .= '&language='.$this->lang_tmdb;
        $r = Http::get($endpoint);
        $genero = collect($r['genres']);
        $this->genero_list = array();
        $genero->map(function ($item, $key) { 
           $this->genero_list[$item['id']] = $item['name']; 
        });
    }

    public function trending(){
        $endpoint = $this->endpoint_tmdb;
        $endpoint .= 'trending/movie/day';
        $endpoint .= '?api_key='.$this->key_tmdb;
        $endpoint .= '&language='.$this->lang_tmdb;
        
        $r = Http::get($endpoint);
        $collection = collect($r->json());
        $results = $collection['results'];
        $sorted = collect($results)->sortBy('title')->values();

        $this->filmes_list = array();
        foreach($sorted as $key => $value){
            array_push($this->filmes_list, $value);
            foreach($sorted[$key]['genre_ids'] as $key2 => $value2){
                $this->filmes_list[$key]['genre_ids'][$key2] = $this->genero_list[$value2];
            }
            sort($this->filmes_list[$key]['genre_ids']);
        }
        return response()->json($this->filmes_list);
    }

    public function movieID($id){
        $endpoint = $this->endpoint_tmdb;
        $endpoint .= 'movie/'.$id;
        $endpoint .= '?api_key='.$this->key_tmdb;
        $endpoint .= '&language='.$this->lang_tmdb;
        
        $r = Http::get($endpoint);
        return response()->json($r->json());
    }

    public function genre(){
        $endpoint = $this->endpoint_tmdb;
        $endpoint .= 'genre/movie/list';
        $endpoint .= '?api_key='.$this->key_tmdb;
        $endpoint .= '&language='.$this->lang_tmdb;
        
        $r = Http::get($endpoint);
        return response()->json($r->json());
    }
    
    public function movies_genre($id){
        $endpoint = $this->endpoint_tmdb;
        $endpoint .= 'discover/movie';
        $endpoint .= '?api_key='.$this->key_tmdb;
        $endpoint .= '&language='.$this->lang_tmdb;
        $endpoint .= '&sort_by=title.asc';
        $endpoint .= '&with_genres='.$id;
        
        $r = Http::get($endpoint);
        $collection = collect($r->json());
        $results = collect($collection['results']);

        $this->filmes_list = array();
        foreach($results as $key => $value){
            array_push($this->filmes_list, $value);
            foreach($results[$key]['genre_ids'] as $key2 => $value2){
                $this->filmes_list[$key]['genre_ids'][$key2] = $this->genero_list[$value2];
            }
            sort($this->filmes_list[$key]['genre_ids']);
        }
        return response()->json($this->filmes_list);
    }
}
