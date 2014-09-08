<?php

Class ClassOrganizer {

    public $org;
    public $comments = Array('/*','//','*','*/');
    public $class;
    public $classComment;
    public $outName;
    public $fileConvention = 'organized';
    public $outFileFormat = "<?php \n %1\$s \n\n %2\$s } \n ?>";
    private $order = 'asc';
    private $groupScope;
    private $functionGap = 3;
    private $variableGap = 1;



    public function __construct($file=null){
        if($file!=null){
            $this->file($file)->organize()->write();
        }//if
    }//construct


    public function set_order($o){
        $this->order = $o;
        return $this;
    }//publicFirst


    public function set_group($g){
        $this->groupScope = $g; 
        return $this;
    }//group


    public function set_function_gap($n){
        $this->functionGap = $n;
        return $this;
    }//function_gap


    public function set_variable_gap($n){
        $this->variableGap = $n;
        return $this;
    }//function_gap


    public function file($file){
        $this->file = $file;
        $this->content = file_get_contents($file);
        $this->content = explode("\n",$this->content);
        $this->outName = null;
        $this->class = null;
        $this->classComment = null;
        $this->org = Array('var'=>Array(),'function'=>Array());
        return $this;
    }//file


    public function set_out_name($c){
        $this->outName = $c;
        return $this;
    }//convention


    public function set_convention($c){
        $this->fileConvention = $c;
        return $this;
    }//set_convetion


    public function write($outName=null){
        if($outName){
            $this->outName = $outName;
        }//if

        ksort($this->org['var']);
        ksort($this->org['function']);

        if($this->order=='desc'){
            $this->org['var'] = array_reverse($this->org['var']);
            $this->org['function'] = array_reverse($this->org['function']);
        }//if

        $file = '';
        $scopePieces = Array('public-var'=>'','private-var'=>'','public-function'=>'','private-function'=>'');
        $fA = false;
        foreach($this->org as $type=>$data){
            foreach($data as $k=>$v){
                $piece = '';

                if($type=='function' && !$fA){
                    $piece = "\n\n";
                    $fA = true;
                }//if

                if($v['comments']['start']!=0){
                    $piece .= $this->get_chunk($v['comments']['start'],$v['comments']['end']);
                }//if

                $piece .= $this->get_chunk($v['start'],$v['end']);

                if($type=='function'){ 
                    $piece .= str_repeat("\n",$this->functionGap);
                }//if
                else {
                    $piece .= str_repeat("\n",$this->variableGap);
                }//el

                if(!$this->groupScope){
                    $file .= $piece;
                }//if
                else if($type=='function'){
                    if($v['scope']=='public'){
                        $scopePieces['public-function'] .= $piece;
                    }//if
                    else {
                        $scopePieces['private-function'] .= $piece;
                    }//el
                }//if
                else {
                    if($v['scope']=='public'){
                        $scopePieces['public-var'] .= $piece;
                    }//if
                    else {
                        $scopePieces['private-var'] .= $piece;
                    }//el
                }//el
            }//foreach
        }//foreach

        if($this->groupScope){
            if($this->groupScope=='public-private'){
                $file = $scopePieces['public-var'].$scopePieces['private-var'].$scopePieces['public-function'].$scopePieces['private-function'];
            }//if
            else if($this->groupScope=='private-public'){
                $file = $scopePieces['private-var'].$scopePieces['public-var'].$scopePieces['private-function'].$scopePieces['public-function'];
            }//elif
        }//if

        if($this->classComment){
            $this->class = $this->get_chunk($this->classComment['start'],$this->classComment['end']).$this->class;
        }//if

        $file = sprintf($this->outFileFormat,$this->class,$file);

        if($this->outName){
            $newFile = $this->outName;
        }//if
        else {
            $newFile = explode('.',$this->file);
            $count = count($newFile);
            $newFile[$count] = $newFile[$count-1];
            $newFile[$count-1] = $this->fileConvention;
            
            $newFile = implode('.',$newFile);
        }//el
        
        file_put_contents($newFile,$file);

    }//write


    public function organize(){
        $k=0;
        while(isset($this->content[$k])){
        
            $v = $this->content[$k];
            $v = trim($v);
            $firstWord = explode(' ',$v)[0];
            $firstWord = strtolower($firstWord);
            $data = false;
        
            if($firstWord == 'class'){
                $this->classComment = $this->get_comments($k);
                $this->class = $v; 
                $k++;
                continue;
            }//if
        
            if($firstWord == 'public' || $firstWord=='private'){
        
                $comment = $this->get_comments($k);
        
                if(stripos($v,' function ')!==false){
                    $type = 'function';
                    $data = $this->get_functions($k);
                }//if
                else {
                    $type = 'var';
                    $data = $this->get_vars($k);
                }//el

                if($data){
                    $name = $data['name'];
                    unset($data['name']);
                    $data['comments'] = $comment;
                    $data['scope'] = $firstWord;
                    $this->org[$type][$name] = $data; 
                }//if

            }//if
        
            $k++;
        
        }//while

        return $this;

    }//organize


    public function get_chunk($start,$end){

        $chunk = '';

        if($start == $end){
            $chunk .= $this->content[$start]."\n";
        }//if
        else {
            while($start <= $end){
                $chunk .= $this->content[$start]."\n";
                $start++;
            }//while
        }//el

        return trim($chunk,"\n")."\n";

    }//get_chunk


    public function get_vars($k){
        $v = $this->content[$k];
        $v = explode('$',$v)[1];
        if(stripos($v,'=')!==false){
            $v = explode('=',$v)[0];
        }//if

        $startK = $k;
        $name = trim($v,';');

        while(stripos($v,';')===false){
            $k++;
            $v = $this->content[$k];
        }//while

        return Array('name'=>$name,'start'=>$startK,'end'=>$k);
   
    }//get_var
    

    public function get_functions($k){

        $v = $this->content[$k];

        $v = explode('(',$v)[0];
        $v = explode(' ',$v);
        $v = $v[count($v)-1];

        $startK = $k;
        $name = $v;

        $found = false;
        $openStack = Array();
        $closeStack = Array();

        $v = $this->content[$k];

        while(!$found){
            $hasOpen = stripos($v,'{');
            $hasClose = stripos($v,'}');

            if($hasOpen !== false){
                $openStack[] = true;
            }//if

            if($hasClose !== false){
                $closeStack[] = true;
            }//if

            $closeCount = count($closeStack);
            if($closeCount != 0 && count($openStack) == $closeCount){
                $found = true;
            }//if

            if(!$found){
                $k++;
                $v = $this->content[$k];
            }//if
            
        }//while

        return Array('name'=>$name,'start'=>$startK,'end'=>$k);

    }//get_function
    

    public function get_comments($k){
        $comment = Array('start'=>0,'end'=>0);
        $startK = $k-1;
        foreach($this->comments as $c){
            while(stripos($this->content[$startK],$c)!==false){
                $startK--;
            }//while
        }//foreach

        if($startK != $k-1){
            $comment['end'] = $k-1; 
            $comment['start'] = $startK; 
        }//if

        return $comment;
    
    }//get_comment


}//ClassOrganizer



