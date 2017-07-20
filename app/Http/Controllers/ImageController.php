<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Intervention\Image\Response;
use Storage;

class ImageController extends Controller
{
    /**
     * Método para redimensionamento dinâmico de imagens
     *	@author <rafaelqm@gmail.com>
     * @param $request Request podendo vir em querystring as seguintes variáveis
     *	file String Caminho onde a imagem está, ex: img/profile/imagem.jpg * Obrigatório
     *	mode String Modo de recorte (fit / resize) * Opcional
     *	width Integer Largura em pixels * Opcional
     *	height Integer Altura em pixels * Opcional
     *  @return Response
     */
    public function index(Request $request)
    {
        $path_response = storage_path('app/'.$request->file);
        if( !is_file($path_response) ){
            $path_response = public_path('img/no-photo.jpg');
        }

        if($request->width || $request->height){

            $mode = ($request->mode?$request->mode: ($request->width && $request->height?'fit':'resize'));
            $filename = substr($request->file, strrpos($request->file, '/')+1);

            $path_small = storage_path('app/public/redimensionadas/'.$request->width.'-'.$request->height. $mode . $filename);
            if(!is_file($path_small)){
                if(!is_dir(storage_path('app/public/redimensionadas/'))){
                    mkdir(storage_path('app/public/redimensionadas/'));
                }
                $imagem = Image::make($path_response);
                if($mode=='fit'){
                    $imagem->fit($request->width, $request->height, function ($constraint) {
                        $constraint->upsize();
                    },$request->anchor?$request->anchor:'center')
                        ->save($path_small);
                }
                if($mode=='resize'){
                    $imagem->resize($request->width, $request->height, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    })
                        ->save($path_small);
                }
                $imagem->destroy();
            }
            $path_response = $path_small;
        }
        return Image::make($path_response)->response();
    }
}
