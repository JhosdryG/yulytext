<?php 
    if(isset($_POST['createfolder'])){
        $foldername = $_POST['name'];
        $path = $_POST['path'];
        // Obtiene el path exacto donde se creará la carpeta
        $folderpath = $path . '/' . $foldername;
        createFolder($folderpath, $path);
        die();
    }else{
        header('Location: index.php?path=' . $path);
        die();
    }

    function createFolder($folderpath, $path){

        // Crea la carpeta si no existe
        if(!file_exists($folderpath)){

            if(mkdir($folderpath)){
                header('Location: index.php?path=' . $folderpath);
            }else{
                $_SESSION['errormessage'] = 'La operación no se pudo completar con éxito';
                header('Location: index.php?path=' . $path); 
            }

        }else{
            session_start();
            $_SESSION['folderexist'] = true;
            header('Location: index.php?path=' . $path);
        }
    }

?>

