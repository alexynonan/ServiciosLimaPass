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

    if(trim($valor) == '' || strlen($valor) < 9){
       return false;
    }else{
       return true;
    }
 }

if ($body){

   if($_SERVER['REQUEST_METHOD'] == "POST"){

    $dni      = $body["dni"];
    $codigo   = $body["codigo"];

    if(!validateDni($dni)){
        $json = array("status" => 100, "mensaje" => "Ingrese correcto su dni");
        echo json_encode($json);
        return;
    }

    if(!is_numeric($dni)){
        $json = array("status" => 100, "mensaje" => "Ingrese correcto su dni");
        echo json_encode($json);
        return;
    }

    if(!validateFormText($codigo)){
        $json = array("status" => 100, "mensaje" => "Ingrese correcto el codigo de la Tarjeta");
        echo json_encode($json);
        return;
    }

    if(!is_numeric($codigo)){
        $json = array("status" => 100, "mensaje" => "Ingrese correcto el codigo de la Tarjeta");
        echo json_encode($json);
        return;
    }
    
    
      $querySelect = "SELECT * FROM usuario WHERE dni = $dni";
        
      $parseSelect = mysqli_query($conexion,$querySelect);

      $resultSelect = mysqli_fetch_assoc($parseSelect);
 
      if(isset($resultSelect['dni'])){

         $queryCodigo = "SELECT * FROM tarjetas_generales WHERE numero = '$codigo'";
        
         $parseCodigo = mysqli_query($conexion,$queryCodigo);

         $resultCodigo = mysqli_fetch_assoc($parseCodigo);

         if (isset($resultCodigo['numero'])){            
            
            $json = array("status" => 200, "mensaje" => "Tarjeta Verificada");
         
        }else{
   
            $json = array("status" => 100, "mensaje" => "Tu tarjeta no esta registrada o se inhabilito");
         }

      }else{
         $json = array("status" => 100, "mensaje" => "No se encontro al usuario");
         
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
