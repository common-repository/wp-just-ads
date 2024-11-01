<?php
/*
Name: ajaxed-status
URI: http://en.bainternet.info
Description: answer to : Custom column for changing post status via ajax
http://wordpress.stackexchange.com/questions/33442/custom-column-for-changing-post-status-via-ajax
Version: 1.0
Author: Bainternet
Author URI: http://en.bainternet.info
*/

if ( !class_exists('ajaxed_status')){
    class ajaxed_status {

        //Constructor
        public function __construct() {
            global $pagenow; //&& $typenow =='page'
            if (is_admin()  && $pagenow=='edit.php'){
                add_filter('admin_footer',array($this,'insert_ajax_status_script'));
            }

            add_filter( 'manage_edit-jads_columns', array($this,'add_new_columns'));
            add_action( 'manage_jads_posts_custom_column', array($this, 'manage_columns'), 10, 2);

            //manage columns
            add_filter('manage_pages_columns', array($this,'add_new_columns'));
            add_action('manage_pages_custom_column', array($this, 'manage_columns'), 10, 2);

            //ajax function
            add_action('wp_ajax_change_status', array($this,'ajax_change_status'));
        }

        /*
        * the function that will actually change the post status
        $post_id - The ID of the post you'd like to change.
        $status -  The post status publish|pending|draft|private|static|object|attachment|inherit|future|trash.
        */
        public function change_post_status($post_id,$status){
            $current_post = get_post( $post_id, 'ARRAY_A' );            
            $current_post['post_status'] = $status;
            
            $ads_price = get_post_meta($post_id, 'ads_price', TRUE);
            $ads_email = get_post_meta($post_id, 'ads_email', TRUE);
            $ads_location = get_post_meta($post_id, 'ads_location', TRUE);
            $ads_contact_name = get_post_meta($post_id, 'ads_contact_name', TRUE);
            $ads_phone_number = get_post_meta($post_id, 'ads_phone_number', TRUE);
            $ads_views = get_post_meta($post_id, 'ads_views', TRUE);
            
            wp_update_post($current_post);
            
            update_post_meta($post_id, 'ads_price', $ads_price );	
            update_post_meta($post_id, 'ads_email',  $ads_email );	
            update_post_meta($post_id, 'ads_location',  $ads_location );	
            update_post_meta($post_id, 'ads_contact_name', $ads_contact_name );	
            update_post_meta($post_id, 'ads_phone_number', $ads_phone_number );
            update_post_meta($post_id, 'ads_views', $ads_views );
        }


        /* 
         ****************************
         * manage columns functions *
         ****************************
         */

        //add new columns function 
        public function add_new_columns($columns){
            $columns['status']= __('Status');
            return $columns;
        }

        //rander columns function 
        public function manage_columns($column_name, $id) {
            global $wpdb,$post;
            if ("status" == $column_name){
                echo '<div id="psatus">';
                switch ($post->post_status) {
                    case 'publish':
                        echo '<a href="#" class="pb" change_to="pending" pid="'.$id.'">Published</a>';
                        break;
                    case 'draft':
                        echo '<a href="#" class="pb" change_to="publish" pid="'.$id.'">Draft</a>';
                        break;
                    case 'pending':
                        echo '<a href="#" class="pb" change_to="publish" pid="'.$id.'">Pending</a>';
                        break;
                    default:
                        echo 'unknown';
                        break;
                } // end switch
                echo '</div>';
            }
        }


        //js/jquery code to call ajax
        public function insert_ajax_status_script(){
            ?>
            <div id="status_update_working" style="background-color: green; color: #fff; font-wieght: bolder;   font-size: 22px;   height: 33px;   left: 40%;   padding: 35px;   position: fixed;   top: 100px;   width: 350px; display:none !important; ">Changing status...</div>
            <script type="text/javascript">

            function ajax_change_status(p){
                jQuery("#status_update_working").show('fast');
                jQuery.getJSON(ajaxurl,
                    {   post_id: p.attr("pid"),
                        action: "change_status",
                        change_to: p.attr("change_to")
                    },
                    function(data) {
                        if (data.error){
                            alert(data.error);                      
                        }else{
                            p.text(data.text);
                            p.attr("change_to",data.change_to);
                        }
                    }
                );
                jQuery("#status_update_working").hide('9500');
            }
            jQuery(document).ready(function(){
                jQuery(".pb").click(function(){
                    ajax_change_status(jQuery(this));
                });
            });
            </script>
            <?php
        }

        //ajax callback function
        public function ajax_change_status(){
            if (!isset($_GET['post_id'])){
                $re['data'] = 'something went wrong ...';
                echo json_encode($re);
                die();
            }
            if (isset($_GET['change_to'])){
                $this->change_post_status($_GET['post_id'],$_GET['change_to']);
                if ($_GET['change_to'] == "pending"){
                    $re['text'] = "Pending";
                    $re['change_to'] = "publish";
                }else{
                    $re['text'] = "Published";
                    $re['change_to'] = "pending";
                }
            }else{
                $re['data'] = 'something went wrong ...';
            }
            echo json_encode($re);
            die();
        }
    }
}

new ajaxed_status();