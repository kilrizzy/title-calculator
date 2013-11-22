<?php
namespace kilrizzy\TitleCalculator;

class TitleCalculator{

	public $debug;
	public $states;
	public $values;
   
    public function __construct(){
    	//Import settings
    	$this->debug = false;
    	include('settings.php');
    	$this->states = $settings->states;
    	//Setup default values
    	$this->state = false;
    	$this->type = false;
    	$this->rates = false;
    	$this->endorsements = false;
        $this->values = new \StdClass();
        $this->values->totalCost = 0;
        $this->values->state = 'NJ';
        $this->values->type = 'purchase';
        $this->values->purchasePrice = 0;
        $this->values->loanAmount = 0;
        $this->values->priorLoanAmount = 0;
        $this->values->rateTotal = 0;
        $this->values->endorsementsTotal = 0;
        $this->values->endorsementsSet = array();
        $this->updateProperties();
    }
    public function updateProperties(){
    	//Update state / type
    	$this->state = $this->states[$this->values->state];
    	$this->type = $this->state->types[$this->values->type];
    	$this->rates = $this->type->rates;
    	$this->endorsements = $this->type->endorsements;
    }
    public function calculate(){
    	//update properties
    	$this->updateProperties();
    	//calculate rates
    	if($this->values->type == "purchase"){
			if($this->values->purchasePrice >= $this->values->loanAmount){
				$money = $this->values->purchasePrice;
			}else{
				$money = $this->values->loanAmount;
			}
		}else{
			if($this->values->priorLoanAmount >= $this->values->loanAmount && $this->values->state != "PA"){
				$money = $this->values->priorLoanAmount;
			}else{
				$money = $this->values->loanAmount;
			}
		}
		$this->values->rateTotal = $this->getRateCost($money);
		//$this->values->rateTotal2 = $this->getRateCost($this->values->loanAmount);
    	//calculate endorsements
    	$this->getEndorsements();
    	//calculate total
    	$this->values->totalCost = $this->values->rateTotal+$this->values->endorsementsTotal;
    }
    public function setEndorsement($setName,$setValue=true){
    	//update properties
    	$this->updateProperties();
    	//
    	$setEndorsement = new \StdClass();
    	$setEndorsement->name = $setName;
    	$setEndorsement->value = $setValue;
    	//See if able to be set
    	$checkEndorsement = $this->getEndorsementByName($setName);
    	if($checkEndorsement){
    		//see if allowed to edit
    		if($checkEndorsement->editable){
    			//check if endorsement exists
    			$endorsementSetFound = false;
	    		foreach($this->values->endorsementsSet as $endorsementSetKey => $endorsementSet){
	    			if($endorsementSet->name == $setName){
	    				$endorsementSetFound = $endorsementSetKey;
	    			}
	    		}
    			if($endorsementSetFound){
    				//update the existing item
    				$this->values->endorsementsSet[$endorsementSetKey] = $setEndorsement;
    			}else{
    				$this->values->endorsementsSet[] = $setEndorsement;
    			}
    		}
    	}
    }
    public function getEndorsementByName($name){
    	$endorsementFound = false;
    	foreach($this->endorsements as $endorsement){
    		if($endorsement->name == $name){
    			$endorsementFound = $endorsement;
    		}
    	}
    	return $endorsementFound;
    }
    public function getEndorsementSetByName($name){
    	$endorsementFound = false;
    	foreach($this->values->endorsementsSet as $endorsement){
    		if($endorsement->name == $name){
    			$endorsementFound = $endorsement;
    		}
    	}
    	return $endorsementFound;
    }
    public function getEndorsements(){
    	$endorsementsTotal = 0;
    	$endorsementItems = array();
    	foreach($this->endorsements as $endorsement){
    		$endorsementAdd = $endorsement->default;
    		//see if this should be added to list
    		if($endorsement->editable){
    			//see if updated
    			$checkEndorsementSet = $this->getEndorsementSetByName($endorsement->name);
    			if($checkEndorsementSet){
    				$endorsementAdd = $checkEndorsementSet->value;
    			}
    		}
    		if($endorsementAdd){
    			$endorsementsTotal += $endorsement->cost;
    			$endorsementItems[] = $endorsement;
    		}
    	}
    	$this->values->endorsementsTotal = $endorsementsTotal;
    	$this->values->endorsementItems = $endorsementItems;
    }
    public function getRateCost($money){
    	$rateTotal = 0;
    	//NJ REFI NOT CORRECT BUT OTHERS WORK???
    	//$remaining = $money;
    	//loop through rates to determine cost
    	foreach($this->rates as $rate){
			if($rate->max < $money){
				$this->debug("START - $".$rateTotal." | Rate: ".$rate->min." - ".$rate->max." / PER: ".$rate->per);
				//$remaining = $remaining - $rate->max;
				if($rate->per > 0){
					$multiplier = ($rate->max-$rate->min)/$rate->per;
					$rateTotal += $multiplier * $rate->cost;
				}else{
					$rateTotal += $rate->cost;
				}
				$this->debug("END - $".$rateTotal." | Rate: ".$rate->min." - ".$rate->max." / PER: ".$rate->per);
			}else if($rate->min < $money && $money > 0){
				$this->debug("FinalSTART - $".$rateTotal." | Rate: ".$rate->min." - ".$rate->max." / PER: ".$rate->per);
				if($rate->per > 0){
					$multiplier = ($money-$rate->min)/$rate->per;
					$rateTotal += $multiplier * $rate->cost;
				}else{
					$rateTotal += $rate->cost;
				}
				$this->debug("FinalSTART - $".$rateTotal." | Rate: ".$rate->min." - ".$rate->max." / PER: ".$rate->per);
			}
		}
    	//print_r($rates);
    	return $rateTotal;
    }
    public function debug($message){
    	if($this->debug){
    		echo "<br/>".$message."<br/>";
    	}
    }
}
//
class PropertyState {
    
    public $name;
    public $key;
    public $types = array();

    function __construct($key,$name) {
    	$this->name = $name;
    	$this->key = $key;
    }

} 
//
class PropertyType {
    
    public $name;
    public $key;
    public $rates = array();
    public $endorsements = array();

    function __construct($key,$name) {
    	$this->name = $name;
    	$this->key = $key;
    }

} 
//
class TypeRate {
    
    public $min;
    public $max;
    public $cost;
    public $per;
    
    function __construct($min,$max,$cost,$per) {
    	$this->min = $min;
    	$this->max = $max;
    	$this->cost = $cost;
    	$this->per = $per;
    }

} 
//
class TypeEndorsement{
    
    public $name;
    public $cost;
    public $default;
    public $editable;
    
    function __construct($name,$cost=0,$default=true,$editable=true) {
    	$this->name = $name;
    	$this->cost = $cost;
    	$this->default = $default;
    	$this->editable = $editable;
    }

} 