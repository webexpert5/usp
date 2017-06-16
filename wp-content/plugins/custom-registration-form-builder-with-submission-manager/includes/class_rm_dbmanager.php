<?php

/**
 * Database management class
 *
 * Hold general methods of database operations
 * Singleton class and get_instance is used to access the insatnce of the class
 *
 * @author cmshelplive
 */
class RM_DBManager
{

    private static $instance;

    //Ensures that only one instance is being used.
    //All other functions should use it to access the DBM interface.
    public static function get_instance()
    {
        if (!isset(self::$instance) && !( self::$instance instanceof RM_DBManager ))
        {
            self::$instance = new RM_DBManager;
        }

        return self::$instance;
    }

    /**
     * Ref: http://www.phptherightway.com/pages/Design-Patterns.html
     * Private constructor to prevent creating a new instance of the
     * *Singleton* via the `new` operator from outside of this class.
     */
    private function __construct()
    {
        
    }

    /**
     * Private clone method to prevent cloning of the instance of the
     * *Singleton* instance.
     */
    private function __clone()
    {
        
    }

    /**
     * Private unserialize method to prevent unserializing of the *Singleton*
     * instance.
     */
    private function __wakeup()
    {
        
    }

    /**
     * Inserts a new row into db
     *
     * @global      object    $wpdb
     * @param       string    $model_identifier
     * @param       array     $array_attributes
     * @param       array     $array_attribute_format
     * @return      boolean
     */
    public static function insert_row($model_identifier, $array_attributes, $array_attribute_format)
    {
        global $wpdb;

        $table_name = RM_Table_Tech::get_table_name_for($model_identifier);

        $result = $wpdb->insert($table_name, $array_attributes, $array_attribute_format);

        if ($result !== false)
            return $wpdb->insert_id;
        else
            return false;
    }

    public static function update_row($model_identifier, $unique_id_value, $array_attributes, $array_attribute_format)
    {
        global $wpdb;

        $unique_id_name = RM_Table_Tech::get_unique_id_name($model_identifier);

        if ($unique_id_name === false)
            return false;

        //Safety check
        if ($unique_id_value === NULL)
            return false;

        $table_name = RM_Table_Tech::get_table_name_for($model_identifier);

        $result = $wpdb->get_row("SELECT * from `$table_name` where $unique_id_name = $unique_id_value");

        if ($result === null)
            return false;

        return $wpdb->update($table_name, $array_attributes, array($unique_id_name => $unique_id_value), $array_attribute_format, array('%d'));
    }
    
    public static function remove_fields_for_page($page_no, $form_id)
    {
        global $wpdb;
        
        $table_name = RM_Table_Tech::get_table_name_for('FIELDS');
        
        $wpdb->delete($table_name, array('page_no' => $page_no,'form_id'=>$form_id), array('%d','%d'));
    }
    
    public static function remove_row($model_identifier, $unique_id_value = false, $where = null)
    {
        global $wpdb;

        $unique_id_name = RM_Table_Tech::get_unique_id_name($model_identifier);

        if ($unique_id_name === false)
            return false;

        $table_name = RM_Table_Tech::get_table_name_for($model_identifier);

        $result = $wpdb->get_row("SELECT * from `$table_name` where $unique_id_name = $unique_id_value");

        if ($result === null)
            return false;

        if (!$where)
            return $wpdb->delete($table_name, array($unique_id_name => $unique_id_value), array('%d'));

        elseif (is_array($where))
        {
            if (false !== $unique_id_value)
                $where[$unique_id_name] = $unique_id_value;
            return $wpdb->delete($table_name, $where, array('%d'));
        } else
            throw new InvalidArgumentException("Invalid Argument 3 supplied to " . __CLASS__ . "::" . __FUNCTION__);
    }

    public static function get_row($model_identifier, $unique_id_value)
    {
        global $wpdb;

        $unique_id_name = RM_Table_Tech::get_unique_id_name($model_identifier);

        if ($unique_id_name === false)
            return false;

        $table_name = RM_Table_Tech::get_table_name_for($model_identifier);
        $res = $wpdb->get_row("SELECT * from `$table_name` where $unique_id_name = $unique_id_value");

        return $res;
    }

    /**
     * gets all the entries of a table spaecified.
     *
     * @global object $wpdb
     * @param   string    $model_identifier
     * @param   int       $limit    No of results to be returned
     * @param   int       $offset
     * @param   string    $column
     * @param   string    $sort_by
     * @param   boolean   $descending
     * @return  mixed       returns the result of the query or false if fails
     */
    public static function get_all($model_identifier, $offset = 0, $limit = 9999999, $column = '*', $sort_by = '', $descending = false)
    {
        return self::get($model_identifier, 1, null, 'results', $offset, $limit, $column, $sort_by, $descending);
    }

    /**
     * This function retrieves the fields corresponding to a form
     *
     * @global object $wpdb
     * @param int $form_id
     * @return mixed
     */
    public static function get_fields_by_form_id($form_id)
    {
        global $wpdb;

        $table_name = RM_Table_Tech::get_table_name_for('FIELDS');

        $foreign_key = RM_Table_Tech::get_unique_id_name('FORMS');

        $results = $wpdb->get_results("SELECT * FROM `$table_name` WHERE `$foreign_key` = $form_id ORDER BY `page_no` ASC, `field_order` ASC");

        if ($results === NULL || count($results) === 0)
        {
            return false;
        }

        return $results;
    }

    /**
     * This functions sets the order of the fields for a form
     * This function is now assigned to a ajax request so then arguments can not
     * be passed to the function.
     * This function should not be used for a direct ajax callback so another
     * function should be created that will use this function to update the order.
     */
    public static function set_field_order($order_list)
    {
        global $wpdb;

        $table_name = RM_Table_Tech::get_table_name_for('FIELDS');

        $unique_id_name = RM_Table_Tech::get_unique_id_name('FIELDS');

        if ($unique_id_name === false)
            return false;
        if (count($order_list))
        {
            foreach ($order_list as $order => $field_id)
            {
                $array_attributes = array('field_order' => $order);
                $array_attribute_format = array('%d');
                $result = $wpdb->update($table_name, $array_attributes, array($unique_id_name => $field_id), $array_attribute_format, array('%d'));
                if (false === $result)
                    return false;
            }
            return true;
        } else
            return false;
    }

