<?php

require(__DIR__. "/../models/Movie.php");
require(__DIR__. "/../connection/connection.php");
require(__DIR__. "/../services/MovieService.php");
require(__DIR__ ."/../services/ResponseService.php");

class MovieController{
    
    public function HandleMovieRequest(){
        $method = $_SERVER["REQUEST_METHOD"];
        if($method == 'GET'){
            $this->getMovie();
        }
        else if ($method == 'POST'){
            $this->getAllMovies();
        }
        else if ($method == 'DELETE'){
            $this->deleteMovie();
        }
        else if ($method == 'PUT'){
            $this->updateMovie();
        }
    }
    public function getMovie(){
        if(isset($_GET["id"])){
            $this->getMovieById();
        }
        else{
            $this->getAllMovies();
        }
    }

    public function createMovie(){
        
    }
    public function deleteMovie(){}
    public function updateMovie(){}
    public function getMovieById(){}
    public function getAllMovies(){
        global $mysqli;
        $movies = Movie::all($mysqli);
        $movies_array = MovieService::moviesToArray($movies);
        echo ResponseService::success_response($movies_array);
    }
}