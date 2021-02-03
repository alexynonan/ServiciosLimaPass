<?php

include_once('cnx.php');

$body = json_decode(file_get_contents('php://input'),true);

function validateDni($valor){

   if(trim($valor) == '' || strlen($valor) > 13 || strlen($valor) < 8){
      return false;
   }else{
      return true;
   }
}

function validateFormText($valor){

   if(trim($valor) == '' || strlen($valor) < 3){
      return false;
   }else{
      return true;
   }
}

function validar_email($email){
   return (preg_match('/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/', $email)); 
}

function validar_fecha ($fecha){
   return (preg_match('/^(\d\d\/\d\d\/\d\d\d\d){1,1}$/', $fecha));
}

function validarTelefono($numero){
   $reg = "/^[0-9]{9,9}$/";
   return preg_match($reg, $numero);
 }


if ($body){

   if($_SERVER['REQUEST_METHOD'] == "POST"){

      $nombre   = $body["nombre"];
      $apellido = $body["apellido"];
      $dni      = $body["dni"];
      $fecha    = $body["fecha"];
      $correo   = $body["correo"];
      $numero   = $body["numero"];

      if(!validateFormText($nombre)){
         $json = array("status" => 100, "mensaje" => "Ingrese correcto su nombre");
         echo json_encode($json);
         return;
      }

      if(!validateFormText($apellido)){
         $json = array("status" => 100, "mensaje" => "Ingrese correcto su apellido");
         echo json_encode($json);
         return;
      }

      if (!validateDni($dni)) {               
         $json = array("status" => 100, "mensaje" => "Ingrese correcto su dni");
         echo json_encode($json);
         return;
      }

      if(!is_numeric($dni)){
         $json = array("status" => 100, "mensaje" => "Ingrese correcto su dni");
         echo json_encode($json);
         return;
      }

      if(!validar_fecha($fecha)){
         $json = array("status" => 100, "mensaje" => "Ingrese correcto su fecha");
         echo json_encode($json);
         return;
      }

      if (!validar_email($correo)){
         $json = array("status" => 100, "mensaje" => "Ingrese correcto su correo");
         echo json_encode($json);
         return;
      }

      if(!validarTelefono($numero)){
         $json = array("status" => 100, "mensaje" => "Ingrese correcto su numero");
         echo json_encode($json);
         return;
       } 
    
      $querySelect = "SELECT * FROM usuario WHERE dni = $dni";
        
      $parseSelect = mysqli_query($conexion,$querySelect);

      $resultSelect = mysqli_fetch_assoc($parseSelect);
 
      if(!isset($resultSelect['dni'])){

         $queryCorreo = "SELECT * FROM correos WHERE correo = '$correo'";
        
         $parseCorreo = mysqli_query($conexion,$queryCorreo);

         $resultCorreo = mysqli_fetch_assoc($parseCorreo);

         if (!isset($resultCorreo['correo'])){

            $queryNumber = "SELECT * FROM numeros WHERE numero = '$numero'";
        
            $parseNumber = mysqli_query($conexion,$queryNumber);

            $resultNumber = mysqli_fetch_assoc($parseNumber);
  
            if (!isset($resultNumber['numero'])){

               $sqlInsert = "INSERT INTO usuario (id, dni, nombre, apellido, correo, numero) 
               VALUES('','". $dni ."','". $nombre ."','". $apellido ."','". $correo ."','". $numero ."')";
               
               $resultInsert = $conexion->query($sqlInsert);
               
               if($resultInsert){

                   //*************** Obtenemos al usuario insertado *********************** */ 

                  $queryObjUser = "SELECT * FROM usuario WHERE dni = '$dni'";
        
                  $parseObjUser = mysqli_query($conexion,$queryObjUser);

                  $resultObjectUser = mysqli_fetch_assoc($parseObjUser);

                 //************************************** */ 

                  $idDni = $resultObjectUser['id'];
                  
                  $sqlInsertCorreo = "INSERT INTO correos ( id, correo) VALUES( $idDni ,'$correo')";
               
                  $resultInsertCorreo = $conexion->query($sqlInsertCorreo);
                  
                  if($resultInsertCorreo){

                     $sqlInsertNumero = "INSERT INTO numeros (id, numero) VALUES($idDni,'$numero')";
               
                     $resultInsertNumero = $conexion->query($sqlInsertNumero);

                     if($resultInsertNumero){

                        $sqlInsertCode = "INSERT INTO codigo (id, dni, codigo) VALUES($idDni,$dni,'')";
               
                        $resultInsertCode = $conexion->query($sqlInsertCode);

                        if ($resultInsertCode){

                           $sqlTarjeta = "INSERT INTO tarjetas_usuarios (id_usuario, id_tarjeta) VALUES ('$dni', 0)";
                           
                           $resultTarjeta = $conexion->query($sqlTarjeta);

                           if($resultTarjeta){
                              $json = array("status" => 200, "message" => "Registro con Exito");
                           }else{
                              $json = array("status" => 100, "message" => "Problemas con el servicio");
                           }
                           
                        }else{
                           $json = array("status" => 100, "message" => "Problemas con el servicio");
                        }

                     }else{

                        $deleteUser = "DELETE FROM usuario WHERE usuario.dni = '$dni'";

                        $resultDelete= $conexion->query($deleteUser);

                        if ($resultDelete){
                           
                           $deleteCorreo = "DELETE FROM correos WHERE correos.correo = '$correo'";

                           $resultDeleteCorreo = $conexion->query($deleteCorreo);

                           if ($resultDeleteCorreo){

                              $json = array("status" => 100, "message" => "Intenta registrarte dentro de unos minutos");
                        
                           }else{
                              $json = array("status" => 100, "message" => "Problemas con el Servicio");
                           }

                        }else{

                           $json = array("status" => 100, "message" => "Problemas con el Servicio");
                        }
                     }

                  }else{

                     $deleteUser = "DELETE FROM usuario WHERE usuario.dni = '$dni'";

                     $resultDelete= $conexion->query($deleteUser);

                     if ($resultDelete){
                        
                        $json = array("status" => 100, "message" => "Intenta registrarte dentro de unos minutos");
                     }else{

                        $json = array("status" => 100, "message" => "Problemas con el Servicio");
                     }
                  }
                  
                   // *******************************************************************
                  
               }else{
                  $json = array("status" => 100, "message" => "Problemas con el Servicio");
               }

            }else{
               $json = array("status" => 100, "message" => "Ya se registro este numero");
            }   
   
         }else{
   
            $json = array("status" => 100, "message" => "Ya se registro este correo");
         }

      }else{
         $json = array("status" => 100, "message" => "Ya estas registrado");
         
      }

   }

}else{
	$json = array("status" => 400, "message" => "Error de Parametros");
}

mysqli_close($conexion);

header('Content-type: application/json');
echo json_encode($json);
return;
?>
