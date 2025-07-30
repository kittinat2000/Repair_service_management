<?php
// ตั้งค่า target
$target = 'B';

if ($target == 'A') {
  $moveTarget = 'B';
  $discription = 'IT';
} else if ($target == 'B'){
  $moveTarget = 'A';
  $discription = 'MT';
} else {
  $moveTargetA = 'A';
  $discriptionA = 'IT';
  $moveTargetB = 'B';
  $discriptionB = 'MT';
}
include 'assets/detail_page.php';
?>