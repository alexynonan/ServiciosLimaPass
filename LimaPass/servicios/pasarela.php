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

function validarTarjeta($num_tarjeta){
   $num_tarjeta = preg_replace('/\D|\s/', '', $num_tarjeta);
   $length = strlen($num_tarjeta);

$parity = $length % 2;
$sum=0;

for($i=0; $i<$length; $i++){
$digit = $num_tarjeta [$i] ;
if ($i%2==$parity) $digit=$digit*2;
if ($digit>9) $digit=$digit-9;
$sum=$sum+$digit;
}

return ($sum%10==0);
}

function validateFormText($valor){

   if(trim($valor) == '' || strlen($valor) < 3){
      return false;
   }else{
      return true;
   }
}

function validar_fecha ($fecha){
   return (preg_match('/^(\d\d\/\d\d\d\d){1,1}$/', $fecha));
}

function validarTelefono($numero){
   $reg = "/^[0-9]{9,9}$/";
   return preg_match($reg, $numero);
}

function filterNumberTarjeta($str) { 
      
   // Using str_replace() function  
   // to replace the word  
   $res = str_replace( array('5','1','0','4' ), '*', $str); 
     
   // Returning the result  
   return $res; 
}

if ($body){

   if($_SERVER['REQUEST_METHOD'] == "POST"){

    $dni      = $body["dni"];
    $numero   = $body['nro'];
    $mes      = $body['mes'];
    $anio     = $body['anio'];
    $ccv      = $body['ccv'];
    $tipo     = $body['tipo'];
    $monto    = $body['monto'];

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

      if(!is_numeric($numero)){
         $json = array("status" => 100, "mensaje" => "Caracteres de la tarjeta no valido");
         echo json_encode($json);
         return;
      }

      if (!validarTarjeta($numero)){
         $json = array("status" => 100, "mensaje" => "Nro de Tarjeta no valida");
         echo json_encode($json);
         return;
      }

      if(!is_numeric($mes)){
         $json = array("status" => 100, "mensaje" => "Ingrese correcto el mes");
         echo json_encode($json);
         return;
      }

      if(!is_numeric($anio)){
         $json = array("status" => 100, "mensaje" => "Ingrese correcto el año");
         echo json_encode($json);
         return;
      }

      if( intval($anio)  <= 2022  ) {
         $json = array("status" => 100, "mensaje" => "Fecha no valida" );
         echo json_encode($json);
         return;
      }

      if(!is_numeric($ccv)){
         $json = array("status" => 100, "mensaje" => "Ingrese correcto su CCV");
         echo json_encode($json);
         return;
      }

      if( intval(strlen($ccv))  < 3 || intval(strlen($ccv)) > 4) {
         $json = array("status" => 100, "mensaje" => "Ingrese correctamente el CCV" );
         echo json_encode($json);
         return;
      }

      if(!is_numeric($tipo)){
         $json = array("status" => 100, "mensaje" => "Ingrese correcto el tipo");
         echo json_encode($json);
         return;
      }

      if(intval($tipo) > 4 ){
         $json = array("status" => 100, "mensaje" => "No se encontro la tarjeta");
         echo json_encode($json);
         return;
      }

      if(!is_numeric($monto)){
         $json = array("status" => 100, "mensaje" => "Ingrese correcto el monto");
         echo json_encode($json);
         return;
      }
       if ($monto < "1" || $monto == "0"){
         $json = array("status" => 100, "mensaje" => "Monto no valido");
         echo json_encode($json);
         return;
       }

      $sqlInsert = "INSERT INTO tarjetas_recarga (`id`, `id_usuario`, `tipo_tarjeta`, `recarga`, `tipo_transac`, `fecha`) VALUES
       (NULL, $dni , $tipo, $monto, '0', current_timestamp())";
      
      $resultInsert = $conexion->query($sqlInsert);
               
      if($resultInsert){
         
         $vaucher = "SELECT * FROM tarjetas_recarga WHERE id_usuario = $dni and tipo_tarjeta = $tipo ORDER BY id DESC LIMIT 1";
         $resultVoucher =  mysqli_query($conexion,$vaucher);
        
         if ($resultVoucher){ 

            $row = mysqli_fetch_assoc($resultVoucher);

            $row["nro_recibo"] = $row["id"];
            $row["tarjeta_enmascarada"] = filterNumberTarjeta($numero);
            $row["monto"] = $monto;
            $querySelect = "SELECT * FROM usuario WHERE dni = $dni";
     
            $parseSelect = mysqli_query($conexion,$querySelect);

            if($parseSelect){

               $resultSelect = mysqli_fetch_assoc($parseSelect);

               $row["apellido"] = $resultSelect["apellido"];
               $row["nombre"] = $resultSelect["nombre"];
               $row["correo"] = $resultSelect["correo"];
               $row["celular"] = $resultSelect["numero"];
               
               $json = array("status" => 200, "mensaje" => "Proceso Verificado ", "data" => $row );

            }else{
               $json = array("status" => 100, "mensaje" => "Problemas con la conexion");
            }
            
         }else{
            $json = array("status" => 100, "mensaje" => "Problemas con la conexion");
         }

      }else{
         $json = array("status" => 100, "mensaje" => "Problemas con la conexion");
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