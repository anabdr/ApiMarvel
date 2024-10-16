<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;

class ApiController extends Controller
{
    public function getCharacters(){
        
        $publicKey= "2203d7f7f420afeaaa0c0c4f3ccf5640";
        $privateKey= "6836c03be12e0510f732bedadbe28045aebe86aa";
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
                return response()->json($characters);
            } else {
                file_put_contents('error.log',$response->getStatusCode(). ': ' . $e->getMessage());
                return response()->json(['message' => 'ha ocurrido un error'],500);
            }
        } catch (RequestException $e) {
            file_put_contents('errorCURL.log',$response->getStatusCode(). ': ' . $e->getMessage());
            return response()->json(['message' => 'ha ocurrido un error en el curl'],500);
        }
    }

    
}
