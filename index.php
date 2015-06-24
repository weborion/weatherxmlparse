<?php
// Script to parse Weather data from weather.gov XML at and Display as valid XHTML
//Class uses two apporaches
//1) Using Pre-defined HTML Template refer function get_weather_html_from_template
//2) Making HTML on the fly refer function get_weather_html 

//Main Class to contain all the functions.
Class WeatherAPI{
    
public $url = "http://www.weather.gov/xml/current_obs/KDCA.xml";
/**
 * 
 * @param type $url
 * @return Object of parsed XML
 */    
function parse_xml($url)
{
    try{
        
       $weather_object = simpleXML_load_file($url,"SimpleXMLElement",LIBXML_NOCDATA);
        if($weather_object ===  FALSE)
        {
           return false;
        }
        else { 
            return $weather_object;
        } 
        
    } catch (Exception $ex) {
         echo 'Error Parsing XML ',  $e->getMessage(), "\n";
    }
    
}
function get_key_array($weather_object){
    $weather_array = array();
    foreach ($weather_object as $wkey=>$wvalue){
        $weather_array[(string)$wkey]= (string)$weather_object->$wkey;
    }
    return $weather_array;
}
/**
 * 
 * @param array $parse_array
 * @return string
 */
function get_weather_html($parse_array){
 // Ideally in MVC Environment We replace the variables in template
 //For sake of safety including sample HTML for page in here
$html = <<<"TABLE"
<table width="700" border="0" cellspacing="0">
        <tr>
          <td width="119" valign="top">
            
          </td>
          <td width="525" valign="top">
            <table cellspacing="2" cellpadding="0" border="0">
              <tr valign="top">
                <td>&#160;&#160;&#160;&#160;&#160;&#160;&#160;</td>
                <td width="100%" align="center"><a name="contents" id="contents"></a>
        <h2 style="text-align: center;">

TABLE;

                 if($parse_array['location']!="") $html .= $parse_array['location']." <br />";
                 if($parse_array['station_id']!="") $html .=  $parse_array['location']."&#160";
                 if($parse_array['latitude']!="") $html .= $parse_array['location']."N";
                 if($parse_array['latitude']!="") $html .= $parse_array['latitude']."S";
                 if($parse_array['longitude']!="") $html .= $parse_array['latitude']."E";
                 if($parse_array['longitude']!="") $html .= $parse_array['longitude']."W";
                
                 $html .= "</h2>";
                 
                 if($parse_array['two_day_history_url']!="") $html .= "<a href='".$parse_array['two_day_history_url']."'>2 Day History</a>";
                 //if($parse_array['two_day_history_url']!="") $html .='  
    
                 $html .="</table></td></tr></table>";
            
     
    return $html;
}

function get_weather_html_from_template($parse_array){
    
    $html_content = file_get_contents("http://demo.weborion.in/weather/xml/template.html");
    $patterns = array_keys($parse_array); $rep = array_values($parse_array);
    array_walk_recursive($patterns, array(&$this,"addbraces"));
    //$this->my_pre($patterns);$this->my_pre($rep);exit;
    $html_content= str_replace($patterns,$rep, $html_content);
    
    return $html_content;
    
    
    
}
function addbraces(&$it)
    {
        return $it=  "{{".$it."}}";
    }
function generate_xhtml($html){
$xhtml = <<<XHTML
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" 
   "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<meta charset="utf-8" />
<html xmlns="http://www.w3.org/1999/xhtml">
  {myhtml}

XHTML;

$retxhtml = str_replace("{myhtml}", $html, $xhtml);

return $retxhtml;
    
}

function my_pre($var){
  echo "<pre>".print_r($var,true);
}

}

$weatherApi  = new WeatherAPI();
$wobject = $weatherApi->parse_xml($question1->url);
        
$parse_array= $weatherApi->get_key_array($wobject);

$html = $weatherApi->get_weather_html_from_template($parse_array);

echo $final_xhtml = $weatherApi->generate_xhtml($html);


