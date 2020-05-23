@extends('layouts.app')
@section('title', ' ')

@section('content')
<script src="/js/message.js?A1"></script>
<div id="app">
	<div class="flash_message bg-warning text-center py-3 my-0" 
		id="message_index_flash_wrap" style="display: none;">
		<p class="mb-0" id="message_index_flash">@{{flash_message}}</p>
	</div>	
	<div class="panel panel-default">
		<div class="panel-heading">
			<div class="row">
				<div class="col-sm-6"><h3>Messages - index</h3>
				</div>
				<div class="col-sm-6" style="text-align: right;">
					{{ link_to_route('messages.create', 'Create' ,null, 
					['class' => 'btn btn-primary']) }}
				</div>
			</div> 
		</div>
		<hr class="mt-2 mb-2">
		<div class="panel-body">
			<ul class="nav nav-tabs">
				<li class="nav-item">
					<a href="#" class="nav-link active" id="nav_receive_tab" v-on:click="change_type(1)">
						Receive
					</a>
				</li>
				<li class="nav-item">
					<a href="#" class="nav-link" id="nav_sent_tab" v-on:click="change_type(2)">
						Sent
					</a>
				</li>
			</ul>
			<ul class="ul_post_box" style="list-style: none;">
				<li v-for="item in items" v-bind:key="item.id">
					<div class="title_wrap">
						<span v-if="item.status==1" >
							<i class="far fa-envelope"></i>
						</span>
						<span v-if="mode==1">
							<a v-bind:href="'/messages/' + item.id">
								@{{ item.title }}
							</a>
							<br />
						</span>
						<span v-else>
							<a v-bind:href="'/messages/show_sent?id=' + item.id">
								@{{ item.title }}
							</a>
							<br />
						</span>	
						<!-- date -->
						<span class="date_str" style="margin-top:0px;">@{{ item.date_str }}
						</span>
						<span v-if="mode==1" class="date_str">, from @{{item.user_name}}
						</span>							
						<span v-if="mode==2" class="date_str">
							, To @{{item.user_name}}
						</span>	
						<span class="date_str">
							, ID: @{{ item.id }}
						</span>
										
					</div>
				</li>
			</ul>

		</div>
	</div>
</div>
<!-- -->
<div class="time_text_wrap" style="display: none;">
	watch-test:
	<input type="text" id="time_text" value="0" />
	<input type="text" id="message_title" value="" />
</div>
<!-- info -->
<br />
@include('element.page_info',
[
	'git_url' => 'https://github.com/kuc-arc-f/lara58a_7message',
	'blog_url' => 'https://knaka0209.hatenablog.com/entry/lara58_26message'
])
<!-- -->
<script>
var USER_ID = {{$user->id}};
var TIMER_COUNT = 0;
var TIMER_COUNT_MAX = 60;
var MODE_RECEIVE = 1;
var MODE_SENT = 2;
var TIME_TEXT_STR = 0;
/**********************************************
 *
 *********************************************/    
 function valid_notification(){
	if (!('Notification' in window)) {//対応してない場合
		alert('未対応のブラウザです');
	}
	else {
		// 許可を求める
		Notification.requestPermission()
		.then((permission) => {
			if (permission === 'granted') {// 許可
			}
			else if (permission == 'denied') {// 拒否
				$("#message_index_flash_wrap").css('display','inherit');
				$("#message_index_flash").text("ブラウザ通知を許可に設定すると。自動更新の通知を受信できます。");
			}
			else if (permission == 'default') {// 無視
			}
//			console.log(permission);
		});
	}  
}
/**********************************************
 *
 *********************************************/    
 function set_time_text(){
	var data = {
				'user_id': USER_ID,
				'type': 1,
			};           
	axios.post('/api/apimessages/get_last_item' , data).then(res =>  {
		var item = res.data
		if(item.id != null){
			$("input#time_text").val( item.id );
			$("input#message_title").val( item.title );
		}else{
			$("input#time_text").val( 0 );
		}
console.log( item );
	});	 
 }
 set_time_text();
 //timer
var timer_func = function(){
	 set_time_text();
//console.log( TIME_TEXT_STR );
};
var TIMER_SEC = 1000 * 600;
//var TIMER_SEC = 1000 * 180;
setInterval(timer_func, TIMER_SEC );
var set_firstTimeText = function(){
	TIME_TEXT_STR = $("input#time_text").val();
console.log( "tm="+ TIME_TEXT_STR );
};
setTimeout(set_firstTimeText, 5000);

v = valid_notification();
//
new Vue({
	el: '#app',
	created () {
		this.get_items(USER_ID);
		this.timer_start();
	},    
	data: {
		items : [],
		timerObj : null,
		mode : MODE_RECEIVE,
		flash_message : "",
	},
	methods: {
		get_items(USER_ID) {
			var data = {
				'user_id': USER_ID,
				'type': 1,
			};           
			axios.post('/api/apimessages/get_item' , data).then(res =>  {
				this.items = res.data
//console.log(this.items );
				this.mode = MODE_RECEIVE;
//				TIME_TEXT_STR = $("input#time_text").val();
//console.log( "tm="+ TIME_TEXT_STR );
			});             
		},
		get_sent_item: function() {
			var data = {
				'user_id': USER_ID,
				'type': 1,
			};           
			axios.post('/api/apimessages/get_sent_item' , data).then(res =>  {
				this.items = res.data
//console.log(res.data );
				this.mode = MODE_SENT;
			});
		}, 
		change_type: function(type) {
console.log(type );
			if(type == MODE_RECEIVE){
				$('#nav_receive_tab').addClass('active');
				$('#nav_sent_tab').removeClass('active');	
				this.get_items(USER_ID);			
			}else{
				$('#nav_sent_tab').addClass('active');
				$('#nav_receive_tab').removeClass('active');
				this.get_sent_item();
			}
		},
		count: function() {
			var chk_time = $("input#time_text").val();
//console.log("ct=" + TIME_TEXT_STR );
//console.log( "ct.chk=" + chk_time);
			if( parseFloat(TIME_TEXT_STR) != parseFloat(chk_time) ){
				console.log( "#change_time");
				if(this.mode == MODE_RECEIVE){
					var msg = $("input#message_title").val();
					display_notification("Recive Message", msg );
					this.get_items(USER_ID);
					TIME_TEXT_STR = $("input#time_text").val();
				}
			}
		},
		timer_start: function() {
			var self = this;
			this.timerObj = null;
			this.timerObj = setInterval(function() {self.count()}, 10000)
		},		
	}
});
</script>
<!-- -->
<style>
.ul_post_box .date_str{ font-size: 0.84rem; }
.ul_post_box .title_wrap{
	/* font-size: 18px; */
	font-size: 1.32rem;
	border-bottom: 1px solid #000; 
	margin-top: 8px;
}
</style>

@endsection
