<?php
   
  // start the main function
  
  $message = isset($_REQUEST['message']) ? $_REQUEST['message'] : "";
  
?>
<html>
  <head>
    <title>Control Gate</title>
  </head>
  <body>
    <form action='controlGateResponse.php' method='post'>
    <input type='hidden' name='task' value='open'>
      <table border='1'>
        <tr>
          <td>&nbsp</td>
          <td><input type='submit' value='Open Gate'></td>
          <td>&nbsp</td>
        </tr>
      </table> 
    </form>
    <form action='controlGateResponse.php' method='post'>
    <input type='hidden' name='task' value='close'>
      <table border='1'>
        <tr>
          <td>&nbsp</td>
          <td><input type='submit' value='Close Gate'></td>
          <td>&nbsp</td>
        </tr>
      </table> 
    </form>
    <?php
    if (strlen($message)> 0) {
        echo "<h2>".$message."</h2>";
    }
    ?>
  </body>
</html>