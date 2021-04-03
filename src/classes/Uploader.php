<?php 
namespace Src\Classes;

class Uploader{

    protected String $dir;
    protected array $validator;
    protected String $ext;
    protected $file;
    protected $path;
    
    function __construct(String $dir, array $validator, $file)
    {
        $this->dir = __DIR__.'/../../assets/files/uploads/'.$dir;
        $this->file = $file;
        $this->validator = $validator;
        if($file){
            $this->path = pathinfo($file['name']);
            $this->ext = $this->path['extension'];
        }
    }

    public function check()
    {
        if( !$this->file || !$this->ext || !in_array($this->ext,$this->validator) ){
            return false;
        }
        return true;
    }

    public function checkDir()
    {
        return is_dir($this->dir);
    }

    public function save()
    {
        $temp_name=$this->file['tmp_name'];
        $new_filename = uniqid('file_');
        $path_filename_ext = $this->dir.$new_filename.".".$this->ext;
        
        move_uploaded_file($temp_name,$path_filename_ext);
    }
}