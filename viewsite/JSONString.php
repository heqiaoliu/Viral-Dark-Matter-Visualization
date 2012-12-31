<?php
class JSONAssArray{
	public $values=array();
	public $level=-1;
	public	$type=-1;
	private $counter=0;
	public function JSONString($level,$type){
		$this->level=$level;
		$this->type=$type;
	}

	public function add($value,$typeSet){
		$key=$value[$this->level];
		if(!array_key_exists($key,$this->values)){
			if($this->type==3)
				$this->values[$key]=new JSONString($this->level+1,$typeSet[$this->level+1]);
			if($this->type==2)
				$this->values[$key]=array();
		}
		$this->insertToExist($value,$typeSet);
	}

	private function insertToExist($value,$type){
		$key=$value[$this->level];
		if($this->type==1)
			$this->values[$key]=$value[$this->level+1];
		if($this->type==2)
			$this->values[$key]=array_slice($value,$this->level+1);
		if($this->type==3)
			$this->values[$key]->add($value,$type);
	}
	
	public function toJsonString(){
		if($this->type!=3)
			return json_encode($this->values);
		else{
			$string="{";
			$keys=array_keys($this->values);
			for($i=0;$i<sizeof($keys);$i++){
				if($i!=0)
					$string.=",";
				$string.="\"".$keys[$i]."\":".$this->values[$keys[$i]]->toJsonString();
			}
			return $string."}";
				
		}
	}
}
?>
