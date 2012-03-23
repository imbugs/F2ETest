<?php

$stat = array(
    array('id' => 1, 'type' => 'chrome'),
    array('id' => 2, 'type' => 'firefox'),
//    array('id' => 3, 'type' => 'opera'),
    array('id' => 4, 'type' => 'ie6')
);

echo json_encode($stat);
?>