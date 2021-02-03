<?php

include_once('cnx.php');

function validaRequerido($valor){
    if (trim($valor) == '' || strlen($valor) > 13 || strlen($valor) < 8) {
       return false;
    } else {
       return true;
    }
 }

$body = json_decode(file_get_contents('php://input'),true);

if ($body){

    if ($_SERVER['REQUEST_METHOD'] == "POST") {

        $usuario = $body["usuario"];
        
        if (!validaRequerido($usuario)) {
    
            $json = array("status" => 100, "mensaje" => "Ingrese correcto el parametro del usuario");
            echo json_encode($json);
            return;
        }

        if(!is_numeric ($usuario)){
            $json = array("status" => 100, "mensaje" => "Ingrese correcto el parametro del usuario");
            echo json_encode($json);
            return;
         }
    
        $query = "SELECT * FROM codigo WHERE dni = $usuario";
        
        $qur = mysqli_query($conexion,$query);
   
        $row = mysqli_fetch_assoc($qur);
        
        if (isset($row['dni'])){

            if (isset($row['codigo'])  && $row['codigo'] != ""){
                
                $codigo = $row['codigo'];

                $objCodigo = array("codigo" => $codigo);

                $json = array("status" => 200, "message" => "Correcto", "data" => $objCodigo );
    
            }else{

                $json = array("status" => 100, "mensaje" => "Codigo no registrado");
                
            }
    
        }else{
    
            $json = array("status" => 100, "mensaje" => "Dni no registrado");
            
        }   
    } 
      
} else {
   $json = array("status" => 400, "message" => "Error de Parametros");
}

mysqli_close($conexion);

header('Content-type: application/json');
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

echo json_encode($json);
return;
?>