# gateControlyayayayayaya
I want to create an API which we can control the gate from device

## To check the status of gate
http://localhost/gateControl/readStatus.php

0 - gate is closed
1 - gate is opened

## Raspabery pi command to close the gate

http://localhost/gateControl/closeGate.php

## Raspabery pi command to open the gate

http://localhost/gateControl/openGate.php

## Raspaberry pi command to check the status of gate
http://localhost/gateControl/readStatus.php

## A test interface where we can manually open and close the gate
http://localhost/gateControl/controlGate.php


The command is actually simply write a file gateStatus.txt to either 0 or 1

The raspaberry will check if ZERO, it will poll it again every 1 seconds.
But, once the value is ONE, it send command to open the gate, wait for 5 seconds, then
  it will give command to close the gate (which is to write the file to 0 again)
Then, it will back to poll every 1 seconds, if the file is changed to 1 again.




