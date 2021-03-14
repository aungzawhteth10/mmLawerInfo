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
		if (($_POST['page'] ?? '') == 'division_delete') {
			return $this->_deleteDivisionData($_POST);
		}
		if (($_POST['page'] ?? '') == 'lawyer_delete') {
			return $this->_deleteLawyerData($_POST);
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
			$auth_key = $_GET['auth_key'] ?? '';
			return ($auth_key == 'saSksjdjdor9897uAKJCJSDFL12454524jdfdf2345jdll') ? $this->_getControlRoom() : $this->render('auth.twig');
		}
		if ($page == 'auth') {
			$auth_key = $_GET['auth_key'] ?? '';
			return ($auth_key == 'saSksjdjdor9897uAKJCJSDFL12454524jdfdf2345jdll') ? new Response('true') : new Response('false');
		}
		if ($page == 'division_add') {
			$auth_key = $_GET['auth_key'] ?? '';
			return ($auth_key == 'saSksjdjdor9897uAKJCJSDFL12454524jdfdf2345jdll') ? $this->render('division_add.twig') : $this->render('auth.twig');
		}
		if ($page == 'detail_list') {
			$auth_key = $_GET['auth_key'] ?? '';
			return ($auth_key == 'saSksjdjdor9897uAKJCJSDFL12454524jdfdf2345jdll') ? $this->_getToDetailList() : $this->render('auth.twig');
		}
		if ($page == 'register_detail') {
			$auth_key = $_GET['auth_key'] ?? '';
			return ($auth_key == 'saSksjdjdor9897uAKJCJSDFL12454524jdfdf2345jdll') ? $this->render('register_detail.twig') : $this->render('auth.twig');
		}
		$this->_index();
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
   private function _getToDetailList()
   {
		$division = $_GET['division'];
		$sql = 'SELECT lawyer_id,' .
				'lawyer_name,' .
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
				'FROM lawyer ' .
				'WHERE division=?';
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute([$division]);
  		$row = $stmt->fetchAll();
  		$result = [];
  		$index = 1;
  		foreach ($row as $key => $value) {
  			$result[] = [
	    		'index'            => $index,
	    		'lawyer_id'        => $value['lawyer_id'],
	    		'lawyer_name'      => $value['lawyer_name'],
	    		'position'         => $value['position'],
	    		'type'             => $value['type'],
	    		'office'           => $value['office'],
	    		'ph_1'             => $value['ph_1'],
	    		'ph_2'             => $value['ph_2'],
	    		'ph_3'             => $value['ph_3'],
	    		'ph_4'             => $value['ph_4'],
	    		'ph_5'             => $value['ph_5'],
	    		'division'         => $value['division'],
	    		'township'         => $value['township'],
	    		'town'             => $value['town'],
	    		'lawyer_name_show' => $value['lawyer_name'] == '' ? true : false,
	    		'position_show'    => $value['position'] == '' ? true : false,
	    		'type_show'        => $value['type'] == '' ? true : false,
	    		'office_show'      => $value['office'] == '' ? true : false,
	    		'ph_1_show'        => $value['ph_1'] == '' ? true : false,
	    		'ph_2_show'        => $value['ph_2'] == '' ? true : false,
	    		'ph_3_show'        => $value['ph_3'] == '' ? true : false,
	    		'ph_4_show'        => $value['ph_4'] == '' ? true : false,
	    		'ph_5_show'        => $value['ph_5'] == '' ? true : false,
	    		'division_show'    => $value['division'] == '' ? true : false,
	    		'township_show'    => $value['township'] == '' ? true : false,
	    		'town_show'        => $value['town'] == '' ? true : false,
	    	];
	    	$index++;
  		}
		// 接続を閉じる
	    $this->pdo = null;
	    $stmt = null;
	    return $this->render('detail_list.twig', ['division' => $division, 'detailList' => $result]);
   }
   private function _getDetail()
   {
		$division = $_GET['division'];
		$sql = 'SELECT lawyer_id,' .
				'lawyer_name,' .
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
				'FROM lawyer ' .
				'WHERE division=?';
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute([$division]);
  		$row = $stmt->fetchAll();
  		$result = [];
  		$index = 1;
  		foreach ($row as $key => $value) {
  			$result[] = [
	    		'index'           => $index,
	    		'lawyer_id'        => $value['lawyer_id'],
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
   		$division_length = strlen($division);
   		for ($i = strlen($division)-1; $i >= 0; $i--) {
   			if ($division{$i} == ' ') {
   				$division = mb_substr($division, 0, $i);
   			} else {
   				break;
   			}
   		}
   		if (strlen($division) == 0) {
   			return new Response('fail');
   		}
   		$sql = 'SELECT division ' .
				'FROM division_list where division=?';
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute([$division]);
  		$row = $stmt->fetchAll();
		if (count($row) != 0) {
   			return new Response('duplicate');
		}
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
   private function _getControlRoom()
   {
		$sql = 'SELECT id, division ' .
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
	    		'id'       => $value['id'],
	    		'division' => $value['division'],
	    	];
	    }
	    return $this->render('control_room.twig', ['divisionList' => $result]);
   }
   public function _registerDetail($postData)
   {
		$lawyer_name = $postData['lawyer_name'];
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
		$sql = 'INSERT INTO lawyer ' .
				'(lawyer_name, office, position, type, ph_1, ph_2, ph_3, ph_4, ph_5, division, township, town) ' .
				'VALUES (:lawyer_name, :office, :position, :type, :ph_1, :ph_2, :ph_3, :ph_4, :ph_5, :division, :township, :town)';
		$stmt = $this->pdo->prepare($sql);
		$stmt->bindParam(':lawyer_name', $lawyer_name, \PDO::PARAM_STR);
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
   public function _deleteDivisionData($postData)
   {
		$id = $postData['id'];
		$division= $postData['division'];
		$sql = 'DELETE FROM division_list ' .
				'WHERE id=:id';
		$stmt = $this->pdo->prepare($sql);
		$stmt->bindParam(':id', $id, \PDO::PARAM_INT);
		$count = 0;
		$count = $stmt->execute();
		if ($count == 0) {
			return new Response("failed");
		}
		$sql = 'DELETE FROM lawyer ' .
				'WHERE division=:division';
		$stmt = $this->pdo->prepare($sql);
		$stmt->bindParam(':division', $division, \PDO::PARAM_STR);
		$count += $stmt->execute();
		if ($count == 0) {
			return new Response("failed");
		}
		// 接続を閉じる
	    $this->pdo = null;
	    $stmt = null;
	    return new Response("success");
   }
   public function _deleteLawyerData($postData)
   {
		$lawyer_id = $postData['lawyer_id'];
		$sql = 'DELETE FROM lawyer ' .
				'WHERE lawyer_id=:lawyer_id';
		$stmt = $this->pdo->prepare($sql);
		$stmt->bindParam(':lawyer_id', $lawyer_id, \PDO::PARAM_INT);
		$count = 0;
		$count = $stmt->execute();
		if ($count == 0) {
			return new Response("failed");
		}
		// 接続を閉じる
	    $this->pdo = null;
	    $stmt = null;
	    return new Response("success");
   }

}
?>