<?php
/**
 * Description of captcha
 * Simple captcha class
 * @author Konstantinos Tsatsarounos
 */
class JADS_Captcha {
    public function __construct() {
        if( !isset( $_SESSION ) ){
            session_start();
        }       
    }
    
    public function createImage(){
    $_SESSION['cpt_1'] = mt_rand( 10, 99 );
	$_SESSION['cpt_2'] = mt_rand( 10, 99 );
	
	//assign to variables
	$first_num = $_SESSION['cpt_1'];
	$second_num = $_SESSION['cpt_2'];
	
	$font_size = 17;
	$image_width = 160;
	$image_height = 50;
	
	$image = imagecreatetruecolor($image_width, $image_height);
	
	imagecolorallocate($image, 230, 230, 230);
	for($i = 0; $i < $image_width; $i++) {
		for($j = 0; $j < $image_height; $j++) {
			$color = imagecolorallocate($image, rand(150,255), rand(150,255), rand(150,255));
			imagesetpixel($image, $i, $j, $color);
		}
	}
	
	$font_color = imagecolorallocate($image, 60 , 60 , 60);	
	
	imagettftext($image, $font_size, 16, 15, 30, $font_color, JADS_PATH."/includes/fonts/arial.ttf", $first_num);
	imagettftext($image, $font_size+5, 0, 80, 30, $font_color, JADS_PATH."/includes/fonts/arial.ttf", '+');
	imagettftext($image, $font_size, -16, 100, 30, $font_color, JADS_PATH."/includes/fonts/arial.ttf", $second_num);
	
	ob_start();
	imagejpeg($image);
	$img = ob_get_contents();
	ob_end_clean();

	echo '<img class="ads-captcha" src="data:image/jpeg;base64,'.base64_encode($img).'" />';
       
    }
    
    public function check_validation_code( $sended_code ){
	if( isset( $_SESSION ) ){
		$first_num = $_SESSION['cpt_1'];
		$second_num = $_SESSION['cpt_2'];
		
		$code = $first_num + $second_num;
		
		return $code == $sended_code;
	}
	else {
		return  false;
	}
}
}

?>
