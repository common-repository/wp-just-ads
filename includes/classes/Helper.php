<?php
/**
 * Description of Helper
 * @author Konstantinos Tsatsarounos
 */
class JADS_Helper {
    
        //This method, search in templates, and runs a specific function from a specific file
	//function name must be template_name_function_name
	public static function get_plugin_template($part = false){
		require_once plugin_dir_path( JADS_BASEFILE )."includes/templates/template.{$part}.php";
		
		if(function_exists($part)){
			return $part();
		}		
		
	}
        
        public static function sortText($text, $limit){
		$output = "";
		try { 
			if( mb_strlen( $text ) > $limit ){
				$output = mb_substr($text, 0, $limit)."...";
			}
			else { $output = $text; }
			echo $output;
		}
		catch(Exception $evt){
			$evt->getTraceAsString();
		}
	
	}
        
        public static function get_meta_value( $value, $id = false, $get_first = TRUE ){
            $id = ( $id ) ? $id : get_post()->ID;             
            $value = get_post_meta( $id, $value );           
            
            if ( is_string( $value ) || is_numeric( $value ) ){
                return $value;
            }
            elseif ( is_array( $value ) && count($value) > 0 ){
                if( $get_first ){ 
                    return $value[0];                    
                }
                else { 
                    return $value;                   
                }
            } 
            return null;
        }
        
        public static function log( $text, $file = false ){
            if( !file_exists( $file ) && !$file ){
                $file = ".";
            }
            if( is_array( $text ) ){
                $text = implode( '||' , $text );
            }

            $handler = fopen( $file, 'w+');
            $before = fread( $handler, filesize( $file )+10 );
            $result = fwrite( $handler, $text );
            fclose( $handler );

            return $result;
        }
        
}

?>
