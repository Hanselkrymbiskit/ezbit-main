<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
 
/*
 * Helper functions for building a DataTables server-side processing SQL query
 *
 * The static functions in this class are just helper functions to help build
 * the SQL used in the DataTables demo server-side processing scripts. These
 * functions obviously do not represent all that can be done with server-side
 * processing, they are intentionally simple to show how it works. More complex
 * server-side processing operations will likely require a custom script.
 *
 * See http://datatables.net/usage/server-side for full details on the server-
 * side processing requirements of DataTables.
 *
 * @license MIT - http://datatables.net/license_mit
 */
 #[AllowDynamicProperties]
class Datatables {

    public static $conn;
    public static $sqlhelper = "";
    public $CI;
    
    function __construct()
    {
        $this->CI =& get_instance();
        self::$conn =  array(
            'user' => $this->CI->remote1->username,
            'pass' => $this->CI->remote1->password,
            'db'   => $this->CI->remote1->database,
            'host' => $this->CI->remote1->dsn,
            'encrypt' => @$this->CI->remote1->encrypt
        );
        
        // self::$sqlhelper = $this->CI->sqlhelper->local;
    }
    
    /**
     * Create the data output array for the DataTables rows
     *
     *  @param  array $columns Column information array
     *  @param  array $data    Data from the SQL get
     *  @return array          Formatted data in a row based format
     */
    static function data_output ( $columns, $data, $datatablesessionid = '' )
    {
        $out = array();
        for ( $i=0, $ien=count($data) ; $i<$ien ; $i++ ) {
            $row = array();
            for ( $j=0, $jen=count($columns) ; $j<$jen ; $j++ ) {
                $column = $columns[$j];
                // Is there a formatter?
                if ( isset( $column['formatter'] ) ) {
                    $row[ $column['dt'] ] = $column['formatter']( $data[$i][ $column['db'] ], $data[$i], $column['db'], $datatablesessionid, $column['field'] );
                }
                else {
                    $row[ $column['dt'] ] = $data[$i][ $columns[$j]['db'] ];
                }
            }
            $out[] = $row;
        }
        return $out;
    }
    /**
     * Database connection
     *
     * Obtain an PHP PDO connection from a connection details array
     *
     *  @param  array $conn SQL connection details. The array should have
     *    the following properties
     *     * host - host name
     *     * db   - database name
     *     * user - user name
     *     * pass - user password
     *  @return resource PDO connection
     */
    static function db ( $conn )
    {
        
        if ( is_array( $conn ) ) {
            return self::sql_connect( $conn );
        }
        else
        {
            return self::sql_connect( self::$conn ); 
        }

        return $conn;
    }
    /**
     * Paging
     *
     * Construct the LIMIT clause for server-side processing SQL query
     *
     *  @param  array $request Data sent to server by DataTables
     *  @param  array $columns Column information array
     *  @return string SQL limit clause
     */
    static function limit ( $request, $columns )
    {
        $limit = '';
        if ( isset($request['start']) && $request['length'] != -1 ) {
            $limit = "LIMIT ".intval($request['start']).", ".intval($request['length']);
        }
        return $limit;
    }
    /**
     * Ordering
     *
     * Construct the ORDER BY clause for server-side processing SQL query
     *
     *  @param  array $request Data sent to server by DataTables
     *  @param  array $columns Column information array
     *  @return string SQL order by clause
     */
    static function order ( $request, $columns )
    {
        $order = '';
        if ( isset($request['order']) && count($request['order']) ) {
            $orderBy = array();
            $dtColumns = self::pluck( $columns, 'dt' );
            for ( $i=0, $ien=count($request['order']) ; $i<$ien ; $i++ ) {
                // Convert the column index into the column data property
                $columnIdx = intval($request['order'][$i]['column']);
                $requestColumn = $request['columns'][$columnIdx];
                $columnIdx = array_search( $requestColumn['data'], $dtColumns );
                $column = $columns[ $columnIdx ];
                if ( $requestColumn['orderable'] == 'true' ) {
                    $dir = $request['order'][$i]['dir'] === 'asc' ?
                        'ASC' :
                        'DESC';
                    $orderBy[] = '`'.$column['db'].'` '.$dir;
                }
            }
            if ( count( $orderBy ) ) {
                $order = 'ORDER BY '.implode(', ', $orderBy);
            }
        }
        return $order;
    }
    /**
     * Searching / Filtering
     *
     * Construct the WHERE clause for server-side processing SQL query.
     *
     * NOTE this does not match the built-in DataTables filtering which does it
     * word by word on any field. It's possible to do here performance on large
     * databases would be very poor
     *
     *  @param  array $request Data sent to server by DataTables
     *  @param  array $columns Column information array
     *  @param  array $bindings Array of values for PDO bindings, used in the
     *    sql_exec() function
     *  @return string SQL where clause
     */
    static function filter ( $request, $columns, &$bindings )
    {
        $globalSearch = array();
        $columnSearch = array();
        $dtColumns = self::pluck( $columns, 'dt' );
        if ( isset($request['search']) && $request['search']['value'] != '' ) {
            $str = $request['search']['value'];
            for ( $i=0, $ien=count($request['columns']) ; $i<$ien ; $i++ ) {
                $requestColumn = $request['columns'][$i];
                $columnIdx = array_search( $requestColumn['data'], $dtColumns );
                $column = $columns[ $columnIdx ];
                if ( $requestColumn['searchable'] == 'true' ) {
                    $binding = self::bind( $bindings, '%'.$str.'%', PDO::PARAM_STR );
                    $globalSearch[] = "`".$column['db']."` LIKE ".$binding;
                }
            }
        }
        // Individual column filtering
        if ( isset( $request['columns'] ) ) {
            for ( $i=0, $ien=count($request['columns']) ; $i<$ien ; $i++ ) {
                $requestColumn = $request['columns'][$i];
                $columnIdx = array_search( $requestColumn['data'], $dtColumns );
                $column = $columns[ $columnIdx ];
                $str = $requestColumn['search']['value'];
                if ( $requestColumn['searchable'] == 'true' &&
                 $str != '' ) {
                    $binding = self::bind( $bindings, '%'.$str.'%', PDO::PARAM_STR );
                    $columnSearch[] = "`".$column['db']."` LIKE ".$binding;
                }
            }
        }
        // Combine the filters into a single string
        $where = '';
        if ( count( $globalSearch ) ) {
            $where = '('.implode(' OR ', $globalSearch).')';
        }
        if ( count( $columnSearch ) ) {
            $where = $where === '' ?
                implode(' AND ', $columnSearch) :
                $where .' AND '. implode(' AND ', $columnSearch);
        }
        if ( $where !== '' ) {
            $where = 'WHERE '.$where;
        }
        return $where;
    }
    /**
     * Perform the SQL queries needed for an server-side processing requested,
     * utilising the helper functions of this class, limit(), order() and
     * filter() among others. The returned array is ready to be encoded as JSON
     * in response to an SSP request, or can be modified if needed before
     * sending back to the client.
     *
     *  @param  array $request Data sent to server by DataTables
     *  @param  array|PDO $conn PDO connection resource or connection parameters array
     *  @param  string $table SQL table to query
     *  @param  string $primaryKey Primary key of the table
     *  @param  array $columns Column information array
     *  @return array          Server-side processing response array
     */
    static function simple ( $request, $conn, $table, $primaryKey, $columns )
    {
        $bindings = array();
        $db = self::db( $conn );
        // Build the SQL query string from the request
        $limit = self::limit( $request, $columns );
        $order = self::order( $request, $columns );
        $where = self::filter( $request, $columns, $bindings );
        // Main query to actually get the data
        $data = self::sql_exec( $db, $bindings,
            "SELECT `".implode("`, `", self::pluck($columns, 'db'))."`
             FROM `$table`
             $where
             $order
             $limit"
        );
        // Data set length after filtering
        $resFilterLength = self::sql_exec( $db, $bindings,
            "SELECT COUNT(`{$primaryKey}`)
             FROM   `$table`
             $where"
        );
        $recordsFiltered = $resFilterLength[0][0];
        // Total data set length
        $resTotalLength = self::sql_exec( $db,
            "SELECT COUNT(`{$primaryKey}`)
             FROM   `$table`"
        );
        $recordsTotal = $resTotalLength[0][0];
        /*
         * Output
         */
        return array(
            "draw"            => isset ( $request['draw'] ) ?
                intval( $request['draw'] ) :
                0,
            "recordsTotal"    => intval( $recordsTotal ),
            "recordsFiltered" => intval( $recordsFiltered ),
            "data"            => self::data_output( $columns, $data )
        );
    }
    /**
     * The difference between this method and the `simple` one, is that you can
     * apply additional `where` conditions to the SQL queries. These can be in
     * one of two forms:
     *
     * * 'Result condition' - This is applied to the result set, but not the
     *   overall paging information query - i.e. it will not effect the number
     *   of records that a user sees they can have access to. This should be
     *   used when you want apply a filtering condition that the user has sent.
     * * 'All condition' - This is applied to all queries that are made and
     *   reduces the number of records that the user can access. This should be
     *   used in conditions where you don't want the user to ever have access to
     *   particular records (for example, restricting by a login id).
     *
     *  @param  array $request Data sent to server by DataTables
     *  @param  array|PDO $conn PDO connection resource or connection parameters array
     *  @param  string $table SQL table to query
     *  @param  string $primaryKey Primary key of the table
     *  @param  array $columns Column information array
     *  @param  string $whereResult WHERE condition to apply to the result set
     *  @param  string $whereAll WHERE condition to apply to all queries
     *  @return array          Server-side processing response array
     */
    static function complex ( $request, $conn, $table, $primaryKey, $columns, $whereResult=null, $whereAll=null, $groupBy ='' , $targetTableSessionID = '')
    {
        $bindings = array();
        $db = self::db( ($conn =="") ? self::$conn : $conn );
        $localWhereResult = array();
        $localWhereAll = array();
        $whereAllSql = '';
        // Build the SQL query string from the request
        $limit = self::limit( $request, $columns );
        $order = self::order( $request, $columns );
        $where = self::filter( $request, $columns, $bindings );

        $whereResult = self::_flatten( $whereResult );
        $whereAll = self::_flatten( $whereAll );
        if ( $whereResult ) {
            $where = $where ?
                $where .' AND '.$whereResult :
                'WHERE '.$whereResult;
        }
        if ( $whereAll ) {
            $where = $where ?
                $where .' AND '.$whereAll :
                'WHERE '.$whereAll;
            $whereAllSql = 'WHERE '.$whereAll;
        }

        $groupBy = ($groupBy) ? ' GROUP BY '.$groupBy .' ' : '';

        // Main query to actually get the data
        $data = self::sql_exec( $db, $bindings,
            "SELECT `".implode("`, `", self::pluck($columns, 'db'))."`
             FROM $table
             $where
             $groupBy
             $order
             $limit"
        );
        
        // log_message("error", "SELECT `".implode("`, `", self::pluck($columns, 'db'))."`
        //      FROM $table
        //      $where
        //      $groupBy
        //      $order
        //      $limit");

        // Data set length after filtering
        $resFilterLength = self::sql_exec( $db, $bindings,
            "SELECT COUNT(`{$primaryKey}`)
             FROM   $table
             $where ".$groupBy
        );

        $recordsFiltered = $resFilterLength[0][0];
        // Total data set length
        $bindings = array();
        
        // $resTotalLength = self::sql_exec( $db, $bindings,
        //     "SELECT COUNT(`{$primaryKey}`)
        //      FROM   $table ".
        //     $whereAllSql." ".$groupBy
        // );

        // $recordsTotal = $resTotalLength[0][0];

        $recordsTotal = $recordsFiltered;
        /*
         * Output
         */
        return array(
            "draw"            => isset ( $request['draw'] ) ?
                intval( $request['draw'] ) :
                0,
            "recordsTotal"    => intval( $recordsTotal ),
            "recordsFiltered" => intval( $recordsFiltered ),
            "data"            => self::data_output( $columns, $data, $targetTableSessionID )
        );
    }


