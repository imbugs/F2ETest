<?php

    $result = true;
    $logs = array();
    $type = $_POST[ 'type' ];
    $levels = array( 'command', 'result', 'data', 'screenshotSave', 'error', 'custom' );
    $browsers = array( 'chrome', 'firefox', 'opera', 'ie6' );

    for( $i = 0; $i < 20; $i++ ){

        array_push( $logs, array(
            'type' => $levels[ $i % 6 ],
            'msg' => 'hello world' . $type
        ));
    }

    $screen = 'http://img.f2e.taobao.net/img.png_600x1000.jpg';

    $data = array(
        'result' => $result,
        'data' => array(
            'type' => $type,
            'logs' => $logs,
            'screen' => $screen
        )
    );

    echo json_encode( $data );

?>
