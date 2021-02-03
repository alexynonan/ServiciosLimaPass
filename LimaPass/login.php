<?php

include_once('cnx.php');

function validaRequerido($valor){
    if(trim($valor) == '' || strlen($valor) > 13 || strlen($valor) < 8){
       return false;
    }else{
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

        $usuario = $body["dni"];
        
        if (!validaRequerido($usuario)) {

            $json = array("status" => 100, "mensaje" => "Ingrese correcto su numero de documento");
            echo json_encode($json);
            return;
        }

        if(!is_numeric ($usuario)){
            $json = array("status" => 100, "mensaje" => "Ingrese correcto su numero de documento");
            echo json_encode($json);
            return;
         }

        $querySelect = "SELECT * FROM usuario WHERE dni = $usuario";
        
        $parseSelect = mysqli_query($conexion,$querySelect);

        $resultSelect = mysqli_fetch_assoc($parseSelect);
        
        if (isset($resultSelect['dni']) && $resultSelect['dni'] != "" ){

            $codigo = generarCodigo(4);
            
            $queryUpdate = "UPDATE codigo SET codigo = '$codigo' WHERE dni = $usuario";
        
            $parseUpdate = mysqli_query($conexion,$queryUpdate);
        
            if ($parseUpdate){             

                $resultSelect['codigo'] = $codigo;

                $json = array("status" => 200, "message" => "Correcto", "data" => $resultSelect );

            }else{

                $json = array("status" => 100, "mensaje" => "Codigo no registrado");
            }

        }else{

            $json = array("status" => 100, "mensaje" => "Parece que no estas registrado");
        }
        
    }else{
        $json = array("status" => 100, "message" => "Proeblmas con el Servicio ");  
    } 
   
}else{
	$json = array("status" => 400, "message" => "Error de Parametros");
}

mysqli_close($conexion);

header('Content-type: application/json');
echo json_encode($json);
return;
?>
