<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Bible Search Results</title>
    </head>
    <body>
       <h1>Bible Search Results</h1>

<?php

  include "bibleResultProcessor.php";

  // create short variable names
  $book=rtrim($_POST['book']);
  $chapter=rtrim($_POST['chapter']);
  $verse=rtrim($_POST['verse']);
  $bibletextsearch=($_POST['bibletextsearch']);     // don't want rtrim so you can specify " word " to get just word results back

  //print "<strong>Anything?<br />".$bibletextsearch.$verse.$chapter.$book."</strong>";

print <<<_END_
<form action="$_SERVER[PHP_SELF]" method="post" name="BibleReferenceSearch">Book: <input
name="book" value="$book">Chapter: <input name="chapter" value="$chapter">Verse: <input name="verse" value="$verse"><input
name="search" value="search" type="submit"><br />
</form>

<form action="$_SERVER[PHP_SELF]" method="post" name="BibleTextSearch">Text to Find: <input name="bibletextsearch" value="$bibletextsearch"><input name="searchtext" value="search" type="submit"><br>
</form>
_END_;

@ $db = new mysqli('localhost', 'david', '', 'bible');


  $bibleTexts = new BibleResultProcessor;

//print "<strong>Anything?<br />".$bibletextsearch.$verse.$chapter.$book."</strong>";

//$mysqli = new mysqli('localhost', 'my_user', 'my_password', 'my_db');
  if (mysqli_connect_errno()) {
     echo 'Error: Could not connect to database.  Please try again later.';
     exit;
  }
//  print "<strong>Anything?<br />".$bibletextsearch.$verse.$chapter.$book."</strong>";
  if ($bibletextsearch)
      $query ="select * from kjv where vtext like '%".$bibletextsearch."%'";
  else{
      $query = "";
      if ($book || $chapter || $verse){
        $query = "select * from kjv";
        $wa = " where";
        if ($book) {
            $query .= $wa." bname like '%".$book."%'";
            $wa = " and";
        }
        if ($chapter) {
            $query.=  $wa." cnum=".$chapter;
            $wa = " and";
        }
        if ($verse)
            $query.= $wa." vnum=".$verse;
        $query .=";";
    }
  }

//  print "<br />query is: ". $query;
  $result = $db->query($query);
  $num_results = $result->num_rows;

  print "<br /><p>Number of references found: ".$num_results."</p>";

  for ($i=0; $i <$num_results; $i++) {
     $row = $result->fetch_assoc();

     $bname = htmlspecialchars(stripslashes($row['bname']));
     $bnum=stripslashes($row['bnum']);
     $cnum= stripslashes($row['cnum']);
     $vnum= stripslashes($row['vnum']);
     $vtext = stripslashes($row['vtext']);

//     echo "Just before running add and proccess link";

//     echo $bname.$bnum.$cnum.$vnum.$i;

     $bibleLink = $bibleTexts->add_and_process_link ($bname, $vtext , $bnum , $cnum , $vnum, $i);
     echo $bibleLink;
     //echo "just after echo biblelink";

     $query =<<<_SQL_
     select
        kjv.bname, kjv.vtext, kjv.bnum, kjv.cnum ,kjv.vnum, crossref.notes
     from
             crossref, kjv
     where
             crossref.referring_bnum =$bnum
             and crossref.referring_cnum=$cnum
             and crossref.referring_vnum=$vnum
             and kjv.bnum = crossref.target_bnum
             and kjv.cnum = crossref.target_cnum
             and kjv.vnum = crossref.target_vnum;
_SQL_;

     //print "<br />query string: ".$query;
     $references = $db->query($query);
     $num_refs = $references->num_rows;

$ref_add = <<<_ENDFORM_
<form method="post" action="CrossRef.php" name="CrossReference">
<input name="function" value="Add" type="submit">
<input name="bnum" value="$bnum" type="hidden">
<input name="cnum" value="$cnum" type="hidden">
<input name="vnum" value="$vnum" type="hidden">
Book: <input name="tar_bnum" value="$tar_bnum" type="text">
Chapter: <input name="tar_cnum" value="$tar_cnum" type="text">
Verse: <input name="tar_vnum" value="$tar_vnum" type="text">
</form>
_ENDFORM_;

    echo $ref_add;

    if ($num_refs) print "<small><ol>\n";
    for ($j=0; $j <$num_refs; $j++) {

        $rrow = $references->fetch_assoc();

        $bname = htmlspecialchars(stripslashes($rrow['bname']));
        $tar_bnum=stripslashes($rrow['bnum']);
        $tar_cnum=stripslashes($rrow['cnum']);
        $tar_vnum=stripslashes($rrow['vnum']);
        $vtext = stripslashes($rrow['vtext']);
        $notes = stripslashes($rrow['notes']);

        //echo $bname.$tar_bnum.$tar_cnum.$tar_vnum.$vtext;

        $rtd = $bnum.":".$cnum.":".$vnum."-".$tar_bnum.":".$tar_cnum.":".$tar_vnum;

$ref_del = <<<_ENDFORM1_
<form method="post" action="CrossRef.php" name="CrossReference">
<input name="function" value="Delete" type="submit">
<input name="bnum" value="$bnum" type="hidden">
<input name="cnum" value="$cnum" type="hidden">
<input name="vnum" value="$vnum" type="hidden">
<input name="tar_bnum" value="$tar_bnum" type="hidden">
<input name="tar_cnum" value="$tar_cnum" type="hidden">
<input name="tar_vnum" value="$tar_vnum" type="hidden">
</form>
_ENDFORM1_;


echo $ref_del;
        $bibleLink = $bibleTexts->add_and_process_link ($bname, $vtext , $tar_bnum , $tar_cnum , $tar_vnum, $j);
        echo "<li>".$bibleLink."<br />".$notes."</li>";
     }

     print "</ol></small>\n";
  }

  print "<strong><br /> - The End - <br /> </strong>";

  $references->free();
  $result->free();
  $db->close();

  print "<br />";
  //$paramURL = $bibleTexts->getparamURL();
  //
  //// "mp3=".ltrim ($paramURL, "|");
    //  $paramTITLE = "&amp;title=".ltrim ($paramTITLE, "|");

?>
<object class="playerpreview" type="application/x-shockwave-flash" data="http://flash-mp3-player.net/medias/player_mp3_multi.swf" width="200" height="100">
    <param name="movie" value="http://flash-mp3-player.net/medias/player_mp3_multi.swf" />
    <param name="bgcolor" value="#ffffff" />
    <param name="FlashVars" value="<?php print $bibleTexts->getparamURL().$bibleTexts->getparamTITLE().'&amp;bgcolor1=189ca8&amp;bgcolor2=085c68&amp;autoplay=1"'; ?> />
</object>

    </body>
</html>
