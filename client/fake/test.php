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
            'screen' => $screen,
            'tests' => json_decode('[{"description":"Main Suite","specs":[{"description":"title Test","items":[{"expected":"Google","received":"Google","operation":"toBe","result":true,"ifNot":false},{"expected":"webdriver","received":"webdriver","operation":"toBe","result":true,"ifNot":false}]}],"suites":[{"description":"Child Suite","specs":[{"description":"Simple Spec","items":[{"expected":2,"received":1,"operation":"toBe","result":true,"ifNot":true},{"expected":"^hello.+","received":"hellohahaa","operation":"toMatch","result":true,"ifNot":false},{"received":"hello","operation":"toBeDefined","result":true,"ifNot":false},{"operation":"toBeUndefined","result":true,"ifNot":false},{"expected":null,"received":null,"operation":"toBeNull","result":true,"ifNot":false}]}],"suites":[]}]},{"description":"The Other Main Suite","specs":[{"description":"The Other Simple Spec","items":[{"expected":true,"received":true,"operation":"toBe","result":true,"ifNot":false}]}],"suites":[]}]')
        )
    );

    echo json_encode( $data );

?>