    public static function get_submissions_for_form($form_id, $limit = 9999999, $offset = 0, $column = '*', $sort_by = '', $descending = false)
    {

        global $wpdb;

        $table_name = RM_Table_Tech::get_table_name_for('SUBMISSIONS');

        if (empty($sort_by))
        {

            $unique_id_name = RM_Table_Tech::get_unique_id_name('SUBMISSIONS');

            if ($unique_id_name === false)
                return false;

            $sort_by = $unique_id_name;
        }

        $foreign_key = RM_Table_Tech::get_unique_id_name('FORMS');

        if ($foreign_key === false)
            return false;

        if ($descending === false)
        {
            $results = $wpdb->get_results("SELECT $column FROM `$table_name` WHERE `$foreign_key` = $form_id ORDER BY `$sort_by` LIMIT $limit OFFSET $offset");
        } else
        {
            $results = $wpdb->get_results("SELECT $column FROM `$table_name` WHERE `$foreign_key` = $form_id ORDER BY `$sort_by` DESC LIMIT $limit OFFSET $offset");
        }

        if ($results === NULL || count($results) === 0)
        {
            return false;
        }

        return $results;
    }

    /**
     * get all the field values for a submission
     *
     * @global  object $wpdb
     * @param   int $submission_id
     * @return  boolean
     */
    public static function get_fields_for_submission($submission_id)
    {

        global $wpdb;

        $table_name = RM_Table_Tech::get_table_name_for('SUBMISSION_FIELDS');

        $unique_id_name = RM_Table_Tech::get_unique_id_name('SUBMISSION_FIELDS');

        if ($unique_id_name === false)
            return false;

        $foreign_key = RM_Table_Tech::get_unique_id_name('FORMS');

        if ($foreign_key === false)
            return false;

        $results = $wpdb->get_results("SELECT * FROM $table_name WHERE `$foreign_key` = $submission_id");

        if ($results === NULL || count($results) === 0)
        {
            return false;
        }

        return $results;
    }

    /**
     * This function searches all the submissions for a specific field value
     *
     * @param   $field_id       int         id of the field for which the value is searched
     * @param   $field_value    string      value of the field to be searched
     * @param   $limit          int         number of results to be returned
     * @param   $offset         int         offset
     * @param   $sort_by        string      column name by which the results will be sorted
     * @param   $descending     bool        if set true results will be sorted in descending order
     *
     * @return  Array   array of all the submission ids for the field value
     */
    public static function search_submissions_for($field_id, $field_value, $limit = 9999999, $offset = 0, $sort_by = '', $descending = false)
    {

        global $wpdb;

        $table_name = RM_Table_Tech::get_table_name_for('SUBMISSION_FIELDS');

        if (empty($sort_by))
        {

            $unique_id_name = RM_Table_Tech::get_unique_id_name('SUBMISSION_FIELDS');

            if ($unique_id_name === false)
                return false;

            $sort_by = $unique_id_name;
        }

        $foreign_key = array();

        $foreign_key['submission'] = RM_Table_Tech::get_unique_id_name('SUBMISSIONS');

        $foreign_key['field'] = RM_Table_Tech::get_unique_id_name('FIELDS');

        if ($descending === false)
            $results = $wpdb->get_col($wpdb->prepare("SELECT `" . $foreign_key['submission'] . "` FROM $table_name WHERE `" . $foreign_key['field'] . "` = '%d' AND `value` LIKE '%s' ORDER BY `$sort_by` LIMIT $limit OFFSET $offset", $field_id, '%' . $wpdb->esc_like(esc_sql($field_value)) . '%'));
        else
            $results = $wpdb->get_col($wpdb->prepare("SELECT `" . $foreign_key['submission'] . "` FROM $table_name WHERE `" . $foreign_key['field'] . "` = '%d' AND `value` LIKE '%s' ORDER BY `$sort_by` DESC LIMIT $limit OFFSET $offset", $field_id, '%' . $wpdb->esc_like(esc_sql($field_value)) . '%'));
        if ($results === NULL || count($results) === 0)
        {
            return false;
        }

        return $results;
    }

    /**
     * to get all the submissions by a user by his email
     *
     * @global object $wpdb
     * @param    string     $user_email
     * @param    int        $limit
     * @param    int        $offset
     * @param    string     $sort_by
     * @param    boolean    $descending
     * @return   mixed       returns the result of the query or false if not successful
     */
    public static function get_submissions_for_user($user_email, $limit = 9999999, $offset = 0, $sort_by = '', $descending = false)
    {

        global $wpdb;

        $table_name = RM_Table_Tech::get_table_name_for('SUBMISSIONS');

        if (empty($sort_by))
        {

            $unique_id_name = RM_Table_Tech::get_unique_id_name('SUBMISSIONS');

            if ($unique_id_name === false)
                return false;

            $sort_by = $unique_id_name;
        }

        if ($descending === false)
        {
            $results = $wpdb->get_results("SELECT * FROM `$table_name` WHERE `user_email` = '$user_email' AND `child_id` = 0 ORDER BY `$sort_by` LIMIT $limit OFFSET $offset");
        } else
        {
            $results = $wpdb->get_results("SELECT * FROM `$table_name` WHERE `user_email` = '$user_email' AND `child_id` = 0 ORDER BY `$sort_by` DESC LIMIT $limit OFFSET $offset");
        }

        if ($results === NULL || count($results) === 0)
        {
            return false;
        }

        return $results;
    }
 public static function group_by_total($model_identifier, $where, $data_specifiers = '',$group_by=null)
    {
     global $wpdb;

        $table_name = RM_Table_Tech::get_table_name_for($model_identifier);

        $unique_id_name = RM_Table_Tech::get_unique_id_name($model_identifier);

        if ($unique_id_name === false)
            return false;

        $qry = "SELECT COUNT(*) FROM $table_name WHERE ";
         if (is_array($where))
        {
            foreach ($where as $column_name => $column_value)
            {
                if ($column_value == null)
                    $qry .= "`$column_name` IS NULL AND ";
                elseif ($column_value == 'not null')
                    $qry .= "`$column_name` IS NOT NULL AND ";
                else
                    $qry .= "`$column_name` = '$column_value' AND ";
            }

            $qry = substr($qry, 0, -4);
        } elseif ($where == 1)
        {
            $qry .= "1 ";
        } else
        {
            throw new InvalidArgumentException(
            __FUNCTION__ . " needs the second argument to be an array or 1,'" . gettype($where) . "'is passed.");
        }
        
        if($group_by != null)
        {
            $qry.="GROUP BY `$group_by`";
        }
         $count = $wpdb->get_results($qry);
         return $wpdb->num_rows;
       
 }
    public static function count($model_identifier, $where, $data_specifiers = '')
    {

        global $wpdb;

        $table_name = RM_Table_Tech::get_table_name_for($model_identifier);

        $unique_id_name = RM_Table_Tech::get_unique_id_name($model_identifier);

        if ($unique_id_name === false)
            return false;

        $qry = "SELECT COUNT($unique_id_name) FROM $table_name WHERE ";

        if (is_array($where))
        {
            foreach ($where as $column_name => $column_value)
            {
                if ($column_value === null)
                    $qry .= "`$column_name` IS NULL AND ";
                elseif ($column_value === 'not null')
                    $qry .= "`$column_name` IS NOT NULL AND ";
                else
                    $qry .= "`$column_name` = '$column_value' AND ";
            }

            $qry = substr($qry, 0, -4);
        } elseif ($where == 1)
        {
            $qry .= "1 ";
        } else
        {
            throw new InvalidArgumentException(
            __FUNCTION__ . " needs the second argument to be an array or 1,'" . gettype($where) . "'is passed.");
        }


  
        $count = $wpdb->get_var($qry);
        if ($count === null)
        {
            return false;
        }
        return (int) $count;
    }
    //Run generic queries smartly.
    //Use placeholders #UID# for unique id name and #TNAME# for table name in query string.
    public static function get_generic($identifier, $select_clause, $where_clause, $format = OBJECT)
    {
        global $wpdb;

        $table_name = RM_Table_Tech::get_table_name_for($identifier);

        $unique_id_name = RM_Table_Tech::get_unique_id_name($identifier);

        $select_clause = str_replace('#UID#', "`$unique_id_name`", $select_clause);
        $where_clause = str_replace('#UID#', "`$unique_id_name`", $where_clause);
        $select_clause = str_replace('#TNAME#', "`$table_name`", $select_clause);
        $where_clause = str_replace('#TNAME#', "`$table_name`", $where_clause);

        $qry = "SELECT $select_clause FROM `$table_name` WHERE $where_clause";
        
        $wpdb->query('SET time_zone = "+00:00"');
        
        $results = $wpdb->get_results($qry, $format);

        if (!$results)
            return null;    //function failed.

        if (is_array($results) && count($results) == 0)
            return null;   //Query failed.

        return $results;
    }

