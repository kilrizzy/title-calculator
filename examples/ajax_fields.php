<?php
require('../src/kilrizzy/TitleCalculator/TitleCalculator.php');
use \kilrizzy\TitleCalculator\TitleCalculator as TitleCalculator;
$calculator = new TitleCalculator();
//STATE
if(isset($_POST['action']) && $_POST['action'] == 'state'){
	//Return list of states field
	$output = array();
	$output[] = '<div class="form-group">'; 
	$output[] = '<label for="state">State</label>';
    $output[] = '<select class="form-control" name="state">';
    $output[] = '<option value="" selected="selected">--</option>';
    foreach($calculator->states as $state){
    	$output[] = '<option value="'.$state->key.'">'.$state->name.'</option>';
    }
    $output[] = '</select>'; 
    $output[] = '</div>';
    $output = implode("\n",$output);
    echo $output;
}
//TYPE
if(isset($_POST['action']) && $_POST['action'] == 'type'){
	if(!empty($_POST['state'])){
		$calculator->values->state = $_POST['state'];
		$calculator->updateProperties();
		//Return list of types
		$output = array();
		$output[] = '<div class="form-group">'; 
		$output[] = '<label for="type">Type</label>';
	    $output[] = '<select class="form-control" name="type">';
	    $output[] = '<option value="" selected="selected">--</option>';
	    foreach($calculator->state->types as $type){
	    	$output[] = '<option value="'.$type->key.'">'.$type->name.'</option>';
	    }
	    $output[] = '</select>'; 
	    $output[] = '</div>';
	    $output = implode("\n",$output);
    	echo $output;
	}
}
//DETAILS
if(isset($_POST['action']) && $_POST['action'] == 'details'){
	if(!empty($_POST['state']) && !empty($_POST['type'])){
		$calculator->values->state = $_POST['state'];
		$calculator->values->type = $_POST['type'];
		$calculator->updateProperties();
		//print_r($calculator->type);
		$output = array();
		//purchase price
		if($calculator->values->type == 'purchase'){
			$output[] = '<div class="form-group">'; 
	        $output[] = '<label for="purchasePrice">Purchase Price</label>'; 
	        $output[] = '<div class="input-group">'; 
	        $output[] = '<span class="input-group-addon">$</span>'; 
	        $output[] = '<input type="text" name="purchasePrice" class="form-control" />'; 
	        $output[] = '</div>'; 
	        $output[] = '</div>'; 
    	}
        //loan amount
        $output[] = '<div class="form-group">'; 
        $output[] = '<label for="loanAmount">Loan Amount</label>'; 
        $output[] = '<div class="input-group">'; 
        $output[] = '<span class="input-group-addon">$</span>'; 
        $output[] = '<input type="text" name="loanAmount" class="form-control" />'; 
        $output[] = '</div>'; 
        $output[] = '</div>';
        //prior loan amount
        if($calculator->values->type == 'refinance'){
	        $output[] = '<div class="form-group">'; 
	        $output[] = '<label for="priorLoanAmount">Prior Loan Amount</label>'; 
	        $output[] = '<div class="input-group">'; 
	        $output[] = '<span class="input-group-addon">$</span>'; 
	        $output[] = '<input type="text" name="priorLoanAmount" class="form-control" />'; 
	        $output[] = '</div>'; 
	        $output[] = '</div>'; 
    	}
    	//endorsements
    	$output[] = '<div class="form-group">'; 
		$output[] = '<label for="endorsements">Endorsements</label>'; 
    	foreach($calculator->type->endorsements as $endorsement){
    		//see if editable
    		if($endorsement->editable){
    			$checkedhtml = '';
    			if($endorsement->default){
    				$checkedhtml = 'checked';
    			}
	    		$output[] = '<div class="checkbox">'; 
					$output[] = '<label>'; 
						$output[] = '<input type="checkbox" name="endorsements[]" value="'.$endorsement->name.'" '.$checkedhtml.'>'; 
					    $output[] = $endorsement->name; 
					$output[] = '</label>'; 
				$output[] = '</div>'; 
    		}
    	}
    	$output[] = '</div>';
    	//submit
    	$output[] = '<button type="submit" class="btn btn-primary">Calculate</button>';
		$output = implode("\n",$output);
    	echo $output;
	}
}
?>