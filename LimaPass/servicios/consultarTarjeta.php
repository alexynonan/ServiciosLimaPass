<?php

include("cn/conexion.php");

function validaRequerido($valor){
    if(trim($valor) == '' || strlen($valor) > 12 || strlen($valor) < 8){
       return false;
    }else{
       return true;
    }
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

        $querySelect = "SELECT * FROM tarjetas_usuarios WHERE id_usuario = $usuario";
        
        $parseSelect = mysqli_query($conexion,$querySelect);

        $resultSelect = mysqli_fetch_assoc($parseSelect);

        if (isset($resultSelect['id_usuario']) && $resultSelect['id_usuario'] != ""){

            $idTipo = $resultSelect['id_usuario'];

            $queryTarjeta = "SELECT id_usuario, tarjetas.tipo , tarjetas.nombre, tarjetas.imagen FROM tarjetas_usuarios INNER JOIN tarjetas ON tarjetas.tipo = tarjetas_usuarios.id_tarjeta WHERE tarjetas_usuarios.id_usuario = $usuario ";

            $parseTarjeta = mysqli_query($conexion,$queryTarjeta);

            if($parseTarjeta){

                $result=array(); 
                $i=0;
                
                while($row = mysqli_fetch_assoc($parseTarjeta)) {
                    $result[$i]["dni"] = $row["id_usuario"];
                    $result[$i]["tipo"] = $row["tipo"];
                    $result[$i]["nombre"] = $row["nombre"];
                    $result[$i]["imagen"] = $row["imagen"];
                    $i++;
                }

                $json = array("status" => 200, "mensaje" => "Correcto", "data" => $result);

            }else{
                $json = array("status" => 100, "mensaje" => "Parece que no esta registrado");
            }
            
        }else{

            $json = array("status" => 100, "mensaje" => "Parece que no estas registrado");
        }
        
    }else{
        $json = array("status" => 100, "mensaje" => "Proeblmas con el Servicio ");  
    } 
   
}else{
	$json = array("status" => 400, "mensaje" => "Error de Parametros");
}

mysqli_close($conexion);

header('Content-type: application/json');
echo json_encode($json);
return;
?>
