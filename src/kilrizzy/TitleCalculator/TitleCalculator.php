<?php
namespace kilrizzy\TitleCalculator;
/**
 * Helper functions for calculating Title Insurance
 *
 * 2013 Jeff Kilroy - VisionLine Media
 */ 
class TitleCalculator{

	public $debug;
	public $states;
	public $values;
	public $company;
   
    public function __construct(){
    	//Import settings
    	$this->debug = false;
    	include('settings.php');
    	$this->company = $settings->company;
    	$this->states = $settings->states;
    	//Setup default values
    	$this->state = false;
    	$this->type = false;
    	$this->rates = false;
    	$this->endorsements = false;
        $this->values = new \StdClass();
        $this->values->totalCost = 0;
        $this->values->totalClosingCost = 0;
        $this->values->state = 'NJ';
        $this->values->type = 'purchase';
        $this->values->purchasePrice = 0;
        $this->values->loanAmount = 0;
        $this->values->priorLoanAmount = 0;
        $this->values->rateTotal = 0;
        $this->values->mansionTax = 0;
        $this->values->notaryFee = 0;
        $this->values->settlementFee = 0;
        $this->values->underwriter = 0;
        $this->values->agent = 0;
        $this->values->lender = 0;
        $this->values->owner = 0;
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
    	//Update gov-record / deed / mort
    	$this->values->deed = $this->type->deed;
    	$this->values->mortgage = $this->type->mortgage;
    	//NJ Has differend deed/mort on 0 value
    	if($this->values->loanAmount == 0 && $this->values->state == 'NJ'){
			$this->values->deed = 0;
			$this->values->mortgage = 80;
		}
		$this->values->governmentRecording = $this->values->deed+$this->values->mortgage;
    }
    public function calculate(){
    	$this->debug("Start Calculation for ".$this->values->state." ".$this->values->type);
    	//update properties
    	$this->updateProperties();
    	$this->updateMansionTax();
    	//calculate rates
    	if($this->values->type == "purchase"){
			if($this->values->purchasePrice >= $this->values->loanAmount){
				$money = $this->values->purchasePrice;
			}else{
				$money = $this->values->loanAmount;
			}
		}else{
			$this->values->purchasePrice = 0;
			if($this->values->priorLoanAmount >= $this->values->loanAmount && $this->values->state != "PA"){
				$money = $this->values->priorLoanAmount;
			}else{
				$money = $this->values->loanAmount;
			}
		}
		$this->values->rateTotal = $this->getRateCost($money);
		//only the rates always using loan amount (used in portions function)
		$this->values->rateTotalMortgage = $this->getRateCost($this->values->loanAmount);
    	//calculate endorsements
    	$this->getEndorsements();
    	//Some field values need to pull endorsements
    	$this->values->notaryFee = $this->getEndorsementByName('Notary Fee')->cost;
    	$this->values->settlementFee = $this->getEndorsementByName('Settlement Fee')->cost;
    	$this->values->closingServices = $this->getEndorsementByName('Closing Services Letter')->cost;
    	//portioned items
    	$this->calculatePortions();
    	//calculate total
    	$this->values->totalCost = $this->values->rateTotal+$this->values->endorsementsTotal;
    	$this->debug("Calculate Total Value: Rate (".$this->values->rateTotal.") + Endorsements (".$this->values->endorsementsTotal.") = ".$this->values->totalCost);
    	$this->values->totalClosingCost = $this->values->totalCost+$this->values->governmentRecording;
    }
    public function calculatePortions(){
    	//OWNER / LENDER
    	if($this->values->type == "purchase"){
    		//Get the insurance value against the morgage amount
    		$this->values->lender = $this->values->rateTotalMortgage;
    		if($this->values->lender < 200 && $this->values->state == "NJ" ){
    			//in NJ must be at least 200
    			$this->values->lender = 200;
    		}
    		$this->values->owner = ($this->values->rateTotal-$this->values->lender)+$this->values->notaryFee;
    	}else{
    		$this->values->lender = $this->values->rateTotalMortgage;
    		if($this->values->lender < 200 && $this->values->state == "NJ" ){
    			//in NJ must be at least 200
    			$this->values->lender = 200;
    		}
    		$this->values->lender += 150; //Where does this come from?
    	}
    	if($this->values->loanAmount == 0){
			$this->values->lender = 0;
			$this->values->owner = $this->values->rateTotal;
		}
    	//AGENT / UNDERWRITER
    	if($this->values->loanAmount == 0){
    		$this->values->underwriter = $this->values->rateTotal*.15;
    		$this->values->agent = $this->values->rateTotal*.85;
    	}else{
    		$this->values->underwriter = ($this->values->rateTotal*.15) + $this->values->closingServices;
    		$agentEndorseTotal = 0;
    		//only add selectable set endorsements
    		foreach($this->values->endorsementItems as $endorsementItem){
    			if($endorsementItem->editable){
    				//$this->debug(print_r());
    				$agentEndorseTotal += $endorsementItem->cost;
    			}
    		}
    		$this->values->agent = ($this->values->rateTotal*.85) + (100) +$agentEndorseTotal;//!!what's that 100 all about?
    	}
    }
    public function updateMansionTax(){
		if($this->values->purchasePrice > 1000000){
			$this->values->mansionTax = $this->values->purchasePrice*.01;
		}
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
    /**
	 * Add Endorsements to calculation, ignored endorsements will be excluded
	 *
	 * @param  array    $endorsements  Array of endorsement names to include in the calculation
	 * @return NULL
	*/ 
    public function useEndorsements($endorsements){
    	//loop all optional endorsements
    	foreach($this->endorsements as $endorsement){
    		if($endorsement->editable){
    			//If not checked in the array, remove it
    			if(!in_array($endorsement->name,$endorsements)){
    				$this->setEndorsement($endorsement->name,false);
    			}else{
    				$this->setEndorsement($endorsement->name,true);
    			}
    		}
    	}
    }
    /**
	 * Base calculation for determining insurance cost
	 *
	 * @param  integer    $money  Loan amount to used to calculate rate against
	 * @return integer
	*/ 
    public function getRateCost($money){
    	$this->debug("Determine Rates using $".$money);
    	$rateTotal = 0;
    	//ERROR - NJ REFI NOT CORRECT BUT OTHERS WORK???
    	//loop through rates to determine cost
    	foreach($this->rates as $rate){
			if($rate->max < $money){
				$this->debug("START - $".$rateTotal." | Rate: ".$rate->min." - ".$rate->max." / PER: ".$rate->per);
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
        /*
        NJ Refinance has an odd addition:
        if the loan amount is greater than the previous loan amount,
        we take the difference between the two and multiply this against the cheapest rate
        */
        if($this->values->state == 'NJ' && $this->values->type == 'refinance'){
            if($this->values->loanAmount > $this->values->priorLoanAmount){
                $priorLoanDifference = $this->values->loanAmount - $this->values->priorLoanAmount;
                //get last rate
                $lastRate = end($this->rates);
                $lastRateTotal = $lastRate->cost/$lastRate->per;
                $rateAddition = $priorLoanDifference * $lastRateTotal;
                $rateTotal += $rateAddition;
                $this->debug('APPLY NJ REFI RULE - '.$priorLoanDifference.' * '.$lastRateTotal.' = '.$rateAddition);
                $this->debug('NEW RATE IS NOW $'.$rateTotal);
            }
        }
    	return $rateTotal;
    }
    /**
     * Base calculation for determining insurance cost addition if NJ Refinance
     *
     * @param  integer    $money  Loan amount to used to calculate rate against
     * @return integer
    */ 
    /*
    public function getRateCostDifference($){
        $this->debug("Determine Rates using $".$money);
        $rateTotal = 0;
        //ERROR - NJ REFI NOT CORRECT BUT OTHERS WORK???
        //loop through rates to determine cost
        foreach($this->rates as $rate){
            if($rate->max < $money){
                $this->debug("START - $".$rateTotal." | Rate: ".$rate->min." - ".$rate->max." / PER: ".$rate->per);
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
        return $rateTotal;
    }*/
    /**
	 * Output a value to the screen, applies formatting
	 *
	 * @param  string    $field  Desired field output
	 * @return NULL
	*/ 
    function out($field){
    	$value = $this->values->$field;
    	$fieldTypes = array(
    		'purchasePrice' => 'money',
    		'loanAmount' => 'money',
    		'priorLoanAmount' => 'money',
    		'totalClosingCost' => 'money',
    		'totalCost' => 'money',
    		'rateTotal' => 'money',
    		'deed' => 'money',
    		'mortgage' => 'money',
    		'governmentRecording' => 'money',
    		'notaryFee' => 'money',
    		'settlementFee' => 'money',
    		'agent' => 'money',
    		'underwriter' => 'money',
    		'owner' => 'money',
    		'lender' => 'money',
    	);
    	$fieldType = 'string';
    	if(isset($fieldTypes[$field])){
    		$fieldType = $fieldTypes[$field];
    	}
    	if($fieldType == 'money'){
    		$value = '$'.number_format($value,2);
    	}
    	echo $value;
    }
    /**
	 * Helper function to display debug / output info
	 *
	 * @param  string    $message  Output debug message
	 * @return NULL
	*/ 
    public function debug($message){
    	if($this->debug){
    		echo $message."<br/>";
    	}
    }
}
/**
	* Additional Classes used
*/ 
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
    public $deed;
    public $mortgage;
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