if(stripos(__FILE__,$_SERVER['SCRIPT_FILENAME'])!==false){

    if(!isset($argv[1])){
        echo 'No File specified, exiting.'.PHP_EOL;
        exit;
    }//if

    $opts = Array('-c'=>'','-o'=>'','-fg','-vg','-g');
    $custom = false;
    $k = 2;
    while(isset($argv[$k])){
        foreach($opts as $optK => $opt){
            if($argv[$k] == $optK){
                $opts[$optK] = $argv[$k+1];
                $k = $k + 2;
                $custom = true;
                break;
            }//if
        }//foeach
    }//while


    if($custom){
        $CO = new ClassOrganizer();
        if($opts['-c']!=''){
            $CO->set_convention($opts['-c']);
        }//if
        if($opts['-o']!=''){
            $CO->set_out_name($opts['-o']);
        }//if
        if($opts['-fg']!=''){
            $CO->set_function_gap($opts['-fg']);
        }//if
        if($opts['-vg']!=''){
            $CO->set_variable_gap($opts['-vg']);
        }//if
        if($opts['-g']!=''){
            $CO->set_group($opts['-g']);
        }//if

        $CO->file($argv[1])->organize()->write();
    }//if
    else {
        new ClassOrganizer($argv[1]);
    }//el
}//if


?>
