<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

echo "hc";

/**
 * Description of newPHPClass
 *
 * @author david
 */

class Downloader {
    private $urls;

    private function init(){
        //$this->save_to=
    }

    public function add_url($title, $url){
         
         $this->urls[$title] = $url;
         
         $g='/var/www/bible-database/mp3/'.basename($url);
         print "<br /> \n REMOTE: ".$url."<br /> \n";
         print "<br /> \n LOCAL: ".$g."<br /> \n";

         if(!is_file($g)){

//          create curl resource
//          $ch = curl_init($url);
          // make a file to write to

          //$surl = substr($url, 8);
          //echo $surl;
          //
          // try it with sockets
          //$fip = fsockopen($surl, 80, $errno, $errstr, 30);


            // try it with files
            if(!($fip = fopen($url, "r"))) {
                print "couldn't open fip: ".$url;
                if(!($fop = fopen ($g."ERROR", "w"))) {print "couldn't open fop: ".$g;}
                }else if(!($fop = fopen ($g, "w"))) {print "couldn't open fop: ".$g;}

            //curl_setopt ($ch, CURLOPT_FILE, $fp);
            //curl_setopt ($ch, CURLOPT_HEADER ,0);
           
           // guessing this is what I want
           //curl_setopt ($ch, CURLOPT_BINARYTRANSFER, $fp);
            //curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,60);
         

            if (!$fip) {
                echo "$errstr ($errno)<br />\n";
            }else{
                while(!feof($fip)){
                    $out = fread($fip, 1000);
                    //echo $out;
                    fwrite($fop, $out);
                    echo "*";
                }
            }

            if(!fclose($fip))
                print "error fip close";
            if (!fclose($fop))
                print "error fop close";
        }else{print "\n<br> SKIPPING: local copy of file: ".$url." already exists<br>\n";}
    }
        // $output contains the output string
        //$fp = curl_exec($ch);

        // close curl resource to free up system resources

 //       curl_close($ch);
 //       fclose ($fp);

//    }

    // the main reason I do it this way, is because I want to see what happens when I assign 31000 files...
    // normally I'd be more memory conscious
    public function processURLs(){
    echo "processURLs";
        $mh = curl_multi_init();
        foreach ($this->urls as $i => $url) {
            $g='/home/david/bible-database/mp3/'.basename($url);
            echo "\n<br />REMOTE: (".$url.")\n";
            print "<br/> \n LOCAL: (".$g.")<br /> \n";
            //echo $i.$url.$g;

            if(!is_file($g)){
                $conn[$i]=curl_init($url);
                $fp[$i]=fopen ($g, "w");
                echo "<br>opened fp[i] connected to url<br>\n";
                curl_setopt ($conn[$i], CURLOPT_FILE, $fp[$i]);
                curl_setopt ($conn[$i], CURLOPT_HEADER ,0);
                curl_setopt($conn[$i],CURLOPT_CONNECTTIMEOUT,60);
                curl_multi_add_handle ($mh,$conn[$i]);
            }
        }
        do {
            $n=curl_multi_exec($mh,$active);
            // what else to do to the $fp's?
            echo "*";
            //each one should go to its fp...
        }
        while ($active);

        foreach ($urls as $i => $url) {
            echo "<br>CLOSING curl multi and fps <br>\n";
            curl_multi_remove_handle($mh,$conn[$i]);
            curl_close($conn[$i]);
            fclose ($fp[$i]);
        }
        curl_multi_close($mh);
    }

}


// this is run one time to get all the files, so I can mess with them (tired of running them from remote)

echo "HELLO";

include "bibleResultProcessor.php";

$xportlist = stream_get_transports();
print_r($xportlist);
print "<br>\n";
print_r(stream_get_wrappers());


@ $db = new mysqli('localhost', 'bible', 'bible', 'bible');
$bibleTexts = new BibleResultProcessor;
$dl = new Downloader;

echo "After init";

  if (mysqli_connect_errno()) {
     echo 'Error: Could not connect to database.  Please try again later.';
     exit;
  }

  $query = "select * from kjv";
  $result = $db->query($query);
  $num_results = $result->num_rows;
  echo $num_results;

    for ($i=0; $i <$num_results; $i++) {
         $row = $result->fetch_assoc();

         $bname = htmlspecialchars(stripslashes($row['bname']));
         $bnum=stripslashes($row['bnum']);
         $cnum= stripslashes($row['cnum']);
         $vnum= stripslashes($row['vnum']);

         $bibleURL = $bibleTexts->getbibleURL ($bname , $bnum , $cnum , $vnum);

         $dl->add_url($bnum.$bname.$cnum.$bname.$vnum, $bibleURL);
    }

    break;  //end prog

    $dl->processURLs();


