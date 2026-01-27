<?php


class mixing{
 public $datamix = '', $filemix = '', $html = '';
	
 public function mixing(){

 }

 public function setMix($mix = array()){
 	$this->datamix = $mix['data']; 
 	$this->filemix = $mix['file'];
 }

 public function setHtml($html){
    $this->html = $html;
 }

 public static function getHtml($plantilla){
 	$html = file_get_contents($plantilla);
 	return $html;
 }

 public static function keys($datamix){
 	foreach ($datamix as $key => $value) {
 		$keys[] = '{'.$key.'}';
 	}
 	return $keys;
 }

 public  function mixer(){
    $html = utf8_decode(self::getHtml($this->filemix));
    $keyname = self::keys($this->datamix);
    return str_replace($keyname, $this->datamix, $html);
 }


}


/*$d = array(
		'data' => 
		     array(
		     	'nombre' => 'Katina',
		     	'usuario' => 'ltoscano', 
		     	'pass'=>'123456',
		     	'telefono' => '3796691'
		     ), 
		'file' =>'name.html'
	);


$mix = new mixing();
$mix->setMix($d);
echo nl2br($mix->mixer());*/


?>