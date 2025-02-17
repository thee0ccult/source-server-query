<?php
namespace App\Http\Controllers;
ini_set("max_execution_time", 0);

use Illuminate\Http\Request;

class HLServerController extends Controller
{
    public $server_address ;
    public $ip ;
    public $port ;
    public $fp ;
    public $challenge ;
    public $serverinfo ;
    public $playerlist ;
    public $cvarlist ;
    
    
    public $A2S_SERVERQUERY_GETCHALLENGE  =  " \ x55 \ xFF \ xFF \ xFF \ xFF " ;  // challenge
    public $A2S_INFO  =  "TSource Engine Query \ x00 " ;  // info
    public $A2S_PLAYER  =  " \ x55 " ;  // player
    public $A2S_RULES  =  " \ x56 " ;  // rules
    
    //Separate IP and PORT 
    public function hlserver ( $server_address  =  0 )  {
        list ( $this->ip ,  $this->port )  =  explode ( ":" ,  $server_address ) ;
    }
    
    // Establish a connection to the server
    public function connect ( )  {
        $this->fp = fsockopen($this->ip, $this->port, $errno, $errstr, 3);
        if  ( ! $this->fp )  {
            $error  =  1 ;
        }
        
        return $this->fp;
    }
    
    // Send string command "
    public function send_strcmd ( $strcmd )  {
        fwrite ( $this->fp ,  sprintf ( '% c% c% c% c% s% c' ,  0xFF ,  0xFF ,  0xFF ,  0xFF ,  $strcmd ,  0x00 ) ) ;
    }
    
    // get 1 byte from the server
    public function get_byte ( )  {
        return  ord ( fread ( $this->fp ,  1 ) ) ;
    }
    
    // Get 1 character (1 byte) from the server
    public function get_char ( )  {
        return  fread ( $this->fp ,  1 ) ;
    }
    
    // get an int16 value (2 bytes) from the server
    public function get_int16 ( )  {
        $unpacked  =  unpack ( 'sint' ,  fread ( $this->fp ,  2 ) ) ;
        return  $unpacked [ "int" ] ;
    }
    
    // get an int32 value (4 bytes) from the server
    public function get_int32 ( )  {
        $unpacked  =  unpack ( 'iint' ,  fread ( $this->fp ,  4 ) ) ;
        return  $unpacked [ "int" ] ;
    }
    
    // get a float32 value (4 bytes) from the server
    public function get_float32 ( )  {
        $unpacked  =  unpack ( 'fint' ,  fread ( $this->fp ,  4 ) ) ;
        return  $unpacked [ "int" ] ;
    }
    
    // get a string from the server
    public function get_string ( )  {
        $str  =  '' ;
        while ( ( $char  =  fread ( $this->fp ,  1 ) )  !=  chr ( 0 ) )  {
            $str  .=  $char ;
        }
        return  $str ;
    }
    
    // Get 4 bytes from the challenge
    public function get_4 ( )
    {
        return  fread ( $this->fp ,  4 ) ;
    }
    
    // get challenger from server 
    public function challenge ( )  {
        $this->connect ( ) ;
        $this->send_strcmd ( $this->A2S_SERVERQUERY_GETCHALLENGE ) ;
        $this->get_int32 ( ) ;
        $this->get_byte ( ) ;
        $challenge  =  $this->get_4 ( ) ;
        fclose ( $this->fp ) ;
        return $challenge ;
        
    }
    
    // Get information from the server
    public function infos ( )  {
        $this->connect ( ) ;
        $this->send_strcmd ( $this->A2S_INFO ) ;
        $this->get_int32 ( ) ;
        $this->get_byte ( ) ;
        
        $this->serverinfo [ "network_version" ]  =  $this->get_byte ( ) ;
        $this->serverinfo [ "name" ]  =  $this->get_string ( ) ;
        $this->serverinfo [ "map" ]  =  $this->get_string ( ) ;
        $this->serverinfo [ "discription" ] =  $this->get_string ( ) ;
        $this->serverinfo [ "steam_id" ]  =  $this->get_int16 ( ) ;
        $this->serverinfo [ "players" ]  =  $this->get_byte ( ) ;
        $this->serverinfo [ "maxplayers" ]  =  $this->get_byte( ) ;
        $this->serverinfo [ "bot" ]  =  $this->get_byte ( ) ;
        $this->serverinfo [ "dedicated" ]  =  $this->get_char ( ) ;
        $this->serverinfo [ "os" ]  =  $this->get_char ( ) ;
        $this->serverinfo [ "password" ]  =  $this->get_byte( ) ;
        $this->serverinfo [ "secure" ]  =  $this->get_byte ( ) ;
        $this->serverinfo [ "version" ]  =  $this->get_string ( ) ;
        
        fclose ( $this->fp ) ;
        return  $this->serverinfo ;
    }
    
    // Get the player list from the server
    public function players ( )  {
        $challenge  =  $this->challenge ( ) ;
        $this->connect ( ) ;
        $this->send_strcmd ( $this->A2S_PLAYER . $challenge ) ;
        $this->get_int32 ( ) ;
        $this->get_byte ( ) ;
        
        $playercount  =  $this->get_byte ( ) ;
        
        for ( $i = 1 ;  $i  <=  $playercount ;  $i ++ )  {
            $this->playerlist [ "index" ] [ $i ]  =  $this->get_byte ( ) ;
            $this->playerlist [ "name" ] [ $i ]  =  $this->get_string ( ) ;
            $this->playerlist [ "questions" ] [$i ]  =  $this->get_int32 ( ) ;
            $this->playerlist [ "time" ] [ $i ]  =  date ( 'H: i: s' ,  round ( $this->get_float32 ( ) ,  0 ) + 82800 ) ;
        }
        fclose ( $this->fp ) ;
        return  $this->playerlist ;
    }
    
    // Get rules list (CVARs) from the server
    public function cvars ( )  {
        $challenge  =  $this->challenge ( ) ;
        $this->connect ( ) ;
        $this->send_strcmd ( $this->A2S_RULES . $challenge ) ;
        $this->get_int32 ( ) ;
        $this->get_byte ( ) ;
        
        $cvarcount  =  $this->get_int16 ( ) ;
        
        for ( $i = 1 ;  $i  <=  $cvarcount ;  $i ++ )  {
            $this->cvarlist [ $this->get_string ( ) ]  =  $this->get_string ( ) ;
        }
        fclose ( $this->fp ) ;
        return  $this->cvarlist ;
    } 
}
