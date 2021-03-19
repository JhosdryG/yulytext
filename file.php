<?php 
    if(isset($_POST['createfile'])){
        // Crear un archivo no existente
        $filename = $_POST['name'];
        $path = $_POST['path'];
        $filepath = $path . '/' . $filename . ".txt";
        createfile($filepath, $path, $filename);
        die();

    }else if( isset($_POST['save']) ){

        // Guarda el texto en un archivo existente
        $filename = $_POST['file'];
        $path = $_POST['path'];
        $text = $_POST['text'];
        $filepath = $path . '/' . $filename;

        if(file_exists($filepath)){

            $file = fopen($filepath,'w+') or die();

            if($file){
                fwrite($file, $text);
                fclose($file);
                header('Location: index.php?path=' . $path . '&file=' . $filename);
            }

        }
    }else{
        header('Location: index.php?path=' . $path);
        die();
    }




    function createfile($filepath, $path, $filename){
        // Crea el archivo si no existe
        if(!file_exists($filepath)){
            $file = fopen($filepath, 'a');

            if($file){
                header('Location: index.php?path=' . $path . '&file=' . $filename . '.txt');
            }else{
                $_SESSION['errormessage'] = 'La operación no se pudo completar con éxito';
                header('Location: index.php?path=' . $path); 
            }

        }else{
            session_start();
            $_SESSION['fileexist'] = true;
            header('Location: index.php?path=' . $path);
        }
    }

?>

