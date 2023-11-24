<?php
// this file defines a couple values that can be used to output a list select

//echo "line1 bibleResources";

$opt_special= <<<_BIBLESPECIALS_
<option value="">Entire Bible</option>
<option value="OT">Old Testament</option>
<option value="NT">New Testament</option>
<option value="JESUS"> Words of Jesus</option>
_BIBLESPECIALS_;

$opt_bbooks = <<<_BIBLEBOOKSOPTIONS_
<option value="01">Genesis</option>
<option value="02">Exodus</option>
<option value="03">Leviticus</option>
<option value="04">Numbers</option>
<option value="05">Deuteronomy</option>
<option value="06">Joshua</option>
<option value="07">Judges</option>
<option value="08">Ruth</option>
<option value="09">1 Samuel</option>
<option value="10">2 Samuel</option>
<option value="11">1 Kings</option>
<option value="12">2 Kings</option>
<option value="13">1 Chronicles</option>
<option value="14">2 Chronicles</option>
<option value="15">Ezra</option>
<option value="16">Nehemiah</option>
<option value="17">Esther</option>
<option value="18">Job</option>
<option value="19">Psalms</option>
<option value="20">Proverbs</option>
<option value="21">Ecclesiastes</option>
<option value="22">Song of Solomon</option>
<option value="23">Isaiah</option>
<option value="24">Jeremiah</option>
<option value="25">Lamentations</option>
<option value="26">Ezekiel</option>
<option value="27">Daniel</option>
<option value="28">Hosea</option>
<option value="29">Joel</option>
<option value="30">Amos</option>
<option value="31">Obadiah</option>
<option value="32">Jonah</option>
<option value="33">Micah</option>
<option value="34">Nahum</option>
<option value="35">Habakkuk</option>
<option value="36">Zephaniah</option>
<option value="37">Haggai</option>
<option value="38">Zechariah</option>
<option value="39">Malachi</option>
<option value="40">Matthew</option>
<option value="41">Mark</option>
<option value="42">Luke</option>
<option value="43">John</option>
<option value="44">Acts</option>
<option value="45">Romans</option>
<option value="46">1 Corinthians</option>
<option value="47">2 Corinthians</option>
<option value="48">Galatians</option>
<option value="49">Ephesians</option>
<option value="50">Philippians</option>
<option value="51">Colossians</option>
<option value="52">1 Thessalonians</option>
<option value="53">2 Thessalonians</option>
<option value="54">1 Timothy</option>
<option value="55">2 Timothy</option>
<option value="56">Titus</option>
<option value="57">Philemon</option>
<option value="58">Hebrews</option>
<option value="59">James</option>
<option value="60">1 Peter</option>
<option value="61">2 Peter</option>
<option value="62">1 John</option>
<option value="63">2 John</option>
<option value="64">3 John</option>
<option value="65">Jude</option>
<option value="66">Revelation</option>
_BIBLEBOOKSOPTIONS_;

//echo "after defines";

$opts = $opt_special.$opt_bbooks;

global $select_BIBLETEXT;
$select_BIBLETEXT = '<select name="book" >'.$opts."</select>";

//echo $select_BIBLETEXT;
?>