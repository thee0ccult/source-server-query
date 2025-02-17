@extends('layouts.simple')

@section('content')
	 <div class="container-fluid">
	 	<br>
	 	<h1 class="text-center">Server Info</h1>
	 	<div class="block invisible" data-toggle="appear">
            <div class="block-content block-content-full">
                <form class="form-inline" id="form">
                    <div>
                    	<label class="text-left" for="ip">IP </label>
                    	<input type="text" class="form-control mb-2 mr-sm-2 mb-sm-0" id="ip" name="ip" value="66.242.13.202">
                    </div>
                    <div>
                        <label class="text-left" for="port">Port </label>
                        <input type="number" class="form-control mb-2 mr-sm-2 mb-sm-0" id="port" name="port" value="27015">
                    </div>
                    <div>
                        <label class="text-left" for="password">Password </label>
                        <input type="password" class="form-control mb-2 mr-sm-2 mb-sm-0" id="password" name="password" value="">
                    </div>
                    <div>
                        <label class="text-left" for="refresh_rate">Refresh rate (seconds) </label>
                        <input type="text" class="form-control mb-2 mr-sm-2 mb-sm-0" id="refresh_rate" name="refresh_rate" value="3">
                    </div>
                    <div class="ml-5">
                    	<label>&nbsp;</label>
                    	<button type="button" class="btn btn-alt-primary submit_btn ">Ping</button>
                    </div>
                    <div class="ml-5 stop_btn_portion" style="display: none;">
                    	<label>&nbsp;</label>
                    	<button type="button" class="btn btn-alt-danger stop_btn ">Stop</button>
                    </div>
                	<div class="spinner-border text-danger spinning_status ml-15 mt-15" role="status" style="display:none;">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <div class="refresh_portion ml-15" style="display: none;">
                    	<br>
                    	<label>Refreshes in:&nbsp;<span></span>s</label>
                    </div>
                </form>
            </div>
        </div>
        <div class="row invisible" data-toggle="appear">
        	<div class="col-12">
                <a class="block block-link-rotate text-right " href="javascript:void(0)">
                    <div class="block-content block-content-full clearfix">
                        <div class="float-left mt-10 d-none d-sm-block">
                            <i class="si si-globe fa-3x text-primary"></i>
                        </div>
                        <div class="font-size-h4 font-w600 text-primary-darker host" ></div>
                        <div class="font-size-sm font-w600 text-uppercase text-primary-dark">Host</div>
                    </div>
                </a>
            </div>
        </div>
        <div class="row invisible" data-toggle="appear">
        	<div class="col-12 col-sm-6 ">
                <a class="block block-link-rotate text-right" href="javascript:void(0)">
                    <div class="block-content block-content-full clearfix">
                        <div class="float-left mt-10 d-none d-sm-block">
                            <i class="si si-game-controller fa-3x text-primary"></i>
                        </div>
                        <div class="font-size-h4 font-w600 text-primary-darker game" ></div>
                        <div class="font-size-sm font-w600 text-uppercase text-primary-dark">Game</div>
                    </div>
                </a>
            </div>
            <!-- Row #1 -->
            <div class="col-12 col-sm-6 ">
                <a class="block block-link-rotate text-right" href="javascript:void(0)">
                    <div class="block-content block-content-full clearfix">
                        <div class="float-left mt-10 d-none d-sm-block">
                            <i class="si si-map fa-3x text-primary"></i>
                        </div>
                        <div class="font-size-h4 font-w600 text-primary-darker map" ></div>
                        <div class="font-size-sm font-w600 text-uppercase text-primary-dark">Map</div>
                    </div>
                </a>
            </div>
        </div>
        <div class="row invisible" data-toggle="appear">
            <div class="col-12 col-sm-4">
                <a class="block block-link-rotate text-right" href="javascript:void(0)">
                    <div class="block-content block-content-full clearfix">
                        <div class="float-left mt-10 d-none d-sm-block">
                            <i class="si si-screen-desktop fa-3x text-primary"></i>
                        </div>
                        <div class="font-size-h4 font-w600 text-primary-darker os"></div>
                        <div class="font-size-sm font-w600 text-uppercase text-primary-dark">OS</div>
                    </div>
                </a>
            </div>
            <div class="col-12 col-sm-4">
                <a class="block block-link-rotate text-right" href="javascript:void(0)">
                    <div class="block-content block-content-full clearfix">
                        <div class="float-left mt-10 d-none d-sm-block">
                            <i class="si si-users fa-3x text-primary"></i>
                        </div>
                        <div class="font-size-h4 font-w600 text-primary-darker max_players" data-toggle="countTo" data-speed="1000" data-to=""></div>
                        <div class="font-size-sm font-w600 text-uppercase text-primary-dark">Max players</div>
                    </div>
                </a>
            </div>
            <div class="col-12 col-sm-4">
                <a class="block block-link-rotate text-right" href="javascript:void(0)">
                    <div class="block-content block-content-full clearfix">
                        <div class="float-left mt-10 d-none d-sm-block">
                            <i class="si si-user-following fa-3x text-primary"></i>
                        </div>
                        <div class="font-size-h4 font-w600 text-primary-darker online_player" data-toggle="countTo" data-speed="1000" data-to=""></div>
                        <div class="font-size-sm font-w600 text-uppercase text-primary-dark">Online</div>
                    </div>
                </a>
            </div>
            <!-- END Row #1 -->
        </div>
        <hr>
        <section id="player_info" >
        	<h1 class="text-center">Players Info</h1>
        	<div class="block invisible" data-toggle="appear">
            	<div class="block-content block-content-full">
            		<div class="table-responsive">
            			<table class="table table-bordered players_table table-condensed table-vcenter table-hover display display-table">
            				<thead>
            					<tr>
            						<th>Name</th>
            						<th>Score</th>
            						<th>Time</th>
            					</tr>
            				</thead>
            				<tbody></tbody>
            			</table>
            		</div>
            	</div>
        	</div>
        </section>
        
    </div>