    /**
     * Connect to the database
     *
     * @param  array $sql_details SQL server connection details array, with the
     *   properties:
     *     * host - host name
     *     * db   - database name
     *     * user - user name
     *     * pass - user password
     * @return resource Database connection handle
     */
    static function sql_connect ( $sql_details )
    {
        try {
            $pdo_options = [];
            if( @$sql_details['encrypt'] <> '' && is_array(@$sql_details['encrypt']) )
            {
                $pdo_options = array(
                    //PDO::ATTR_ERRMODE               => PDO::ERRMODE_EXCEPTION,
                    PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT  => @$sql_details['encrypt']['ssl_verify']
                );

                if( @$sql_details['encrypt']['ssl_capath'] <> '')
                {
                    $pdo_options[PDO::MYSQL_ATTR_SSL_CA ] = $sql_details['encrypt']['ssl_capath'];
                }

            }

            $db = @new PDO(
                $sql_details['host'],
                $sql_details['user'],
                $sql_details['pass'],
                $pdo_options
            );
        }
        catch (PDOException $e) {
            self::fatal(
                "An error occurred while connecting to the database. ".
                "The error reported by the server was: ".$e->getMessage()
            );
        }
        return $db;
    }
    /**
     * Execute an SQL query on the database
     *
     * @param  resource $db  Database handler
     * @param  array    $bindings Array of PDO binding values from bind() to be
     *   used for safely escaping strings. Note that this can be given as the
     *   SQL query string if no bindings are required.
     * @param  string   $sql SQL query to execute.
     * @return array         Result from the query (all rows)
     */
    static function sql_exec ( $db, $bindings, $sql=null )
    {
        //log_message("db",@$sql);
        // Argument shifting
        $sqlQ = $sql;
        if ( $sql === null ) {
            $sql = $bindings;
        }
        $stmt = $db->prepare( $sql );
        //echo $sql;
        // Bind parameters
        if ( is_array( $bindings ) ) {
            for ( $i=0, $ien=count($bindings) ; $i<$ien ; $i++ ) {
                $binding = $bindings[$i];
                $stmt->bindValue( $binding['key'], $binding['val'], $binding['type'] );
            }
        }
        // Execute
        try {
            $stmt->execute();
            return $stmt->fetchAll( PDO::FETCH_BOTH );
        }
        catch (PDOException $e) {
            log_message("db",@$sqlQ);
            self::fatal( "An SQL error occurred: ".$e->getMessage() );

             
        }
        // Return all
        
    }
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
     * Internal methods
     */
    /**
     * Throw a fatal error.
     *
     * This writes out an error message in a JSON string which DataTables will
     * see and show to the user in the browser.
     *
     * @param  string $msg Message to send to the client
     */
    static function fatal ( $msg )
    {
        log_message("db",['datatable_error' => $msg]);

        // echo json_encode( array( 
        //     "error" => $msg
        // ) );
        exit(0);
    }
    /**
     * Create a PDO binding key which can be used for escaping variables safely
     * when executing a query with sql_exec()
     *
     * @param  array &$a    Array of bindings
     * @param  *      $val  Value to bind
     * @param  int    $type PDO field type
     * @return string       Bound key to be used in the SQL where this parameter
     *   would be used.
     */
    static function bind ( &$a, $val, $type )
    {
        $key = ':binding_'.count( $a );
        $a[] = array(
            'key' => $key,
            'val' => $val,
            'type' => $type
        );
        return $key;
    }
    /**
     * Pull a particular property from each assoc. array in a numeric array, 
     * returning and array of the property values from each item.
     *
     *  @param  array  $a    Array to get data from
     *  @param  string $prop Property to read
     *  @return array        Array of property values
     */
    static function pluck ( $a, $prop )
    {
        $out = array();
        for ( $i=0, $len=count($a) ; $i<$len ; $i++ ) {
            $out[] = $a[$i][$prop];
        }
        return $out;
    }
    /**
     * Return a string from an array or a string
     *
     * @param  array|string $a Array to join
     * @param  string $join Glue for the concatenation
     * @return string Joined string
     */
    static function _flatten ( $a, $join = ' AND ' )
    {
        if ( ! $a ) {
            return '';
        }
        else if ( $a && is_array($a) ) {
            return implode( $join, $a );
        }
        return $a;
    }

    /*
    DataTable Table Display
    $sconfig = array(
        'tableid'           =>'',
        'dbtable'           =>'',
        'dbtableprimary'    =>'',
        'ajaxdatasource'    =>'',
        'title'             =>'',
        'defaultorder'      =>'',
        'dataexport'        =>true,
        'tablecondensed'    =>true or false,
        'bactionscolumn'    =>array(
                                "enable"       =>false,
                                "add"          =>false,
                                "addmethod"    =>'',
                                "view"         =>false,
                                "viewmethod"   =>'',
                                "edit"         =>false,
                                "editmethod"   =>'',
                                "delete"       =>false,
                                "deletemethod" =>'',
                                "checkbox"     =>false,
                                "indexnumber"  =>false,
                            ),
        'column'            =>array(
                                array(
                                  // DataTables Properties
                                  'fieldid'            => '',
                                  'fieldlabel'         => '',
                                  'fieldtype'          => '', // text,date,time,datetime,numeric
                                  'base64_encrypted'   => false, // true or false
                                  'searchable'         => true, // true or false
                                  'advancesearch'      => true, // true or false
                                  'orderable'          => true, // true or false
                                  'width'              => '15', 
                                  'columnClass'        => '',
                                  'visible'            => true,
                                  'exportable'         => true,

                                  // For Advance Search Display
                                  'inputdisplay'       => '', // input,select,checkbox,radio,textarea use for advance search display
                                  'inputcustomattr'    => '',
                                  'inputcustomclass'   => '',

                                  // Reference Config
                                  'referenceid'        => '', // use for Display Result and Advance Search
                                  'referencefilter'    => '', // use for reference filter
                                  'referenceorder'     => '',

                                  // Parent Child Relation
                                  'parentfieldid'      => '', 
                                  'childfieldid'       => '',
                                  'childfieldfilter'   => '',
                                ),
                            ),
        'scrollx'           =>false,
        'scrollpagination'  =>false,
        'actiobbuttons'     =>'', // html content
        'fixedcolumns'      =>array("leftColumns"=>0,"rightColumns"=>0), // Column Number to Freeze
        'callbackjsfunction'=>'',
        'bordercolor'       =>'', // white, success, warning, info,danger,secondary,dark,primary
    );

    $returnMode = 1 default, array('datatablehtml'=>'','datatablejscall','dropdowncall');
    */

