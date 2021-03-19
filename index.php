<?php 
    session_start();
    $path = '';

    if(isset($_GET['path'])){
        $path = $_GET['path'];
        $files = verifyPath($path);
    }else{
        header('Location: index.php?path=archivos');
        die();
    }

    /** Verifica que el path exista y sea una carpeta sino devuelve al inicio y si es renderiza */
    function verifyPath($path){
        
        if(!is_dir($path)){
            header('Location: index.php');
            die();
        }else{
            return renderDir($path);
        }
    }

    /* Funcion que renderiza el menu donde se muestran carpetas y archivo */
    function renderDir($path){
        $files = '';
        $dir = opendir($path . "/");
        while($e = readdir($dir)){
            if($e != '.' && $e != '..'){
                if(is_dir($path . '/' . $e)){
                    $files .= "<li><a href='index.php?path=$path/$e'>Carpeta: $e</a> <a href='delete.php?path=$path/$e'><i class='fas fa-trash-alt'></i></a></li>";
                }else{
                    $files .= "<li><a href='index.php?path=$path&file=$e'>Archivo: $e</a><a href='delete.php?path=$path/$e'><i class='fas fa-trash-alt'></i></a></li>";
                }
            }
        }
        return $files;
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editor de texto</title>

    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.13.1/css/solid.css" integrity="sha384-wG7JbYjXVhle8f17qIp6KJaO/5PsPzOrT76RgvdRGLHj0yXZZ3jg98yb0GNRv1+M" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.13.1/css/fontawesome.css" integrity="sha384-O6duc3QftgMWW3awKiGYswymy288kVFZgGWC/4YCl48Y0codWJRgs8DA0N4dX/zx" crossorigin="anonymous">
    
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Renderiza el directorio actual -->
    <h2 class="path">
        Directorio: /<?php echo $path ?>
    </h2>


    <!-- Volver al directorio principal -->
    <a class="inicio" href="index.php?path=archivos">Inicio</a>

<div class="row">

<div class="dir">
    <ul class="dirlist">
        <?php 
            echo $files;
        ?>
    </ul>

    <!-- Botonones para activar el portal con los formularios (Codificar en js) -->
    <div class="buttons">
        <button id="createFolder">Crear Carpeta</button>
        <button id="createFile">Crear Archivo</button>
    </div>
</div>
    <!-- Renderiza la lista de directorios y archivos -->

    <!-- Renderiza el formulario con el text area si se ha seleccionado un archivo -->
    <div class="editorpanel">
        <?php 
                if(isset($_GET['file'])){ ?>
                <form action="file.php" method="POST" class="editor">
                    <textarea name="text" ><?php 
                            $filename = $path . '/' . $_GET['file'];
                            $file = fopen($filename,'r') or die();
                            while(!feof($file)){
                                $txt = fgets($file);
                                echo $txt;
                            }
                        ?>
                    </textarea>
                    <input type="hidden" name="file" value="<?php echo $_GET['file']?>">
                    <input type="hidden" name="path" value="<?php echo $_GET['path']?>">
                    <input type="submit" value="Guardar" name="save">
                </form>
    
        <?php }  ?>  
    </div>
</div>
    
            <!-- Portal para crear carpeta nueva -->
            <div class="portal" id="portalfolder">
                <div class="portal_box">
                    <form class="portal_box_form" action="folder.php" method="POST">
                        <h2>Ingrese nombre de la carpeta</h2>
                        <input type="text" name="name" required />
                        <input type="hidden" name="path" value="<?php echo $path ?>">
                        <input type="submit" value="Crear carpeta" name="createfolder" />
                    </form>
                </div>
            </div>

            <!-- Mensaje por si el nombre ingresado ya existe -->
            <?php
                    if(isset($_SESSION['folderexist'])){                                        
                        if($_SESSION['folderexist']){
                            echo "<p class='alert'>La carpeta ya existe</p>";
                            
                        }    
                    }           
            ?>

            <!-- Portal para crear archivo nuevo -->
            <div class="portal" id="portalfile">
                <div class="portal_box">
                    <form class="portal_box_form" action="file.php" method="POST">
                        <h2>Ingrese nombre del archivo</h2>
                        <input type="text" name="name" required />
                        <input type="hidden" name="path" value="<?php echo $path ?>" >
                        <input type="submit" value="Crear Archivo" name="createfile" />
                    </form>
                </div>
            </div>

            <!-- Mensaje por si el nombre ingresado ya existe -->
            <?php 
                    if(isset($_SESSION['fileexist'])){                                        
                        if($_SESSION['fileexist']){
                            echo "<p class='alert'>El archivo ya existe</p>";
                            
                        }    
                    }           
            ?>

            <!-- Mensaje por si ha ocurrido un error -->
            <?php 
                    if(isset($_SESSION['errormessage'])){                                        
                        if($_SESSION['errormessage']){
                            $message = $_SESSION['errormessage'];
                            echo "<p>$message</p>";
                            
                        }    
                    }           
            ?>
    <!-- Destruir sessión -->
    <?php  session_destroy(); ?>

    <script>
        document.getElementById("createFolder").addEventListener( "click", () => {
            document.getElementById("portalfolder").classList.toggle('show_portal')
        })
        document.getElementById("createFile").addEventListener( "click", () => {
            document.getElementById("portalfile").classList.toggle('show_portal')
        })
    </script>
</body>
</html>