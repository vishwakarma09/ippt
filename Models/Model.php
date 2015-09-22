<?php

class pushup extends ADODB_Active_Record{}
class situp extends ADODB_Active_Record{}
class run extends ADODB_Active_Record{}
class agegroup extends ADODB_Active_Record{}

class iptt{
	
	private $pushup;
	private $situp;
	private $run;
	private $agegroup;
	
	public function __construct(){
		$this->pushup = new pushup('pushup');
		$this->situp = new situp('situp');
		$this->run = new run('run');
		$this->agegroup = new agegroup('agegroup');
	}
	
	public function LoadAgeSelect(){
		echo '<select id="selAge" width="50%" class="form-control" onchange="Calculate()">';
		for($i=18; $i<=60; $i++){
			echo '<option value="'.$i.'" label="'.$i.'" years old">'.$i.' years old</option>';
		}
		echo '</select>';
	}
	
	public function LoadSitupSelect(){
		echo '<select id="selSitup" width="50%" class="form-control" onchange="Calculate()">';
		for($i=0; $i<=60; $i++){
			echo '<option value="'.$i.'" label="'.$i.'" reps">'.$i.' reps</option>';
		}
		echo '</select>';
	}
	
	public function LoadPushupSelect(){
		echo '<select id="selPush" width="50%" class="form-control" onchange="Calculate()">';
		for($i=0; $i<=60; $i++){
			echo '<option value="'.$i.'" label="'.$i.'" reps">'.$i.' reps</option>';
		}
		echo '</select>';
	}
	
	public function LoadRunSelectMin(){
		echo '<select id="selRunMin" class="form-control input-small" onchange="Calculate()">';
		for($i=8; $i<=18; $i++){
			echo '<option value="'.$i.'" label="'.$i.'" mins">'.$i.' mins</option>';
		}
		echo '</select>';
	}
	
	public function LoadRunSelectSec(){
		echo '<select id="selRunSec" width="25%" class="form-control" onchange="Calculate()">';
		for($i=0; $i<=50; $i=$i+10){
			echo '<option value="'.$i.'" label="'.$i.'" secs">'.$i.' secs</option>';
		}
		echo '</select>';
	}
	
	public function CalcAgeGroup($age){
		$result = ExecuteQuery("select * from agegroup where agegroup >= $age limit 1");
		if($result[0] && $result[1]->RecordCount() > 0 && $row = $result[1]->FetchRow()){
			return $row[0];
		}else{
			return 0;
		}
	}
	
	public function CalcSitupPoints($age, $situps){
		$agegroup = $this->CalcAgeGroup($age);
		$col = "a".$agegroup;
		$query = "select $col from situp where repetition=$situps";
		//echo $query;
		$result = ExecuteQuery($query);
		if($result[0] && $result[1]->RecordCount() > 0 && $row = $result[1]->FetchRow()){
			return $row[0];
		}else{
			return 0;
		}
	}
	
	public function CalcPushupPoints($age, $pushups){
		$agegroup = $this->CalcAgeGroup($age);
		$col = "a".$agegroup;
		$query = "select $col from pushup where repetition=$pushups";
		//echo $query;
		$result = ExecuteQuery($query);
		if($result[0] && $result[1]->RecordCount() > 0 && $row = $result[1]->FetchRow()){
			return $row[0];
		}else{
			return 0;
		}
	}
	
	public function CalcRunPoints($age, $runmin, $runsec){
		$agegroup = $this->CalcAgeGroup($age);
		$col = "a".$agegroup;
		$query = "select $col from run where repetition='".trim($runmin).":".trim($runsec)."'";
		$result = ExecuteQuery($query);
		if($result[0] && $result[1]->RecordCount() > 0 && $row = $result[1]->FetchRow()){
			return $row[0];
		}else{
			return 0;
		}
	}
	
	public function Calculate($age, $situps, $pushups, $runmin, $runsec){
		$agegroup = $this->CalcAgeGroup($age);
		$situppoints = $this->CalcSitupPoints($age, $situps);
		$pushuppoints = $this->CalcPushupPoints($age, $pushups);
		$runpoints = $this->CalcRunPoints($age, $runmin, $runsec);
		$totalpoints = $situppoints + $pushuppoints + $runpoints;
		if($totalpoints>=80) $totalresult ='<button class="btn btn-primary">GOLD</button>';
		elseif ($totalpoints>=70 && $totalpoints < 80 ) $totalresult = '<button class="btn btn-success">SILVER</button>';
		elseif ($totalpoints>=60 && $totalpoints < 70 ) $totalresult = '<button class="btn btn-info">PASS with incentive</button>';
		elseif ($totalpoints>=50 && $totalpoints < 60 ) $totalresult = '<button class="btn btn-warning">PASS</button>';
		else $totalresult = '<button class="btn btn-danger">FAIL</button>';
		
		return json_encode(array("result"=>array(
				"agegroup"=>$agegroup,
				"situppoints"=>$situppoints,
				"pushuppoints"=>$pushuppoints,
				"runpoints"=>$runpoints,
				"totalpoints"=>$totalpoints,
				"totalresult"=>$totalresult
		)));
	}
}