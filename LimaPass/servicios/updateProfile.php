<?php

include("cn/conexion.php");

function validaRequerido($valor){
    if(trim($valor) == '' || strlen($valor) > 13 || strlen($valor) < 8){
       return false;
    }else{
       return true;
    }
}

function validar_email($email){
    return (preg_match('/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/', $email)); 
}

function validarTelefono($numero){
    $reg = "/^[0-9]{9,9}$/";
    return preg_match($reg, $numero);
}

$body = json_decode(file_get_contents('php://input'),true);

if ($body){
    
    if ($_SERVER['REQUEST_METHOD'] == "POST") {

        $usuario    = $body["dni"];
        $email      = $body["correo"];
        $number     = $body["numero"];

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
        if (!validar_email($email)){
            $json = array("status" => 100, "mensaje" => "Ingrese correcto su correo");
            echo json_encode($json);
            return;
        }
   
        if(!validarTelefono($number)){
            $json = array("status" => 100, "mensaje" => "Ingrese correcto su numero");
            echo json_encode($json);
            return;
        } 

        $queryUser = "SELECT * FROM usuario WHERE dni = $usuario";
        
        $parseUser = mysqli_query($conexion,$queryUser);

        $resultUser = mysqli_fetch_assoc($parseUser);
        
        if (isset($resultUser['dni']) && $resultUser['dni'] != "" ){

            $queryUpdate = "UPDATE usuario SET correo = '$email', numero = '$number' WHERE usuario.dni = $usuario";

            $parseUpdate = mysqli_query($conexion,$queryUpdate);
        
            if ($parseUpdate){             

                $idCorreo = $resultUser['id'];
                $queryEmail = "UPDATE correos SET correo = '$email' WHERE correos.id = $idCorreo";
                $parseEmail = mysqli_query($conexion,$queryEmail);
            
                if ($parseEmail){ 

                    $idNumero = $resultUser['id'];
                    $queryNumber = "UPDATE numeros SET numero = $number WHERE numeros.id = $idNumero";
                    $parseNumber = mysqli_query($conexion,$queryNumber);
                
                    if ($parseNumber){ 

                        $json = array("status" => 200, "mensaje" => "Actualización correcta");

                    }else{
                        $json = array("status" => 100, "mensaje" => "Problemas con el update del numero");
                    }

                }else{
                    $json = array("status" => 100, "mensaje" => "Problemas con el update del correo");
                }

            }else{

                $json = array("status" => 100, "mensaje" => "Problemas con el update Usuario");
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
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
echo json_encode($json);
return;
?>
