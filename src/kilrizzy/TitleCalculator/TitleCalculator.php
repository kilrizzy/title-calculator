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
        $this->values->title_cost = 0;
    }

    public function getData(){

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
    public $fees = array();
    public $endorsements = array();

    function __construct($name) {
    	$this->name = $name;
    }

} 
//
class TypeFee {
    
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