<?php
require('../src/kilrizzy/TitleCalculator/TitleCalculator.php');
use \kilrizzy\TitleCalculator\TitleCalculator as TitleCalculator;
$calculator = new TitleCalculator();
//$calculator->debug = true;
$calculator->values->state = $_POST['state'];
$calculator->values->type = $_POST['type'];
$calculator->values->loanAmount = $_POST['loanAmount'];
if($_POST['type'] == 'purchase'){
  $calculator->values->purchasePrice = floatval($_POST['purchasePrice']);
}else{
  $calculator->values->priorLoanAmount = floatval($_POST['priorLoanAmount']);
}
$calculator->useEndorsements($_POST['endorsements']);
$calculator->calculate();
?>
<!DOCTYPE html>
<html>
<head>
  <title>Title Quote Results</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="http://netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap.min.css" rel="stylesheet">
  <script src="http://code.jquery.com/jquery.js"></script>
  <script src="http://netdna.bootstrapcdn.com/bootstrap/3.0.2/js/bootstrap.min.js"></script>
</head>
<body>
  <div class="container">
    <h1>Title Quote Results</h1>
    <h2>Fees For <?php echo $calculator->state->name; ?> <?php echo $calculator->type->name; ?></h2>
    <?php if($calculator->values->type == 'purchase'){ ?>
    <h4>Purchase Amount: $<?php echo number_format($calculator->values->purchasePrice,2); ?></h4>
    <?php } ?>
    <h4>Loan Amount: $<?php echo number_format($calculator->values->loanAmount,2); ?></h4>
    <?php if($calculator->values->type == 'refinance'){ ?>
    <h4>Prior Loan Amount: $<?php echo number_format($calculator->values->priorLoanAmount,2); ?></h4>
    <?php } ?>
    <h3>Title Insurance Premium Only: $<?php echo number_format($calculator->values->rateTotal,2); ?></h3>
    <table class="table table-bordered table-condensed">
      <tbody>
        <tr class="active">
          <td>Insurance Amount</td>
          <td>$<?php echo number_format($calculator->values->rateTotal,2); ?></td>
        </tr>
        <?php
        foreach($calculator->values->endorsementItems as $endorsementItem){
        ?>
        <tr>
          <td><?php echo $endorsementItem->name; ?></td>
          <td>$<?php echo number_format($endorsementItem->cost,2); ?></td>
        </tr>
        <?php
        }
        ?>
        <tr class="success">
          <td>Title Charges</td>
          <td>$<?php echo number_format($calculator->values->totalCost,2); ?></td>
        </tr>
      </tbody>
    </table>
  </div>
</body>
</html>
