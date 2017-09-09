<?php

include 'DB.php';
$db = DB::getInstance();

    $limit = 10; // posts limit
    
    // get feed
    $rss = new DOMDocument();
    $rss->load('https://3arrafni.com/feed/');
    $feed = array();
    $i=1;
    foreach ($rss->getElementsByTagName('item') as $node) {
	        	$item = array ( 
            		'title' => $node->getElementsByTagName('title')->item(0)->nodeValue,
            		'link' => $node->getElementsByTagName('link')->item(0)->nodeValue,
        		);
    		    array_push($feed, $item);
    		    
    		if($i == $limit){ break; }
    	$i++;
    }

    // reverse values
    $feed = array_reverse($feed);
    
    // check if have new posts or not
    for($x=0;  $x < $limit;   $x++) {
        $title = str_replace(' & ', ' &amp; ', $feed[$x]['title']);
        $link = $feed[$x]['link'];
        
        // check if url in DB
        $urls = $db->table('links')->where('url',$link)->get(); 
        $urls = json_decode(json_encode($urls),true);
        
        // if have
        if(empty($urls)){
             $db->insert('links',
                 [
                    'title' => $title,
                    'url' => $link ,
                 ]);
                 echo $x." <br>";                 
        // if not
        }else {
            echo "0 <br>";
        }
    }
   