    public static function get($model_identifier, $where, $data_specifier, $result_type = 'results', $offset = 0, $limit = 9999999, $column = '*', $sort_by = null, $descending = false)
    {
        global $wpdb;

        $table_name = RM_Table_Tech::get_table_name_for($model_identifier);

        $unique_id_name = RM_Table_Tech::get_unique_id_name($model_identifier);

        if ($unique_id_name === false)
            return null;


        if (!$sort_by)
            $sort_by = $unique_id_name;

        $args = array();

        $qry = "SELECT $column FROM `$table_name` WHERE ";

        if (is_array($where))
        {
            $i = 0;
            foreach ($where as $column_name => $column_value)
            {
                if ($i !== 0)
                    $qry .= " AND";
                $qry .= " `$column_name` = $data_specifier[$i] ";
                $args[] = $column_value;
                $i++;
            }
        }
        elseif ($where == 1)
        {
            $qry .= "1 ";
        } else
        {
            throw new InvalidArgumentException(
            __FUNCTION__ . " needs the second argument to be an array or 1,'" . gettype($where) . "'is passed.");
        }

        if ($descending === false)
        {
            if($limit===0)
            $qry .= "ORDER BY `$sort_by`";
            else
            $qry .= "ORDER BY `$sort_by` LIMIT $limit OFFSET $offset";    
        } else
        {
           if($limit===0)
            $qry .= "ORDER BY `$sort_by` DESC";
            else
            $qry .= "ORDER BY `$sort_by` DESC LIMIT $limit OFFSET $offset"; 
        }

        if ($result_type === 'results' || $result_type === 'row' || $result_type === 'var' || $result_type === 'col')
        {
            $method_name = 'get_' . $result_type;
            if (count($args) === 0)
                $results = $wpdb->$method_name($qry);
            else
                $results = $wpdb->$method_name($wpdb->prepare($qry, $args));
        } else
        {
            return null;
        }

        if (is_array($results) && count($results) === 0)
        {
            return null;
        }

        return $results;
    }

    public static function delete_form_fields($form_id)
    {
        global $wpdb;
        $table_name = RM_Table_Tech::get_table_name_for('FIELDS');
        $foreign_key = RM_Table_Tech::get_unique_id_name('FORMS');
        if ($foreign_key === false)
            return false;
        $result = $wpdb->query("DELETE FROM `$table_name` where `$foreign_key` = $form_id");
        if (!$result)
            return false;
        return true;
    }

    public static function delete_form_submissions($form_id)
    {
        global $wpdb;
        $table_name = RM_Table_Tech::get_table_name_for('SUBMISSIONS');
        $table_name_ = RM_Table_Tech::get_table_name_for('SUBMISSION_FIELDS');
        $foreign_key = RM_Table_Tech::get_unique_id_name('FORMS');
        if ($foreign_key === false)
            return false;
        $result = $wpdb->query("DELETE FROM `$table_name` where `$foreign_key` = $form_id");
        $result_ = $wpdb->query("DELETE FROM `$table_name_` where `$foreign_key` = $form_id");
        if (!$result || !$result_)
            return false;
        return true;
    }

    public static function delete_form_payment_logs($form_id)
    {
        global $wpdb;
        $table_name = RM_Table_Tech::get_table_name_for('PAYPAL_LOGS');
        $foreign_key = RM_Table_Tech::get_unique_id_name('FORMS');
        if ($foreign_key === false)
            return false;
        $result = $wpdb->query("DELETE FROM `$table_name` where `$foreign_key` = $form_id");
        if (!$result)
            return false;
        return true;
    }

    public static function delete_form_stats($form_id)
    {
        global $wpdb;
        $table_name = RM_Table_Tech::get_table_name_for('STATS');
        $foreign_key = RM_Table_Tech::get_unique_id_name('FORMS');
        if ($foreign_key === false)
            return false;
        $result = $wpdb->query("DELETE FROM `$table_name` where `$foreign_key` = $form_id");
        if (!$result)
            return false;
        return true;
    }

    public static function delete_form_notes($form_id)
    {
        global $wpdb;
        $table_name = RM_Table_Tech::get_table_name_for('NOTES');
        $foreign_key = RM_Table_Tech::get_unique_id_name('FORMS');
        $foreign_key_sub = RM_Table_Tech::get_unique_id_name('SUBMISSIONS');
        $submission_ids = self::get('SUBMISSIONS', array($foreign_key => $form_id), array('%d'), 'col', 0, 999999, 'submission_id', null, true);

        if ($submission_ids)
            $id_str = implode(',', $submission_ids);
        else
            return null;

        if ($foreign_key === false)
            return false;
        $result = $wpdb->query("DELETE FROM `$table_name` where `$foreign_key_sub` IN ($id_str)");
        if (!$result)
            return false;
        return true;
    }

