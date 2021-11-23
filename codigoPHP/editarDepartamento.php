<!DOCTYPE html>

<html lang="es">
    <head>
        <meta charset="utf-8">
        <title>Sonia Anton Llanes - Ejercicio 00</title>
        <meta name="author" content="Sonia Antón Llanes">
        <meta name="description" content="Proyecto DAW2">
        <meta name="keywords" content="">
        <link href="../webroot/css/estiloej.css" rel="stylesheet" type="text/css">
        <link href="../webroot/images/mariposa_vintage.png" rel="icon" type="image/png">
        <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Secular+One&display=swap" rel="stylesheet">
        <style>
            .th{width: 10vw;}
            .table1>td{border: none;}
            .table2{height: 50vh;}
            .tr{height: 4.5vh;}
            .dato{width: 25%;
                  height: 15px;
                  font-size: 18px;}
            .error{height: 20px;
                   margin: -15px 0 10px 30px;}
            .datoUsu>input{width: 100%;
                           height: 30px;
                           font-size: 20px;
                           border: none;
                           border-bottom: 1px solid black;
                           padding: 0 10px;}
            #submit{border: 1px solid black;
                    width: 50%;
                    margin: 20px;
                    padding: 5px;
                    font-size: 1.1rem;}
            .buscar{text-align: center;}
            .ast{color: #bb1212;}
            .h3Dep{text-align: left;
                   padding: 0 50px;}
        </style>
    </head>
    <body class="container">
	<header class="header">
            <h2 class="centrado"><a href="../../proyectoTema4/indexProyectoTema4.php" style="border-bottom: 2px solid black">TEMA 4:</a>
                Mantenimiento de Departamentos Tema 4</h2>
        </header>
        
        <main class="main">
            <div>

                <?php

                /* 
                 * Author: Sonia Antón Llanes
                 * Created on: 22-noviembre-2021
                 * Ejercicio 09: Mantenimiento de Departamentos
                 *    funcion: EDITAR
                 */

                    /* Importamos archivos necesarios */
                        require_once '../config/confDBPDO.php';  //archivo que contiene los parametros de la conexion
                        require_once '../core/libreriaValidacion.php'; //libreria Validación para errores

                    /* VARIABLES: */
                        $entradaOK = true;  //Variable que indica que todo va bien
                        //Constantes para la libreria de validacion
                        define('OBLIGATORIO', 1);
                        define('OPCIONAL', 0);

                    /* ARRAY DE ERRORES Y ENTRADAS DEL FORMULARIO*/
                        $aErrores = array(     //Array para guardar los errores del formulario
                            'descDepartamento' => null,   //E inicializo cada elemento
                            'volumenNegocio' => null
                            );
                        $aRespuestas = array(     //Array para guardar las entradas del formulario correctas
                            'descDepartamento' => null,   //E inicializo cada elemento
                            'volumenNegocio' => null
                            );

                    /* FORMULARIO */
                        /**
                         * Si pulso CANCELAR vuelvo a Mantenimiento de Departamentos sin realizar cambio ninguno 
                         */
                            if (isset($_POST['cancelar'])){  //Pulso el boton cancelar
                                echo "<a href='mtoDepartamentos.php'></a>";
                            }
                        /**
                         * Si pulso ACEPTAR se valida y guardan los cambios
                         */
                        /* VALIDACIÓN de cada entrada del formulario con la libreria de validación que importamos */
                            if (isset($_POST['aceptar'])){  //Pulso el boton enviar
                                //Valido cada campo y si hay algun error lo guardo en el array aErrores
                                    $aErrores['descDepartamento']= validacionFormularios::comprobarAlfabetico($_REQUEST['descDepartamento'], 50, 1, OPCIONAL);
                                    $aErrores['volumenNegocio']= validacionFormularios::comprobarEntero($_REQUEST['volumenNegocio'], 100, 0, OPCIONAL);
                                //Recorro array errores y compruebo si se ha incluido algún error
                                foreach ($aErrores as $campo => $error){  
                                    if ($error!=null){   //si es distinto de null, hay errores
                                        $_REQUEST[$campo] = "";  //limpio el campo $_REQUEST
                                        $entradaOK = false;      //si hay algun error entradaOK es false
                                    }
                                }     
                            }
                            else{  //aun no se ha pulsado el boton enviar
                                $entradaOK = true;       // si no se pulsa enviar, entradaOK es true (para mostrar el resultado
                                $_REQUEST['descDepartamento'] = "";  //doy valor a $_REQUEST 
                            }

                    

                        /* RESULTADO con entradaOK: al comenzar y una vez enviado con entradas correctas */
                            if($entradaOK){  //Si todas las entradas son correctas
                                /* GUARDO EN EL ARRAY $aRespuestas LOS DATOS INTRODUCIDOS EN EL FORMULARIO */
                                    $aRespuestas['descDepartamento']= $_REQUEST['descDepartamento'];

                                /* ESTABLEZCO CONEXIÓN A LA BASE DE DATOS */
                                try {  //Conexión: establezco la conexión y el código que quiero realizar           
                                    $miDB = new PDO (HOST, USER, PASSWORD);  //establezco conexión con objeto PDO 
                                    $miDB -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  //lanzo excepción utilizando manejador propio PDOException cuando se produce un error

                                //Busco si existe algun departamento que coincida con la descripción introducida en el formulario, sino por defecto:
                                    $sqlBuscarDto = <<<EOD
                                            SELECT * FROM Departamento  
                                            WHERE descDepartamento LIKE '%{$aRespuestas['descDepartamento']}%'
                                            LIMIT 0,7;
                                            EOD;
                                    $sqlBuscar = $miDB -> prepare($sqlBuscarDto);  //Con consulta preparada, preparo la consulta que devuelve un objeto PDOStatement
                                    $sqlBuscar -> execute();             //ejecuto la consulta

                                //Si no encuentra ningún departamento nos muestra todos, entonces preparamos otra consulta para mostrar por pantalla
                                    if ($sqlBuscar->rowCount()==0){
                                        $sqlBuscarDto = 'SELECT * FROM Departamento LIMIT 0,7';   //cambiamos el valor del query de busqueda a todos los departamentos
                                        $sqlBuscar = $miDB -> prepare($sqlBuscarDto);   //Con consulta preparada
                                        $sqlBuscar ->execute();                         //ejecuto la consulta
                                    }

                ?>            
                                <!-- MUESTRO LA TABLA DE DEPARTAMENTOS -->    
                                    <div class="table2">
                                        <table>
                                            <tr>
                                                <th colspan="4"><h3 class="h3Dep">Departamentos:</h3></th>
                                            </tr>
                                            <tr class="tr">
                                                <th class="cod">Codigo</th>
                                                <th class="dep">Departamento</th>
                                                <th class="fbaja">Fecha Baja</th>
                                                <th class="vneg">Volumen Negocio</th>
                                                <th colspan="3"></th>
                                            </tr>
                <?php
                                            //Recorro los registros de la database
                                            //Y Muestro la tabla Departametos con los encontrados o entera
                                        $oRegistro = $sqlBuscar->fetch(PDO::FETCH_OBJ);  //guardo en un objeto los datos del primer registro y avanzo puntero
                                        while ($oRegistro){  //mientras haya datos (no esté vacio)
                                                //Dibujo tabla con los datos que nos devuelve el registro $oRegistro
                ?>
                                            <tr class="tr">
                                                <td> <?php echo $oRegistro->codDepartamento; ?> </td>
                                                <td> <?php echo $oRegistro->descDepartamento; ?> </td>
                                                <td> <?php echo $oRegistro->fechaBaja; ?> </td>
                                                <td> <?php echo $oRegistro->volumenNegocio; ?> </td>
                                                <td><img class= "imgtd" src="../webroot/images/editar.png" alt="editar"></td>
                                                <td><img class= "imgtd" src="../webroot/images/eliminar.png" alt="eliminar"></td>
                                                <td><img class= "imgtd" src="../webroot/images/ojo-mostar.png" alt="mostrar"></td>
                                            </tr>
                <?php
                                                //Y avanzo puntero
                                            $oRegistro = $sqlBuscar->fetch(PDO::FETCH_OBJ);  //avanzo puntero al siguiente registro de la base de datos
                                        }

                                }  
                                catch (PDOException $excepcion){
                                    $error = $excepcion->getCode();        //guardamos en la variable error el error que salte
                                    $mensaje = $excepcion->getMessage();  //guardamos en la variable mensaje el mensaje que genera el error que saltó
                                    echo "<p>Error".$error."</p>";
                                    echo "<p style='color: red'>Código del error".$mensaje."</p>";
                                }
                                finally {
                                    unset($miDB);
                                }
                        }
                        else{
                ?>
                        <!-- MUESTRO EL FORMULARIO --> 
                            <form name="formulario" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                            <div class="table1">
                                <table>
                                    <tr>
                                        <th colspan="2"><h3>EDITAR Departamento</h3></th>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="td">
                                            <div class="dato"><label for="LbCodDepartamento">Código del Departamento  <span class="ast">*</span></label></div>
                                            <div class="datoUsu"><input type="text" name="codDepartamento" id="LbCodDepartamento" readonly="readonly" style="background: lightgray"
                                                   value="<?php  //Mantenemos el valor - primary key
                                                            echo $valor = $_SERVER['codDepartamento'];?>"></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="td">
                                            <div class="dato"><label for="LbDescDepartamento">Departamento  <span class="ast">*</span></label></div>
                                            <div class="datoUsu"><input type="text" name="descDepartamento" id="LbDescDepartamento"
                                                   value="<?php  //Si no hay ningun error y se ha enviado mantenerlo
                                                            echo $resultado = ($aErrores['eDescDepartamento']==NULL && isset($_POST['descDepartamento'])) ? $_POST['descDepartamento'] : ""; 
                                                          ?>"></div>
                                            <div class="error"><?php
                                                    if ($aErrores['eDescDepartamento'] != NULL) { //si hay errores muestra el mensaje
                                                        echo "<span style=\"color:red;\">".$aErrores['eDescDepartamento']."</span>"; //aparece el mensaje de error que tiene el array aErrores
                                                    }
                                                 ?></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="td" colspan="2">
                                            <div class="dato"><label for="LbVolumenNegocio">Volumen de Negocio </label></div>
                                            <div class="datoUsu"><input type="text" name="volumenNegocio" id="LbVolumenNegocio"
                                                   value="<?php  //Si no hay ningun error y se ha enviado mantenerlo
                                                            echo $resultado = ($aErrores['eVolumenNegocio']==NULL && isset($_POST['volumenNegocio'])) ? $_POST['volumenNegocio'] : ""; 
                                                          ?>"></div>
                                            <div class="error"><?php
                                                    if ($aErrores['eVolumenNegocio'] != NULL) { //si hay errores muestra el mensaje
                                                        echo "<span style=\"color:red;\">".$aErrores['eVolumenNegocio']."</span>"; //aparece el mensaje de error que tiene el array aErrores
                                                    }
                                                 ?></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th><input type="submit" id="aceptar" name="aceptar" value="ACEPTAR"></th>
                                        <th><input type="submit" id="cancelar" name="cancelar" value="CANCELAR"></th>
                                    </tr>
                                </table>
                            </div>
                <?php
                        }

                ?>    

            </div>

            <div>

            </div>
        </main>

        <footer class="footer">
            <nav class="fnav">
                <ul>
                    <li class="ftexto"><a href="../index.php">&copy 2020-21. Sonia Anton LLanes</a></li>
                    <li>
                        
                        <a class="maxMedia" href="doc/curriculum_SALL.pdf" target="_blank"><img src="webroot/images/CV.png" alt="imagen_CV"></a>
                        <a class="maxMedia" href=""><img src="webroot/images/linkedin.png" alt="imagen_linkedIn"></a>
                        <a class="maxMedia" href="https://github.com/SoniaALLSauces/proyectoTema4" target="_blank"><img src="webroot/images/github.png" alt="imagen_github"></a>
                    </li>
                </ul>
            </nav>
        </footer>
        
    </body>
</html>