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
            'tests' => json_decode('{"summary":{"spec":3,"item":9,"suite":3,"specFailure":1,"itemFailure":1,"suiteFailure":1},"list":[{"description":"Main Suite","result":false,"specs":[{"description":"title Test","result":true,"items":[{"expected":"Google","received":"Google","operation":"toBe","result":true,"ifNot":false},{"expected":"webdriver","received":"webdriver","operation":"toBe","result":true,"ifNot":false}],"summary":{"item":2,"failure":0}}],"suites":[{"description":"Child Suite","result":false,"specs":[{"description":"Simple Spec","result":false,"items":[{"expected":2,"received":1,"operation":"toBe","result":true,"ifNot":true},{"expected":"^hello.+","received":"hellohahaa","operation":"toMatch","result":true,"ifNot":false},{"received":"hello","operation":"toBeDefined","result":true,"ifNot":false},{"expected":2,"received":1,"operation":"toBe","result":false,"ifNot":false},{"operation":"toBeUndefined","result":true,"ifNot":false},{"expected":null,"received":null,"operation":"toBeNull","result":true,"ifNot":false}],"summary":{"item":6,"failure":1}}],"suites":[],"summary":{"spec":1,"item":6,"suite":0,"specFailure":1,"itemFailure":1,"suiteFailure":0}}],"summary":{"spec":2,"item":8,"suite":1,"specFailure":1,"itemFailure":1,"suiteFailure":1}},{"description":"The Other Main Suite","result":true,"specs":[{"description":"The Other Simple Spec","result":true,"items":[{"expected":true,"received":true,"operation":"toBe","result":true,"ifNot":false}],"summary":{"item":1,"failure":0}}],"suites":[],"summary":{"spec":1,"item":1,"suite":0,"specFailure":0,"itemFailure":0,"suiteFailure":0}}]}')
        )
    );

    echo json_encode( $data );

?>
