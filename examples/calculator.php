<!DOCTYPE html>
<html>
<head>
  <title>Title Calculator</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="http://netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap.min.css" rel="stylesheet">
  <script src="http://code.jquery.com/jquery.js"></script>
  <script src="http://netdna.bootstrapcdn.com/bootstrap/3.0.2/js/bootstrap.min.js"></script>
  <script>
    $(function() {
      /*
      //The fields on this page are generated dynamically to ensure
      //proper options and checkboxes are displayed
      */
      setStateContainer();
    });
    //Get states field
    function setStateContainer(){
      $.ajax({
          type: "POST",
          url: "ajax_fields.php",
          data: { action: "state" },
          error: function(XMLHttpRequest, status, errorThrown) {
              alert(status);
          },
          success: function (data, status) {
            $("#field-state-container").html(data);
            $("[name=state]").change(function(){
              setTypeContainer();
            });
            setTypeContainer();
          },
      });
    }
    function setTypeContainer(){
      $.ajax({
          type: "POST",
          url: "ajax_fields.php",
          data: { action: "type", state: $("[name=state]").val() },
          error: function(XMLHttpRequest, status, errorThrown) {
              alert(status);
          },
          success: function (data, status) {
            $("#field-type-container").html(data);
            $("[name=type]").change(function(){
              setDetailsContainer();
            });
            setDetailsContainer();
          },
      });
    }
    function setDetailsContainer(){
      $.ajax({
          type: "POST",
          url: "ajax_fields.php",
          data: { action: "details", state: $("[name=state]").val(), type: $("[name=type]").val() },
          error: function(XMLHttpRequest, status, errorThrown) {
              alert(status);
          },
          success: function (data, status) {
            $("#field-details-container").html(data);
          },
      });
    }
  </script>
</head>
<body>
  <div class="container">
    <h1>Title Calculator</h1>
    <div class="row">
      <div class="col-md-6">
        <form method="post" action="results-invoice.php">
          <div id="field-state-container">..loading...</div>
          <div id="field-type-container"></div>
          <div id="field-details-container"></div>
        </form>
      </div>
    </div>
  </div>
</body>
</html>