@endsection
    
@section('js_after')
<script src="{{ asset('js/plugins/bootstrap-notify/bootstrap-notify.min.js') }}"></script>
<script type="text/javascript">
	$(function(){
		window.interval = null;
		window.request = null;
		$(document).on("click",".stop_btn",function(){
			window.clearInterval(interval);
			if(request)
    		   	request.abort();
		   	$(".refresh_portion").hide();
	   		$(".stop_btn_portion").hide();
    		$(".spinning_status").hide();
		});
		
		$(document).on("click",".submit_btn",function(){
			$(".stop_btn_portion").show();
			if($("#ip").val() == ""){
				Codebase.helpers('notify', {
		            align: 'right',             // 'right', 'left', 'center'
		            from: 'top',                // 'top', 'bottom'
		            type: 'danger',               // 'info', 'success', 'warning', 'danger'
		            icon: 'fa fa-danger mr-5',    // Icon class
		            message: "Please provide ip"
		        });
			}
			else if($("#port").val() == ""){
				Codebase.helpers('notify', {
		            align: 'right',             // 'right', 'left', 'center'
		            from: 'top',                // 'top', 'bottom'
		            type: 'danger',               // 'info', 'success', 'warning', 'danger'
		            icon: 'fa fa-danger mr-5',    // Icon class
		            message: "Please provide port"
		        });
			}
			else{
				GetData();
				
				$('.refresh_portion span').html($("#refresh_rate").val());
				
    			var time = $("#refresh_rate").val();
    			interval = setInterval(function() {
    					$(".refresh_portion").show();
                      var seconds = parseInt(time, 10);
                      --seconds;
                      $('.refresh_portion span').html(seconds);
                      time = seconds;
                      if (seconds < 0){
                      	$(".refresh_portion").hide();
                      	setTimeout(function(){}, 1000);
                      	GetData();
                      	setTimeout(function(){}, 1000);
                      	time = $("#refresh_rate").val();
                      	time++;
                      }
                }, 1000); 
			}
		});
	});
	
	function GetData(){
		$(".refresh_portion").hide();
		
		request = $.ajax({
			url:"{{ route('getInfo') }}",
			type:"POST",
			data:"_token={{ csrf_token() }}&" + $("#form").serialize(),
			dataType:"json",
			success:function(data){
				if(data.message){
			        Codebase.helpers('notify', {
        	            align: 'right',             // 'right', 'left', 'center'
        	            from: 'top',                // 'top', 'bottom'
        	            type: 'danger',               // 'info', 'success', 'warning', 'danger'
        	            icon: 'fa fa-danger mr-5',    // Icon class
        	            message: data.message
        	        });
				}
				else{
    				$(".host").html(data["Server"]["HostName"]);
    				$(".game").html(data["Server"]["ModDesc"]);
    				$(".map").html(data["Server"]["Map"]);
    				$(".os").html(data["Server"]["Os"]);
    				$(".max_players").html(data["Server"]["MaxPlayers"]);
    				$(".max_players").attr("data-to", data["Server"]["MaxPlayers"]);
    				$(".online_player").html(data["Server"]["Players"]);
    				$(".online_player").attr("data-to", data["Server"]["Players"]);
    				
    				if(data["Players_table"] != ""){
    					$(".players_table tbody").html(data["Players_table"]);
    					$("#player_info").show();
    				}
    				else{
    					$(".players_table tbody").html('No data found');
    					$("#player_info").hide();
    				}
				}
			}
		});
	}
</script>
              
@endsection
