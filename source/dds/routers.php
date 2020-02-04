<?php
$router_files = [];
if(is_dir(DDS_PATH . DIR_SEP . 'routers' . DIR_SEP)){
    $router_files = scandir(DDS_PATH . DIR_SEP . 'routers' . DIR_SEP);
}

foreach($router_files as $file){
    if($file != '.' && $file != '..' && substr(strtolower($file), -4) == '.php'){
        include DDS_PATH . DIR_SEP . 'routers' . DIR_SEP . $file;
    }
}
