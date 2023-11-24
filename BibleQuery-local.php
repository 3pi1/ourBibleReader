<html>
<head>
  <title>Our Bible Reader</title>
</head>
<body>
<div style="text-align: center;">
<h1>Our Bible Reader</h1>
</div>

<?php
   
  include "bibleResultProcessor.php";
  //echo "hi";
  //include "bibleResources.php";      // this cant remain commented out
 // echo "after includes";
 //  print phpinfo();

  // create short variable names
  $book=rtrim($_POST['book']);
  $chapter=rtrim($_POST['chapter']);
  $verse=rtrim($_POST['verse']);
  $bibletextsearch=($_POST['bibletextsearch']);     // don't want rtrim so you can specify " word " to get just word results back
  $helpbutton=($_POST['helpbutton']);
  //print "<strong>Anything?<br />".$bibletextsearch.$verse.$chapter.$book."</strong>";

print <<<_END_


 <table style="text-align: left; " border="0"
cellpadding="2" cellspacing="2">
<tbody>
<form action ="$_SERVER[PHP_SELF]" method="post" name="HelpRequest"]>
<tr>
<td style="vertical-align: top;"><br>
</td>
<td style="vertical-align: top;"> Get Help <br>
</td>

<td style="vertical-align: top;"><input name ="helpbutton" value="help" type="submit"><br>
</td>
  <td colspan="1" rowspan="8" style="vertical-align: top; text-align: right;"><img
style="width: 370px; height: 270px;" alt=""
src="Images/artsy-bible.jpg"><br>
</td>
</tr>
</form>
<tr>
<td style="vertical-align: top;"><br>
</td>
<td style="vertical-align: top;"> Search by Bible Reference <br>
</td>
<td style="vertical-align: top;">                       <br>
</td>
</tr>
<form action="$_SERVER[PHP_SELF]" method="post" name="BibleReferenceSearch">   
<tr>
<td style="vertical-align: top;">Book:<br>
</td>
<td style="vertical-align: top;">
  $select_BIBLETEXT
  <input name="book" value="$book"><br>
</td>
<td style="vertical-align: top;"><br>
</td>
</tr>
<tr>
<td style="vertical-align: top;">Chapter<br>
</td>
<td style="vertical-align: top;"><input name="chapter" value="$chapter"><br>
</td>
<td style="vertical-align: top;"><br>
</td>
</tr>
<tr>
<td style="vertical-align: top;">Verse<br>
</td>
<td style="vertical-align: top;"><input name="verse" value="$verse"><br>
</td>
<td style="vertical-align: top;"><input name="search" value="search" type="submit"><br>
</td>
</tr>
<tr>
<td style="vertical-align: top;"><br>
</td>
<td style="vertical-align: top;"> - or - <br>
</td>
<td style="vertical-align: top;"><br>
</td>
</tr>
</form>
<form action="$_SERVER[PHP_SELF]" method="post" name="BibleTextSearch">
<tr>
<td style="vertical-align: top;"><br>
</td>
<td style="vertical-align: top;"> Search Within Bible Text <br>
</td>
<td style="vertical-align: top;"><br>
</td>
</tr>
<tr>
<td style="vertical-align: top;">Text to Find<br>
</td>
<td style="vertical-align: top;"><input name="bibletextsearch" value="$bibletextsearch"><br>
</td>
<td style="vertical-align: top;"><input name="searchtext" value="search" type="submit"><br>
</td>
</tr>
</form>
</tbody>
</table> 

_END_;

  //phpinfo();
  
  $db = new mysqli('localhost', 'david', 'bible', 'bible');


  $bibleTexts = new BibleResultProcessor;
  $bibleTexts->set_media_source($_SERVER['HTTP_HOST']+"/bible-database/mp3");     // was "localhost/bible-database/mp3"
  
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
    else if ($helpbutton){
        $helptext =<<<_HELPTEXT_
<center>
<strong> How to use the search capabilities, and what ourbiblereader does.</br></br></strong>
OurBibleReader lets you search the bible (currently only in the King James Version)</br>
Then in reads it back to you. </br>
</br>
"" is a convenient way to say leave the field blank.</br>
"hi " is hi with a blank after it</br>
" hi " is a space before hi and a space after</br>
(You don't actually enter the quotes)</br>
</br>
Notes for the bible reference search:</br>
the book, chapter and verse are independent, so you can look for</br>
 "", 24, 7 to give you all the books of the bible that have a 24:7</br>
 reference.</br>
 (Gen 24:7, Exo 24:7, etc)</br>
</br>
Notes for the text search:</br>
If you use the text search " love " will give you just verses with the word love in it, " love" will give you love, loveth loves,</br>
 etc but just "love" will give you all those but also cloven, etc.  You  don't enter quotes in the text field</br>
</br>
</center>
_HELPTEXT_;
    print $helptext;
    }
  }

//  print "<br />query is: ". $query;
  $result = $db->query($query);
  $num_results = $result->num_rows;
  if ($num_results){
    print "<br /><p>Number of references found: ".$num_results."</p>";
  }

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
     if ($bnum <=66){
        echo $bibleLink;
     }
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
Book: <input name="tar_bnum" value="$tar_bname" type="text">
Chapter: <input name="tar_cnum" value="$tar_cnum" type="text">
Verse: <input name="tar_vnum" value="$tar_vnum" type="text">
</form>
_ENDFORM_;
     
   // echo $ref_add;

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


//echo $ref_del;
        $bibleLink = $bibleTexts->add_and_process_link ($bname, $vtext , $tar_bnum , $tar_cnum , $tar_vnum, $j);
        echo "<li>".$bibleLink."<br />".$notes."</li>";        
     }

     print "</ol></small>\n";
  }

  //print "<strong><br /> - The End - <br /> </strong>";
print "<br><br>";


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