    public function ShowDataTable($sconfig = '',$returnMode = 1)
    {
        if(!is_array($sconfig))
        {
            return '';
        }

        if( @$sconfig['tableid'] == '' )
        {
            return '';
        }
     
        $tableid = trim(str_replace(' ','',@$sconfig['tableid']));
        $_SESSION['DataTables'][$tableid] = @$sconfig['tableid'];
        
        $DataTableHTML = '';
        $requiredParam = array(
            'tableid'           => array(
                                    'valuemode' => 'string', // String , Array, Boolean,
                                    'required'  => true, // true or false
                                    'default'   => '',
                                    'errormessage' => 'DataTable ID is not define!',
                                ),
            'dbtable'           => array(
                                    'valuemode' => 'string', // String , Array, Boolean,
                                    'required'  => false, // true or false
                                    'default'   => '',
                                    'errormessage' => '',
                                ),
            'dbtableprimary'    => array(
                                    'valuemode' => 'string', // String , Array, Boolean,
                                    'required'  => false, // true or false
                                    'default'   => '',
                                    'errormessage' => '',
                                ),
            'ajaxdatasource'    => array(
                                    'valuemode' => 'string', // String , Array, Boolean,
                                    'required'  => true, // true or false
                                    'default'   => '',
                                    'errormessage' => 'DataTable Datasource is not define!',
                                ),
            'title'             => array(
                                    'valuemode' => 'string', // String , Array, Boolean,
                                    'required'  => false, // true or false
                                    'default'   => '',
                                    'errormessage' => '',
                                ),
            'defaultorder'      => array(
                                    'valuemode' => 'array', // String , Array, Boolean,
                                    'required'  => false, // true or false
                                    'default'   => '',
                                    'errormessage' => '',
                                ),
            'dataexport'        => array(
                                    'valuemode' => 'boolean', // String , Array, Boolean,
                                    'required'  => false, // true or false
                                    'default'   => false,
                                    'errormessage' => '',
                                ),
            'tablecondensed'    => array(
                                    'valuemode' => 'String', // String , Array, Boolean,
                                    'required'  => false, // true or false
                                    'default'   => false,
                                    'errormessage' => '',
                                ),
            'bactionscolumn'     => array(
                                    'valuemode' => 'array', // String , Array, Boolean,
                                    'required'  => false, // true or false
                                    'default'   => array(
                                                    "enable"       =>false,
                                                    "add"          =>false,
                                                    "addmethod"    =>'',
                                                    "view"         =>false,
                                                    "viewmethod"   =>'',
                                                    "edit"         =>false,
                                                    "editmethod"   =>'',
                                                    "delete"       =>false,
                                                    "deletemethod" =>'',
                                                    "checkbox"     =>false,
                                                    "indexnumber"  =>false,
                                                ),
                                    'errormessage' => '',
                                ),
            'column'            => array(
                                    'valuemode' => 'array', // String , Array, Boolean,
                                    'required'  => true, // true or false
                                    'default'   => array(),
                                    'errormessage' => 'Table Column is not define!',
                                ),
            'scrollx'           => array(
                                    'valuemode' => 'boolean', // String , Array, Boolean,
                                    'required'  => false, // true or false
                                    'default'   => false,
                                    'errormessage' => '',
                                ),
            'scrollpagination'  => array(
                                    'valuemode' => 'boolean', // String , Array, Boolean,
                                    'required'  => false, // true or false
                                    'default'   => false,
                                    'errormessage' => '',
                                ),
            'fixedcolumns'      => array(
                                    'valuemode' => 'array', // String , Array, Boolean,
                                    'required'  => false, // true or false
                                    'default'   => array("leftColumns"=>0,"rightColumns"=>0),
                                    'errormessage' => '',
                                ),
            'actiobbuttons'     => array(
                                    'valuemode' => 'string', // String , Array, Boolean,
                                    'required'  => false, // true or false
                                    'default'   => '',
                                    'errormessage' => '',
                                ),
            'callbackjsfunction'=>array(
                                    'valuemode' => 'string', // String , Array, Boolean,
                                    'required'  => false, // true or false
                                    'default'   => '',
                                    'errormessage' => '',
                                ),
            'bordercolor'=>array(
                                    'valuemode' => 'string', // String , Array, Boolean,
                                    'required'  => false, // true or false
                                    'default'   => '', // white, success, warning, info,danger,secondary,dark,primary
                                    'errormessage' => '',
                                ),
            'pushparameter'      => array(
                                    'valuemode' => 'array', // String , Array, Boolean,
                                    'required'  => false, // true or false
                                    'default'   => '',
                                    'errormessage' => '',
                                ),
            'lengthChange'      => array(
                                    'valuemode' => 'boolean', // String , Array, Boolean,
                                    'required'  => false, // true or false
                                    'default'   => true,
                                    'errormessage' => '',
                                ),
            'panelclass'        =>array(
                                    'valuemode' => 'string', // String , Array, Boolean,
                                    'required'  => false, // true or false
                                    'default'   => '',
                                    'errormessage' => '',
                                ),
            'headershow'        =>array(
                                    'valuemode' => 'Boolean', // String , Array, Boolean,
                                    'required'  => false, // true or false
                                    'default'   => true,
                                    'errormessage' => '',
                                ),
            'headerclass'        =>array(
                                    'valuemode' => 'string', // String , Array, Boolean,
                                    'required'  => false, // true or false
                                    'default'   => '',
                                    'errormessage' => '',
                                ),
            'footershow'        =>array(
                                    'valuemode' => 'Boolean', // String , Array, Boolean,
                                    'required'  => false, // true or false
                                    'default'   => true,
                                    'errormessage' => '',
                                ),
            'footerclass'        =>array(
                                    'valuemode' => 'string', // String , Array, Boolean,
                                    'required'  => false, // true or false
                                    'default'   => '',
                                    'errormessage' => '',
                                ),
 
        );

      
        
        foreach( $requiredParam as $c => $dv )
        {
            if( isset($sconfig[$c]) )
            {

                if( gettype($sconfig[$c]) <> strtolower($dv['valuemode']) )
                {
                    $sconfig[$c] = $dv['default'];
                }
                else
                {
                    if( $c == 'bactionscolumn' )
                    {
                        $scVal = $sconfig[$c];

                        $sconfig[$c] = $dv['default'];
                        foreach( $sconfig[$c] as $sck => $scv )
                        {
                            $sconfig[$c][$sck] = (isset($scVal[$sck])) ? $scVal[$sck] : $scv;
                        }
                    }
                }
            }
            else
            {
                $sconfig[$c] = $dv['default'];
            }
            


            if( $dv['required'] && $sconfig[$c] == "" )
            {
                return 'Invalid DataTable Config : '+$dv['errormessage'];
                break;
            } 
        }

        // Create DataTables Session for Back DataSource Processing
        

        // Create Table
        if( count($sconfig['column']) > 0 )
        {
            $dtColumns = array(); $th = ''; 
            $visibleColumn = 0;
            $ocolumnCnt = count($sconfig['column']);
            $abcnt = 0;
            $addEnable = ($sconfig['bactionscolumn']['add']) ? true : false ;
            $deleteEnable = ($sconfig['bactionscolumn']['delete']) ? true : false;
            $editEnable = ($sconfig['bactionscolumn']['edit']) ? true : false;
            $searchableCnt = 0;
            $chkBoxEnable = ($sconfig['bactionscolumn']['checkbox']) ? true : false ;
            $indexnumberEnable = ($sconfig['bactionscolumn']['indexnumber']) ? true : false ;
            $advancesearhItem = '';
            $advancesearhItemCnt = 0;
            $eleJSScript = '';

            $addfmethod     = ($sconfig['bactionscolumn']['addmethod'] <> '') ? 'fmethod="'.$sconfig['bactionscolumn']['addmethod'].'"' : '';
            $editfmethod    = ($sconfig['bactionscolumn']['editmethod'] <> '') ? 'fmethod="'.$sconfig['bactionscolumn']['editmethod'].'"' : '';
            $deletefmethod  = ($sconfig['bactionscolumn']['deletemethod'] <> '') ? 'fmethod="'.$sconfig['bactionscolumn']['deletemethod'].'"' : '';

            $viewfmethod  = ($sconfig['bactionscolumn']['viewmethod'] <> '') ? 'fmethod="'.$sconfig['bactionscolumn']['viewmethod'].'"' : '';

            // unset($sconfig['bactionscolumn']['addmethod'],$sconfig['bactionscolumn']['editmethod'],$sconfig['bactionscolumn']['deletemethod'],$sconfig['bactionscolumn']['viewmethod']);

            if( $sconfig['bactionscolumn']['enable'] )
            {
                $crudb = array();
                $columVirtualID = @$sconfig['column'][0]['fieldid'];
                
                if($columVirtualID <> "")
                {
                    foreach($sconfig['bactionscolumn'] as $ak => $aval)
                    {
                        $excludeKeys = array('enable','add','addmethod','editmethod','deletemethod','viewmethod');
                        if( !in_array($ak, $excludeKeys) && $aval)
                        {
                            $pColumn = array(
                                array(
                                  'fieldid'            => $columVirtualID.'_crud_'.$ak,
                                  'fieldlabel'         => '',
                                  'fieldtype'          => '', // text,date,time,datetime,numeric
                                  'base64_encrypted'   => false, // true or false
                                  'searchable'         => false, // true or false
                                  'advancesearch'      => false, // true or false
                                  'orderable'          => false, // true or false
                                  'width'              => '2%', 
                                  'className'        => '',
                                  'visible'            => true,
                                  'exportable'         => false,
                                  // For Advance Search Display
                                  'inputdisplay'       => '', // input,select,checkbox,radio,textarea use for advance search display
                                  'inputcustomattr'    => '',
                                  'inputcustomclass'   => '',
                                  // For List Display & Adavance Search Selection
                                  'referenceid'        => '', // use for Display Result and Advance Search
                                  'referencefilter'    => '', // use for reference filter
                                  'parentfieldid'      => '', // parent field for reference filter & advance search
                                  'excludecellfocus'   => false,
                                )
                            );

                            $tcolumn = $sconfig['column'];
                            $sconfig['column'] = array_merge($pColumn,$tcolumn);
                            $abcnt++;

                            
                        }
                    }
                }
            }

            if( $abcnt > 0 )
            {
                $sconfig['fixedcolumns']['leftColumns'] = (int) $sconfig['fixedcolumns']['leftColumns'] + (int) $abcnt;

                if(is_array($sconfig['defaultorder']) && count($sconfig['defaultorder']) > 0 )
                {   
                    foreach($sconfig['defaultorder'] as $dkeys => $dorder)
                    {
                       $sconfig['defaultorder'][$dkeys][0] = $sconfig['defaultorder'][$dkeys][0] + ( ( (int) $abcnt > 0 ) ? ((int) $abcnt -1) : 0 );
                    }
                }
            }

            @$keysColumn = [];
            $keysColumnCnt = 0;
            $autoWidth = (100-($abcnt*2)) / $ocolumnCnt;

            foreach( $sconfig['column'] as $td => $tdprop)
            {
                $keysColumnCnt++;
                if($tdprop['searchable'])
                {
                    $searchableCnt++;
                }

                if(@$tdprop['fieldid'] <> '')
                {
                    $thDisplay = @$tdprop['fieldlabel'];

                    if( strpos($tdprop['fieldid'],'_crud_indexnumber') > -1 )
                    {
                        $thDisplay = '#';
                    }

                    $th .= '<th class="'.(($tdprop['exportable']) ? '' : 'noExport').' '.@$sconfig['headerclass'].'">'.$thDisplay.'</th>';
                    
                    if( @$tdprop['width'] <> "")
                    {
                        $wtd = $tdprop['width'];
                        switch($wtd)
                        {
                            case is_numeric($wtd):
                                $cwidth = $wtd.'%';
                            break;

                            case !is_numeric($wtd):
                                
                                $pPosition = strpos($wtd,'%');
                                $pxPosition = strpos($wtd,'px');
                                $twtd = str_replace(array('%','px'),'',$wtd);

                                // Percent 
                                if($pPosition > -1)
                                {
                                    $cwidth = ( (is_numeric($twtd)) ? $twtd : $autoWidth ).'%'  ;
                                }


                                // PX
                                if($pxPosition > -1)
                                {
                                    $cwidth = ( (is_numeric($twtd)) ? $twtd : $autoWidth.'%' ).'px'  ;
                                }

                            break;
                        }
                    }
                    else
                    {
                        $cwidth = $autoWidth.'%';
                    }

                    $crudField = false;
                    switch( $tdprop['fieldid'] )
                    {
                        case strpos($tdprop['fieldid'],'_crud_view') > -1:
                        case strpos($tdprop['fieldid'],'_crud_edit') > -1:
                        case strpos($tdprop['fieldid'],'_crud_delete') > -1:
                        case strpos($tdprop['fieldid'],'_crud_checkbox') > -1:
                        case strpos($tdprop['fieldid'],'_crud_indexnumber') > -1:
                            $cwidth = '2%';
                            $crudField = true;
                            $tdprop['className'] = 'align-middle';
                        break;
                    }
                    
                    if(!isset($tdprop['excludecellfocus']))
                    {
                        $tdprop['excludecellfocus'] = false;
                    }


                    if(!$crudField && !$tdprop['excludecellfocus'] )
                    {
                       $keysColumn[] = ($keysColumnCnt-1);
                    }   
            

                    if( $tdprop['visible'] )
                    {
                        $visibleColumn++;
                    }

                    $dtColumns[] = "
                    {
                        targets     : ".$td.",
                        name        : '".@$tdprop['fieldid']."',
                        title       : '".@$thDisplay."',
                        tooltip     : '".@$tdprop['fieldlabel']."',
                        orderable   : ".( ($tdprop['orderable'] ) ? 'true' : 'false').",
                        searchable  : ".( ($tdprop['searchable'] ) ? 'true' : 'false').",
                        visible     : ".( ($tdprop['visible'] ) ? 'true' : 'false').",
                        className   : '".@$tdprop['className']."',
                        width       : '".$cwidth."',
                        render      : function(data, type, row, meta){
                            return data;
                        },
                        ".( ($chkBoxEnable && strpos($tdprop['fieldid'],'_crud_checkbox') > -1) ? "
                        checkboxes  : {
                           'selectRow': true,
                        }
                        " : "")."  
                    }
                    ";

                    // $dtColumns[] = "
                    // {
                    //     targets     : ".$td.",
                    //     name        : '".@$tdprop['fieldid']."',
                    //     title       : '".@$thDisplay."',
                    //     tooltip     : '".@$tdprop['fieldlabel']."',
                    //     orderable   : ".( ($tdprop['orderable'] ) ? 'true' : 'false').",
                    //     searchable  : ".( ($tdprop['searchable'] ) ? 'true' : 'false').",
                    //     visible     : ".( ($tdprop['visible'] ) ? 'true' : 'false').",
                    //     className   : '".@$tdprop['className']."',
                    //     width       : '".$cwidth."',
                    //     render      : function(data, type, row, meta){
                    //         return data;
                    //     },
                      
                    // }
                    // ";


                    if( $tdprop['referenceid'] <> "" && is_numeric($tdprop['referenceid']) )
                    {
                        // Get References
                    }

                    $inputgroupstyle = '';
                    // Advance Search HTML
                    if( $tdprop['searchable'] )
                    {
                        switch( strtolower($tdprop['inputdisplay']) )
                        {
                            case "checkbox":
                            case "radio":
                                
                                $etype = strtolower($tdprop['inputdisplay']);

                                if( $tdprop['referenceid'] <> "" && is_numeric($tdprop['referenceid']) )
                                {
                                    $aitemTmp = '';
                                    $referenceConfig = $this->CI->myutilities->getReferenceConfig(@$tdprop['referenceid']);

                                    $refItem = $this->CI->sqlhelper->remote1->select($referenceConfig['Reference_Table'],$referenceConfig['Reference_Value_Field']." as rval, ".$referenceConfig['Reference_Description_Field']." as rdesc")->result();
                                    if($refItem['Count'] > 0)
                                    {
                                        foreach($refItem['Data'] as $rRow => $rCols)
                                        {
                                            $aitemTmp .= '
                                                <div class="'.$etype.'-inline '.$etype.'-custom '.$etype.'-primary">
                                                    <input type="'.$etype.'" id="'.@$tdprop['fieldid'].'_'.$rCols['rval'].'" name="'.@$tdprop['fieldid'].'[]" forminputgroup="DTTableAdvanceSearch_'.$tableid.'" value="'.$rCols['rval'].'" title="'.$rCols['rdesc'].'">
                                                    <label class="font-size-13" for="'.@$tdprop['fieldid'].'_'.$rCols['rval'].'">'.$rCols['rdesc'].'</label>
                                                </div>
                                            ';
                                        }  
                                    }
                                    
                                    $aitemElement = $aitemTmp;
                                    
                                }
                                else
                                {
                                    goto emoveHere;
                                }

                            break;


                            case "select":
                                $aitemElement = '<select id="'.@$tdprop['fieldid'].'" name="'.@$tdprop['fieldid'].'" forminputgroup="DTTableAdvanceSearch_'.$tableid.'" class="form-control  font-size-13" autocomplete="off" title="'.@$tdprop['fieldlabel'].'"><option>Search '.$tdprop['fieldlabel'].'</option></select>';

                                if( is_numeric($tdprop['referenceid']) )
                                {
                                    $selectrefArray = array(
                                        'referenceid'        => @$tdprop['referenceid'], 
                                        'referencefilter'    => @$tdprop['referencefilter'], 
                                        'referenceorder'     => @$tdprop['referenceorder'], 
                                        'parentfieldid'      => @$tdprop['parentfieldid'], 
                                        'childfieldid'       => @$tdprop['childfieldid'],
                                        'childfieldfilter'   => @$tdprop['childfieldfilter'],
                                        'forminputgroup'     => 'DTTableAdvanceSearch_'.$tableid,
                                    );

                                    // $eleJSScript .= 'addSelectReferenceObjectList(\''.$tdprop['fieldid'].'\','.json_encode($selectrefArray).');';

                                    $eleJSScript .= 'DataTableSelect[\''.$tdprop['fieldid'].'\'] = '.json_encode($selectrefArray).';';
                                }

                                
                            break;
                            case "time":

                                $aitemElement = '<input type="text" class="form-control '.@$elementconfig['class'].' " id="'.@$tdprop['fieldid'].'" name="'.@$tdprop['fieldid'].'" placeholder="hh:mm" forminputgroup="DTTableAdvanceSearch_'.$tableid.'" autocomplete="off" data-plugin="formatter,clockpicker" data-pattern="[[99]]:[[99]]">';

                            break;
                            case "timerange":

                                $inputgroupstyle = 'w-400';
                                $aitemElement = '<span class="input-group-addon w-50 mt-10">From :</span>';
                                $aitemElement .= '
                                                <div class="form-control-wrap w-150">
                                                    <input type="text" class="form-control '.@$elementconfig['class'].' w-150 text-center" id="'.@$tdprop['fieldid'].'[FROM]" name="'.@$tdprop['fieldid'].'[FROM]" placeholder="hh:mm" forminputgroup="DTTableAdvanceSearch_'.$tableid.'" autocomplete="off" data-plugin="formatter,clockpicker" data-pattern="[[99]]:[[99]]">
                                                </div>';

                                $aitemElement .= '<span class="input-group-addon w-50 mt-10 text-center">To :</span>';
                                $aitemElement .= '
                                                <div class="form-control-wrap w-150">
                                                    <input type="text" class="form-control '.@$elementconfig['class'].' w-150 text-center" id="'.@$tdprop['fieldid'].'[TO]" name="'.@$tdprop['fieldid'].'[TO]" placeholder="hh:mm" forminputgroup="DTTableAdvanceSearch_'.$tableid.'" autocomplete="off" data-plugin="formatter,clockpicker" data-pattern="[[99]]:[[99]]">
                                                </div>';
                            break;
                            case "date":

                                $aitemElement = '<input type="text" class="form-control '.@$elementconfig['class'].' " id="'.@$tdprop['fieldid'].'" name="'.@$tdprop['fieldid'].'" placeholder="mm/dd/yyyy" forminputgroup="DTTableAdvanceSearch_'.$tableid.'" autocomplete="off" data-plugin="formatter,datepicker" data-pattern="[[99]]/[[99]]/[[9999]]">';

                            break;
                            case "daterange":

                                $inputgroupstyle = 'w-400';
                                $aitemElement = '<span class="input-group-addon w-50 mt-10">From :</span>';
                                $aitemElement .= '
                                                <div class="form-control-wrap w-150">
                                                    <input type="text" class="form-control '.@$elementconfig['class'].' w-150 text-center" id="'.@$tdprop['fieldid'].'[FROM]" name="'.@$tdprop['fieldid'].'[FROM]" placeholder="mm/dd/yyyy" forminputgroup="DTTableAdvanceSearch_'.$tableid.'" autocomplete="off" data-plugin="formatter,datepicker" data-pattern="[[99]]/[[99]]/[[9999]]">
                                                </div>';

                                $aitemElement .= '<span class="input-group-addon w-50 mt-10 text-center">To :</span>';
                                $aitemElement .= '
                                                <div class="form-control-wrap w-150">
                                                    <input type="text" class="form-control '.@$elementconfig['class'].' w-150 text-center" id="'.@$tdprop['fieldid'].'[TO]" name="'.@$tdprop['fieldid'].'[TO]" placeholder="mm/dd/yyyy" forminputgroup="DTTableAdvanceSearch_'.$tableid.'" autocomplete="off" data-plugin="formatter,datepicker" data-pattern="[[99]]/[[99]]/[[9999]]">
                                                </div>';
                            break;

                            case "text":
                            case "textarea":
                            default:
                                emoveHere:
                                $aitemElement = '
                                
                                    <input type="text" id="'.@$tdprop['fieldid'].'" name="'.@$tdprop['fieldid'].'" forminputgroup="DTTableAdvanceSearch_'.$tableid.'" class="form-control font-size-13" autocomplete="off" value="" placeholder="Search '.$tdprop['fieldlabel'].'" >
                                    <div id="hint_'.@$tdprop['fieldid'].'" class="hint text-success"><i class="fa fa-exclamation-circle mr-2"></i>Press Tab or terminate keyword by comma for multiple keyword search.</div>
                                ';

                                $eleJSScript .= '  
                                $("[forminputgroup=\'DTTableAdvanceSearch_'.$tableid.'\'][id=\''.$tdprop['fieldid'].'\']")
                                .on("tokenfield:createdtoken", function (e) {
                                        var el = e["target"];
                                        if(!$(el).val() && e.attrs.value )
                                        {
                                          $("input[id=\'"+$(el).attr("id")+"-tokenfield\']").removeAttr("placeholder");
                                         
                                        }
                                })
                                .on("tokenfield:removedtoken", function (e) {
                                       var el = e["target"];
                                        if(!$(el).val() && e.attrs.value )
                                        {
                                          $("input[id=\'"+$(el).attr("id")+"-tokenfield\']").attr("placeholder",$(el).attr("placeholder"));
                                        }
                                }).tokenfield({
                                    createTokensOnBlur:true,
                                    minWidth:80
                                });
                                ';
                            break;
                        }

                        if($tdprop['advancesearch'])
                        {
                            $advancesearhItemCnt++;
                            $advancesearhItem .= '
                            <div class="form-group form-material row">
                                <label id="ASearchLabel_'.@$tdprop['fieldid'].'" forminputgroup="DTTableAdvanceSearchLabel_'.$tableid.'" class="col-md-3 col-form-label">'.$tdprop['fieldlabel'].' </label>
                                <div class="col-md-1">
                                    <select id="SearchConditions_'.@$tdprop['fieldid'].'" name="SearchConditions_'.@$tdprop['fieldid'].'" forminputgroup="DTTableAdvanceSearchConditions_'.$tableid.'" class="form-control" autocomplete="off">
                                        '.( ( in_array(strtolower($tdprop['inputdisplay']),['time','timerange','date','daterange','select']) ) ? '' : '<option value="1">contains</option>' ).'
                                        
                                        <option value="2">is equal to</option>
                                    </select>
                                </div>
                                <div class="col-md-8 form-icons">
                                    <div class="input-group '.@$inputgroupstyle.'">
                                        '.@$aitemElement.'
                                    </div>
                                </div>
                            </div>
                            ';
                        }

                        
                    }
                }
            }




            if($searchableCnt > 0)
            {
                $advancesearchHTML = '
                <div id="AdvanceSearchBox_'.@$tableid.'" class="collapse multi-collapse">
                    <div id="" class="card border border-success ">
                        <div class="card-block">
                            <h6 class="card-title">Advance Search <a data-toggle="collapse" href="#AdvanceSearchBox_'.@$tableid.'" role="button" aria-expanded="false" aria-controls="AdvanceSearchBox_'.@$tableid.'" class="float-right"  title="Close Advance Search"><i class="fa fa-search-minus"></i></a></h6>
                            <div class="font-size-13">
                                <form id="AdvanceSearchForm_'.@$tableid.'" class="w-p100">
                                    '.( (@$this->CI->config->item('csrf_protection')) ? '<input type="hidden" name="'.@$this->CI->security->get_csrf_token_name().'" value="'.@$this->CI->security->get_csrf_hash().'" />' : '').'
                                    '.@$advancesearhItem.'
                                    <div class="form-group form-material row mb-0">
                                        <div class="col-md-12 text-right">
                                            <div  class="float-left" >
                                                <input type="checkbox" id="AdvanceSearchStrict_'.@$tableid.'" name="AdvanceSearchStrict_'.@$tableid.'" data-plugin="switchery" data-switchery="true" data-size="small" data-color="#11c26d" class="d-none">
                                                <label class="pt-5 ml-1" for="AdvanceSearchStrict_'.@$tableid.'">Strict Search</label>
                                            </div>
                                            <button type="button" class="btn btn-primary waves-effect waves-classic" id="AdvanceSearchButton_'.@$tableid.'" name="AdvanceSearchButton_'.@$tableid.'" data-toggle="collapse" data-target="#AdvanceSearchBox_'.@$tableid.'" aria-expanded="false" aria-controls="AdvanceSearchBox_'.@$tableid.'">Search</button>
                                            <button type="button" class="btn btn-primary waves-effect waves-classic ml-5" id="AdvanceSearchClearButton_'.@$tableid.'" name="AdvanceSearchClearButton_'.@$tableid.'">Clear</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                ';
            }

            $searchResultHTML = '
            <div id="SearchResultBox_'.@$tableid.'" class="card border border-success mb-4 d-none">
                <div class="card-block">
                  <h6 class="card-title">Search Result For :</h6>
                  <p parent="SearchResultBox_'.@$tableid.'" id="SearchResultBoxText_'.@$tableid.'" class="card-text" ></p>
                </div>
            </div>
            ';

            $_SESSION['DataTables'][$tableid] = $sconfig;

            $TableHTML = '
            <table id="DTTable_'.@$tableid.'" class="table table-hover">
                <thead><tr class="'.( (!$sconfig['headershow']) ? 'd-none' : '').'">'.@$th.'</tr></thead>
                <tbody></tbody>
                '.( ($sconfig['footershow']) ? '<tfoot><tr>'.str_replace(array("<th","</th"),array("<td","</td"),@$th).'</tr></tfoot>' : '' ).'
                
            </table>
            ';
        }

        $actionButtonsAddDelete = '';
        if( $addEnable )
        {
            $actionButtonsAddDelete .= '
                <button type="button" class="btn btn-dark" id="btn_AddRecords" name="DataTable_ActionButtons_'.@$tableid.'" '.$addfmethod.'><i class="fa fa-plus fa-fw mr-2" ></i>New</button>
            ';

            $actionButtonsAddDelete = '';
        }

        if( $editEnable && $chkBoxEnable )
        {
            $actionButtonsAddDelete .= '
                <button type="button" class="btn btn-dark" id="btn_EditSelected"  disabled="disabled" name="DataTable_ActionButtons_'.@$tableid.'" title="Delete Selected Records" '.$editfmethod.'><i class="fa fa-edit fa-fw mr-2"></i>Edit</button>
            ';
            $actionButtonsAddDelete = '';
        }

        if( $deleteEnable && $chkBoxEnable )
        {
            $actionButtonsAddDelete .= '
                <button type="button" class="btn btn-dark" id="btn_DeleteSelected" disabled="disabled"  name="DataTable_ActionButtons_'.@$tableid.'" title="Delete Selected Records" '.$deletefmethod.'><i class="fa fa-trash fa-fw mr-2"></i>Delete</button>
            ';

            $actionButtonsAddDelete = '';
        }

        $MainHTML = '
        <div class="table-responsive">
            <div id="DataTable_MainPanel_'.@$tableid.'" class="panel '.@$sconfig['panelclass'].' mb-0 '.( ($sconfig['bordercolor'] <> '') ? 'border border-'.$sconfig['bordercolor'] : '').'">
                <div id="DataTable_MainPanel_Heading_'.@$tableid.'" class="panel-heading">
                    '.( (@$sconfig['title'] == '' && @$sconfig['actiobbuttons'] == '') ? '' : '
                    <div>
                        <h6 id="DataTable_MainPanel_Heading_Title_'.@$tableid.'" class="panel-title pl-15 pr-15"><i class="fa fa-list fa-fw mr-1"></i>'.@$sconfig['title'].'</h6>
                        <div id="DataTable_MainPanel_Heading_ActionButton_'.@$tableid.'" class="panel-actions float-right">
                            '.@$actionButtonsAddDelete.'
                            '.@$sconfig['actiobbuttons'].'
                        </div>
                    </div>
                    ').'
                </div>   
                <div id="DataTable_MainPanel_Body_'.@$tableid.'" class="panel-body '.( (@$sconfig['title'] == '' && @$sconfig['actiobbuttons'] == '') ? 'pt-10' : '').' pr-10 pl-10 pb-10">
                    '.@$advancesearchHTML.'
                    '.@$searchResultHTML.'
                    <div id="DataTable_MainPanel_Body_TableContainer_'.@$tableid.'" class="">
                        '.@$TableHTML.'
                    </div>
                </div>
            </div>
        </div>
        ';

        if(count($dtColumns) > 0 && $sconfig['ajaxdatasource'] <> "")
        {
            // Load DataTables Plugins

            $JSScript  = '<script nonce="'.$this->CI->nonceV.'" src="'.assetPath().'global/vendor/datatables/datatables.min.js" type="text/javascript" ></script>';

            // $JSScript .= '<script type="text/javascript" src="'.assetPath().'global/vendor/datatables/Checkboxes/dataTables.checkboxes.js"></script>';
            // log_message("error",$keysColumn);
            $JSScript .= '
            <script id="removethis" nonce="'.$this->CI->nonceV.'" type="text/javascript">
                $(document).ready(function(){
                    DTProperty_'.$tableid.' = {
                        "target_table":"'.$tableid.'",
                        "ajax_url":"'.$sconfig['ajaxdatasource'].'",
                        "order": '.((is_array($sconfig['defaultorder']) && count($sconfig['defaultorder']) > 0) ? json_encode($sconfig['defaultorder']) : '').',
                        "dataexport":'.( ($sconfig['dataexport']) ? 'true' : 'false').',
                        "exporttitle":"'.(( $sconfig['title'] == "" ) ? "Exported Data" : $sconfig['title']).'",
                        "scrollX":'.( ($sconfig['scrollx']) ? 'true' : ( ($sconfig['scrollx']) ? (($visibleColumn > 10) ? 'true':'false') : 'false') ).',   
                        "scroller":'.( ($sconfig['scrollpagination']) ? 'true' : 'false').', 
                        "fixedcolumns":'.( ($sconfig['scrollx']) ? json_encode($sconfig['fixedcolumns']) : 'false').',  
                        "keysColumn":'.( (@$keysColumn) ? '['.implode(',',$keysColumn).']' : '""').',   
                        "searchable":'.$searchableCnt.',
                        "advancesearchable":'.$advancesearhItemCnt.',
                        "chkboxon":'.(($chkBoxEnable) ? 'true' : 'false').',
                        "indexcol":'.(($indexnumberEnable) ? 'true' : 'false').',
                        "callback":"'.(($sconfig['callbackjsfunction'] <> '') ? $sconfig['callbackjsfunction'] : '').'",
                        "pushparameter":'.( (@$sconfig['pushparameter'] <> '') ? ( (is_array($sconfig['pushparameter'])) ? ( (array_keys($sconfig['pushparameter']) !== range(0, count($sconfig['pushparameter']) - 1)) ? json_encode($sconfig['pushparameter']) : '""') : '""' ) : '""').',
                        "columnDefs":['.implode(",",$dtColumns).'],
                        "lengthChange":'.( (@$sconfig['lengthChange']) ? 'true' : 'false').',
                    };

                    gtsDataTable(DTProperty_'.$tableid.');

                    DataTableSelect = {};
                    
                    '.$eleJSScript.'

                    loadselectreference(DataTableSelect);

                    
                   
                });  
            </script> 
            ';
        }


        $DataTableHTML = $MainHTML.$JSScript;
        return @$DataTableHTML;
    }

