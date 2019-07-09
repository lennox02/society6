<?php

namespace App\Http\Controllers;

use App\Creatives as Creatives;
use App\Repositories\CreativesRepository;
use Illuminate\Http\Request;

class CreativesController extends Controller
{

    private $creativesRepository;

    function __construct(CreativesRepository $creativesRepository) {
        $this->creativesRepository = $creativesRepository;
    }

    /*
        EXAMPLE INPUT
        {
            "creatives":[
                {
                    "id":1234
                },
                {
                    "id":1235
                }
            ]
        }
    */
    public function getCreatives( Request $request ){
        $creatives = $request->input('creatives');
        $list = [];
        foreach($creatives as $creative){
            $c = $this->creativesRepository->getById($creative->input('id'));
            $list[] = $c;
        }

        return json_encode($list);
    }

    /*
        EXAMPLE INPUT
        {
            "creatives":[
                {
                    "artist_id":1234,
                    "name":"FooArt",
                    "url":"example.com/1234",
                    "description":"This art about Foo"
                },
                {
                    "artist_id":1234,
                    "name":"BarArt",
                    "url":"example.com/1234",
                    "description":"This art about Bar"
                }
            ]
        }
    */
    public function createCreatives( Request $request ){
        $inputs = $request->input('creatives');
        $created = [];
        foreach($inputs as $input){
            if(
                array_key_exists('artist_id', $input) &&
                array_key_exists('url', $input)
            ){
                $creative = new Creatives;
                $created[] = $this->setCreativesValues($creative, $input);
            }
        }

        return json_encode($created);
    }

    /*
        EXAMPLE INPUT
        {
            "creatives":[
                {
                    "id":1,
                    "name":"FooArtNeue",
                    "url":"example.com/1236",
                    "description":"This art about Foo, but new"
                },
                {
                    "id":1235,
                    "name":"BarArtNeue",
                    "url":"example.com/1237",
                    "description":"This art about Bar, but new"
                }
            ]
        }
    */
    public function updateCreatives( Request $request ){
        $inputs = $request->input('creatives');
        $updated = [];
        foreach($inputs as $input){
            $creative = $this->creativesRepository->getById($input['id']);
            $updated[] = $this->setCreativesValues($creative, $input);
        }

        return json_encode($updated);
    }

    //dynamically check if input is a property of Creatives and set it
    public function setCreativesValues(Creatives $creative, array $input){
        $allowedKeys = ['artist_id', 'name', 'url', 'description'];
        foreach($input as $key => $value){
            if(in_array($key, $allowedKeys)){
                $creative->{$key} = $value;
            }
        }
        $creative->save();
        return $creative->id;
    }

}
