<?php

require(__DIR__. "/../models/Movie.php");
require(__DIR__. "/../connection/connection.php");
require(__DIR__. "/../services/MovieService.php");
require(__DIR__ ."/../services/ResponseService.php");
require(__DIR__. "/BaseController.php");


class MovieController extends BaseController{

    public function __construct(){
        parent::__construct("movies");
        
    }
    
    public function HandleMovieGetAndPost(){
        $method = $_SERVER["REQUEST_METHOD"];
        if($method == 'GET'){
            $this->getMovie();
        }
        else if ($method == 'POST'){
            $this->createMovie();
        }
    }


    public function getMovie(){
        if(isset($_GET['id'])){
            $this->getMovieById();
        }
        else{
            $this->getAllMovies();
        }
    }

    public function createMovie(){
        $values = [];
        $colNames = $this->getColumnNames();
        foreach($colNames as $col){
            if(!in_array($col, array_keys($_POST)) && $col != "id"){
                echo ResponseService::error_message("not enough values to create object");
                return;
            }
        }
        if(isset($_POST["id"])){
            if(Movie::find($this->mysqli, (int)$_POST["id"]) != null){
                echo ResponseService::error_message("id already exists");
                return;
            }
            else{
                $values["id"] = (int) $_POST["id"];
            }
        }
        
        foreach($_POST as $key=>$value){
            if(in_array($key, $colNames) && $key != "id"){
                $values[$key] = $value;
            }
        }
        Movie::create($this->mysqli, $values);
        echo ResponseService::created($values);
    }
    public function deleteMovie(){
        if(isset($_POST['id'])){
            $this->deleteMovieById();
        }
        else{
            echo json_encode(["all"]);
            $this->deleteAllMovies();
        }
    }

    public function deleteMovieById(){
        $movie = Movie::find($this->mysqli, $_GET["id"]);
        if($movie == null){
            echo ResponseService::not_found($_GET["id"]);
            return;
        }
        Movie::delete($this->mysqli, $_GET["id"]);
        echo ResponseService::no_response();
    }

    public function deleteAllMovies(){
        Movie::deleteAll($this->mysqli);
        echo ResponseService::no_response();
        return;
    }

    public function updateMovie(){
        $colNames = $this->getColumnNames();
        //$colNames = $this->getColumnNames();
        if(!isset($_POST["id"])){
            echo ResponseService::not_found("id");
            return;
        }
        foreach($_POST as $key => $value){
            if(in_array($key,$colNames) && $key != "id"){
                $values[$key] = $value; 

            }
        }
        if(sizeof($values) == 0){
            echo ResponseService::error_message("no values to update");
        }
        Movie::update($this->mysqli,$values, $_POST["id"]);
        echo ResponseService::OK();
    }
    public function getMovieById(){
    
        $id = $_GET["id"];
        $movie = Movie::find($this->mysqli, $id);
        if($movie == null){
            echo ResponseService::not_found($id);
            return;
        }
        $movie = $movie->toArray();
        echo ResponseService::success_response($movie);
        return;
    }
    public function getAllMovies(){
        $movies = Movie::all($this->mysqli);
        $movies_array = MovieService::moviesToArray($movies);
        echo ResponseService::success_response($movies_array);
    }
}