    public static function is_expired_by_date($form_id, &$remaining_days = null)
    {
        global $wpdb;
        $table_name = RM_Table_Tech::get_table_name_for('FORMS');
        $primary_key = RM_Table_Tech::get_unique_id_name('FORMS');
        $result = maybe_unserialize($wpdb->get_var("Select form_options FROM `$table_name` where `$primary_key` = $form_id"));

        if (isset($result->form_expiry_date))
        {
            $form_expiry_date = strtotime($result->form_expiry_date);

            if (time() > $form_expiry_date)
            {
                if ($remaining_days !== null)
                {
                    $remaining_days = 0;
                }
                return true;
            } else
            {
                if ($remaining_days !== null)
                {
                    $diff = $form_expiry_date - time();
                    $diff = (int) ($diff / 86400);
                    $remaining_days = $diff;
                }
            }
        } else
            $remaining_days = 'no_expiry_date';

        return false;
    }

    public static function is_expired_by_submissions($form_id, $limit, &$remaining_subs = null)
    {
        global $wpdb;
        $table_name = RM_Table_Tech::get_table_name_for('SUBMISSIONS');
        $num_submissions = $wpdb->get_var("Select count(*) FROM `$table_name` where `form_id` = $form_id AND `child_id` = 0 ");
        if ($num_submissions >= $limit)
        {
            $remaining_subs = 0;
            return true;
        } else
            $remaining_subs = $limit - $num_submissions;

        return false;
    }

    public static function get_primary_fields_by_type($form_id, $type)
    {
        global $wpdb;
        $email_fields = array();
        $primary_fields = array();

        $table_name = RM_Table_Tech::get_table_name_for('FIELDS');
        // echo "Select * from `$table_name` where form_id=$form_id and field_type='".$type."'"; die;
        $results = $wpdb->get_results("Select * from `$table_name` where form_id=$form_id and field_type='" . $type . "' AND `is_field_primary`=1");
        if (is_array($results))
        {
            foreach ($results as $row)
            {
                $email_fields[] = $row->field_type . '_' . $row->field_id;
            }
        }
        $primary_fields['emails'] = $email_fields;

        return $primary_fields;
    }

    public static function get_results_for_last($interval, $form_id, $field_id, $field_value, $offset = 0, $limit = 999999, $sort_by = 'submission_id', $descending = false, $dates = null)
    {
         
        if (!(int) $form_id)
            return false;
 
        global $wpdb;

        $wpdb->query('SET time_zone = "+00:00"');

        //echo "<pre>",var_dump($wpdb->get_results('SELECT @@global.time_zone, @@session.time_zone')),die;

        $table_name = RM_Table_Tech::get_table_name_for('SUBMISSIONS');

        $interval_string = '';
        $read_status="";
        $qry = "";

        $sub_ids = null;

        $searched = false;

        if ((int) $field_id)
        {
            $sub_ids = self::search_submissions_for($field_id, $field_value, 999999, 0, null, false);
            if ($sub_ids)
                $sub_ids = implode(',', $sub_ids);
            $searched = true;
        }

        switch (strtolower($interval))
        {
            case 'today':
                $interval_string = 'BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 1 day)';
                break;
            case 'week':
                 $interval_string = 'BETWEEN DATE_ADD(CURDATE(), INTERVAL 1-DAYOFWEEK(CURDATE()) DAY) AND DATE_ADD(CURDATE(), INTERVAL 7-DAYOFWEEK(CURDATE()) DAY)';
                break;
            case 'month':
                $interval_string = 'BETWEEN DATE_SUB(CURDATE(),INTERVAL (DAY(CURDATE())-1) DAY) AND LAST_DAY(NOW())';
                break;
            case 'year':
                $interval_string = 'BETWEEN DATE_FORMAT(NOW() ,"%Y-01-01") AND LAST_DAY(NOW())';
                break;
            case 'custom':
                if(is_array($dates))
                {
                   if($dates['from'] != '' || $dates['upto'] != '')
                   {
                       if($dates['from'] == '')
                       {
                           $interval_string = '<= \''.$dates['upto'].'\'';
                       }
                       elseif($dates['upto'] == '')
                       {
                           $interval_string = '>= \''.$dates['from'].'\'';
                       }
                       else
                       $interval_string = 'BETWEEN \''.$dates['from'].'\' AND \''.$dates['upto'].'\'';
                       
                       break;
                   }
                   //Let it fall through to 'all' case.
                }
                
            case 'read':
            case 'unread':
            case 'all':
            {
                if ((int) $field_id && $sub_ids)
                    $qry = "SELECT * FROM `$table_name` WHERE `form_id` = $form_id AND `child_id` = 0 AND `submission_id` in($sub_ids) ";
                elseif ($searched)
                    $qry = "SELECT * FROM `$table_name` WHERE `form_id` = $form_id AND `child_id` = 0 AND  `submission_id` = 0 ";
                else
                    $qry = "SELECT * FROM `$table_name` WHERE `form_id` = $form_id AND `child_id` = 0 ";

                if (!$descending)
                {
                    $qry .= "ORDER BY `$sort_by` LIMIT $limit OFFSET $offset";
                } else
                {
                    $qry .= "ORDER BY `$sort_by` DESC LIMIT $limit OFFSET $offset";
                }
                
                $results = $wpdb->get_results($qry);
                if (is_array($results) && count($results) === 0)
                {
                    return null;
                }

            return $results;}
           
            default: return false;
        }
        
        if ((int) $field_id && $sub_ids)
            $qry = "SELECT * FROM `$table_name` WHERE `form_id` = $form_id AND `child_id` = 0 AND  `submission_id` in($sub_ids) AND (`submitted_on` $interval_string) $read_status ";
        elseif ($searched)
            $qry = "SELECT * FROM `$table_name` WHERE `form_id` = $form_id AND `child_id` = 0 AND  `submission_id` = 0 AND (`submitted_on` $interval_string) $read_status ";
        else
            $qry = "SELECT * FROM `$table_name` WHERE `form_id` = $form_id AND `child_id` = 0 AND  (`submitted_on` $interval_string) $read_status ";
 
       
        if (!$descending)
        {
            $qry .= "ORDER BY `$sort_by` LIMIT $limit OFFSET $offset";
        } else
        {
            $qry .= "ORDER BY `$sort_by` DESC LIMIT $limit OFFSET $offset";
        }
        
        $results = $wpdb->get_results($qry);
        if (is_array($results) && count($results) === 0)
        {
            return null;
        }

        return $results;
    }

