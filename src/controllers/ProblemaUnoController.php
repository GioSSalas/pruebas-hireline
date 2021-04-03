<?php
namespace Src\Controllers;

use Src\Classes\Uploader;

class ProblemaUnoController{
    
    protected static array $wordLengths;
    protected static array $wordList;
    protected static String $wordHash;
    protected static Int $minWordLen=2;
    protected static Int $maxWordLen=50;
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
        $save_directory = 'prueba_uno/';
        
        $temp_name = $file['tmp_name'];
        $file_content = explode(PHP_EOL ,file_get_contents($temp_name));
        
        if(count($file_content)<3){
            self::printMessage('El contenido del archivo no es válido',400);
            return;
        }
        
        self::$wordLengths = explode(' ',$file_content[0]);
        // if(count(self::$wordLengths)!==3){
        //     self::printMessage('El contenido del archivo no es válido',400);
        // }
        foreach(self::$wordLengths as $w => $value){
            self::$wordLengths[$w] = intval($value);
        }

        /* Llenado de array con palabras a buscar */
        self::$wordList = array_slice($file_content, 1, count($file_content)-2);

        /* Asignación del hash */
        self::$wordHash = trim(end($file_content));

        if(!preg_match('/[a-zA-Z0-9]/',self::$wordHash)){
            self::printMessage("Hash no valido ".self::$wordHash,400);
            return;
        }

        /* Valida que cada palabra tenga una longitud asignada en el archivo */
        if(!(count(self::$wordLengths)-1)===count(self::$wordList)){
            self::printMessage("Las palabras no coinciden con el número de longitudes",400);
            return;
        }
        
        /* Valida que las longitudes esté dentro de los rangos permitidos */
        /* Valida que la longitud ingresada coincida con la longitud del string correspondiente */
        $isLengthsValid=true;
        $isEachLengthValid=true;
        for($i=0;$i<(count(self::$wordLengths)-1);$i++){
            if(!self::checkNumberLimits(self::$wordLengths[$i], 2, 50))
                $isLengthsValid=false;
            if(self::$wordLengths[$i]!==strlen(self::$wordList[$i]))
                $isEachLengthValid=false;
        }
        if(!$isLengthsValid){
            self::printMessage("Longitud fuera de rango",400);
            return;
        }

        if(!$isEachLengthValid){
            self::printMessage("Longitud no coincide con palabra",400);
            return;
        }

        if(!self::checkNumberLimits(self::$wordLengths[count(self::$wordLengths)-1], 3, 5000)){
            self::printMessage("Longitud de hash fuera de rango",400);
            return;
        }
        if(self::$wordLengths[count(self::$wordLengths)-1]!==strlen(self::$wordHash)){
            self::printMessage("Longitud de hash no coincide con longitud definida",400);
            return;
        }

        $decriptedHash=self::decrypt(self::$wordHash);
        
        $result=[];
        foreach(self::$wordList as $word){
            $result[]=(strpos($decriptedHash,$word))?'SI':'NO';
        }        

        $uploader=new Uploader($save_directory,$allowed_extensions,$file);
        if($uploader->check() && $uploader->checkDir()){
            $uploader->save();
        }

        echo json_encode([
            'result'=>$result,
            'content'=>$file_content,
        ]);
    }

    private static function checkNumberLimits(Int $value, int $min, int $max)
    {
        if($value>=$min && $value<=$max){
            return true;
        }
        return false;
    }

    private static function printMessage($message,Int $code=200)
    {
        http_response_code($code);
        echo json_encode(['message'=>$message]);
    }

    private static function decrypt($word){
        $aword=str_split($word);
        $lastLetter='';
        $newWord='';
        foreach($aword as $aw){
            if($aw!==$lastLetter){
                $newWord.=$aw;
                $lastLetter=$aw;
            }
        }
        return ($newWord);
    }

}