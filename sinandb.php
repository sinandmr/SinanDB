<?php
class SinanDB {

	/* Database bağlantısı örneği
	$vt = new SinanDB('localhost','sinan','utf8','root','');
	*/

	# PDO işlemlerini yapmak için db bağlantısını sakladığımız değişken.
    private $database;

	# Class çağırıldığında ilk iş olarak database bağlantısını yapan constructor methodu.
    public function __construct($dbhost = 'localhost', $dbname = '', $charset = 'utf8', $dbuser = 'root', $dbpass = ''){
		try {
            $this->database = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset='.$charset,$dbuser,$dbpass);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

	# Sadece tablo ve değişmesini istediğimiz sütun adlarını girerek update işlemini yapabildiğimiz method.
	public function update($table,$c1,$c2,$c3,$where,$value){
		$sql = "UPDATE ".$table." SET ".$c1[0]."=?";
		$execute = array($c1[1],$value);

		if($c2 != null){
			$sql .= ','.$c2[0].'=?';
			$execute[1] = $c2[1];
			$execute[2] = $value;
		}
		if($c3 != null){
			$sql .= ','.$c3[0].'=?';
			$execute[2] = $c3[1];
			$execute[3] = $value;
		}
		$update = $this->database->prepare($sql." WHERE ".$where."=?");
		$update->execute($execute);
		echo $update->rowCount().' tane satır güncellendi.';
		/* Kullanım Örneği
		$vt->update('mesajlar',['isim','isimlan'],['eposta','post1alan@hotmail.com'],['mesaj','burası mesaj'],'id',11);
		*/
	}

	# Tek satırı seçmemizi sağlayan method.
	public function select($table,$columnName='id',$id){
		$get = $this->database->prepare('SELECT * FROM '.$table.' WHERE '.$columnName.'=?');
		$get->execute(array($id));
		return $get->fetch(PDO::FETCH_OBJ);
		/* Kullanım Örneği
		$tek = $vt->select('yazilar','yazi_id',58);
		echo $tek['yazi_sef'];
		*/
	}

	# Tablodaki tüm satırları seçmemizi sağlayan method.
	public function selectAll($table){
		$get = $this->database->prepare('SELECT * FROM '.$table);
		$get->execute();
		return $get->fetchAll(PDO::FETCH_OBJ);
		/* Kullanım Örneği
		$cek = $vt->selectAll('yazilar');
		foreach($cek as $c){
			echo $c['yazi_baslik'].'<br>';
		}
		*/
	}

	# Seçili satırı silmemizi sağlayan method.
	public function delete($table,$where,$value){
		$delete = $this->database->prepare('DELETE FROM '.$table.' WHERE '.$where.'=?');
		$delete->execute(array($value));
		/* Kullanım Örneği
		$vt->delete('uye','id','10');
		*/
	}

	# Tüm tabloyu silmemizi sağlayan method.
	public function deleteAll($table){
		$deleteAll = $this->database->prepare('DELETE FROM '.$table);
		$deleteAll->execute();
		/* Kullanım Örneği
 		$vt->deleteAll('uye');
		*/
	}
	
	# Databaseye veri ekleme işlemini yapan method.
	public function insert($sql,$arr){
		$insert = $this->database->prepare($sql);
		$insert->execute($arr);
	}

	# Databaseye eklenen son verinin id değerini döndüren method.
	public function lastId(){
		return $this->database->lastInsertId();
	}
	
	# Database bağlantısını sonlandıran method.
	public function end(){
		$this->database = null;
	}


}