    public static function get_results_for_last_col($interval, $form_id, $field_id, $field_value, $offset = 0, $limit = 999999, $sort_by = 'submission_id', $descending = false)
    {
        global $wpdb;

        $wpdb->query('SET time_zone = "+00:00"');

        //echo "<pre>",var_dump($wpdb->get_results('SELECT @@global.time_zone, @@session.time_zone')),die;

        $table_name = RM_Table_Tech::get_table_name_for('SUBMISSIONS');
        $col_name = RM_Table_Tech::get_unique_id_name('SUBMISSIONS');

        $interval_string = '';

        $qry = "";

        $sub_ids = null;

        $searched = false;

        if ((int) $field_id)
        {
            $sub_ids = self::search_submissions_for($field_id, $field_value, 999999, 0, null, false);
            if ($sub_ids)
                $sub_ids = implode(',', $sub_ids);
            $searched = true;
        }

        switch (strtolower($interval))
        {
            case 'today':
                $interval_string = 'BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 1 day)';
                break;
            case 'week':
                $interval_string = 'BETWEEN DATE_ADD(CURDATE(), INTERVAL 1-DAYOFWEEK(CURDATE()) DAY) AND DATE_ADD(CURDATE(), INTERVAL 7-DAYOFWEEK(CURDATE()) DAY)';
                break;
            case 'month':
                $interval_string = 'BETWEEN DATE_SUB(CURDATE(),INTERVAL (DAY(CURDATE())-1) DAY) AND LAST_DAY(NOW())';
                break;
            case 'year':
                $interval_string = 'BETWEEN DATE_FORMAT(NOW() ,"%Y-01-01") AND LAST_DAY(NOW())';
                break;
            case 'all':

                if ((int) $field_id && $sub_ids)
                    $qry = "SELECT `$col_name` FROM `$table_name` WHERE `form_id` = $form_id AND `submission_id` in($sub_ids) ";
                elseif ($searched)
                    $qry = "SELECT `$col_name` FROM `$table_name` WHERE `form_id` = $form_id AND `submission_id` = 0 ";
                else
                    $qry = "SELECT `$col_name` FROM `$table_name` WHERE `form_id` = $form_id ";

                if ($descending === false)
                {
                    $qry .= "ORDER BY `$sort_by` LIMIT $limit OFFSET $offset";
                } else
                {
                    $qry .= "ORDER BY `$sort_by` DESC LIMIT $limit OFFSET $offset";
                }

                $results = $wpdb->get_col($qry);
                if (is_array($results) && count($results) === 0)
                {
                    return null;
                }

                return $results;

            default: return false;
        }

        if ((int) $field_id && $sub_ids)
            $qry = "SELECT `$col_name` FROM `$table_name` WHERE `form_id` = $form_id AND `submission_id` in($sub_ids) AND (`submitted_on` $interval_string) ";
        else
            $qry = "SELECT `$col_name` FROM `$table_name` WHERE `form_id` = $form_id AND (`submitted_on` $interval_string) ";

        if ($descending === false)
        {
            $qry .= "ORDER BY `$sort_by` LIMIT $limit OFFSET $offset";
        } else
        {
            $qry .= "ORDER BY `$sort_by` DESC LIMIT $limit OFFSET $offset";
        }
        //echo $qry;
        $results = $wpdb->get_col($qry);

        if (is_array($results) && count($results) === 0)
        {
            return null;
        }

        return $results;
    }

    public static function sidebar_user_search($criterion, $type)
    {

        global $wpdb;
        $user_ids = array();


        if ($type == "time")
        {
            $table_name = $wpdb->prefix . "users";
            foreach ($criterion as $period)
            {
                $query = "Select ID from $table_name where user_registered between '" . $period['start'] . "' and '" . $period['end'] . "'";
                $result = $wpdb->get_results($query);
                foreach ($result as $el)
                {
                    $user_ids[] = $el->ID;
                }
            }
        }

        if ($type == "user_status")
        {
            $table_name = $wpdb->prefix . "usermeta";
            if (count($criterion) > 1)
                $query = "Select distinct user_id from $table_name";
            else
                $query = "Select distinct user_id from $table_name where meta_key='rm_user_status' and meta_value=" . $criterion[0];
            $result = $wpdb->get_results($query);
            foreach ($result as $el)
            {
                $user_ids[] = $el->user_id;
            }
        }

        if ($type == "name")
        {
            $args = array(
                'search' => $criterion,
            );
            $users = get_users($args);
            foreach ($users as $user)
                $user_ids[] = $user->ID;
        }

        if ($type == "email")
        {
            $args = array(
                'search' => $criterion,
            );
            $users = get_users($args);
            foreach ($users as $user)
                $user_ids[] = $user->ID;
        }


        return array_unique($user_ids);
    }

    public static function delete_front_user($interval, $time_format, $by_last_activity = false)
    {

        global $wpdb;

        switch ($time_format)
        {
            case 'H':
            case 'h':
                $mul = 60 * 60;
                break;
            case 'S':
            case 's':
                $mul = 1;
                break;
            default :
                $mul = 1 * 60;
                break;
        }

        $table_name = RM_Table_Tech::get_table_name_for('FRONT_USERS');

        if ($by_last_activity)
            $qry = "DELETE FROM $table_name WHERE `last_activity_time` < '" . RM_Utilities::get_current_time(time() - $interval * $mul) . "'";
        else
            $qry = "DELETE FROM $table_name WHERE `created_date` < '" . RM_Utilities::get_current_time(time() - $interval * $mul) . "'";

        return $wpdb->query($qry);
    }

    public static function update_last_activity()
    {

        global $wpdb;

        $table_name = RM_Table_Tech::get_table_name_for('FRONT_USERS');

        return $wpdb->query("UPDATE $table_name set `last_activity_time`= '" . RM_Utilities::get_current_time() . "'");
    }

    public static function delete_rows($model_identifier, $where, $where_format = null)
    {

        global $wpdb;

        $table_name = RM_Table_Tech::get_table_name_for($model_identifier);

        return $wpdb->delete($table_name, $where, $where_format);
    }

    public static function get_average_value($model_identifier, $column_name_that_has_numeric_values, $where = 1)
    {
        global $wpdb;

        $table_name = RM_Table_Tech::get_table_name_for($model_identifier);
        $qry = "SELECT AVG(`$column_name_that_has_numeric_values`) FROM `$table_name` WHERE";

        if (is_array($where))
        {
            $i = 0;
            foreach ($where as $column_name => $column_value)
            {
                if ($i !== 0)
                    $qry .= " AND";
                if ($column_value == null)
                    $qry .= "`$column_name` IS NULL";
                else
                    $qry .= " `$column_name` = $column_value";
                $i++;
            }
        }
        elseif ($where == 1)
        {
            $qry .= " 1";
        } else
        {
            throw new InvalidArgumentException(
            __FUNCTION__ . " needs the second argument to be an array or 1,'" . gettype($where) . "'is passed.");
        }


        $avg = $wpdb->get_var($qry);
        return floatval($avg);
    }