/* example code from apache */

  function curlit_rewritecond($U,$pass){
    global $RPASS,$RSITE;
  $RPASS=$pass;

    $FF_HDR=array("Accept: text/xml,application/xml,application/xhtml+xml,text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5",
  "Accept-Language: en-us,en;q=0.9,de;q=0.8,ja;q=0.8,zh;q=0.7,zh-cn;q=0.6,nl;q=0.5,fr;q=0.5,it;q=0.4,ko;q=0.3,es;q=0.2,ru;q=0.2,pt;q=0.1",
  "Accept-Encoding: gzip,deflate","Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7","Keep-Alive: 300","Connection: keep-alive","Pragma:");

    if($fp = tmpfile()){
        $mh = curl_multi_init();
        foreach ($U as $i => $ur) {
      $url=$RSITE.'?Q='.$ur;
            if (!$url_info = parse_url($url)) die('bad url '.$url);
            $ch[$i] = curl_init($url);
            curl_setopt ($ch[$i], CURLOPT_HEADERFUNCTION, 'aacurlheader');
            curl_setopt ($ch[$i], CURLOPT_HEADER, 1);
            curl_setopt ($ch[$i], CURLOPT_VERBOSE, 0);
            curl_setopt ($ch[$i], CURLOPT_NOBODY, 1);
            curl_setopt ($ch[$i], CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt ($ch[$i], CURLOPT_STDERR, $fp);
            curl_setopt ($ch[$i], CURLOPT_FAILONERROR, 0);
            curl_setopt ($ch[$i], CURLOPT_FOLLOWLOCATION, 0);
            curl_setopt ($ch[$i], CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.7) Gecko/20070914 Firefox/2.0.0.7 (via www.askapache.com)');
            curl_setopt ($ch[$i], CURLOPT_INTERFACE, '208.86.158.195');
            curl_setopt ($ch[$i], CURLOPT_HTTPHEADER, $FF_HDR);
            curl_setopt ($ch[$i], CURLOPT_REFERER, 'http://www.askapache.com');
            curl_setopt ($ch[$i], CURLOPT_ENCODING, 0);
            curl_setopt ($ch[$i], CURLOPT_CONNECTTIMEOUT, 45);
            curl_setopt ($ch[$i], CURLOPT_MAXCONNECTS, 5);
            curl_setopt ($ch[$i], CURLOPT_MAXREDIRS, 0);
            curl_multi_add_handle ($mh,$ch[$i]);
        }
        do { ob_start();$r=curl_multi_exec($mh,$active);$t=ob_get_clean();}
    while($r == CURLM_CALL_MULTI_PERFORM || $active);
    if ($r != CURLM_OK) die("Curl multi read error $r");
        foreach ($U as $i => $url) {
      if (curl_errno($ch[$i])) {echo curl_error($ch[$i])."-".curl_errno($ch[$i]);}
      //else $cch=curl_getinfo($ch[$i]);
            curl_multi_remove_handle($mh,$ch[$i]);
            curl_close($ch[$i]);
        }
        curl_multi_close($mh);
        fclose($fp);
    }
  sleep(1);
  return true;
}



// place this code inside a php file and call it f.e. "download.php"
$path = $_SERVER['DOCUMENT_ROOT']."/path2file/"; // change the path to fit your websites document structure
$fullPath = $path.$_GET['download_file'];

if ($fd = fopen ($fullPath, "r")) {
    $fsize = filesize($fullPath);
    $path_parts = pathinfo($fullPath);
    $ext = strtolower($path_parts["extension"]);
    switch ($ext) {
        case "pdf":
        header("Content-type: application/pdf"); // add here more headers for diff. extensions
        header("Content-Disposition: attachment; filename=\"".$path_parts["basename"]."\""); // use 'attachment' to force a download
        break;
        default;
        header("Content-type: application/octet-stream");
        header("Content-Disposition: filename=\"".$path_parts["basename"]."\"");
    }
    header("Content-length: $fsize");
    header("Cache-control: private"); //use this to open files directly
    while(!feof($fd)) {
        $buffer = fread($fd, 2048);
        echo $buffer;
    }
}
fclose ($fd);
exit;
// example: place this kind of link into the document where the file download is offered:
// <a href="download.php?download_file=some_file.pdf">Download here</a>


    ?>




