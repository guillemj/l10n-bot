<?
// Copyright (C) 2003-2004 Tim Dijkstra
//
// This program is free software; you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation; either version 2 of the License, or
// (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
// # You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA. 
//
// FUNCTION:
//  This script displays information which was abstracted from emails send to 
//  debian-l10n-dutch@l.d.o and containing pseudo-urls. The info was gathered
//  and put in a MySQL db by another program, l10n-bot. This script will only
//  show the latest record of a certain translation, unless the user specified
//  otherwise. It's also possible to sort the list on name, translaror, status 
//  or date.
//  

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
  <title>Coordination of debian-l10n-dutch</title>
  <style type='text/css'>
    .ITT {background-color:#ff6d6d;}
    .RFR, .RFR2 {background-color:#f4cf16}
    .LCFC {background-color:#edf416}
    .BTS {background-color:#a8e5a5}
    .DONE {background-color:#1df416}
    A {text-decoration:none;}
    .nobg{background-color:white;}
  </style>
</head>
<body text="#000000" bgcolor="#FFFFFF" link="#0000FF" vlink="#800080" alink="#FF0000">
<table border="0" cellpadding="3" cellspacing="0" align="center" summary="">

<tr>
<td>
<a href="http://www.debian.org/"><img src="http://www.debian.org/logos/openlogo-nd-50.png" border="0" hspace="0" vspace="0" alt="" width="50" height="61"></a>
<a href="http://www.debian.org/"><img src="http://www.debian.org/Pics/debian.jpg" border="0" hspace="0" vspace="0" alt="Debian Project" width="179" height="61"></a>
</td>
</tr>
</table>
<h1>Coordination of debian-l10n-dutch</h1>
<p>
This page is made to aid the coordination of translating debian related text to dutch. As documented <a href='pseudo-urls.html'>here</a>, translators and reviewers use pseudo-urls in the subject of e-mails to the debian-l10n-dutch list for coordidation. 
<p>
A program parses these pseudo-urls and collects the relevant data, which is thendisplayed below. In case of RFR/LCFC messages it will also look for an attached file with the right extension (po,wml) <i>Don't know how translating manpages works</i>.
<p>
<table>
  <tr><th></th><th><a href='<?=$PHP_SELF?>?sort=name'>Name</a></th><th><a href='<?=$PHP_SELF?>?sort=translator'>Translator</th><th><a href='<?=$PHP_SELF?>?sort=status'>Status</th><th><a href='<?=$PHP_SELF?>?sort=date'>Date</a></th><th>File</th><th></th></tr>
<?
  
$expanded=explode(",",$history);
// Only sorting on a few things is allowed.
if(!in_array($sort,array('status','date','name','translator'))){$sort='name';}
// Make a compare function on-the-fly, used to sort the array
if($sort!='status'){
    $cmpfunc=create_function('$a,$b',"return strcmp(\$a[$sort],\$b[$sort]);");
} else {
    function comp_status ($a,$b){
      $stati = array('ITT','RFR','RFR2','LCFC','BTS','DONE'); 
      return array_search($a['status'],$stati) > array_search($b['status'],$stati);
    }
    $cmpfunc="comp_status";
}


$link=mysql_connect("localhost","USER","PASSWORD");
mysql_select_db("debian");

// Get everything from the db
$res=mysql_query($sql="SELECT id,name,status,type,translator,date,file,bugnr FROM translation ORDER BY type,name,date,status ");

// Loop over it
while(($var=mysql_fetch_assoc($res)) or $old[0]){
    // If the former record doesn't have the same name, that was the last 
    // record for this 'name'.
    if(isset($old) && ($var['name']!=$old[0]['name'])){
	// Skip done if > 2 weeks ago 1209600
	if(!( ($old[count($old)-1]['status']=="DONE") and (isset($nodone) or $old[count($old)-1]['date']<date("Y-m-d H:i:s", time()-1209600)) ) ){
//echo $old[count($old)-1]['date'] .$old[count($old)-1]['status'].date("Y-m-d H:i:s", time()+1)."<br>";
	$cur_name=$old[0]['name'];
        // If the user wants to see the history of the translation
        // We'll put all records of the same name in an array and append it to
	// the last record. Only this record will end up in the data array
	// This way we can still sort on 'name'
	if(in_array($old[0]['name'],$expanded)){
	    for($i=1;$i<count($old);$i++){
		unset($old[$i]['name']);
		// Rows form history, are printed a bit different
		if($old[0]['translator']==$old[$i]['translator']) 
		    unset($old[$i]['translator']);
		$old[$i]['class']=$old[count($old)-1]['status'];
	    }
	        
	    $old[0]['button']="<a href='$PHP_SELF?history=".implode(",",array_diff($expanded,array($old[0]['name'])))."#{$old[0]['name']}'>-</a>";
	    $old[0]['class']=$old[count($old)-1]['status'];

	    $old[count($old)-1]['history']=$old;
	    $data[]=array_pop($old);
	    $data[count($data)-1]['name']=$cur_name;
	} else {
	// Else we'll only keep the last
	    $old[count($old)-1]['button']="<a href='$PHP_SELF?history=$history,{$old[0]['name']}#{$old[0]['name']}'>+</a>";
	    $data[]=array_pop($old);
	}
	}

        unset($old);
    }
    // If the former record has a diff type, we can process the former type.
    if(isset($data) && ($var['type']!=$data[0]['type'])){
	echo "<tr><td colspan=3><h3>{$data[0]['type']}</h3></td></tr>";
	// Sort the array
	usort($data,$cmpfunc);

	// Loop over all 'name's
        for($i=0;$data[$i];$i++){
	    // If we have a history, put the records of the 'history' in the 
	    // data array, so it will also be printed.
	    if($data[$i]['history']){
		array_splice($data,$i,1,$data[$i]['history']);
	    }
	    // Process the fields a bit
	    $data[$i]['translator']=htmlentities($data[$i]['translator']);
	    $data[$i]['date']=preg_replace("/(\d{4})(\d{2})(\d{2}).*/","\\1-\\2-\\3",$data[$i]['date']);
	    $data[$i]['class']=($data[$i]['class']?$data[$i]['class']:$data[$i]['status']);
	    // If status is BTS, make a link.
	    $data[$i]['status']=($data[$i]['status']=="BTS" && $data[$i]['bugnr']?"<a href='http://bugs.debian.org/cgi-bin/bugreport.cgi?bug={$data[$i]['bugnr']}'>BTS</a>":$data[$i]['status']);
	    // If we have a file, make link. File was saved as $id.$exts
	    $data[$i]['file']=($data[$i]['file']?"<a href='{$data[$i]['id']}.{$data[$i]['file']}'>[{$data[$i]['file']}]</a>":"");
	    // Print a row
	    echo 
 "<tr class='{$data[$i]['class']}'>".   
   "<td".($data[$i]['name']?'':" class='nobg' ").">".
     "<a name='{$data[$i]['name']}'></a>{$data[$i]['button']}".
   "</td>".
   "<td".($data[$i]['name']?" style='padding-right:2em;' ":" class='nobg' ").">{$data[$i]['name']}</td>".
   "<td ".($data[$i]['name']?" style='font-style:italic' ":" class='nobg' ").">{$data[$i]['translator']}</td>".
   "<td>{$data[$i]['status']}</td>".
   "<td>{$data[$i]['date']}</td>".
   "<td>{$data[$i]['file']}</td>".
 "</tr>\n";
	}

	unset($old);
	unset($data);
    }
   
    // Make the current record the former.
    $old[]=$var;
}

mysql_close($link);
?>
</table>
<p>
<hr noshade width="100%" size="1">
<p>
<small>Comments: <a href='mailto:webmaster@famdijkstra.org'>webmaster</a></small>
</body>
