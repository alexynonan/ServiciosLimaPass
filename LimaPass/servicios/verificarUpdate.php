<?php

include("cn/conexion.php");

function validaRequerido($valor){
    if (trim($valor) == '' || strlen($valor) > 13 || strlen($valor) < 8) {
       return false;
    } else {
       return true;
    }
 }

 function generarCodigo($longitud) {
    $key = '';
    $pattern = '1234567890abcdefghijklmnopqrstuvwxyz';
    $max = strlen($pattern)-1;
    for($i=0;$i < $longitud;$i++) $key .= $pattern{mt_rand(0,$max)};
    return $key;
 } 

$body = json_decode(file_get_contents('php://input'),true);

if ($body){

    if ($_SERVER['REQUEST_METHOD'] == "POST") {

        $usuario = $body["usuario"];
        $tipo = $body["tipo"];
        
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

         if(!is_numeric ($tipo)){
            $json = array("status" => 100, "mensaje" => "Ingrese correcto el tipo");
            echo json_encode($json);
            return;
         }

        if ($tipo == "1"){

            $codigo = generarCodigo(4);
            
            $queryUpdate = "UPDATE codigo SET codigo = '$codigo' WHERE dni = $usuario";
        
            $parseUpdate = mysqli_query($conexion,$queryUpdate);
        
            if ($parseUpdate){             

                $resultSelect['codigo'] = $codigo;

                $json = array("status" => 200, "message" => "Correcto", "data" => $resultSelect );

            }else{

                $json = array("status" => 100, "mensaje" => "Codigo no registrado");
            }

        }else if ($tipo == "0"){
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
        }else{
            $json = array("status" => 100, "mensaje" => "Ingrese correcto el tipo");
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