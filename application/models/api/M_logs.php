<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
 
class M_logs extends CI_Model
{
  public $CI;

	function __construct()
	{
		parent::__construct();
		$this->CI =& get_instance();
		$this->CI->sqlhelper->local->setajaxevent(1);
	}

  function stats_method($filter='')
  {
      $return = [];

      if($filter)
      {
        $w = 'where '.$filter;
      }

      $q = "
        select 
          a.method as 'METHOD',
          count(*) as 'METHOD_COUNT'
        from rest_api_logs a  
        ".@$w."
        group by a.method
      ";
      
      $getStats = $this->CI->sqlhelper->local->sql($q)->result();

      if($getStats['Count'] > 0)
      {
        foreach($getStats['Data'] as $r => $c)
        {
          $return[strtoupper($c['METHOD'])] = $c['METHOD_COUNT'];
        }
      }

      return $return;
  }

  function stats_httpresponse($filter='')
  {
      $return = [];

      if($filter)
      {
        $w = 'where '.$filter;
      }

      $q = "
        select 
          a.response_code as 'RESPONSE_CODE',
          count(*) as 'RESPONSE_CODE_COUNT'
        from rest_api_logs a  
        ".@$w."
        group by a.response_code
      ";

      $getStats = $this->CI->sqlhelper->local->sql($q)->result();

      if($getStats['Count'] > 0)
      {
        foreach($getStats['Data'] as $r => $c)
        {
          $return[$c['RESPONSE_CODE']] = $c['RESPONSE_CODE_COUNT'];
        }
      }

      return $return;
  }
 
  function stats_fhirhttpresponse($filter='')
  {
      $return = [];
      $excluded = ['bundle','fhir','codeset','valueset','structuredefinition','organization'];
      if($filter)
      {
        $w = 'where '.$filter;
      }

      $q = "
        select 
          a.uri,
          a.response_code as 'RESPONSE_CODE',
          count(*) as 'RESPONSE_CODE_COUNT',
          COUNT(CASE WHEN a.response_code in ('201') AND a.method = 'post' THEN 1 END) as 'TOTAL_RECORD'
        from rest_api_logs a  
        ".@$w."
        group by a.uri,a.response_code
      ";

      $getStats = $this->CI->sqlhelper->local->sql($q)->result();

      if($getStats['Count'] > 0)
      {
        foreach($getStats['Data'] as $r => $c)
        {
          $uri = explode("/",$c['uri']);
          $xuri = @$uri[0].'/'.@$uri[1];
          $return[$xuri.'_'.$c['RESPONSE_CODE']] = ((@$return[$xuri.'_'.$c['RESPONSE_CODE']] <> '') ? $return[$xuri.'_'.$c['RESPONSE_CODE']] : 0 )  + $c['RESPONSE_CODE_COUNT'];
          if($c['RESPONSE_CODE'] == '201')
          {
             $return[$xuri.'_totalrecord'] = ((@$return[$xuri.'_'.$c['TOTAL_RECORD']] <> '') ? $return[$xuri.'_'.$c['TOTAL_RECORD']] : 0 )  + $c['TOTAL_RECORD']; 
          }
        }

        

      }

      $getAPIList = $this->CI->sqlhelper->remote1->select('rest_api_controller')->where(" id not in ('10','11','12','13','14') ")->result();
      foreach($getAPIList['Data'] as $r => $c)
      {
        $resourcename = str_replace(['fhir/','fhir','_totalrecord'],"",strtolower($c['controller']));
        if(!in_array($resourcename, $excluded))
        {
          $rcnt = $this->CI->sqlhelper->remote2->select("resource_".strtolower($resourcename),"count(*) as 'RTotal'")->where($filter)->ex_select(( ($filter <> '') ? 'healthfacilitycode' : ''),"","");
         
          $rcnt = $rcnt->row();

          if(isset($return[$c['controller'].'_totalrecord']) )
          {
            $return[$c['controller'].'_totalrecord'] = ($rcnt['Count'] > 0) ? ( ($rcnt['Data']['RTotal'] > 0) ? $rcnt['Data']['RTotal'] : $return[$c['controller'].'_totalrecord'] ) : $return[$c['controller'].'_totalrecord'];
          }
          else
          {
            $return[$c['controller'].'_totalrecord'] = ($rcnt['Count'] >  0) ? $rcnt['Data']['RTotal'] : 0;
          }
        }
      }


      return $return;
  }

}

/* End of file users.php */
/* Location: ./application/models/auth/users.php */