    public static function get_all_form_attachments($form_id)
    {

        global $wpdb;

        $field_ids = self::get('FIELDS', array('field_type' => 'File', 'form_id' => $form_id), array('%s', '%d'), 'col', 0, 99999, 'field_id', null, false);

        $table_name = RM_Table_Tech::get_table_name_for('SUBMISSION_FIELDS');

        $unique_id_name = RM_Table_Tech::get_unique_id_name('SUBMISSION_FIELDS');

        if ($unique_id_name === false)
            return null;
        if ($field_ids)
        {
            $qry = "SELECT `value` FROM $table_name WHERE ";
            $i = 0;

            foreach ($field_ids as $field_id)
            {
                if ($i === 0)
                    $qry .= "`field_id` = $field_id ";
                else
                    $qry .= "OR `field_id` = $field_id ";

                $i++;
            }

            $qry .= "ORDER BY `$unique_id_name`";

            $results = $wpdb->get_col($qry);
        } else
            return false;

        if (empty($results))
        {
            return false;
        }

        return $results;
    }

    public static function delete_and_reset_table($identifier)
    {
        RM_Table_Tech::delete_and_reset_table($identifier);
    }

    public static function get_fields_filtered_by_types($form_id, array $types_array)
    {
        if (!(int) $form_id)
            return false;

        global $wpdb;

        $table_name = RM_Table_Tech::get_table_name_for('FIELDS');

        $qry = "SELECT * FROM `$table_name` WHERE `form_id` = $form_id AND `field_type` IN (";

        $i = 0;
        foreach ($types_array as $type)
        {
            if ($i != 0)
                $qry .= ",";
            $qry .= "'$type'";

            $i++;
        }

        $qry .= ")";

        $result = $wpdb->get_results($qry);
        return $result;
    }

    /* Counts multiple distinct values in a given column,
     * optionally a where clause can be specified.
     */

    public static function count_multiple($identifier, $column, $where = 1, $in_spacifier = null)
    {
        /* SELECT `value`, COUNT(*) FROM `wp_rm_submission_fields` WHERE `field_id` = 94 GROUP BY `value` */
        global $wpdb;

        $table_name = RM_Table_Tech::get_table_name_for($identifier);

        $qry = "SELECT `$column`, COUNT(*) AS `count` FROM `$table_name` WHERE ";

        if (is_array($where))
        {
            foreach ($where as $column_name => $column_value)
            {
                if ($column_value == null)
                    $qry .= "`$column_name` IS NULL AND ";
                else if ($column_value == 'not null')
                    $qry .= "`$column_name` IS NOT NULL AND ";
                else if ($column_value{0} == '!')
                {
                    $act_val = substr($column_value,1);
                    $qry .= "`$column_name` != '$act_val' AND ";
                }
                else
                    $qry .= "`$column_name` = '$column_value' AND ";
            }

            $qry = substr($qry, 0, -4);
        }
        elseif ($where == 1)
        {
            $qry .= "1 ";
        } else
        {
            throw new InvalidArgumentException(
            __FUNCTION__ . " needs the second argument to be an array or 1,'" . gettype($where) . "'is passed.");
        }

        if ($in_spacifier != null)
        {
            foreach ($in_spacifier as $in_field => $in_string)
            {
                $in_arr = explode(',', $in_string);
                $in_arr2 = array();

                foreach ($in_arr as $v)
                    $in_arr2[] = '"' . $v . '"';

                $in_string = implode(',', $in_arr2);

                $qry .= "AND `$in_field` IN ($in_string)";
            }
        }

        $qry .= "GROUP BY `$column`";
        //echo("<br>Query: ".$qry);
        $result = $wpdb->get_results($qry);
        return $result;
    }

    /**
     * This function generates a "IN" query for a given array
     * 
     * @global object $wpdb
     * @param string    $model_identifier 
     * @param string    $column_to_search   name of the column to search for the values in the array
     * @param array     $types_array        array of values.
     * @return array
     */
    public static function get_results_for_array($model_identifier, $column_to_search, array $types_array)
    {
        global $wpdb;

        $table_name = RM_Table_Tech::get_table_name_for($model_identifier);

        $qry = "SELECT * FROM `$table_name` WHERE `$column_to_search` IN (";

        $i = 0;
        foreach ($types_array as $type)
        {
            if ($i != 0)
                $qry .= ",";
            $qry .= "'$type'";

            $i++;
        }

        $qry .= ")";

        $result = $wpdb->get_results($qry);
        return $result;
    }

    public static function get_sub_fields_for_array($model_identifier, $column_to_search, array $types_array, $column_to_search2, array $types_array2)
    {
        global $wpdb;

        $table_name = RM_Table_Tech::get_table_name_for($model_identifier);

        $qry = "SELECT * FROM `$table_name` WHERE `$column_to_search` IN (";

        $i = 0;
        foreach ($types_array as $type)
        {
            if ($i != 0)
                $qry .= ",";
            $qry .= "'$type'";

            $i++;
        }

        $qry .= ") AND `$column_to_search2` IN (";

        $i = 0;
        foreach ($types_array2 as $type)
        {
            if ($i != 0)
                $qry .= ",";
            $qry .= "'$type'";

            $i++;
        }

        $qry .= ")";

        $result = $wpdb->get_results($qry);
        return $result;
    }
    
