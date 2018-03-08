<?php 

namespace Hcode\Model;

use \Hcode\DB\Sql;
use \Hcode\Model;
use \Hcode\Mailer;

class Product extends Model {

	public static function listAll()
	{

		$sql = new Sql();

		return $sql->select("SELECT * FROM tb_products ORDER BY desproduct");
	}

	public static function checkList($list)
	{

		foreach ($list as &$row) {
			
			$p = new Product();
			$p->setData($row);
			$row = $p->getValues();
		}

		return $list;
	}

	public function save()
	{

		$sql = new Sql();

		$results = $sql->select("CALL sp_products_save(:idproduct, :desproduct, :vlprice, :vlwidth, :vlheight, :vllength, :vlweight, :desurl, :descriptup, :descriptdown)", array(
			":idproduct"=>$this->getidproduct(),
			":desproduct"=>$this->getdesproduct(),
			":vlprice"=>$this->getvlprice(),
			":vlwidth"=>$this->getvlwidth(),
			":vlheight"=>$this->getvlheight(),
			":vllength"=>$this->getvllength(),
			":vlweight"=>$this->getvlweight(),
			":desurl"=>$this->getdesurl(),
			":descriptup"=>$this->getdescriptup(),
			":descriptdown"=>$this->getdescriptdown()
		));

		$this->setData($results[0]);

	}

	public function get($idproduct)
	{

		$sql = new Sql();

		$results = $sql->select("SELECT * FROM tb_products WHERE idproduct = :idproduct", [
			':idproduct'=>$idproduct
		]);

		$this->setData($results[0]);
	}

	public function delete()
	{

		$sql = new Sql();

		$sql->query("DELETE FROM tb_products WHERE idproduct = :idproduct", [
			':idproduct'=>$this->getidproduct()
		]);
	}

	public function checkPhoto()
	{

		if(file_exists(
			$_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 
			"res" . DIRECTORY_SEPARATOR . 
			"site" . DIRECTORY_SEPARATOR . 
			"img" . DIRECTORY_SEPARATOR . 
			"products" . DIRECTORY_SEPARATOR . 
			$this->getidproduct() . ".jpg"
			)) {

			$url = "/res/site/img/products/" . $this->getidproduct() . ".jpg";
		} else {

			$url = "/res/site/img/product.jpg";
		}

		return $this->setdesphoto($url);
	}

	public function getValues()
	{
		$this->checkPhoto();

		$values = parent::getValues();

		return $values;
	}

	public function setPhoto($file)
	{
		if(empty($file['name'])){
			$this->checkPhoto();
		}else{

		$extension = explode('.', $file['name']);
		$extension = end($extension);

		switch ($extension)
		{
			case "jpg":
			case "jpeg":
			$image = imagecreatefromjpeg($file["tmp_name"]);
			break;

			case "gif":
			$image = imagecreatefromgif($file["tmp_name"]);
			break;

			case "png":
			$image = imagecreatefrompng($file["tmp_name"]);
			break;
		}

		$dist = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 
			"res" . DIRECTORY_SEPARATOR . "site" . DIRECTORY_SEPARATOR . 
			"img" . DIRECTORY_SEPARATOR . "products" . DIRECTORY_SEPARATOR . 
			$this->getidproduct() . ".jpg";

		imagejpeg($image, $dist);

		imagedestroy($image);

		$this->checkPhoto();

		}
	}

	public function getFromURL($desurl)
	{

		$sql = new Sql();

		$rows = $sql->select("SELECT * FROM tb_products WHERE desurl = :desurl LIMIT 1", [
			':desurl'=>$desurl
		]);

		$this->setData($rows[0]);
	}

	public function getCategories()
	{

		$sql = new Sql();

		return $sql->select("
			SELECT * FROM tb_categories a INNER JOIN tb_productscategories b ON a.idcategory = b.idcategory WHERE b.idproduct = :idproduct
		", [
			':idproduct'=>$this->getidproduct()
		]);
	}

	public static function getPage($page = 1, $itemsPerPage = 10)
	{
		$start = ($page - 1) * $itemsPerPage;

		$sql = new Sql();

		$results = $sql->select("
			SELECT SQL_CALC_FOUND_ROWS * 
			FROM tb_products 
			ORDER BY desproduct 
			LIMIT $start, $itemsPerPage;
		");

		$resultTotal = $sql->select("SELECT FOUND_ROWS() AS nrtotal;");

		return [
			'data'=>$results,
			'total'=>(int)$resultTotal[0]["nrtotal"],
			'pages'=>ceil($resultTotal[0]["nrtotal"] / $itemsPerPage)
		];
		
	}
	

	public static function getPageSearch($search, $page = 1, $itemsPerPage = 10)
	{
		$start = ($page - 1) * $itemsPerPage;

		$sql = new Sql();

		$results = $sql->select("
			SELECT SQL_CALC_FOUND_ROWS * 
			FROM tb_products
			WHERE desproduct LIKE :search OR idproduct LIKE :search
			ORDER BY desproduct
			LIMIT $start, $itemsPerPage;
		", [
			':search'=>'%'.$search.'%'
		]);

		$resultTotal = $sql->select("SELECT FOUND_ROWS() AS nrtotal;");

		return [
			'data'=>$results,
			'total'=>(int)$resultTotal[0]["nrtotal"],
			'pages'=>ceil($resultTotal[0]["nrtotal"] / $itemsPerPage)
		];
		
	}

	public function getBestSellers()
	{

		$sql = new Sql();

		return $sql->select("
		SELECT b.idproduct, b.desproduct, COUNT(*) AS nrqtd
		FROM tb_cartsproducts a 
		INNER JOIN tb_products b ON a.idproduct = b.idproduct 
		GROUP BY b.idproduct
		ORDER BY nrqtd desc");
	}

	public function getBestSellers1()
	{

		$sql = new Sql();

		return $sql->select("
		SELECT b.idproduct, b.desproduct, COUNT(*) AS nrqtd
		FROM tb_cartsproducts a 
		INNER JOIN tb_products b ON a.idproduct = b.idproduct 
		GROUP BY b.idproduct
		ORDER BY nrqtd desc LIMIT 1");
	}

	public function getProductsCategory()
	{

		$sql = new Sql();

		return $sql->select("
		SELECT d.descategory, b.desproduct, COUNT(*) AS nrqtd
		FROM tb_cartsproducts a 
		INNER JOIN tb_products b USING(idproduct)
        INNER JOIN tb_productscategories c
        ON b.idproduct = c.idproduct
        INNER JOIN tb_categories d using (idcategory)
        WHERE idcategory = d.idcategory
		GROUP BY a.idproduct
		ORDER BY idcategory, nrqtd desc");
	}

	public function getProductsCategory1()
	{

		$sql = new Sql();

		return $sql->select("
		SELECT d.descategory, b.desproduct, COUNT(*) AS nrqtd
		FROM tb_cartsproducts a 
		INNER JOIN tb_products b USING(idproduct)
        INNER JOIN tb_productscategories c
        ON b.idproduct = c.idproduct
        INNER JOIN tb_categories d using (idcategory)
        WHERE idcategory = d.idcategory
		GROUP BY a.idproduct
		ORDER BY idcategory, nrqtd desc LIMIT 1");
	}

}

 ?>