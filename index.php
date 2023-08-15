<?php

require_once 'Lib/Livro/Core/ClassLoader.php';
$al = new \Livro\Core\ClassLoader;
$al->addNamespace( 'Livro', 'Lib/Livro' );
$al->register();

require_once'Lib/Livro/Core/AppLoader.php';
$al = new \Livro\Core\AppLoader;
$al->addDirectory( 'App/Control' );
$al->addDirectory( 'App/Model' );
$al->register();

$loader = require_once 'vendor/autoload.php';
$loader->register();

$template =  file_get_contents( 'App/Template/template.html' );
$content = '';
$class = 'Home';

if( $_GET ){
	$class = $_GET['class'];
    try{
        $page = new $class;
        ob_start();
        $page->show();
        $content = ob_get_contents();
        // var_dump($content);
        ob_end_clean();
    }
    catch( Exception $e ){
        $content = $e->getMessage() . '<br>' . $e->getTraceAsString();
    }
}
else{
    try{
        $page = new ListaCompraForm;
        ob_start();
        $page->show();
        $content = ob_get_contents();
        ob_end_clean();
    }
    catch( Exception $e ){
        $content = $e->getMessage() . '<br>' . $e->getTraceAsString();
    }
}

$output = str_replace( '{content}', $content, $template );
$output = str_replace( '{class}', $class, $output );
echo $output;


