<br /><br />
<div>
	<p>
		<strong>instructions:</strong> 
		<ul>
			<li>Set Host, Username and Password of joomla database same as wordpress database. </li>
			<li>Delete users of wordPress site excluding admin user.</li>
			<li>To remove duplication of users ID in users table, please make unique ID of admin user ID in user table and user meta table which are not exists in joomla users IDs.</li>
		</ul>
	</p>
</div>
<?php 
//Check form submition
if(isset($_POST['submit'])){
	$joomlaDbName 	= sanitize_text_field($_POST['jw_joomlaDbName']);
	$DbNameexpld 	= explode(".",$joomlaDbName);
	$DbName			= $DbNameexpld[0];
	$jw_host 		= sanitize_text_field($_POST['jw_host']);
	$jw_port 		= sanitize_text_field($_POST['jw_port']);
	$jw_username 	= sanitize_text_field($_POST['jw_username']);
	$jw_password 	= sanitize_text_field($_POST['jw_password']);
	
	if(($joomlaDbName != '' && $joomlaDbName != 'joomlaDBname.prefix') || $jw_host != '' || $jw_port != '' || $jw_username != '' || $jw_password != ''){
			// Check db
			// 1st Method - Declaring $wpdb as global and using it to execute an SQL query statement that returns a PHP object			
			//WP DB Prefix
			$lwpdb 		= new wpdb( $jw_username, $jw_password, $DbName, $jw_host );
			//$lwpdb->show_errors();
	      	//---------------------------------------------XXXXXX---------------------------------------------------------     
	      	//Insert records into users meta       
	      	$jUser = $lwpdb->get_results( $lwpdb->prepare( "SELECT 
					      u.id user_id, 
	      				  u.username user_login,
	      				  u.password password
					 FROM 
					        ".$joomlaDbName."_users u 
					 ORDER BY 
					      u.id LIMIT 0 , 100","","") );
	      	global $wpdb;
	      	$wpdb->show_errors();
	      	$wpPrefix	=	$wpdb->prefix;
			if($jUser){
				$i=1;
		   		foreach($jUser as $jUserVal){ 
		           //$this_id 		= 	$jUserVal->user_id."<br />"; 
		           $user_login 		= 	$jUserVal->user_login;
		           $password 		= 	$jUserVal->password; 
				   //Insert into users
		           if($user_login){					
					$userdata = array(
						'user_login' 	=>  $user_login,
						'user_pass'   	=>  $password,
						'user_nicename' =>  '',
						'user_email'  	=>  '',
						'user_registered' =>  '',
						'user_status'  	=>  '',
						'display_name'  =>  ''
					);					
					$this_id = wp_insert_user( $userdata ) ;
		           }
		           
			       if($this_id){ 		
						$wpdb->query( $wpdb->prepare(  "INSERT INTO ".$wpPrefix."usermeta ( user_id, meta_key, meta_value ) VALUES ( '$this_id', 'rich_editing', 'true' )" ,"",""));
						//Insert comment shortcuts status
						$wpdb->query( $wpdb->prepare(  "INSERT INTO ".$wpPrefix."usermeta ( user_id, meta_key, meta_value ) VALUES ( '$this_id', 'comment_shortcuts', 'false' )","",""));
						//Insert admin color
						$wpdb->query( $wpdb->prepare(  "INSERT INTO ".$wpPrefix."usermeta ( user_id, meta_key, meta_value ) VALUES ( '$this_id', 'admin_color', 'fresh' )","",""));
						//Insert Nickname
						$wpdb->query( $wpdb->prepare(  "INSERT INTO ".$wpPrefix."usermeta ( user_id, meta_key, meta_value ) VALUES ( '$this_id', 'use_ssl', 0 )","",""));
						//Insert show admin bar front status
						$wpdb->query( $wpdb->prepare(  "INSERT INTO ".$wpPrefix."usermeta ( user_id, meta_key, meta_value ) VALUES ( '$this_id', 'show_admin_bar_front', 'true' )","","")); 			   
				   }	
				   $i++;
		     	}   
		     	echo '<span style="color:green;">Users has been inserted successfully. !!! ENJOY !!!</span>';
			 }  
		}else{ 
			echo '<span style="color:red;">Error establishing a database connection. </span>';
		}
}else{
		$joomlaDbName='joomlaDBname.prefix';
}
?>
<form method="post">
<table>
<tr><th>Insert Joomla database name with prefix<span style="color:red;"> (ex - joomlaDBname.prefix) *</span></th><td><input type="text" name="jw_joomlaDbName" id="jw_joomlaDbName" onfocus="this.value=='joomlaDBname.prefix'?this.value='':this.value=this.value;" onblur="this.value==''?this.value='joomlaDBname.prefix':this.value=this.value;" value="<?php if(isset($joomlaDbName)) { echo $joomlaDbName; } ?>" maxlength="50"></td></tr>
<tr><th>Hostname <span style="color:red;">*</span></th><td><input type="text" name="jw_host" id="jw_host" onfocus="this.value=='Hostname'?this.value='':this.value=this.value;" onblur="this.value==''?this.value='Hostname':this.value=this.value;" value="<?php if(isset($jw_host)) { echo $jw_host; } ?>" maxlength="100"></td></tr>
<tr><th>Port <span style="color:red;">*</span></th><td><input type="text" name="jw_port" id="jw_port" onfocus="this.value=='Port'?this.value='':this.value=this.value;" onblur="this.value==''?this.value='Port':this.value=this.value;" value="<?php if(isset($jw_port)) { echo $jw_port; } ?>" maxlength="100"></td></tr>
<tr><th>Username <span style="color:red;">*</span></th><td><input type="text" name="jw_username" id="jw_username" onfocus="this.value=='Username'?this.value='':this.value=this.value;" onblur="this.value==''?this.value='Username':this.value=this.value;" value="<?php if(isset($jw_username)) { echo $jw_username; } ?>" maxlength="100"></td></tr>
<tr><th>Password <span style="color:red;">*</span></th><td><input type="password" name="jw_password" id="jw_password" onfocus="this.value=='Password'?this.value='':this.value=this.value;" onblur="this.value==''?this.value='Password':this.value=this.value;" value="<?php if(isset($jw_password)) { echo $jw_password; } ?>" maxlength="100"></td></tr>
<tr><td>&nbsp;</td><td><input type="submit" name="submit"></td></tr>
</tr>
</table>
</form><font face="Arial, Helvetica, sans-serif"></font>