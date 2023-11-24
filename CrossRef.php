<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Bible Cross Reference Checker</title>
    </head>
    <body>
<?php

@ $db = new mysqli('localhost', 'david', '', 'bible');

print $_POST['user'];
foreach ($_POST as $key => $value){
    print "key $key <tab> $value <br />";
}

$self = $_SERVER[PHP_SELF];
$function = $_POST['function'];

echo "$function = ".$function;

if ($function){

    $bnum = $_POST['bnum'];
    $cnum = $_POST['cnum'];
    $vnum = $_POST['vnum'];
    $tar_bnum = $_POST['tar_bnum'];
    $tar_cnum = $_POST['tar_cnum'];
    $tar_vnum = $_POST['tar_vnum'];
    $notes = $_POST['notes'];

    switch ($function){
        case "Delete":
            $sql_func = "delete from kjv_crossref \n";
        case "Add":
            $sql_func = "insert into kjv_crossref \n";
            break;
        case "Edit":
            break;
        default:
            print "You can only add Edit or delete (Undefined Function: $function";
            break;
    }

    $sql_func .= <<<_ENDSQL_
    where
        referring_bnum = $bnum and
        referring_cnum = $cnum and
        referring_vnum = $vnum and
        target_bnum = $tar_bnum and
        target_cnum = $tar_cnum and
        target_vnum = $tar_vnum;
_ENDSQL_;

  echo $sql_func;
  $result = $db->query($sql_func);
  $num_results = $result->num_rows;
}

    $query = "select * from kjv where bnum = $book and cnum = $cnum and vnum = $vnum;";


    $ft =  <<<_HTML_



_HTML_;

 // end of form
    print phpinfo();

 ?>

        <!-- START OF THE PLAYER EMBEDDING TO COPY-PASTE -->
	<object id="player" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" name="player" width="328" height="200">
		<param name="movie" value="player.swf" />
		<param name="allowfullscreen" value="true" />
		<param name="allowscriptaccess" value="always" />
		<param name="flashvars" value="http://ebibleverses.com/01_Gen/01_Gen_29/01_Gen_29_20/01_Gen_29_20.mp34&image=/jwplayer/preview.jpg" />
		<embed
			type="application/x-shockwave-flash"
			id="player2"
			name="player2"
			src="/jwplayer/player.swf"
			width="328"
			height="200"
			allowscriptaccess="always"
			allowfullscreen="true"
			flashvars="url=http://ebibleverses.com/01_Gen/01_Gen_29/01_Gen_29_20/01_Gen_29_20.mp3&image=/jwplayer/preview.jpg"
		/>
	</object>
	<!-- END OF THE PLAYER EMBEDDING -->


    </body>
</html>
