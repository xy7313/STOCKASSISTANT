<?php

include_once '../classes/editor.php';

$updateMin = $_POST['updateMin'];
$updateHour = $_POST['updateHour'];
$updateDay = $_POST['updateDay'];

$predictMin = $_POST['predictMin'];
$predictHour = $_POST['predictHour'];
$predictDay = $_POST['predictDay'];

$editor = new editor();

$editor->setTime($updateMin,$updateHour,$updateDay,$predictMin,$predictHour,$predictDay);

?>