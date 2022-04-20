<?php
    function helloWorld(){
        dd("hola mundo");
    }
    function utf8_encode_deep(&$input) {
        if (is_string($input)) {
            $input = utf8_encode($input);
        } else if (is_array($input)) {
            foreach ($input as &$value) {
                utf8_encode_deep($value);
            }

            unset($value);
        } else if (is_object($input)) {
            $vars = array_keys(get_object_vars($input));

            foreach ($vars as $var) {
                utf8_encode_deep($input->$var);
            }
        }
    }
    function csvToArray($filename = '', $delimiter = ';')
    {
        if (!file_exists($filename) || !is_readable($filename))
            return false;

        $header = null;
        $data = [];
        $head=[];
        $band=0;
        if (($handle = fopen($filename, 'r')) !== false)
        {
            $cont=0;
            while (($row = fgetcsv($handle, 100000, $delimiter)) !== false)
            {
                //dd($row);
                if (!$header)
                    $header = $row;
                else
                    $data[] = array_combine($header, $row);
                    //dd($header);
                }
            fclose($handle);
        }
        //dd($data);
        return $data;
    }
