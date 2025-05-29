<?php
//include("conn.php");
header('Content-Type: application/json');
define("HOST", "localhost");
define("USER", "root");
define("PASS", "5ample_db");
define("DB", "guide");

function strip_html_tags( $text )
{
	// PHP's strip_tags() function will remove tags, but it
	// doesn't remove scripts, styles, and other unwanted
	// invisible text between tags.  Also, as a prelude to
	// tokenizing the text, we need to insure that when
	// block-level tags (such as <p> or <div>) are removed,
	// neighboring words aren't joined.
	$text = preg_replace(
		array(
			// Remove invisible content
			'@<head[^>]*?>.*?</head>@siu',
			'@<style[^>]*?>.*?</style>@siu',
			'@<script[^>]*?.*?</script>@siu',
			'@<object[^>]*?.*?</object>@siu',
			'@<embed[^>]*?.*?</embed>@siu',
			'@<applet[^>]*?.*?</applet>@siu',
			'@<noframes[^>]*?.*?</noframes>@siu',
			'@<noscript[^>]*?.*?</noscript>@siu',
			'@<noembed[^>]*?.*?</noembed>@siu',

			// Add line breaks before & after blocks
			'@<((br)|(hr))@iu',
			'@</?((address)|(blockquote)|(center)|(del))@iu',
			'@</?((div)|(h[1-9])|(ins)|(isindex)|(p)|(pre))@iu',
			'@</?((dir)|(dl)|(dt)|(dd)|(li)|(menu)|(ol)|(ul))@iu',
			'@</?((table)|(th)|(td)|(caption))@iu',
			'@</?((form)|(button)|(fieldset)|(legend)|(input))@iu',
			'@</?((label)|(select)|(optgroup)|(option)|(textarea))@iu',
			'@</?((frameset)|(frame)|(iframe))@iu',
		),
		array(
			' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ',
			"\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0",
			"\n\$0", "\n\$0",
		),
		$text );

	// Remove all remaining tags and comments and return.
	return strip_tags( $text );
}  
 
function getCatgories() 
{
    $con=mysqli_connect(HOST,USER,PASS,DB); 
	$json=array(); 
	 	
	$sel=mysqli_query($con,"SELECT id, name, icon FROM `category` ORDER BY ordering ASC")or die(mysqli_error($con));  
	
	if(mysqli_num_rows($sel)){
        while($row=mysqli_fetch_assoc($sel)){ 
			$cat_id=$row['id']; 
			$sel3=mysqli_query($con,"SELECT c.eng_phrase,c.lug_phrase1, c.filename, c.filepath, c.originalfilename FROM phrase c WHERE categoryid=".$cat_id." ORDER BY ordering ASC")or die(mysqli_error($con));
			
			 while($row3=mysqli_fetch_assoc($sel3)){  
				$row['phrase'][]=array_map('utf8_encode', $row3); 
				array_push($row3);
			 }  			 
		$json['results'][]=$row; 
		} 
		$json['status']="ok"; 
	  }else{  
		$json['status']="empty";
      }   
	echo json_encode($json);  
}

function getCatStuff() 
{
    $con=mysqli_connect(HOST,USER,PASS,DB);
	
	$json=array();    	  	 	
	$sel=mysqli_query($con,"SELECT id, name, icon FROM `category` ORDER BY ordering ASC")or die(mysqli_error($con));
	
			if(mysqli_num_rows($sel)){ 
			 while($row=mysqli_fetch_assoc($sel)){  
			   $json['results'][]=$row; 
			}
			$json['status']='ok'; 
			}else{
				$json['status']='error'; 
				 }  
	echo json_encode($json);
} 


?>