    function customWhereStatement($DefaultParam,$aliasParam = '',$specialParam = '')
    {
      if(!is_array($DefaultParam))
      {
        return "";
      }

      $DataTableConfig = @$_SESSION['DataTables'][$DefaultParam['TableID']];
      
      if( $DataTableConfig == '' )
      {
        return "";  
      }
      $DefaultParam['ColumnProperties'] = $DataTableConfig['column'];

      $whereResult = (isset($DefaultParam['DefaultFilter']) && isset($DefaultParam['DefaultFilter']) <> '') ? base64_decode($DefaultParam['DefaultFilter']) : "" ; 
     
      $dvariable = array(
        'addedFilterMode' => array('vname'=>'SearchModeOptions','vtype'=>'array'),
        'StrictSearchMode' => array('vname'=>'StrictSearchMode','vtype'=>'string'),
        );

       // log_message("error",$DefaultParam);
      foreach($dvariable as $dk => $dval)
      {
        if( isset($DefaultParam[$dk]) )
        {
            // log_message("error",$dval);
            // ${$dval['vname']} =  '';
            switch($dval['vtype'])
            {
                case 'array':
                        ${$dval['vname']} = ( isset($DefaultParam[$dk])  ) ? @$DefaultParam[$dk] : array();
                    break;
                case 'string':
                        ${$dval['vname']} = @$DefaultParam[$dk];
                    break;    
            }   
          
            unset($DefaultParam[$dk]);
        }       
      }

      $ColumnProperties = array();
      $BetweenFilter = array();
      foreach( $DefaultParam['ColumnProperties'] as $CKeys => $CVals)
      {
        $ColumnProperties[ $CVals['fieldid'] ] = $CVals;
        if( $CVals['fieldtype'] == 'date' || $CVals['fieldtype'] == 'datetime' || $CVals['fieldtype'] == 'time' || $CVals['fieldtype'] == 'daterange' )
        {
            $BetweenFilter[$CVals['fieldid']]=array();
        }
      }

      if( !isset($StrictSearchMode) ){ $StrictSearchMode = 'AND'; }
      
      if( isset($DefaultParam['addedFilter']) )
      {
        
        $aliasFilter_Field = (is_array($aliasParam) && count($aliasParam) > 0) ? $aliasParam : array();
        // log_message('error',$aliasFilter_Field);
        foreach($DefaultParam['addedFilter'] as $keys=>$values)
        {
          $f_fields = "";
          $f_values = "";
          $where_operations = "=";
          if(array_key_exists($keys, $aliasFilter_Field))
          {
            $f_fields = $aliasFilter_Field[$keys];
          }
          else
          {
            $f_fields = $keys;
          }

          if(!is_array($values))
          {
            if(array_key_exists($keys, $ColumnProperties)  )
            {
                switch( $ColumnProperties[$keys]['inputdisplay'])
                {
                    case "date":
                    case "datetime":
                    case "time":
                        // $values = explode(",",$values);
                        $where_operations = ' in ';
                        $f_values = "('".$this->CI->myutilities->dbValueFormatter($keys,$values)."')";
                    break;
                    case "checkbox":
                        $values = explode(",",$values);
                        $where_operations = ' in ';
                        foreach($values as $xval => $yval)
                        {
                            $values[$xval] = $this->CI->myutilities->dbValueFormatter($keys,$yval);
                        }
                        $f_values = "('".implode("','",$values)."')";
                    break;

                    default:

                        if( isset($SearchModeOptions) && is_array($SearchModeOptions) && array_key_exists($keys, $SearchModeOptions) )
                        {
                            if( count(explode(",",$values)) > 0 )
                            {
                                $values = explode(",",$values);
                                if( trim($SearchModeOptions[$keys]) == 2 )
                                {
                                    $where_operations = ' in ';
                                    foreach($values as $xval => $yval)
                                    {
                                        $values[$xval] = $this->CI->myutilities->dbValueFormatter($keys,$yval);
                                    }
                                    $f_values = "('".implode("','",array_map('trim', $values))."')";
                                }
                                else
                                {
                                    $where_operations = '';
                                    $sseperator = '';
                                    foreach( $values as $searchVal )
                                    {
                                        $f_values .= $sseperator." LIKE '%".trim($this->CI->myutilities->dbValueFormatter($keys,$searchVal))."%'";
                                        $sseperator = ' OR '.$f_fields;
                                    }
                                }
                            }
                        }
                        else
                        {
                            $f_values = "'".$this->CI->myutilities->dbValueFormatter($keys,$values)."'";
                        }

                        break;
                }          
            }
            else
            {
                $f_values = "'".$values."'";
            }
          }
          else
          {
            if(array_key_exists($keys, $ColumnProperties)  )
            {
                switch( $ColumnProperties[$keys]['inputdisplay'])
                {
                    case "daterange":
                    case "datetimerange":
                    case "timerange":
                        if($values['FROM'] == $values['TO'] || is_null($values['FROM']) || $values['FROM'] == "" || is_null($values['TO']) || $values['TO'] == "" )
                        {
                            $where_operations = ' = ';
                            $f_values = "'".$this->CI->myutilities->dbValueFormatter($keys,$values['FROM'])."'";
                        }
                        else
                        {
                            $where_operations = ' between ';
                            $f_values = "'".$this->CI->myutilities->dbValueFormatter($keys,$values['FROM'])."' AND '".$this->CI->myutilities->dbValueFormatter($keys,$values['TO'])."'";
                        }
                        
                    break;
                    default:

                        if( isset($SearchModeOptions) && is_array($SearchModeOptions) && array_key_exists($keys, $SearchModeOptions) )
                        {
                            if( count(explode(",",$values)) > 0 )
                            {
                                $values = explode(",",$values);
                                if( trim($SearchModeOptions[$keys]) == 2 )
                                {
                                    $where_operations = ' in ';
                                    foreach($values as $xval => $yval)
                                    {
                                        $values[$xval] = $this->CI->myutilities->dbValueFormatter($keys,$yval);
                                    }
                                    $f_values = "('".implode("','",array_map('trim', $values))."')";
                                }
                                else
                                {
                                    $where_operations = '';
                                    $sseperator = '';
                                    foreach( $values as $searchVal )
                                    {
                                        $f_values .= $sseperator." LIKE '%".trim($this->CI->myutilities->dbValueFormatter($keys,$searchVal))."%'";
                                        $sseperator = ' OR '.$f_fields;
                                    }
                                }
                            }
                        }
                        else
                        {
                            $f_values = "'".$this->CI->myutilities->dbValueFormatter($keys,$values)."'";
                        }

                        break;
                }          
            }

            if( count($values) == count($values, COUNT_RECURSIVE) )
            { 
                switch( $ColumnProperties[$keys]['inputdisplay'])
                {
                    case "daterange":
                    case "datetimerange":
                    case "timerange":
                    break;
                    default:
                        if( count($values) > 0)
                        {
                            $where_operations = ' in ';
                            foreach($values as $xval => $yval)
                            {
                                $values[$xval] = $this->CI->myutilities->dbValueFormatter($keys,$yval);
                            }
                            $f_values = "('".implode("','",$values)."')";
                        }
                    break;
                }
              
            }
          }

          $padd_operations = '';
          if($whereResult <> '')
          {
            $padd_operations = ' '.$StrictSearchMode.' ';
           
          }
          
          if( $f_fields <> '' && $f_values <> '')
          {
            $whereResult.= $padd_operations.$f_fields." ".$where_operations." ".$f_values;
          }
          
        }
        // log_message('error',$whereResult);
        unset($DefaultParam['addedFilter']);
      } 
        // log_message("error",$whereResult);
        return $whereResult;
    }

