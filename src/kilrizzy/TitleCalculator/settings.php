<?php
use \kilrizzy\TitleCalculator\PropertyState as PropertyState;
use \kilrizzy\TitleCalculator\PropertyType as PropertyType;
use \kilrizzy\TitleCalculator\TypeRate as TypeRate;
use \kilrizzy\TitleCalculator\TypeEndorsement as TypeEndorsement;
$settings = new \StdClass();
$settings->states = array();
/*
NJ
*/
$settings->states['NJ'] = new PropertyState("NJ","New Jersey");
//PURCHASE
//rates
$settings->states['NJ']->types['purchase'] = new PropertyType("Purchase");
$settings->states['NJ']->types['purchase']->rates[] = new TypeRate(0,100000,5.25,1000);
$settings->states['NJ']->types['purchase']->rates[] = new TypeRate(100001,500000,4.25,1000);
$settings->states['NJ']->types['purchase']->rates[] = new TypeRate(500001,2000000,2.75,1000);
$settings->states['NJ']->types['purchase']->rates[] = new TypeRate(2000001,99000000,2,1000);
//endorsements
$settings->states['NJ']->types['purchase']->endorsements[] = new TypeEndorsement("Examination Fee",100);
$settings->states['NJ']->types['purchase']->endorsements[] = new TypeEndorsement("Flood Search",10);
$settings->states['NJ']->types['purchase']->endorsements[] = new TypeEndorsement("Upper Court Search",30);
$settings->states['NJ']->types['purchase']->endorsements[] = new TypeEndorsement("Municipal Searches",48);
$settings->states['NJ']->types['purchase']->endorsements[] = new TypeEndorsement("Alta 8.1 Endorsement",25);
$settings->states['NJ']->types['purchase']->endorsements[] = new TypeEndorsement("Alta 9 Endorsement",25);
$settings->states['NJ']->types['purchase']->endorsements[] = new TypeEndorsement("Survey",25);
$settings->states['NJ']->types['purchase']->endorsements[] = new TypeEndorsement("Metes and Bounds",50);
$settings->states['NJ']->types['purchase']->endorsements[] = new TypeEndorsement("Closing Services Letter",75);
$settings->states['NJ']->types['purchase']->endorsements[] = new TypeEndorsement("Copies",15);
$settings->states['NJ']->types['purchase']->endorsements[] = new TypeEndorsement("Tideland Search",42);
$settings->states['NJ']->types['purchase']->endorsements[] = new TypeEndorsement("Overnight Charges",25);
$settings->states['NJ']->types['purchase']->endorsements[] = new TypeEndorsement("Notice of Settlement",50);
$settings->states['NJ']->types['purchase']->endorsements[] = new TypeEndorsement("Notary Fee",25);
$settings->states['NJ']->types['purchase']->endorsements[] = new TypeEndorsement("E-Download Fee",50);
$settings->states['NJ']->types['purchase']->endorsements[] = new TypeEndorsement("Simultanious Policy Fee",25);
$settings->states['NJ']->types['purchase']->endorsements[] = new TypeEndorsement("Variable Rate Endorsement",25);
$settings->states['NJ']->types['purchase']->endorsements[] = new TypeEndorsement("Condo Endorsement",25);
$settings->states['NJ']->types['purchase']->endorsements[] = new TypeEndorsement("PUD Endorsement",25);
$settings->states['NJ']->types['purchase']->endorsements[] = new TypeEndorsement("Secondary Rate Endorsement",25);
$settings->states['NJ']->types['purchase']->endorsements[] = new TypeEndorsement("Settlement Fee",150);
//REFINANCE
//rates
$settings->states['NJ']->types['refinance'] = new PropertyType("Refinance");
$settings->states['NJ']->types['refinance']->rates[] = new TypeRate(0,100000,2.75,1000);
$settings->states['NJ']->types['refinance']->rates[] = new TypeRate(100001,500000,2.5,1000);
$settings->states['NJ']->types['refinance']->rates[] = new TypeRate(500001,2000000,2.25,1000);
$settings->states['NJ']->types['refinance']->rates[] = new TypeRate(2000001,99000000,1.75,1000);
//endorsements
$settings->states['NJ']->types['refinance']->endorsements[] = new TypeEndorsement("Examination Fee",100);
$settings->states['NJ']->types['refinance']->endorsements[] = new TypeEndorsement("Flood Search",10);
$settings->states['NJ']->types['refinance']->endorsements[] = new TypeEndorsement("Upper Court Search",30);
$settings->states['NJ']->types['refinance']->endorsements[] = new TypeEndorsement("Municipal Searches",48);
$settings->states['NJ']->types['refinance']->endorsements[] = new TypeEndorsement("Alta 8.1 Endorsement",25);
$settings->states['NJ']->types['refinance']->endorsements[] = new TypeEndorsement("Alta 9 Endorsement",25);
$settings->states['NJ']->types['refinance']->endorsements[] = new TypeEndorsement("Survey",25);
$settings->states['NJ']->types['refinance']->endorsements[] = new TypeEndorsement("Closing Services Letter",75);
$settings->states['NJ']->types['refinance']->endorsements[] = new TypeEndorsement("Copies",15);
$settings->states['NJ']->types['refinance']->endorsements[] = new TypeEndorsement("Overnight Charges",25);
$settings->states['NJ']->types['refinance']->endorsements[] = new TypeEndorsement("Notice of Settlement",25);
$settings->states['NJ']->types['refinance']->endorsements[] = new TypeEndorsement("Notary Fee",25);
$settings->states['NJ']->types['refinance']->endorsements[] = new TypeEndorsement("E-Download Fee",50);
$settings->states['NJ']->types['refinance']->endorsements[] = new TypeEndorsement("County Discharge Fee",75);
$settings->states['NJ']->types['refinance']->endorsements[] = new TypeEndorsement("Payoff Overnight Fee",25);
$settings->states['NJ']->types['refinance']->endorsements[] = new TypeEndorsement("Variable Rate Endorsement",25);
$settings->states['NJ']->types['refinance']->endorsements[] = new TypeEndorsement("Condo Endorsement",25);
$settings->states['NJ']->types['refinance']->endorsements[] = new TypeEndorsement("PUD Endorsement",25);
$settings->states['NJ']->types['refinance']->endorsements[] = new TypeEndorsement("Secondary Rate Endorsement",25);
$settings->states['NJ']->types['refinance']->endorsements[] = new TypeEndorsement("Settlement Fee",300);


