<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

require '../vendor/autoload.php';
require('../config.php');
require('../function/db_lib.php');
require('../function/MysqliDb.php');
require('../function/function.php');
$user = New Users();
$user->set($_SESSION[_site_]['userid']);
$user->module = basename(dirname(__FILE__));
check($user->acess());
$oDB = new db();
$newDB = new MysqliDb(_DB_HOST_, _DB_USER_, _DB_PASS_, _DB_name_);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	//CHECK ID IS VALID
	if (!isset($_GET['id'])) {

		return 'Empty product id';

	} else if (!ctype_digit($_GET['id'])) {

		$errors[] = 'Invalid product id';
		return;

	} else {
		$id = $_GET['id'];
		$filename = $_FILES['fileToUpload']['name'];
		$tmp = explode(".", $filename);
		$ext = end($tmp);
		$insert_id = $newDB->insert('documentdetail', [
			'DocumentId' => $id,
			'DocumentDetailVersion' => $_POST['DocumentDetailVersion'],
			'DocumentDetailDesc' => $_POST['DocumentDetailDesc'],
			'DocumentDetailFileName' => $filename,
			'UsersId' => $_SESSION[_site_]['userid']
		]);
		//insert to document detail line approval
		$newDB->where('DocumentId', $id);
		$lines = $newDB->get('documentlineapproval');
		foreach($lines as $index => $line){
			$tmp1 = [
				'DocumentDetailId' => $insert_id,
				'UsersId' => $line['UsersId']];
			if($index == 0){
				$tmp1['DocumentDetailLineApprovalStatus'] = 1;
			}
			$ddlaid = $newDB->insert('documentdetaillineapproval', $tmp1);

			//send mail to the first line
			if($index == 0){
				$newDB->where('DocumentDetailLineApprovalId', $ddlaid);
				$firstLineApp = $newDB->getOne('documentdetaillineapproval');

				$newDB->where('UsersId', $firstLineApp['UsersId']);
				$lineUser = $newDB->getOne('users');
				//Create a new PHPMailer instance
				$mail = new PHPMailer;
				//Tell PHPMailer to use SMTP
				$mail->isSMTP();
				//Enable SMTP debugging
				// SMTP::DEBUG_OFF = off (for production use)
				// SMTP::DEBUG_CLIENT = client messages
				// SMTP::DEBUG_SERVER = client and server messages
				$mail->SMTPDebug = SMTP::DEBUG_SERVER;
				configurePHPMailer($mail, 'Document Approval');
				//Set who the message is to be sent to
				$mail->addAddress($lineUser['UsersEmail'], $lineUser['UsersFullName']);
				//Set the subject line
				$mail->Subject = 'Document Approval';
				//Read an HTML message body from an external file, convert referenced images to embedded,
				$mail->Body = "
				<p>Dear </p>
				<p>New Document waiting your approval</p>
				<p><a href='localhost/smes/document/approveorrejectdoc.php?id=".$insert_id."'>Please follow this link and approval this request</a></p>
				";
				//convert HTML into a basic plain-text alternative body
				// $mail->msgHTML(file_get_contents('email_template.html'), __DIR__);
				//Replace the plain text body with one created manually
				$mail->IsHTML(true);
				$mail->AltBody = '';
				//Attach an image file
				$mail->addAttachment('');

				//send the message, check for errors
				if (!$mail->send()) {
					echo 'Mailer Error: ' . $mail->ErrorInfo;
				} else {
					echo 'Message sent!';
				}
			}
		}
		//upload file
		$target_dir = "files/";
		$target_file = $target_dir . $insert_id.'.'.$ext;

		if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
			echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";

		} else {
			echo "Sorry, there was an error uploading your file.";
		}

		header('Location:documentlistdetail.php');
	}
	
}else{
	header('Location:../404.html');
}

