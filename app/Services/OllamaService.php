<?php 
namespace App\Services;

use Illuminate\Support\Facades\Http;

class OllamaService
{
    public static function ask($prompt){
        $response = Http::timeout(120)->post('http://localhost:11434/api/generate',[
            'model'=>'tinyllama',
            'prompt'=>$prompt,
            'stream'=>false
        ]);
        $da = $response->json();
        return $da;
    }

}