    function getPrimaryKey($targettable)
    {

    } 

    function columnProperties($param)
    {
        $DataTableConfig = $_SESSION['DataTables'][$param['TargetTableID']];
        $Column = $DataTableConfig['column'];
        // log_message("error",$Column);
        // $extracolumn = trim(strtolower($DataTableConfig['extracolumn']));
        
        //Default Column Properties for DataTable
        if( isset($param['ccColumnProperties']) && is_array($param['ccColumnProperties']) && count($param['ccColumnProperties']) == 2)
        {
            $ccColumnProperties = $param['ccColumnProperties'];
        }
        else
        {
            $ccColumnProperties = array(
                array(
                    'db'        => $param['TablePrimaryKey'],
                    'dt'        => 'DT_RowId', // TR ID
                    'field'     => $param['TablePrimaryKey'].'_rowid',
                    'formatter' => function( $d, $row, $fieldid, $TargetTableID ) {
                      return 'row_'.$d;
                    }
                ),
                array(
                    'db' => $param['TablePrimaryKey'],
                    'dt' => 'DT_RowClass', // TR CLASS
                    'field' =>$param['TablePrimaryKey'].'_rowclass',
                    'formatter' => function( $d, $row, $fieldid, $TargetTableID ) {
                        return 'datatableRow_style_'.$TargetTableID;
                    }
                )
            );
        }
        

        $colCount = 0; // Starting DT Count for DataTable Column Properties
        $keyfield = [];
        if( !isset($_SESSION['DataTables'][$param['TargetTableID']]['References']) )
        {
            $_SESSION['DataTables'][$param['TargetTableID']]['References'] = [];
        }

        foreach( $Column as $colRows => $colCol)
        {
            $dcolcolfieldid = $colCol['fieldid'];
            $keyfieldDup = false;

            if(!array_key_exists($colCol['fieldid'], $keyfield))
            {
                $keyfield[$colCol['fieldid']] = 1;
            }
            else
            {
                $keyfield[$colCol['fieldid']]++;
                $dcolcolfieldid .= $keyfield[$colCol['fieldid']];
                $keyfieldDup = true;
            }

            if( strpos($colCol['fieldid'],'_crud_') > -1)
            {
                $field = $param['TablePrimaryKey'];
                $colCol['fieldid'] = $param['TablePrimaryKey'].(substr($colCol['fieldid'], strpos($colCol['fieldid'],'_crud_'), strlen($colCol['fieldid'])));
            }
            else
            {
                $field = $colCol['fieldid']; 
                $colCol['fieldid'] = ($keyfieldDup) ? $dcolcolfieldid : $colCol['fieldid'];
            }
            
            $carray = array( 
                            'db' =>$field, 
                            'dt' => $colCount ,
                            'field' => $colCol['fieldid'] ,
                            'formatter' => function( $d, $row, $fieldid, $TargetTableID, $colid )
                            {
                                $columnposition = array_search($fieldid, array_column( $_SESSION['DataTables'][ $TargetTableID ]['column'], 'fieldid'));

                                $DTTableSession = $_SESSION['DataTables'][ $TargetTableID ];

                                $columnProp = $DTTableSession['column'][$columnposition];
                                $d = (is_null($d)) ? '' : $d;
                                $de = base64_encode(base64_encode($d)); //$this->CI->encryption->encrypt($d); 
                              

                                $editfmethod    = ($DTTableSession['bactionscolumn']['editmethod'] <> '') ? ( (filter_var($DTTableSession['bactionscolumn']['editmethod'], FILTER_VALIDATE_URL)) ? 'href="'.$DTTableSession['bactionscolumn']['editmethod'].'/'.$de.'"' : 'href="javascript:void(0)" fmethod="'.$DTTableSession['bactionscolumn']['editmethod'].'"')  : 'href="javascript:void(0)"';
                                
                                $deletefmethod  = ($DTTableSession['bactionscolumn']['deletemethod'] <> '') ? ( (filter_var($DTTableSession['bactionscolumn']['deletemethod'], FILTER_VALIDATE_URL)) ? 'href="'.$DTTableSession['bactionscolumn']['deletemethod'].'/'.$de.'"' : 'href="javascript:void(0)" fmethod="'.$DTTableSession['bactionscolumn']['deletemethod'].'"')  : 'href="javascript:void(0)"';


                                $viewfmethod  = ($DTTableSession['bactionscolumn']['viewmethod'] <> '') ? ( (filter_var($DTTableSession['bactionscolumn']['viewmethod'], FILTER_VALIDATE_URL)) ? 'href="'.$DTTableSession['bactionscolumn']['viewmethod'].'/'.$de.'"' : 'href="javascript:void(0)" fmethod="'.$DTTableSession['bactionscolumn']['viewmethod'].'"')  : 'href="javascript:void(0)"';

                               
                                switch( $colid )
                                {
                                    case strpos($colid,'_crud_view') > -1:
                                        $d = '<a dataid="'.$de.'" grouplink="viewdetails"  title="View Details" datatable="'.$TargetTableID.'" '.@$viewfmethod.'><i class="fa fa-search"></i></a>';
                                    break;

                                    case strpos($colid,'_crud_edit') > -1:
                                        $d = '<a dataid="'.$de.'" grouplink="editdetails" title="Edit Details" datatable="'.$TargetTableID.'" '.@$editfmethod.'><i class="fa fa-edit"></i></a>';
                                    break;

                                    case strpos($colid,'_crud_delete') > -1:
                                        $d = '<a dataid="'.$de.'" grouplink="deletedetails" title="Delete Details" datatable="'.$TargetTableID.'" '.@$deletefmethod.'><i class="fa fa-trash"></i></a>';
                                    break;

                                    case strpos($colid,'_crud_checkbox') > -1:
                                        $d = '
                                        <div class="checkbox-custom checkbox-primary">
                                            <input type="checkbox" dataid="'.$de.'" name="DTListChxBx[]" class="dt-checkboxes" datatable="'.$TargetTableID.'">
                                            <label for="'.$de.'"></label>
                                        </div>
                                        ';

                                        // $d='';
                                    break;

                                    // case strpos($colid,'_rowclass') > -1:
                                    //     $d = '<a dataid="'.$de.'" grouplink="viewdetails"  title="View Details" datatable="'.$TargetTableID.'" '.@$viewfmethod.'><i class="fa fa-search"></i></a>';
                                    // break;

                                    //  case strpos($colid,'_rowid') > -1:
                                    //     $d = '<a dataid="'.$de.'" grouplink="viewdetails"  title="View Details" datatable="'.$TargetTableID.'" '.@$viewfmethod.'><i class="fa fa-search"></i></a>';
                                    // break;

                                    case strpos($colid,'_crud_indexnumber') > -1:
                                        $d = '';
                                    break;

                                    default:

                                        if( $columnProp['referenceid'] <> '' && is_numeric($columnProp['referenceid']) )
                                        {
                                            $refDescTmp = $d;
                                            if( !isset($_SESSION['DataTables'][$TargetTableID]['References'][$columnProp['referenceid']]) ) 
                                            {
                                                
                                                $_SESSION['DataTables'][$TargetTableID]['References'][$columnProp['referenceid']] = [];
                                                if($refDescTmp <> '')
                                                {
                                                    $_SESSION['DataTables'][$TargetTableID]['References'][$columnProp['referenceid']][$refDescTmp] = $this->CI->myutilities->getRef_Desc($columnProp['referenceid'],$refDescTmp);
                                                    $d = $_SESSION['DataTables'][$TargetTableID]['References'][$columnProp['referenceid']][$refDescTmp];
                                                }
                                                else
                                                {
                                                    $d = '';
                                                }
                                            }
                                            else
                                            {
                                                if( !isset($_SESSION['DataTables'][$TargetTableID]['References'][$columnProp['referenceid']][$refDescTmp]) )
                                                {
                                                    $_SESSION['DataTables'][$TargetTableID]['References'][$columnProp['referenceid']][$refDescTmp] = $this->CI->myutilities->getRef_Desc($columnProp['referenceid'],$refDescTmp);

                                                }

                                                $d = (@$refDescTmp <> '') ? $_SESSION['DataTables'][$TargetTableID]['References'][$columnProp['referenceid']][$refDescTmp] : $refDescTmp;
                                            }
                                           
                                            // $d = $this->CI->myutilities->getRef_Desc($columnProp['referenceid'],$d);
                                        }
                                        else
                                        {
                                            $dd = $d;
                                            switch( trim( strtolower($columnProp['fieldtype']) ) )
                                            {
                                                case "date":
                                                    $dd = ($d <> '') ? date("m/d/Y",strtotime($d)) : '';
                                                    break;
                                                case "time":
                                                    $dd = ($d <> '') ? date("h:i:s A",strtotime($d)) : '';
                                                    break;
                                                case "datetime":
                                                    $dd = ($d <> '') ? date("m/d/Y h:i:s A" ,strtotime($d)) : '';
                                                    break;
                                            }

                                            $d = ( $this->CI->myutilities->isDate($dd) ) ? $dd : $d;
                                        }
                                    break;
                                }

                                

                                return ($columnProp['base64_encrypted']) ? base64_encode($d) : $d;
                            }
                        );

            if( array_key_exists($dcolcolfieldid, $param['FunctionOverride']) )
            {
                $carray['formatter'] = $param['FunctionOverride'][$dcolcolfieldid];
            }

            $ccColumnProperties[] = $carray;
            $colCount++;
        }     

        if( isset($param['FunctionOverride'][$param['TablePrimaryKey'].'_rowclass']) )
        {
            $ccColumnProperties[1]['formatter'] = $param['FunctionOverride'][$param['TablePrimaryKey'].'_rowclass'];
        }

        // Added Column Properties
        if( count($param['AddedColumnProperties']) > 0 )
        {
            foreach( $param['AddedColumnProperties'] as $acFieldID => $acFieldFunction )
            {
                $carray =   array( 
                                'db' => $acFieldID, 
                                'dt' => $colCount,
                                'field' =>$acFieldID,
                                'formatter' => $acFieldFunction 
                            );
                $ccColumnProperties[] = $carray;
                $colCount++;
            } 
        } 


        // log_message("error",$ccColumnProperties);
        return @$ccColumnProperties;
    }
//
}

