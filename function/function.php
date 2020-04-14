<?php

function check($key){
    if ($key==1||$key==2||$key==3||$key==4||$key==5) {
        # 1: Admin  2: Super User ( thêm sửa xóa tất cả ) 3: User thêm sửa xóa của mình 4: Onlyview 5: Only view but less
        //echo "Hợp lệ";
    }else{
        //echo 'không hợp lệ';
        header('Location:../home/');
        exit();
    }
}
// Log

function color($key){
	$color='yellow';
	$text='';
	switch ($key) {
	  case '1':
		$color='yellow';
		$text='Doing';
		break;
	  case '2':
		$color='Green';
		$text='Done';
		break;
	  case '3':
		$color='Red';
		$text='Delay';
	  break;
	  case '4':
		$color='Grey';
		$text='Cancel';
		break;
	  default:
		# code...
		break;
	}
	$return[0]=$color;
	$return[1]=$text;
	return $return;

}

function w_logs($dir,$content){
	$name = date("Y-m-d");
	$foldername = date("Ym");
	if (!is_dir($dir)) {
		mkdir($dir, 0700);
	}
	if (!is_dir($dir.$foldername)) {
		mkdir($dir.$foldername, 0700);
	}
	$content = preg_replace('~[\r\n]+~', ' ', $content);
	$dir = $dir.$foldername.'\\';
	$now = date("Y-m-d H:i:s");
	$text = $now."\t".$content.PHP_EOL;
	if (!file_exists ($dir.$name.".txt")) {
		$myfile = fopen($dir.$name.".txt", "w") or die("Unable to open file!");
		file_put_contents ($dir.$name.".txt",$text ,FILE_APPEND);
	}else{
		file_put_contents ($dir.$name.".txt",$text ,FILE_APPEND);
	}
}

function configurePHPMailer($mail, $from){
	//Set the hostname of the mail server
	$mail->Host = 'mail.hallavina.vn';
	//Set the SMTP port number - likely to be 25, 465 or 587
	$mail->Port = 587;
	//Whether to use SMTP authentication
	$mail->SMTPAuth = true;
	//Username to use for SMTP authentication
	$mail->Username = 'hallasystem@hallavina.vn';
	//Password to use for SMTP authentication
	$mail->Password = 'Halla123@';
	//Set who the message is to be sent from
	$mail->setFrom('hallasystem@hallavina.vn', $from);
	//Set an alternative reply-to address
	$mail->addReplyTo('noreply@hallavina.vn', 'No reply');
	return $mail;
}


class ResizeImage
{
	private $ext;
	private $image;
	private $newImage;
	private $origWidth;
	private $origHeight;
	private $resizeWidth;
	private $resizeHeight;

	/**
	 * Class constructor requires to send through the image filename
	 *
	 * @param string $filename - Filename of the image you want to resize
	 */
	public function __construct( $filename )
	{
		if(file_exists($filename))
		{
			$this->setImage( $filename );
		} else {
			throw new Exception('Image ' . $filename . ' can not be found, try another image.');
		}
	}

	/**
	 * Set the image variable by using image create
	 *
	 * @param string $filename - The image filename
	 */
	private function setImage( $filename )
	{
		$size = getimagesize($filename);
		$this->ext = $size['mime'];

		switch($this->ext)
	    {
	    	// Image is a JPG
	        case 'image/jpg':
	        case 'image/jpeg':
	        	// create a jpeg extension
	            $this->image = imagecreatefromjpeg($filename);
	            break;

	        // Image is a GIF
	        case 'image/gif':
	            $this->image = @imagecreatefromgif($filename);
	            break;

	        // Image is a PNG
	        case 'image/png':
	            $this->image = @imagecreatefrompng($filename);
	            break;

	        // Mime type not found
	        default:
	            throw new Exception("File is not an image, please use another file type.", 1);
	    }

	    $this->origWidth = imagesx($this->image);
	    $this->origHeight = imagesy($this->image);
	}

	/**
	 * Save the image as the image type the original image was
	 *
	 * @param  String[type] $savePath     - The path to store the new image
	 * @param  string $imageQuality 	  - The qulaity level of image to create
	 *
	 * @return Saves the image
	 */
	public function saveImage($savePath, $imageQuality="100", $download = false)
	{
	    switch($this->ext)
	    {
	        case 'image/jpg':
	        case 'image/jpeg':
	        	// Check PHP supports this file type
	            if (imagetypes() & IMG_JPG) {
	                imagejpeg($this->newImage, $savePath, $imageQuality);
	            }
	            break;

	        case 'image/gif':
	        	// Check PHP supports this file type
	            if (imagetypes() & IMG_GIF) {
	                imagegif($this->newImage, $savePath);
	            }
	            break;

	        case 'image/png':
	            $invertScaleQuality = 9 - round(($imageQuality/100) * 9);

	            // Check PHP supports this file type
	            if (imagetypes() & IMG_PNG) {
	                imagepng($this->newImage, $savePath, $invertScaleQuality);
	            }
	            break;
	    }

	    if($download)
	    {
	    	header('Content-Description: File Transfer');
			header("Content-type: application/octet-stream");
			header("Content-disposition: attachment; filename= ".$savePath."");
			readfile($savePath);
	    }

	    imagedestroy($this->newImage);
	}

