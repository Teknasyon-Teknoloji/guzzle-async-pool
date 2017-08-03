<?php

namespace {
    $action = $_REQUEST['action'];
    $intA = $_REQUEST['intA'];
    $intB = $_REQUEST['intB'];
    $delay = intval($_REQUEST['delay']);
    if ($delay > 0) {
        sleep($delay);
    }

    header('Content-Type: application/json; charset=utf-8;');
    switch ($action) {
        case 'Add':
            echo json_encode(['AddResult' => $intA + $intB]);
            break;
        case 'Divide':
            echo json_encode(['DivideResult' => $intA / $intB]);
            break;
        case 'Multiply':
            echo json_encode(['MultiplyResult' => $intA * $intB]);
            break;
        case 'Subtract':
            echo json_encode(['Subtract' => $intA - $intB]);
            break;
    }
}
