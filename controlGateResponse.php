<?php
   
  // start the main function
  $task = $_REQUEST['task'];
  
  if($task === 'open') {
    echo "<br/>Open the date please <br/>";
    include 'openGate.php';
    echo "<br/Gate open is executed.<br/";
    //echo $_SESSION['importFieldManager'];
    //$importField = unserialize($_SESSION['importFieldManager']);
    //$importField->getInputData();
    //header('Location: quotationPdfOut.php?id='.$id);
     header("Location: controlGate.php?message=Gate is opened now");
    
  } else {
      
    echo "<br/>Close the gate now<br/>";    
    include 'closeGate.php';
    echo "<br/Gate close is executed.<br/";
    header("Location: controlGate.php?message=Gate is closed now");
  }
  
?>