/*
PA
*/
$settings->states['PA'] = new PropertyState("PA","Pennsylvania");
//PURCHASE
//rates
$settings->states['PA']->types['purchase'] = new PropertyType("Purchase");
$settings->states['PA']->types['purchase']->rates[] = new TypeRate(0,30000,500,0);
$settings->states['PA']->types['purchase']->rates[] = new TypeRate(30001,45000,6.50,1000);
$settings->states['PA']->types['purchase']->rates[] = new TypeRate(45001,100000,5.50,1000);
$settings->states['PA']->types['purchase']->rates[] = new TypeRate(100001,500000,5.00,1000);
$settings->states['PA']->types['purchase']->rates[] = new TypeRate(500001,1000000,4,1000);
$settings->states['PA']->types['purchase']->rates[] = new TypeRate(1000001,2000000,3,1000);
$settings->states['PA']->types['purchase']->rates[] = new TypeRate(2000001,7000000,2,1000);
$settings->states['PA']->types['purchase']->rates[] = new TypeRate(7000001,30000000,1.5,1000);
//endorsements
$settings->states['PA']->types['purchase']->endorsements[] = new TypeEndorsement("Closing Services Letter",75);
$settings->states['PA']->types['purchase']->endorsements[] = new TypeEndorsement("Notary Fee",20);
$settings->states['PA']->types['purchase']->endorsements[] = new TypeEndorsement("Endorsements",125);
$settings->states['PA']->types['purchase']->endorsements[] = new TypeEndorsement("Recording Fee",25);
$settings->states['PA']->types['refinance']->endorsements[] = new TypeEndorsement("Settlement Fee",75);

//REFINANCE
//rates
$settings->states['PA']->types['refinance'] = new PropertyType("Refinance");
$settings->states['PA']->types['refinance']->rates[] = new TypeRate(0,30000,450,0);
$settings->states['PA']->types['refinance']->rates[] = new TypeRate(30001,45000,5.25,1000);
$settings->states['PA']->types['refinance']->rates[] = new TypeRate(45001,100000,4.75,1000);
$settings->states['PA']->types['refinance']->rates[] = new TypeRate(100001,500000,4.25,1000);
$settings->states['PA']->types['refinance']->rates[] = new TypeRate(500001,1000000,3.75,1000);
$settings->states['PA']->types['refinance']->rates[] = new TypeRate(1000001,2000000,2.75,1000);
$settings->states['PA']->types['refinance']->rates[] = new TypeRate(2000001,7000000,2,1000);
$settings->states['PA']->types['refinance']->rates[] = new TypeRate(7000001,30000000,1.5,1000);
//endorsements
$settings->states['PA']->types['refinance']->endorsements[] = new TypeEndorsement("Closing Services Letter",75);
$settings->states['PA']->types['refinance']->endorsements[] = new TypeEndorsement("Notary Fee",20);
$settings->states['PA']->types['refinance']->endorsements[] = new TypeEndorsement("Endorsements",100);
$settings->states['PA']->types['refinance']->endorsements[] = new TypeEndorsement("Tax and Utility ",80.50);
$settings->states['PA']->types['refinance']->endorsements[] = new TypeEndorsement("Settlement Fee",75);
?>