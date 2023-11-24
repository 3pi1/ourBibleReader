<?php
//echo "hello? from biblereseltprocessor";

/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of bibleResultProcessor
 *
 * @author david
 */

class BibleResultProcessor {
    protected $paramURL, $paramTITLE, $media_source;

    public function clear_all() {
        //echo "CLEAR ALL";
//        $this->bibleURL = "";
        $this->paramURL= "";
        $this->paramTitle ="";
}

    public function set_media_source($source){
        //$source = "localhost/bible-database/mp3/"
        //$source = "ebibleverses.com";
        //$source = "ebiblemedia.com";
        $this->media_source = $source;        
    }


    public function __construct() {
        //echo "inside construtor";
        $this->clear_all();
    }

    // this function appends to $paramURL and $paramTitle until the results list is finished for the flash mp3 player.
    public function add_and_process_link ($bname, $vtext , $bnum , $cnum , $vnum, $index) {
        //echo "ADD AND PROCESS LINK";
         if ($bnum <=66){    // books above 66 are for special searches at the moment
             $linkval =$bname.' '.$cnum.' : '.$vnum;
             $bibleURL = $this->getbibleURL($bname, $bnum, $cnum, $vnum);

             //echo $linkval;
             //echo $bibleURL;

             $this->paramURL .="|".$bibleURL;
             $this->paramTITLE .= "|".$linkval;

             $bibleLink = sprintf("<a href =\"%s\" name = \"%s\" title = \"%s\" > %s </a>\n", $bibleURL, $linkval, $linkval, $vtext);
             $ref = "<p><strong>".($index+1).".) ".$bname." ".$cnum." : ".$vnum."<br />Text: </strong>";

             return $ref.$bibleLink;
         }
     }

    // this function doesn't have anything to do with this class, but it is here to make it easier to call from inside
    // the class
    public function getbibleURL ( $bname,  $bnum,  $cnum,  $vnum) {
        //echo "in function getbibleURL";
        $bname = str_replace(" ", "", $bname);
        //echo $bname;
        $bname = substr($bname, 0, 3);
        //echo $bname;

        // set cf (chapter format) and vf verse format to 2 digits (printf format)
        $cf = $vf = "%02d";
        
        //$source = "ebibleverses.com";
        //$source = "ebiblemedia.com";

        switch ($bnum){
            // special names (not first three letters of long name)
            case 50: $bname = "Php"; break;
            case 57: $bname = "Phm"; break;
            case 65: $bname = "Jde"; break;
            case 19: // for psalms (19th book), chapter and verse are triple digit
                        $cf = "%03d";
                        $vf = "%03d";
                        break;
        }
        
        $url_root = "http://".$this->media_source;
        $book_and_chapter = "";
        $chapter_and_verse = "";
        $filename = sprintf("/%02d_%s_".$cf."_".$vf, $bnum, $bname, $cnum, $vnum).".mp3";
        
        if ($this->media_source != "localhost/bible-database/mp3"){
            
            $book_and_chapter = sprintf("/%02d_%s/%02d_%s_".$cf,$bnum,$bname,$bnum,$bname,$cnum);           
            $chapter_and_verse = sprintf("/%02d_%s_".$cf."_".$vf, $bnum, $bname, $cnum, $vnum);
        }
        $url .= $url_root.$book_and_chapter.$chapter_and_verse.$filename;
        
        //echo $url;
        return $url;
    }

     public function getparamURL (){
        // echo "getparamURL";
        return ("mp3=".ltrim ($this->paramURL, "|"));
     }

     public function getparamTITLE () {
        // echo "getparamTITLE";
        return("&amp;title=".ltrim ($this->paramTITLE, "|"));
     }
}
// end class
/* test code *//*
echo "hello";
$b = new BibleResultProcessor;
echo $b->getparamURL();
echo $b->getparamTITLE();

$b->set_media_source("localhost/bible-database/mp3");
$bname ="Gen";
$bnum = $cnum = $vnum = $index = 1;

$lt = $b->add_and_process_link($bname, $bnum, $cnum, $vnum, $index);
echo $lt;

$b->set_media_source("ebiblemedia.com");
$lt = $b->add_and_process_link($bname, $bnum, $cnum, $vnum, $index);
echo $lt;

echo $b->getparamURL();
echo $b->getparamTITLE();
// *//* test code */
 echo "bye bye from bible result processor";
?>
