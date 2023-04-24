<?php

spl_autoload_register( function ( $class_name ) {
    include $class_name . '.php';
});

try {
	$procesor = new CFDsProcessor( $argc, $argv );
	$procesor->process();

} catch( \Exception $e ){
	echo $e->getMessage() . "\n";
}