<?php
namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
class DefaultController extends AbstractController
{
   protected $pdo;
   public function __construct()
   {
    	$dsn = 'mysql:dbname=white_shadow;host=34.85.118.6';
		$servername = 'root';
		$password = '#tooradmin';
       	try {
		    $this->pdo = new \PDO($dsn, $servername, $password);
		} catch (\PDOException $e) {
		    echo 'Connection failed: ' . $e->getMessage();
		}
   }
   public function control()
   {
		if (($_POST['page'] ?? '') == 'division_add') {
			return $this->_register_division($_POST['division']);
		}
		if (($_POST['page'] ?? '') == 'register_detail') {
			return $this->_registerDetail($_POST);
		}
		$page = $_GET['page'] ?? '';
		if ($page == '') {
			return $this->_index();
		}
		if ($page == 'detail') {
			$division = $_GET['division'];
			return $this->_getDetail();
		}
		if ($page == 'control_room') {
			$auth_key = $_GET['auth_key'];
			return ($auth_key == 'saSksjdjdor9897uAKJCJSDFL12454524jdfdf2345jdll') ? $this->render('control_room.twig') : $this->render('auth.twig');
		}
		if ($page == 'auth') {
			$auth_key = $_GET['auth_key'];
			return ($auth_key == 'saSksjdjdor9897uAKJCJSDFL12454524jdfdf2345jdll') ? new Response('true') : new Response('false');
		}
		if ($page == 'division_add') {
			$auth_key = $_GET['auth_key'];
			return ($auth_key == 'saSksjdjdor9897uAKJCJSDFL12454524jdfdf2345jdll') ? $this->render('division_add.twig') : $this->render('auth.twig');
		}
		if ($page == 'detail_add_choice') {
			$auth_key = $_GET['auth_key'];
			return ($auth_key == 'saSksjdjdor9897uAKJCJSDFL12454524jdfdf2345jdll') ? $this->_getDivisionList() : $this->render('auth.twig');
		}
		if ($page == 'register_detail') {
			$auth_key = $_GET['auth_key'];
			return ($auth_key == 'saSksjdjdor9897uAKJCJSDFL12454524jdfdf2345jdll') ? $this->render('register_detail.twig') : $this->render('auth.twig');
		}
		$this->index();
   }
   private function _index()
   {
		$sql = 'SELECT division ' .
				'FROM division_list';
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute();
  		$row = $stmt->fetchAll();
		// 接続を閉じる
	    $this->pdo = null;
	    $stmt = null;
	    $result = [];
	    foreach ($row as $key => $value) {
	    	$result[] = [
	    		'division' => $value['division'],
	    	];
	    }
	    return $this->render('index.twig', ['divisionList' => $result]);
   }
   private function _getDetail()
   {
		$division = $_GET['division'];
		$sql = 'SELECT lawer_id,' .
				'lawer_name,' .
				'office,' .
				'position,' .
				'type,' .
				'ph_1,' .
				'ph_2,' .
				'ph_3,' .
				'ph_4,' .
				'ph_5,' .
				'division,' .
				'township,' .
				'town ' .
				'FROM lawer ' .
				'WHERE division=?';
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute([$division]);
  		$row = $stmt->fetchAll();
  		$result = [];
  		$index = 1;
  		foreach ($row as $key => $value) {
  			$result[] = [
	    		'index'           => $index,
	    		'lawer_id'        => $value['lawer_id'],
	    		'office'          => $value['office'],
	    		'ph_1'            => $value['ph_1'],
	    		'ph_2'            => $value['ph_2'],
	    		'ph_3'            => $value['ph_3'],
	    		'ph_4'            => $value['ph_4'],
	    		'ph_5'            => $value['ph_5'],
	    		'division'        => $value['division'],
	    		'town'            => $value['town'],
	    		'office_show'     => $value['office'] == '' ? true : false,
	    		'ph_1_show'       => $value['ph_1'] == '' ? true : false,
	    		'ph_2_show'       => $value['ph_2'] == '' ? true : false,
	    		'ph_3_show'       => $value['ph_3'] == '' ? true : false,
	    		'ph_4_show'       => $value['ph_4'] == '' ? true : false,
	    		'ph_5_show'       => $value['ph_5'] == '' ? true : false,
	    		'division_show'   => $value['division'] == '' ? true : false,
	    		'town_show'       => $value['town'] == '' ? true : false,
	    	];
	    	$index++;
  		}
		// 接続を閉じる
	    $this->pdo = null;
	    $stmt = null;
	    return $this->render('detail.twig', ['division' => $division, 'detailList' => $result]);
   }
   private function _register_division($division)
   {
		$sql = 'INSERT INTO division_list ' .
				'(division) ' .
				'VALUES (:division)';
		$stmt = $this->pdo->prepare($sql);
		$stmt->bindParam(':division', $division, \PDO::PARAM_STR);
		$result = $stmt->execute();
		// 接続を閉じる
	    $this->pdo = null;
	    $stmt = null;
	    return new Response("success");
   }
   private function _getDivisionList()
   {
		$sql = 'SELECT division ' .
				'FROM division_list';
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute();
  		$row = $stmt->fetchAll();
		// 接続を閉じる
	    $this->pdo = null;
	    $stmt = null;
	    $result = [];
	    foreach ($row as $key => $value) {
	    	$result[] = [
	    		'division' => $value['division'],
	    	];
	    }
	    return $this->render('detail_add_choice.twig', ['divisionList' => $result]);
   }
   public function _registerDetail($postData)
   {
		$lawer_name = $postData['lawer_name'];
		$office = $postData['office'];
		$position = $postData['position'];
		$type = $postData['type'];
		$ph_1 = $postData['ph_1'];
		$ph_2 = $postData['ph_2'];
		$ph_3 = $postData['ph_3'];
		$ph_4 = $postData['ph_4'];
		$ph_5 = $postData['ph_5'];
		$division = $postData['division'];
		$township = $postData['township'];
		$town = $postData['town'];
		$sql = 'INSERT INTO lawer ' .
				'(lawer_name, office, position, type, ph_1, ph_2, ph_3, ph_4, ph_5, division, township, town) ' .
				'VALUES (:lawer_name, :office, :position, :type, :ph_1, :ph_2, :ph_3, :ph_4, :ph_5, :division, :township, :town)';
		$stmt = $this->pdo->prepare($sql);
		$stmt->bindParam(':lawer_name', $lawer_name, \PDO::PARAM_STR);
		$stmt->bindParam(':office', $office, \PDO::PARAM_STR);
		$stmt->bindParam(':position', $position, \PDO::PARAM_STR);
		$stmt->bindParam(':type', $type, \PDO::PARAM_STR);
		$stmt->bindParam(':ph_1', $ph_1, \PDO::PARAM_STR);
		$stmt->bindParam(':ph_2', $ph_2, \PDO::PARAM_STR);
		$stmt->bindParam(':ph_3', $ph_3, \PDO::PARAM_STR);
		$stmt->bindParam(':ph_4', $ph_4, \PDO::PARAM_STR);
		$stmt->bindParam(':ph_5', $ph_5, \PDO::PARAM_STR);
		$stmt->bindParam(':division', $division, \PDO::PARAM_STR);
		$stmt->bindParam(':township', $township, \PDO::PARAM_STR);
		$stmt->bindParam(':town', $town, \PDO::PARAM_STR);
		$result = $stmt->execute();
		// 接続を閉じる
	    $this->pdo = null;
	    $stmt = null;
	    return new Response("success");
   }

}
?>