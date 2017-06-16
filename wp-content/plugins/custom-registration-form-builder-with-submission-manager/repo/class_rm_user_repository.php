<?php
/**
 * Centralizing User related database operations
 * Still many functions are in DBManager class. Eventually all the User related db operations will be performed from this class.
 */
class RM_User_Repository {
     
     /**
      * 
      * @param type $options (array with form_id and group by clause)
      * @return array (list of users)
      */                
     public function get_users_for_front($options) {
 
         global $wpdb;
         $table_name = RM_Table_Tech::get_table_name_for('SUBMISSIONS');
         $qry = "";
         $users = array();
         $limit= 2;
         
         if (!empty($options['group_by'])):
             $form_query="";
             //check if form_id given
             if(isset($options['form_id']) && !empty($options['form_id'])):
                 $form_query= " AND FORM_ID=". (int) $options['form_id'];
             endif;
             
             // Limit result set
             $limit_query= " limit $limit ";
             if(!empty($options['page_number'])):
                 $offset= $limit*$options['page_number'];
                 $limit_query .= " OFFSET $offset";
             endif;
             
             // Order by clause
             $order_by = " ORDER BY SUBMITTED_ON ";
             if (!empty($options['group_by'])):
                 switch ($options['group_by']) {
                     case 'today' : $qry = "SELECT distinct user_email from $table_name WHERE SUBMITTED_ON BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 1 day)";
                         break;
                     case 'yesterday': $qry = "SELECT distinct user_email from $table_name WHERE SUBMITTED_ON BETWEEN subdate(current_date, 1) AND DATE_ADD(subdate(current_date, 1), INTERVAL 1 day)";
                         break;
                     case 'week' : $qry = "SELECT distinct user_email from $table_name WHERE SUBMITTED_ON BETWEEN DATE_ADD(CURDATE(), INTERVAL 1-DAYOFWEEK(CURDATE()) DAY) AND DATE_ADD(CURDATE(), INTERVAL 7-DAYOFWEEK(CURDATE()) DAY)";
                         break;
                     case 'month' : $qry = "SELECT distinct user_email from $table_name WHERE SUBMITTED_ON BETWEEN DATE_SUB(CURDATE(),INTERVAL (DAY(CURDATE())-1) DAY) AND LAST_DAY(NOW())";
                         break;
                     case 'year' : $qry = 'SELECT distinct user_email from ' . $table_name . ' WHERE SUBMITTED_ON BETWEEN DATE_FORMAT(NOW() ,"%Y-01-01") AND LAST_DAY(NOW())';
                         break;
                     default: $qry = "SELECT distinct user_email from $table_name";
                         break;
                 }
                 $qry = $qry.$form_query.$order_by.$limit_query; 
                 $emails = $wpdb->get_col($qry);
                 if (is_array($emails)):
                     foreach ($emails as $email) {
                         $user = get_user_by('email', $email);
                         if ($user && !in_array('administrator', $user->roles)):
                             $users[] = $user;
                         endif;
                             
                     }
                     return $users;
                 endif;
 
 
             endif;
         endif;
 
 
         return null;
     }
     
 }

?>