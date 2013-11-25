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
  <link href="css/main.css" rel="stylesheet">
  <script src="http://code.jquery.com/jquery.js"></script>
  <script src="http://netdna.bootstrapcdn.com/bootstrap/3.0.2/js/bootstrap.min.js"></script>
</head>
<body>
  <div class="container">
    <h1><?php echo $calculator->company->name; ?></h1>
    <div class="pull-right">
      <p><strong><?php echo $calculator->company->name; ?></strong><br/><?php echo $calculator->company->address; ?></p>
      <p><?php echo $calculator->company->phone; ?></p>
    </div>
    <h2>Fees For <?php echo $calculator->state->name; ?> <?php echo $calculator->type->name; ?></h2>
    <?php if($calculator->values->type == 'purchase'){ ?>
    <h4>Purchase Amount: <?php $calculator->out('purchasePrice'); ?></h4>
    <?php } ?>
    <h4>Loan Amount: <?php $calculator->out('loanAmount'); ?></h4>
    <?php if($calculator->values->type == 'refinance'){ ?>
    <h4>Prior Loan Amount: <?php $calculator->out('priorLoanAmount'); ?></h4>
    <?php } ?>
    <h3>Title Insurance Premium Only: <?php $calculator->out('rateTotal'); ?></h3>

    <table class="table table-bordered table-condensed" style="margin-bottom:0px;">
      <thead>
        <tr>
          <th width="70%">1100. Title Charges</th>
          <th width="15%"></th>
          <th width="15%" class="align-right"></th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>1101. Title Service and lender's title insurance</td>
          <td>(from GFE #4)</td>
          <td class="align-right">$<?php echo number_format($calculator->values->totalCost,2); ?></td>
        </tr>
        <tr>
          <td>1102. Settlement or closing fee to Agent</td>
          <td><?php $calculator->out('settlementFee'); ?></td>
          <td class="align-right"></td>
        </tr>
        <tr>
          <td>1103. Owner's title insurance</td>
          <td>(from GFE#5)</td>
          <td class="align-right"><?php $calculator->out('owner'); ?></td>
        </tr>
        <tr>
          <td>1104. Lender's title insurance</td>
          <td><?php $calculator->out('lender'); ?></td>
          <td class="align-right"></td>
        </tr>
        <tr>
          <td>1105. Lender's title policy limit <?php $calculator->out('loanAmount'); ?></td>
          <td></td>
          <td class="align-right"></td>
        </tr>
        <tr>
          <td>1106. Owner's title policy limit <?php $calculator->out('purchasePrice'); ?></td>
          <td></td>
          <td class="align-right"></td>
        </tr>
        <tr>
          <td>1107. Agent's portion of the total title insurance premium</td>
          <td><?php $calculator->out('agent'); ?></td>
          <td class="align-right"></td>
        </tr>
        <tr>
          <td>1108. Underwriter's portion of the total title insurance premium</td>
          <td><?php $calculator->out('underwriter'); ?></td>
          <td class="align-right"></td>
        </tr>
        <tr>
          <td>1109. Notary Fee</td>
          <td><?php $calculator->out('notaryFee'); ?></td>
          <td class="align-right"></td>
        </tr>
      </tbody>
    </table>

    <table class="table table-bordered table-condensed">
      <thead>
        <tr>
          <th width="70%">1200. Government and Recording Transfer Charges</th>
          <th width="15%"></th>
          <th width="15%" class="align-right"></th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td> 1201. Government recording charges </td>
          <td>(from GFE #7)</td>
          <td class="align-right"><?php $calculator->out('governmentRecording'); ?></td>
        </tr>
        <tr>
          <td>1202. Deed <?php $calculator->out('deed'); ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Mortgage <?php $calculator->out('mortgage'); ?> </td>
          <td></td>
          <td class="align-right"></td>
        </tr>
        <tr>
          <td>1203. Transfer taxes</td>
          <td>(from GFE#8)</td>
          <td class="align-right"></td>
        </tr>
        <tr>
          <td>1204. Realty Transfer Fee &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Deed &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Mortgage</td>
          <td></td>
          <td class="align-right"></td>
        </tr>
        <?php if($calculator->values->mansionTax > 0){ ?>
        <tr>
          <td>1205. Mansion Tax</td>
          <td><?php $calculator->out('mansionTax'); ?></td>
          <td class="align-right"></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>

    <table class="table table-bordered table-condensed">
      <thead>
        <tr>
          <th width="70%">Total Charges for Title and Closing</th>
          <th width="15%"></th>
          <th width="15%" class="align-right"><?php $calculator->out('totalClosingCost'); ?></th>
        </tr>
      </thead>
    </table>

    <table class="table table-bordered table-condensed">
      <thead>
        <tr>
          <th width="70%">1101. Itemization</th>
          <th width="15%"></th>
          <th width="15%" class="align-right"></th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>1101. Title Service and lender's title insurance</td>
          <td>(from GFE #4)</td>
          <td class="align-right"><?php $calculator->out('totalCost'); ?></td>
        </tr>
        <tr>
          <td>Lender's premium</td>
          <td><?php $calculator->out('rateTotal'); ?></td>
          <td class="align-right"></td>
        </tr>

        <?php
        foreach($calculator->values->endorsementItems as $endorsementItem){
          ?>
          <tr>
            <td><?php echo $endorsementItem->name; ?></td>
            <td>$<?php echo number_format($endorsementItem->cost,2); ?></td>
            <td class="align-right"></td>
          </tr>
          <?php
        }
        ?>

      </tbody>
    </table>


  </div>
</body>
</html>
