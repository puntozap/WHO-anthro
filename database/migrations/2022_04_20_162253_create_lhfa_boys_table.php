<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Helpers;
use Illuminate\Support\Facades\DB;

class CreateLhfaBoysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //$this->helloWorld();
        Schema::create('lhfa_boys', function (Blueprint $table) {
            $table->id();
            $table->integer("day")->nullable();
            $table->double("SD4neg")->nullable();
            $table->double("SD3neg")->nullable();
            $table->double("SD2neg")->nullable();
            $table->double("SD1neg")->nullable();
            $table->double("SD0")->nullable();
            $table->double("SD1")->nullable();
            $table->double("SD2")->nullable();
            $table->double("SD3")->nullable();
            $table->double("SD4")->nullable();
            $table->timestamps();
        });
        //dd(public_path());
        $file=public_path()."/z-score/Length-height-for-age/lhfa-boys-zscore-expanded-tables.csv";
        //dd($file);
        $customerArr = $this->csvToArray($file);
        //dd($this->addPointer($customerArr[0]["SD4neg"]));
        //dd($customerArr);
        foreach($customerArr as $custom){
            DB::table('lhfa_boys')->insert([
                'day' => $custom["Day"],
                'SD4neg' => $this->addPointer($custom["SD4neg"]),
                'SD3neg' => $this->addPointer($custom["SD3neg"]),
                'SD2neg' => $this->addPointer($custom["SD2neg"]),
                'SD1neg' => $this->addPointer($custom["SD1neg"]),
                'SD0' => $this->addPointer($custom["SD0"]),
                'SD1' => $this->addPointer($custom["SD1"]),
                'SD2' => $this->addPointer($custom["SD2"]),
                'SD3' => $this->addPointer($custom["SD3"]),
                'SD4' => $this->addPointer($custom["SD4"]),
            ]);
        }
        print("lhfa-boys-zscore-expanded-tables.csv uploaded");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function addPointer($data){
        $aux=explode(",",$data);
        if(count($aux)>1){
            $aux=$aux[0].".".$aux[1];
            $data=$aux;
        }else{
            $aux=$aux[0];
            $data=$aux;
        }
        return $data;
    }
    public function down()
    {
        Schema::dropIfExists('lhfa_boys');
    }
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
        //dd(file_exists($filename),"hola",$filename);
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
}