    public static function get_submissions($form_id=0,$filter,$selection="*",$sort_by = 'submission_id', $descending = true){
        if (!(int) $form_id)
            return false;
        global $wpdb;
        $wpdb->query('SET time_zone = "+00:00"');
        $table_name = RM_Table_Tech::get_table_name_for('SUBMISSIONS');
        $qry = "";
        $interval_string="";
        $sub_ids = null;
        $searched = false;
        $filters= $filter->filters;
        
            

        if (isset($filters['rm_field_to_search']) && (int) $filters['rm_field_to_search'])
        {
            $sub_ids = self::search_submissions_for($filters['rm_field_to_search'], $filters['rm_value_to_serach'], 999999, 0, null, false);
            if ($sub_ids)
                $sub_ids = implode(',', $sub_ids);
            $searched = true;
        }

        switch (strtolower($filters['rm_interval']))
        {
            case 'today':
                $interval_string = 'BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 1 day)';
                break;
            case 'week':
                //$interval_string = 'BETWEEN DATE_ADD(CURDATE(), INTERVAL 1-DAYOFWEEK(CURDATE()) DAY) AND DATE_ADD(CURDATE(), INTERVAL 7-DAYOFWEEK(CURDATE()) DAY)';
               // $interval_string = ' > DATE_SUB(NOW(), INTERVAL 1 WEEK) ';
                $interval_string = ' >DATE_ADD(CURDATE(), INTERVAL 1-DAYOFWEEK(CURDATE()) DAY) ';
                break;
            case 'month':
                $interval_string = 'BETWEEN DATE_SUB(CURDATE(),INTERVAL (DAY(CURDATE())-1) DAY) AND LAST_DAY(NOW())';
                break;
            case 'year':
                $interval_string = 'BETWEEN DATE_FORMAT(NOW() ,"%Y-01-01") AND LAST_DAY(NOW())';
                break;
            case 'custom':
                   
                   if($filters['rm_fromdate'] != '' || $filters['rm_dateupto'] != '')
                   {
                       if($filters['rm_fromdate'] == '')
                       {
                           $interval_string = '<= \''.$filters['rm_dateupto'].'\'';
                       }
                       elseif($filters['rm_dateupto'] == '')
                       {
                           $interval_string = '>= \''.$filters['rm_dateupto'].'\'';
                       }
                       else
                       $interval_string = 'BETWEEN \''.$filters['rm_fromdate'].' 00:00:00\' AND \''.$filters['rm_dateupto'].' 23:59:59\'';
                       
                       break;
                   }
                   //Let it fall through to 'all' case.
            case 'all':
                if ((int) $filters['rm_field_to_search'] && $sub_ids)
                    $qry = "SELECT $selection FROM `$table_name` WHERE `form_id` = $form_id AND `child_id` = 0 AND `submission_id` in($sub_ids) ";
                elseif ($searched)
                    $qry = "SELECT $selection FROM `$table_name` WHERE `form_id` = $form_id AND `child_id` = 0 AND `submission_id` = 0 ";
                else
                    $qry = "SELECT $selection FROM `$table_name` WHERE `form_id` = $form_id AND `child_id` = 0 ";
                
                if($selection=="*"):
                    if (!$descending)
                    $qry .= "ORDER BY `$sort_by` LIMIT ".$filter->pagination->entries_per_page." OFFSET ".$filter->pagination->offset;
                    else
                    $qry .= "ORDER BY `$sort_by` DESC LIMIT ".$filter->pagination->entries_per_page." OFFSET ".$filter->pagination->offset; 
                endif;
                    
                
                $results = $wpdb->get_results($qry);
                if (is_array($results) && count($results) === 0)
                {
                    return null;
                }

                return $results;

            default: return false;
        }

        if ((int) $filters['rm_field_to_search'] && $sub_ids)
            $qry = "SELECT $selection FROM `$table_name` WHERE `form_id` = $form_id AND `child_id` = 0 AND `submission_id` in($sub_ids) AND (`submitted_on` $interval_string) ";
        elseif ($searched)
            $qry = "SELECT $selection FROM `$table_name` WHERE `form_id` = $form_id AND `child_id` = 0 AND `submission_id` = 0 AND (`submitted_on` $interval_string) ";
        else
            $qry = "SELECT $selection FROM `$table_name` WHERE `form_id` = $form_id AND `child_id` = 0 AND (`submitted_on` $interval_string) ";
        
        $qry= self::add_filter_queries($form_id,$filters,$qry);
        
        if($selection=="*"):
            if (!$descending)
                $qry .= "ORDER BY `$sort_by` LIMIT ".$filter->pagination->entries_per_page." OFFSET ".$filter->pagination->offset;
            else
                $qry .= "ORDER BY `$sort_by` DESC LIMIT ".$filter->pagination->entries_per_page." OFFSET ".$filter->pagination->offset;
        endif;    
        
        
        
        $results = $wpdb->get_results($qry);

        if (is_array($results) && count($results) === 0)
        {
            return null;
        }

        return $results;
    
    }
    
    public static function get_latest_submission_for_user($user_email,$form_ids = array()){
        global $wpdb;
        
        $table_name = RM_Table_Tech::get_table_name_for('SUBMISSIONS');

        $unique_id_name = RM_Table_Tech::get_unique_id_name('SUBMISSIONS');

        if ($unique_id_name === false)
                return false;
        
        if(count($form_ids) !== 0)
            $params = "AND `form_id` IN(".  implode(',', $form_ids).")";
        else
            $params = "";
        
        $results = $wpdb->get_results("SELECT * FROM $table_name WHERE $unique_id_name IN ("
                . "SELECT MAX($unique_id_name) FROM `$table_name` where `user_email` = '$user_email' $params GROUP BY `form_id`)"
                . "ORDER BY $unique_id_name DESC");
        
        if ($results === NULL || count($results) === 0)
        {
            return false;
        }

        return $results;
        
    }
    
    
    public static function add_filter_queries($form_id,$filters,$qry){  
        $records= array();     
        $excluded_records= array();
        
        if(!empty($filters['filter_tags'])) { 
            $filter_tags= explode(',',$filters['filter_tags']);
            if(is_array($filter_tags)){ 
                foreach($filter_tags as $filter_tag):
                    switch(strtolower($filter_tag)){
                        case 'attachment': 
                            $submission_ids= self::get_all_form_attachments($form_id,' distinct submission_id ');
                            if($submission_ids):
                                $records= array_merge($records,$submission_ids);
                            endif;
                                
                            break;
                        
                        case 'no attachment': 
                        $submission_ids= self::get_all_form_attachments($form_id,' distinct submission_id ');
                        if($submission_ids):
                            $excluded_records= array_merge($excluded_records,$submission_ids);
                        endif;

                        break;
                            
                        case 'have note':
                            $submission_ids= self::get_submissions_with_note($form_id);
                            if(count($submission_ids)):
                                $records= array_merge($records,$submission_ids);
                            endif;
                                
                            break;
                        
                        case 'payment pending':
                            $submission_ids= self::get_submissions_payment_status($form_id,"'pending'");
                            if(count($submission_ids)):
                                $records= array_merge($records,$submission_ids);
                            endif;
                                
                            break;
                            
                        case 'payment received':
                            $submission_ids= self::get_submissions_payment_status($form_id,"'succeeded','completed'");
                            if(count($submission_ids)):
                                $records= array_merge($records,$submission_ids);
                            endif;
                                
                            break;    
                            
                        case 'read':
                            $submission_ids= self::get_submission_read_count($form_id,1,false);
                            if(count($submission_ids)):
                                $records= array_merge($records,$submission_ids);
                            endif;
                                
                            break; 
                            
                        case 'unread':
                            $submission_ids= self::get_submission_read_count($form_id,0,false);
                            if(count($submission_ids)):
                                $records= array_merge($records,$submission_ids);
                            endif;
                                
                            break;
                            
                         case 'blocked':
                            $submission_ids= self::get_blocked_submission($form_id);
                            if(count($submission_ids)):
                                $records= array_merge($records,$submission_ids);
                            endif;
                                
                            break;    
                    }
                endforeach;
              
                if(empty($records) && empty($excluded_records)):
                    $qry .= " and submission_id in (-1) ";
                elseif(count($excluded_records) ):
                     $submission_ids= implode(',',$excluded_records);
                     $qry .= " and submission_id not in ($submission_ids)";  
                endif;
                
                if(count($records)){
                     $records= array_unique($records);
                     $submission_ids= implode(',',$records);
                     
                     if(count($excluded_records))
                        $qry .= " OR submission_id in ($submission_ids)";
                     else
                        $qry .= " AND submission_id in ($submission_ids)";
                }
                    
               
                    
            }
        }
                
        return $qry;
    }
    
