<?php
namespace kilrizzy\TitleCalculator;

class TitleCalculator{

	public $states;
	public $values;
   
    public function __construct(){
    	//Import settings
    	include('settings.php');
    	$this->states = $settings->states;
    	//Setup default values
        $this->values = new \StdClass();
        $this->values->titleCost = 0;
        $this->values->state = '';
        $this->values->type = '';
        $this->values->purchasePrice = 0;
        $this->values->loanAmount = 0;
        $this->values->priorLoanAmount = 0;
        $this->values->rateTotal = 0; //
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
    	//calculate values
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
    	$debug = true;
    	if($debug){
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
    public $rates = array();
    public $endorsements = array();

    function __construct($name) {
    	$this->name = $name;
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