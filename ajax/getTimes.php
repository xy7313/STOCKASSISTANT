<?php
include_once '../classes/editor.php';

$editor = new editor();

$update = $editor->getUpdateTime();
$predict = $editor->getPredictTime();

echo json_encode(array('update' => $update, 'predict' => $predict));

?>