<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use App\Models\Character;

class ApiController extends Controller
{
    public function getCharacters(){
        
        $publicKey = "2203d7f7f420afeaaa0c0c4f3ccf5640";
        $privateKey = "6836c03be12e0510f732bedadbe28045aebe86aa";
        $timestamp= time();
        $hash = md5($timestamp . $privateKey . $publicKey);

        $client = new Client();
        $url = 'https://gateway.marvel.com/v1/public/characters';

        try {
            $response = $client->request('GET', $url, [
                'query' => [
                    'ts' => $timestamp,
                    'apikey' => $publicKey,
                    'hash' => $hash,
                    'limit' => 10,
                ]
            ]);
        
            if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
                $data = json_decode($response->getBody(), true);
                $characters = $data['data']['results'];
                $this->saveCharacters($characters);
                return response()->json($characters);
            } else {
                file_put_contents('error.log',$response->getStatusCode(). ': ' . $e->getMessage());
                return response()->json($this->index());
            }
        } catch (RequestException $e) {
            file_put_contents('errorCURL.log',$response->getStatusCode(). ': ' . $e->getMessage());
            return response()->json($this->index());
        }
    }

    public function saveCharacters($data)
    {
        $charactersToSave = [];

        foreach ($data as $character) {
            $charactersToSave[] = [
                'id' => $character['id'],
                'name' => $character['name'],
                'description' => $character['description'],
                'image' => $character['thumbnail']['path'].'.'.$character['thumbnail']['extension']
            ];
        }        
        Character::upsert($charactersToSave, ['id'], ['name', 'description']);
    }

    public function index()
    {
        return Character::limit(10)->get();
    }

    
}