    //get the newest submission's id from a group of edited submissions
    public static function get_latest_submission_from_group($submission_id){
        $unique_id_name = RM_Table_Tech::get_unique_id_name('SUBMISSIONS');
        
        if ($unique_id_name === false)
                return false;
        
        $last_child = self::get('SUBMISSIONS', array($unique_id_name => $submission_id), array('%d'), 'var', 0, 1, 'last_child');
        
        if($last_child === null)
            return false;
        elseif((int)$last_child === 0)
            return $submission_id;
        
        return $last_child;
    }
    
    public static function get_oldest_submission_from_group($submission_id){
        $last_child = self::get_latest_submission_from_group($submission_id);
        
        switch ($last_child){                
            case false:
                return false;
                
            default:
                global $wpdb;
                $table_name = RM_Table_Tech::get_table_name_for('SUBMISSIONS');
                $unique_id_name = RM_Table_Tech::get_unique_id_name('SUBMISSIONS');
                $first_parent = $wpdb->get_var("SELECT MIN($unique_id_name) FROM $table_name WHERE `last_child` = $last_child");
                if((int)$first_parent)
                    return $first_parent;
                else 
                    return false;
        }
    }
    
    public static function update_submission_group_last_child($old_val, $new_val){
        global $wpdb;
        $table_name = RM_Table_Tech::get_table_name_for('SUBMISSIONS');
        return $wpdb->query("UPDATE $table_name SET `last_child` = $new_val WHERE `last_child` = $old_val");
    }
    
    public static function get_visitors_count($form_id){
        
        if(!$form_id)
            return null;
        
        global $wpdb;
        $table_name = RM_Table_Tech::get_table_name_for('STATS');
        return $wpdb->get_var($wpdb->prepare("SELECT COUNT(DISTINCT user_ip) FROM $table_name WHERE `form_id` = %d AND `visited_on` >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 29 DAY) AND `visited_on` <  UNIX_TIMESTAMP(CURDATE() + INTERVAL 1 DAY)",$form_id));
    }
    
    public static function get_sent_emails($form_id, $filter, $selection="*", $sort_by = 'mail_id', $descending = true)
    {
          global $wpdb;
          $table_name = RM_Table_Tech::get_table_name_for('SENT_EMAILS');
          $uid = RM_Table_Tech::get_unique_id_name('SENT_EMAILS');
          $qry = "";
          $interval_string = null;
          $field_search_string = null;
          $searched = false;
          $filters= $filter->filters;

          $where_array = array();
          $format_clause = "";
          $where_clause = "";
          
          $wpdb->query('SET time_zone = "+00:00"');

          if($form_id != null)
              $where_array[] = "`form_id` = $form_id";

          if (isset($filters['rm_field_to_search'],$filters['rm_value_to_search']) && trim($filters['rm_value_to_search']) != '')
          {
              $searched = true;
              //Sanitize against incorrect column name
              if(!in_array($filters['rm_field_to_search'], array('to','sub','body')))
                      $filters['rm_field_to_search'] = 'body';

              $field_name = $filters['rm_field_to_search'];
              
              //Prepare search value.
              //Replace spaces with % wildcard so that html tags do not hinder search.
              //For example: search term "Hi, user" will not match actual content "Hi.<br><br>user" while it should.
              //So we prepare search term as "Hi.%user" which will match the content.
              
              $search_term = trim($filters['rm_value_to_search']);
              $search_term = htmlspecialchars($search_term);
              $search_term = $wpdb->esc_like($search_term);
              $search_term = preg_replace("/[\s]+/", '%', $search_term);
              $field_value = $search_term;

              $field_search_string = "`$field_name` LIKE '%$field_value%'";
              $where_array[] = $field_search_string;
          }

          switch (strtolower($filters['rm_interval']))
          {
              case 'today':
                  $interval_string = 'BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 1 day)';
                  break;
              case 'week':
                  $interval_string = ' >DATE_ADD(CURDATE(), INTERVAL 1-DAYOFWEEK(CURDATE()) DAY) ';
                  break;
              case 'month':
                  $interval_string = 'BETWEEN DATE_SUB(CURDATE(),INTERVAL (DAY(CURDATE())-1) DAY) AND LAST_DAY(NOW())';
                  break;
              case 'year':
                  $interval_string = 'BETWEEN DATE_FORMAT(NOW() ,"%Y-01-01") AND LAST_DAY(NOW())';
                  break;
              case 'custom':
                     if($filters['rm_fromdate'] != '' || $filters['rm_dateupto'] != '')
                     {
                         if($filters['rm_fromdate'] == '')
                         {
                             $interval_string = '<= \''.$filters['rm_dateupto'].'\'';
                         }
                         elseif($filters['rm_dateupto'] == '')
                         {
                             $interval_string = '>= \''.$filters['rm_dateupto'].'\'';
                         }
                         else
                         $interval_string = 'BETWEEN \''.$filters['rm_fromdate'].' 00:00:00\' AND \''.$filters['rm_dateupto'].' 23:59:59\'';

                         break;
                     }
                     //Let it fall through to 'all' case.
              case 'all':
              default:
                  $interval_string = null;
          }

          if($interval_string !== null)
              $where_array[] = "(`sent_on` $interval_string)";

          if(count($where_array) > 0)
              $where_clause = "WHERE ".implode(" AND ",$where_array);
          else
              $where_array = "WHERE 1";        

          if($selection!="count(*) as count"){
              if (!$descending)
                  $format_clause = "ORDER BY `$sort_by` LIMIT ".$filter->pagination->entries_per_page." OFFSET ".$filter->pagination->offset;
              else
                  $format_clause = "ORDER BY `$sort_by` DESC LIMIT ".$filter->pagination->entries_per_page." OFFSET ".$filter->pagination->offset;
          }

          $qry = "SELECT $selection FROM `$table_name` $where_clause $format_clause";

          $results = $wpdb->get_results($qry);

          if (is_array($results) && count($results) === 0)
          {
              return null;
          }       

          return $results;

      }
      
      public static function run_query($query, $result_type='results')
      {
          global $wpdb;
          
          if ($result_type === 'results' || $result_type === 'row' || $result_type === 'var' || $result_type === 'col')
          {
            $method_name = 'get_' . $result_type;
            
            $results = $wpdb->$method_name($query);
            
            return $results;
          } 
      }

}

