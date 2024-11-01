<?php
/**
 * Description of DateHelper
 * This object provides the methods for calculating and tracking future and past dates! Requires the current timezone as parametre.
 * @author Konstantinos Tsatsarounos
 * @method 
 */
class JADS_DateHelper {
    public $gtm = false;
        
    private $current_date;    
    private $months_days = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
    private $months_names = array(
        'January',
        'February',
        'March',
        'April',
        'May',
        'June',
        'July',
        'August',
        'September',
        'October',
        'November',
        'December'
    );
    
    public function __construct() {      
        ini_set( 'date.timezone', 'UTC' );
        $this->current_date = getdate();
    }
    
    public function get_current_day( $string = false ){
        if( $string ) return $this->current_date['weekday'];
        return $this->current_date['mday'];
    } 
    public function get_current_month( $string = false ){
        if( $string ) return $this->current_date['month'];
        return $this->current_date['mon'];
    } 
    public function get_current_year( $short = false ){
        if( $short ) return substr ( $this->current_date['year'], 2, 3 );
        return $this->current_date['year'];
    } 
    public function get_current_month_days(){
        return $this->months_days[ $this->current_date['mon']-1 ];
    }
    public function get_month_days( $month ){
        if(is_string( $month ) ){
            $month_num = array_search ( $this->months_names, $month);
            return $this->months_days[ $month_num ];            
        }
        return $this->months_days[ $this->current_date['mon']-1 ];
    }
    public function get_date_before( $seconds = 0, $minutes = 0, $hours = 0, $days = 0, $months = 0, $years = 0 ){
        $seconds += $minutes * 60;
        $seconds += $hours * 60 * 60;
        $seconds += $days * 24 * 60 * 60;
        
        if( $months != 0){
            $seconds += $this->get_month_days( false,$this->current_date['mon'], $months ) * 24 * 60 * 60;
        }
        
        $seconds += $years * 365 * 24 * 60 * 60;
        
        $gtm_date = $this->current_date[0] - $seconds;
        
        if( $this->gtm ){ return $gtm_date; }
        return date("M d Y H:i:s", $gtm_date );        
    }
    
    public function get_date_after( $seconds = 0, $minutes = 0, $hours = 0, $days = 0, $months = 0, $years = 0 ){
        $seconds += $minutes * 60;
        $seconds += $hours * 60 * 60;
        $seconds += $days * 24 * 60 * 60;
        
        if( $months != 0){
            $seconds += $this->get_month_days( $this->current_date['mon'], false, $months ) * 24 * 60 * 60;
        }
        
        $seconds += $years * 365 * 24 * 60 * 60;
        
        $gtm_date = $this->current_date[0] + $seconds;
        if( $this->gtm ){ return $gtm_date; }
        return date("M d Y H:i:s", $gtm_date );        
    }
    
    public function get_months_days( $starting_month = false, $ending_month = false, $months =0){
        if( $starting_month && $ending_month ){
            $months = $ending_month - $starting_month;
            return $this->calculate_days_from_months( $starting_month, $months );
        }
        
        if( $starting_month xor $ending_month ){
            if( $starting_month && $months >= 0 ) {
                return $this->calculate_days_from_months( $starting_month, $months );
            }
            if( $ending_month && $months >= 0 ) {
                $starting_month = $ending_month - $months;                    
                return $this->calculate_days_from_months( $starting_month, $months );
            }
        }
        return 0;
    }  
    
    private function calculate_days_from_months( $starting_month, $distance) {        
        $period = array_slice( $this->months_days, $starting_month-1, $distance+1);
        $sum = 0;
        
        for( $counter = 0; $counter < count( $period ); $counter++ ){                      
           $sum += $period[ $counter ];
        }
        return $sum;
    }
}


?>