	/**
	 * Resize the image to these set dimensions
	 *
	 * @param  int $width        	- Max width of the image
	 * @param  int $height       	- Max height of the image
	 * @param  string $resizeOption - Scale option for the image
	 *
	 * @return Save new image
	 */
	public function resizeTo( $width, $height, $resizeOption = 'default' )
	{
		switch(strtolower($resizeOption))
		{
			case 'exact':
				$this->resizeWidth = $width;
				$this->resizeHeight = $height;
			break;

			case 'maxwidth':
				$this->resizeWidth  = $width;
				$this->resizeHeight = $this->resizeHeightByWidth($width);
			break;

			case 'maxheight':
				$this->resizeWidth  = $this->resizeWidthByHeight($height);
				$this->resizeHeight = $height;
			break;

			default:
				if($this->origWidth > $width || $this->origHeight > $height)
				{
					if ( $this->origWidth > $this->origHeight ) {
				    	 $this->resizeHeight = $this->resizeHeightByWidth($width);
			  			 $this->resizeWidth  = $width;
					} else if( $this->origWidth < $this->origHeight ) {
						$this->resizeWidth  = $this->resizeWidthByHeight($height);
						$this->resizeHeight = $height;
					}  else {
						$this->resizeWidth = $width;
						$this->resizeHeight = $height;	
					}
				} else {
		            $this->resizeWidth = $width;
		            $this->resizeHeight = $height;
		        }
			break;
		}

		$this->newImage = imagecreatetruecolor($this->resizeWidth, $this->resizeHeight);
    	imagecopyresampled($this->newImage, $this->image, 0, 0, 0, 0, $this->resizeWidth, $this->resizeHeight, $this->origWidth, $this->origHeight);
	}

	/**
	 * Get the resized height from the width keeping the aspect ratio
	 *
	 * @param  int $width - Max image width
	 *
	 * @return Height keeping aspect ratio
	 */
	private function resizeHeightByWidth($width)
	{
		return floor(($this->origHeight/$this->origWidth)*$width);
	}

	/**
	 * Get the resized width from the height keeping the aspect ratio
	 *
	 * @param  int $height - Max image height
	 *
	 * @return Width keeping aspect ratio
	 */
	private function resizeWidthByHeight($height)
	{
		return floor(($this->origWidth/$this->origHeight)*$height);
	}
}

function makedroplist($table,$where=1,$selected=null,$width='100%'){
	global $oDB;
	$text = "<select name='".$table."Id' id='' class='selectpicker show-tick' data-live-search='true' data-style='btn-info' data-width='".$width."'>";
	$model = $oDB->sl_all($table,$where);
	foreach ($model as $key => $value) {
		$select = ($selected==$value[$table.'Id']) ? 'selected' : '' ;
		$text .="
		<option value='".$value[$table.'Id']."' ".$select.">".$value[$table.'Name']."</option>";
	}
	$text .="</select>";
	echo $text;
}

function makedroplistreadonly($table,$where=1,$selected=null,$readonly,$width='100%'){
	global $oDB;
	$disabled = ($readonly=='Readonly') ? "disabled='true'" : '' ;
	$text = "<select name='".$table."Id' id='' class='selectpicker show-tick' data-live-search='true' data-style='btn-info' data-width='".$width."'>";
	$model = $oDB->sl_all($table,$where);
	foreach ($model as $key => $value) {
		$select = ($selected==$value[$table.'Id']) ? 'selected' : '' ;
		$text .="
		<option value='".$value[$table.'Id']."' ".$select.">".$value[$table.'Name']."</option>";
	}
	$text .="</select>";
	echo $text;
}

function droplistfromarr($name,$array,$selected=null){
	
	$text = "<select name='".$name."' id='' class='selectpicker show-tick' data-live-search='true' data-style='btn-info' >";
	
	foreach ($array as $key => $value) {
		$select = ($selected==$value['Id']) ? 'selected' : '' ;
		$text .="
		<option value='".$value['Id']."' ".$select.">".$value['Name']."</option>";
	}
	$text .="</select>";
	echo $text;
}

function auto_insert($table,$arr,$db){
    // Table(text),array(key==columnname),database object
    $tablearr = array();
    $valuearr = array();
    $slotarr = array();
    foreach ($arr as $key => $value) {
        $tablearr[] = $key;
        if ($key=='password') {
            $valuearr[] = md5($value);
        }else{
            $valuearr[] = $value;
        }
        $slotarr[] = '?';
    }
    echo $sql = "INSERT INTO `".$table."`(".implode(',',$tablearr).") VALUES (".implode(',',$slotarr).")";
    $db->query($sql,$valuearr);
}