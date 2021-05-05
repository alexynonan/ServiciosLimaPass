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
    if(trim($valor) == '' || strlen($valor) > 1){
       return false;
    }else{
       return true;
    }
 }

if ($body){

   if($_SERVER['REQUEST_METHOD'] == "POST"){

      $dni      = $body["dni"];
      $tipo     = $body["tipo"];

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

      if(!validateFormText($tipo)){
         $json = array("status" => 100, "mensaje" => "Ingrese el tipo de tarjeta Correcto");
         echo json_encode($json);
         return;
      }

      if(!is_numeric($tipo)){
         $json = array("status" => 100, "mensaje" => "Ingrese el tipo de tarjeta Correcto");
         echo json_encode($json);
         return;
      }
    
      $querySelect = "SELECT * FROM usuario WHERE dni = $dni";
        
      $parseSelect = mysqli_query($conexion,$querySelect);

      $resultSelect = mysqli_fetch_assoc($parseSelect);
 
      if(isset($resultSelect['dni'])){

         $queryTarjeta = "SELECT * FROM tarjetas_recarga WHERE id_usuario = $dni and tipo_tarjeta = $tipo ORDER BY tarjetas_recarga.fecha DESC";
        
         $parseTarjeta = mysqli_query($conexion,$queryTarjeta);


         if ($parseTarjeta){            

            $result=array();
             
            $sumaTotal = 0;
            $i=0;

            //Tipo 0 Recarga
            //Tipo 1 Descuento

            while($row = mysqli_fetch_assoc($parseTarjeta)) {

                  if ($row["tipo_transac"] == 0 ){
      
                     $result[$i]["id"] = $row["id"];
                     $result[$i]["saldo"] = $row["recarga"];
                     $result[$i]["transac"] =  $row["tipo_transac"];
                     $result[$i]["fecha"] = $row["fecha"];
   
                     $sumaTotal += $row["recarga"];

                     $i++;         
                  }
            }

            

            $data = array("saldo" => $sumaTotal, "movimientos" => $result);

            $json = array("status" => 200, "mensaje" => "Correcto", "data" => $data);
         
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
