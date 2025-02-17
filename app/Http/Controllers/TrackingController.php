<?php
namespace App\Http\Controllers;

use App\Models\Tracking;
use Illuminate\Http\Request;
use xPaw\SourceQuery\SourceQuery;
use App\Models\Player;
use function PHPUnit\Framework\throwException;
use SteamCondenser\Servers\SourceServer;
use SteamCondenser\Servers\SteamPlayer;

class TrackingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('server');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tracking  $tracking
     * @return \Illuminate\Http\Response
     */
    public function show(Tracking $tracking)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tracking  $tracking
     * @return \Illuminate\Http\Response
     */
    public function edit(Tracking $tracking)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tracking  $tracking
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tracking $tracking)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tracking  $tracking
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tracking $tracking)
    {
        //
    }
    
    public function getInfo(Request $request)
    {
        $this->validate($request, [
            "ip" => 'required',
            "port" => 'required',
            "password" => 'nullable',
            "refresh_rate" => 'nullable',
        ]);
        
        $arrData = $this->FetchAllInfo($request->ip, $request->port, $request->password, $request->refresh_rate);
        
        if(count($arrData["Server"]) && isset($arrData["Server"])){
            $server = Tracking::updateOrCreate(["ip" => $request->ip, "port" => $request->port], [
                "HostName" => $arrData["Server"]["serverName"],
                "GameID" => $arrData["Server"]["gameId"],
                "game" => $arrData["Server"]["gameDesc"],
                "Map" => $arrData["Server"]["mapName"],
                "os" => $arrData["Server"]["operatingSystem"],
                "MaxPlayers" => $arrData["Server"]["maxPlayers"],
                "Players" => $arrData["Server"]["numberOfPlayers"],
                "Bots" => $arrData["Server"]["botNumber"],
                "AppID" => $arrData["Server"]["appId"],
                "Version" => $arrData["Server"]["gameVersion"],
                "SteamID" => $arrData["Server"]["serverId"],
                "GameTags" => $arrData["Server"]["serverTags"]
            ]);
        }
        
        $arrData["Server"] = $server;
        $arrData["Server"]["Os"] = $server["os"];
        $arrData["Server"]["ModDesc"] = $server["game"];
        
        if(count($arrData["Players"]) && isset($arrData["Players"])){
            foreach ($arrData["Players"] as $arrPlayer){
                $player = Player::updateOrCreate(["tracking_id" => $server->id, "name" => $arrPlayer->name], [
                    "tracking_id" => $server->id,
                    "player_id" => $arrPlayer->id,
                    "name" => $arrPlayer->name,
                    "score" => $arrPlayer->score,
                    "time" => date("H:i:s", $arrPlayer->connectTime),
                ]);
            }
        }
        
        return $arrData;
    }
    
    public function FetchAllInfo($ip, $port, $password = null, $timeout=1)
    {
        include_once(realpath(base_path() . "/libraries/steamcondenser/vendor/autoload.php"));
        
        try
        {
            $Query = new SourceServer($ip, $port);
            $Query->initialize();
            
            $arrData["Server"] = $Query->getServerInfo( );
            
            if(empty($arrData["Server"])){
                if (empty($password))
                    abort(403, "Could not ping server or Server down. Please enter password to connect and try again");
                else{
                     $Query->rconAuth($password);
                     $str = $Query->rconExec("status")[0];
                     $arrResponse = explode("\n", $str);
                     
                     foreach ($arrResponse as $arrResponseData){
                         $arr = explode(":", $arrResponseData, 2);
                         if (!isset($arr[1])) {
                             continue;
                         }
                         $key = trim($arr[0]);
                         $val = trim($arr[1]);
                         $server[$key] = $val;
                     }
                     
                     
                     $arrData["Server"] = array(
                         "serverName" => $server["hostname"],
                         "mapName" => $server["map"],
                         "maxPlayers" => intval($this->get_string_between($server["players"], "(", ")")),
                         "numberOfPlayers" => intval(explode(" humans", $server["players"])[0]),
                         "gameVersion" => $server["version"],
                         "serverId" => $this->get_string_between($server["steamid"], "(", ")"),
                         "serverTags" => $server["tags"],
                         
                         "gameId" => null,
                         "gameDesc" => null,
                         "operatingSystem" => null,
                         "botNumber" => null,
                         "appId" => null,
                     );
                     
                     $arrData["Players"] = [];
                     $arrData["Players_table"] = "";
                     
                     return $arrData;
                }
            }
            
            $arrData["Players"] = $Query->getPlayers();
            
            $html = "";
            if(count($arrData["Players"]) && isset($arrData["Players"])){
                foreach ($arrData["Players"] as $arrPlayer){
                    $html .= '<tr>
                                    <td>' . $arrPlayer->name . '</td>
                                    <td>' . $arrPlayer->score . '</td>
                                    <td>' . date("H:i:s", $arrPlayer->connectTime) . '</td>
                                </tr>';
                }
            }
            
            $arrData["Players_table"] = $html;
            
            switch($arrData['Server']['operatingSystem']){
                case "w":
                    $arrData['Server']['operatingSystem'] = "Windows";
                    break;
                case "l":
                    $arrData['Server']['operatingSystem'] = "Linux";
                    break;
                case "m":
                case "o":
                    $arrData['Server']['operatingSystem'] = "Mac";
                    break;
            }
            
            return $arrData;
        }
        catch( \Throwable $e )
        {
            $err = ["message" => $e->getMessage()];
            die(json_encode($err, TRUE));
        }
    }
    
    function get_string_between($string, $start, $end){
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) return '';
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }
    
}
