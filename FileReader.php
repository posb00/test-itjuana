<?php

class FileReader{

    private $fileName;
    protected $data =  array();

    function __construct(string $fileName)
    {
        $this->fileName = $fileName;
    }

    public function readFile()
    {
        $handle = fopen(__DIR__ .'/'. $this->fileName, "r");

        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                $data[] = $line;
            }
    
        fclose($handle);
        }

        return $data;

    }
}

?>