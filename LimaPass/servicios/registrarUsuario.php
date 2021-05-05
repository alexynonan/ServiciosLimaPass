<?php

include("cn/conexion.php");

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

function generarCodigo($longitud) {
    $key = '';
    $pattern = '1234567890abcdefghijklmnopqrstuvwxyz';
    $max = strlen($pattern)-1;
    for($i=0;$i < $longitud;$i++) $key .= $pattern{mt_rand(0,$max)};
    return $key;
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

                $codigo = generarCodigo(4);  
                $json = array("status" => 200, "mensaje" => "Codigo generado, por favor revise su correo", "data" => array("codigo" => $codigo, "correo" => $correo));

            }else{
               $json = array("status" => 100, "mensaje" => "Ya se registro este numero");
            }   
   
         }else{
   
            $json = array("status" => 100, "mensaje" => "Ya se registro este correo");
         }

      }else{
         $json = array("status" => 100, "mensaje" => "Ya estas registrado");
         
      }

   }else{
    $json = array("status" => 400, "mensaje" => "Error de Parametros");
   }

}else{
	$json = array("status" => 400, "mensaje" => "Error de Parametros");
}

mysqli_close($conexion);

header('Content-type: application/json');
echo json_encode($json);
return;
?>
