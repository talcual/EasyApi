<?php


class orm{

public $enlace;
public $results,$data,$qr;

public function orm($enlace){
 $this->enlace = $enlace;
}

public function query($sql_data = ''){
	$sql = $sql_data;
	//$this->logSQL($sql.' | ');
        
        try{
            $this->results = $data = mysqli_query($this->enlace,$sql);
            if($this->results){
                    while($rpt = mysqli_fetch_assoc($data)){
                        $datainf[] = $rpt;
                    }
            $this->data = $datainf;
            return $datainf;
            }else{
            echo mysqli_error($this->enlace);	
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
}

public function call($sqlStatment = ''){
    mysqli_query($this->enlace,$sql) or die(mysqli_error());
}

public function logSQL($d){
    file_put_contents('sql_log.txt',$d,FILE_APPEND);
}


public function getData(){
    return $this->sql;
}

public function getFields($tabla = '', $campos = '*'){

    $sql = 'SELECT '.$campos.' FROM '.$tabla.' LIMIT 1';
    $sql = mysqli_query($this->enlace,$sql) or die('error:'.mysqli_error());

    while($field = $sql->fetch_field()){
        $f[] = array("data" => $field->name);
    } 			

    return json_encode($f);
}

public function numRows(){
    return mysqli_num_rows($this->results);
}

public function select($data = array(),$like = false, $debugg = false){
 if($data['tabla'] == ''){
    throw new Exception('No se ha encontrado valor para la tabla.');
 }
 $tabla = $data['tabla'];
 	

 	if($data['campos'] == 'all'){
 	   $campos = ' * ';
 	}else{
 	   foreach($data['campos'] as $key => $value){
	    $campos .= ($key != (count($data['campos'])-1)) ? $value.',' : $value ;
	   }
 	}
	 

	 if($data['where'] != ''){
		 $where = ' WHERE ';
		 foreach($data['where'] as $key => $value){
		 	if($like){
		 	  $where .= $key." like '%".$value."%' AND ";
		 	}else{
		 	  $where .= $key.'='.$value;
		 	}
		 }
	 	if($like){
	 	  $where = substr($where, 0, (strlen($where)-5));
	 	}
	 }else{
		$where = '';
	 }
	 
	 if($data['limit'] != ''){
		$limit = ' LIMIT '.$data['limit']['inferior'].','.$data['limit']['superior'];
	 }else{
		$limit = '';
	 }

	 if($data['order'] != '') {
	 	$order = ' ORDER BY '.$data['order']['campo'].' '.$data['order']['orientacion'];
	 }else{
	 	$order = '';
	 }
 	
	 if($data['join'] != '') {
	 	$join = ' '.$data['join']['tipo'].' JOIN '.$data['join']['tabla_join'].' ON '.$tabla.'.'.$data['join']['campo_t1'].' = '.$data['join']['tabla_join'].'.'.$data['join']['campo_t2'];
	 }else{
	 	$join = '';
	 } 	 
  
	$sql = 'SELECT '.$campos.' FROM '.$tabla.$join.$where.$limit.$order;

	if($debugg){
 		//file_put_contents('trace_data.txt', $sql,FILE_APPEND);
	}

	$data = mysqli_query($this->enlace,$sql) or die('Error : '.mysqli_error().' en la siguiente consulta => '.$sql);

	 while($rpt = mysqli_fetch_assoc($data)){
		$datainf[] = $rpt;
	 }
	 
return $datainf;
}


public function genQuery($data = array(),$like = false, $debugg = false){
  if($data['tabla'] == ''){
 	throw new Exception('No se ha encontrado valor para la tabla.');
 }
 $tabla = $data['tabla'];
 	

 	if($data['campos'] == 'all'){
 	   $campos = ' * ';
 	}else{
 	   foreach($data['campos'] as $key => $value){
	    $campos .= ($key != (count($data['campos'])-1)) ? $value.',' : $value ;
	   }
 	}
	 

	 if($data['where'] != ''){
		 $where = ' WHERE ';
		 foreach($data['where'] as $key => $value){
		 	if($like){
		 	  $where .= $key." like '%".$value."%' AND ";
		 	}else{
		 	  $where .= $key.'='.$value;
		 	}
		 }
	 	if($like){
	 	  $where = substr($where, 0, (strlen($where)-5));
	 	}
	 }else{
		$where = '';
	 }
	 
	 if($data['limit'] != ''){
		$limit = ' LIMIT '.$data['limit']['inferior'].','.$data['limit']['superior'];
	 }else{
		$limit = '';
	 }

	 if($data['order'] != '') {
	 	$order = ' ORDER BY '.$data['order']['campo'].' '.$data['order']['orientacion'];
	 }else{
	 	$order = '';
	 }
 	
	 if($data['join'] != '') {
	 	$join = ' '.$data['join']['tipo'].' JOIN '.$data['join']['tabla_join'].' ON '.$tabla.'.'.$data['join']['campo_t1'].' = '.$data['join']['tabla_join'].'.'.$data['join']['campo_t2'];
	 }else{
	 	$join = '';
	 } 	 
  
	$sql = 'SELECT '.$campos.' FROM '.$tabla.$join.$where.$limit.$order;

	return $sql;
}


public function selectMax($tabla, $campo_id = 'ID'){
 	$sql = 'SELECT CASE WHEN MAX('.$campo_id.') IS NULL THEN 1 ELSE MAX('.$campo_id.')+1 END as ultimo FROM '.$tabla;
	$data = mysqli_query($this->enlace,$sql);
	 $driver = mysqli_fetch_array($data);
	 return $driver['ULTIMO'];
}

public function lastID($campo,$tabla){
  $sql = "SELECT MAX(".$campo.") as id FROM ".$tabla;
  $info = mysqli_query($this->enlace, $sql) or die($sql);
  $infoc = mysqli_result($info, $campo);
  return $infoc;
}

public function insert($data = array()){
 if($data['tabla'] == ''){
 	throw new Exception('El valor de la Tabla esta en Blanco.');
 }
 $tabla = $data['tabla'];
 $i = 1;
 $maxr = count($data['reg']);
 foreach($data['reg'] as $key => $data){
    $campos .= ($i != $maxr) ? $key.',' : $key ;
	
	if($i != $maxr){
		if(is_numeric($data)){
			$values .= $data.",";
		}else{
		    $values .= "'".$data."',";
		}
	}else{
		if(is_numeric($data)){
			$values .= $data;
		}else{
		    $values .= "'".$data."'";
		}
	}

	++$i;
 }
 
 $sql = 'INSERT INTO '.$tabla.' ('.$campos.') values ('.$values.')';
 $data = mysqli_query($this->enlace,$sql) or die('Error : '.$sql.' '.mysqli_error($this->enlace)); 
 return $data;
}

public function updtae($data = array()){
  $tabla = $data['tabla'];

	 $i = 1;
	 $maxr = count($data['reg']);
	 foreach($data['reg'] as $key => $datax){
		$campos .= ($i != $maxr) ? $key.'='.(($datax == '') ? 'NULL' : "'".$datax."'" ).',' : $key.'='.(($datax == '') ? 'NULL' : "'".$datax."'" ) ;
		//$values .= ($i != $maxr) ? "'".$data."'," : "'".$data."'" ;
		++$i;
	 }
	 
     if($data['where'] != ''){
		 $where = ' WHERE ';
		 foreach($data['where'] as $key => $value){
			$where .= $key.'='.$value;
		 }
	 }else{
		$where = '';
	 }
	 
	 if($data['limit'] != ''){
		//$limit = ' LIMIT '.$data['limit']['inferior'].','.$data['limit']['superior'];
	 }else{
		$limit = '';
	 }
	 
	  $sql = 'UPDATE '.$tabla.' SET '.$campos.' '.$where;
      $data = mysqli_query($this->enlace,$sql) or die($sql); 
	 
	 if($data){
	 	return true;
	 }
}

/*
toTable Integration Method
*/

public function toTable($psql, $settings = array()){
 try {
 	if(is_array($psql)){
	 $sql = $this->genQuery($psql,false,false);	
	}else{
	 $sql = $psql;	
	}

	$t = '';
	$export = mysqli_query($this->enlace,$sql) or die('error:'.mysqli_error($this->enlace));
	$fields = mysqli_num_fields($export);
		if (mysqli_num_rows($export)>0){	
			$t .= "<div class='table-responsive'><table align='center' class='table table-hover ".$settings['class']."'>";
			$t.= "<thead><tr>";

				while($campo = mysqli_fetch_field($export)){
					$t.= "<th>".$campo->name."</th>";
				}

			$t.= "</tr></thead><tbody>";

			$cont=1;
			while ($res = mysqli_fetch_row($export)){
				if($cont % 2){ $t.= "<tr>"; }else{ $t.= "<tr>"; }

				for ($k = 0; $k <= $fields-1; $k++){
					$t.= "<td>".$res[$k]."</td>";
				}

				$cont+=1;	

				$t.= "</tr>";
			}
			$t.= '</tbody></table></div>';
		}else
		$t.= 'E00 : No se encontró registros';	

		return $t;	 

	 } catch (Exception $e) {
 		echo $e;
 	 }
	}
 
}