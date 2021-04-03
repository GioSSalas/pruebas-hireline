<?php
namespace Src\Controllers;

use Src\Classes\Uploader;

class ProblemaDosController{

    protected static Int $rounds;
    protected static array $points;
    /* @param $file 
        Archivo enviado en el formulario
    */
    public static function verifyFile($file)
    {
        header('Content-type: application/json');

        /* Se valida que se haya subido un archivo */
        if(!isset($file)){
            self::printMessage('No se subió ningún archivo',400);
            return;
        }

        /* Extensiones de archivo válidas */
        $allowed_extensions = ['txt'];

        /* Ruta para almacenar archivo */
        $save_directory = 'prueba_dos/';
        
        $temp_name = $file['tmp_name'];
        $file_content = explode(PHP_EOL ,file_get_contents($temp_name));
        
        if(count($file_content)<2){
            self::printMessage('El contenido del archivo no es válido',400);
            return;
        }
        self::$rounds = intval($file_content[0]);
        self::$points = array_slice($file_content,1,count($file_content)-1);
        if(self::$rounds!==count(self::$points)){
            self::printMessage('El número de rondas no coincide con el especificado',400);
            return;
        }
        $points=[];
        $isValid=true;
        foreach(self::$points as $p){
            $aux = explode(' ',$p);
            if(count($aux)===2){
                $points[]=[intval($aux[0]),intval($aux[1])];
            }
            else{
                $isValid=false;
            }
        }
        if(!$isValid){
            self::printMessage('Algún par de datos no es valido',400);
            return;
        }
        $winner=0;
        $lastTopPoints=0;
        foreach($points as $p){
            $diff=$p[0]-$p[1];
            $w=($diff>=0)? 1 : 2;
            $absDiff=abs($diff);
            if($absDiff>$lastTopPoints){
                $winner=$w;
                $lastTopPoints=$absDiff;
            }
        }

        $uploader=new Uploader($save_directory,$allowed_extensions,$file);
        if($uploader->check() && $uploader->checkDir()){
            $uploader->save();
        }

        echo json_encode([
            'result'=>[
                'winner'=>$winner,
                'points'=>$lastTopPoints
            ],
            'content'=>$file_content,
        ]);
    }

    private static function printMessage($message,Int $code=200)
    {
        http_response_code($code);
        echo json_encode(['message'=>$message]);
    }

}