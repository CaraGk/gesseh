<?php

include(dirname(__FILE__).'/../../bootstrap/Doctrine.php');

$t = new lime_test(1);

$t->comment('::changeOrder(order)');
$t->is(servicesActions::changeOrder('asc'), 'desc', '::changeOrder() transforme asc en desc');
?>
