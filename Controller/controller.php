<?php
	$cservice = $_POST['cservice'];
	@$age = $_POST['age'];
	@$situps = $_POST['situps'];
	@$pushups = $_POST['pushups'];
	@$runmin = $_POST['runmin'];
	@$runsec = $_POST['runsec'];
	
	require_once('../Functions/DBFunctions.php');
	require_once '../Models/Model.php';
	
	$person = new iptt();
	
	switch($cservice){
		case "LoadAgeSelect":
			$person->LoadAgeSelect();
			break;
		case "LoadSitupSelect":
			$person->LoadSitupSelect();
			break;
		case "LoadPushupSelect":		
			$person->LoadPushupSelect();
			break;
		case "LoadRunSelectMin":
			$person->LoadRunSelectMin();
			break;
		case "LoadRunSelectSec":
			$person->LoadRunSelectSec();
			break;
		case "CalcAgeGroup":
			echo $person->CalcAgeGroup($age);
			break;
		case "CalcSitupPoints":
			echo $person->CalcSitupPoints($age, $situps);
			break;
		case "CalcPushupPoints":
			echo $person->CalcPushupPoints($age, $pushups);
			break;
		case "CalcRunPoints":
			echo $person->CalcRunPoints($age, $runmin, $runsec);
			break;
		case "Calculate":
			echo $person->Calculate($age, $situps, $pushups, $runmin, $runsec);
			break;
		default:
			echo "oops default case";	
	}