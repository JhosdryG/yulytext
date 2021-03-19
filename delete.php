<?php 
    if(isset($_GET['path'])){
        $path = $_GET['path'];
        deleteDirectory($path);

        // Se obtiene el path donde se encuentra actualmente para volver una vez eliminado el elemeno
        $currentElement = explode("/", $path);
        $last = count($currentElement) - 1;
        $currentPath = str_replace("/" . $currentElement[$last] , "", $path  );
        header('Location: index.php?path=' . $currentPath);
        die();
    }

    // Verifica si es un archivo y lo elimina y si es una carpeta
    // Inicia un bucle para eliminar toda las subcarpetas y archivos
    function deleteDirectory( $dir) {
        if(is_file($dir)){
            @unlink($dir);
            return;
        }
        if(!$dh = opendir($dir)){
            return;
        }
        while (false !== ($current = readdir($dh))) {
            if($current != '.' && $current != '..') {
                echo 'Se ha borrado el archivo '.$dir.'/'.$current.'<br/>';
                if (!@unlink($dir.'/'.$current)) 
                    deleteDirectory($dir.'/'.$current);
            }       
        }
        closedir($dh);
        echo 'Se ha borrado el directorio '.$dir.'<br/>';
        @rmdir($dir);
    }
?>

