<?php
/*
Eggdrop:Webinterface
- Eggdrop webinterface to give your usern and/or customers control over there bots.

Copyright (C) 2008 Eric 'take' Kurzhals
    
	www.codershell.org

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program; if not, see <http://www.gnu.org/licenses/>.
*/

/* kernel/bbcode.class.php */

class bbcode {

	public function parse ($text, $smiles=1, $bb=1, $url=true)
	{
	
		$max_l = 70; 
		  $text = htmlspecialchars($text);
		  $lines = explode("\n", $text);
		  $merk = $max_l;
		  for($n=0;$n<count($lines);$n++) {
			  $words = explode(" ",$lines[$n]);
			  $count_w = count($words)-1;
			  if($count_w >= 0) {
				  for($i=0;$i<=$count_w;$i++) {
					  $max_l = $merk;
					  $tword = trim($words[$i]);
					  $tword = preg_replace("/\[(.*?)\]/si", "", $tword);
					  $all = substr_count($tword, "http://") + substr_count($tword, "https://") + substr_count($tword, "www.") + substr_count($tword, "ftp://");
					  if($all > 0) {
						  $max_l = 200;
					  }
					  if(strlen($tword)>$max_l) {
						  $words[$i] = chunk_split($words[$i], $max_l, $lword_replace);
						  $length = strlen($words[$i])-5;
						  $words[$i] = substr($words[$i],0,$length);
					  }
					}
					$lines[$n] = implode(" ", $words);
			  } else {
				  $lines[$n] = chunk_split($lines[$n], $max_l, $lword_replace);
			  }
		  }
	
		$text = implode("\n", $lines);
		$text = nl2br($text);
		
		if($url) {
			  $text = preg_replace('"(( |^)((ftp|http|https){1}://)[-a-zA-Z0-9@:%_\+.~#?&//=]+)"i', '<a class="link" href="\1" target="_blank">\\1</a>', $text);
			  $text = preg_replace('"( |^)(www.[-a-zA-Z0-9@:%_\+.~#?&//=]+)"i', '\\1<a class="link" href="http://\2" target="_blank">\\2</a>', $text);
			  $text = preg_replace('"([_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,3})"i', '<a class="link" href="mailto:\1">\\1</a>', $text);
		}
		  if($bb == 1) {
			  $text = preg_replace("/\[b\](.*?)\[\/b\]/si", "<b>\\1</b>", $text);
			  $text = preg_replace("/\[i\](.*?)\[\/i\]/si", "<i>\\1</i>", $text);
			  $text = preg_replace("/\[u\](.*?)\[\/u\]/si", "<u>\\1</u>", $text);
			  $text = preg_replace("/\[list\](.*?)\[\/list\]/si", "<ul>\\1</ul>", $text);
			  $text = preg_replace("/\[list=(.*?)\](.*?)\[\/list\]/si", "<ol type=\"\\1\">\\2</ol>", $text);
			  $text = preg_replace("/\[\*\](.*?)\\n/si", "<li>\\1</li>", $text);
			  $text = preg_replace("/\[align=(.*?)\](.*?)\[\/align\]/si", "<div align=\"\\1\">\\2</div>", $text);
			  $text = preg_replace("/\[color=(.*?)\](.*?)\[\/color\]/si", "<font color=\"\\1\">\\2</font>", $text);
			  $text = preg_replace("/\[size=(.*?)\](.*?)\[\/size\]/si", "<font size=\"\\1\">\\2</font>", $text);
	
			  $text = preg_replace("/\[quote\](.*?)\[\/quote\]/si", $header_quote.'\\1'.$footer_quote, $text);
			  $text = preg_replace("/\[quote=(.*?)\](.*?)\[\/quote\]/si", "<div style=\"width:90%; overflow: auto\" algin=\"center\"><br><b><i>Zitat von \\1</i></b><div align=\"left\"><hr color=\"#C0C0C0\" width=\"100%\" size=\"1\">".'\\2'.$footer_quote, $text);
			  $text = preg_replace("/\[url=http:\/\/(.*?)\](.*?)\[\/url\]/si", "<a href=\"http://\\1\" target=\"_blank\" id=\"sites\">\\2</a>", $text);
			  $text = preg_replace("/\[url=(.*?)\](.*?)\[\/url\]/si", "<a href=\"\\1\" id=\"sites\">\\2</a>", $text);
			  $text = preg_replace("/\[url](.*?)\[\/url\]/si", "<a href=\"\\1\" target=\"_blank\" id=\"sites\">\\1</a>", $text);
			  $text = str_replace("[hr]" ,$hrvalue ,$text);
	
			  $text = preg_replace("/\[img\](.*?)\[\/img\]/si", "<img src=\"\\1\" border=0>", $text);
	
				if ($smiles == 1) {
					$ort = "gfx/smiles";
					$array = array(
								":|","angry.gif",
								":]","bigsmile.gif",
								"8]","cool.gif",
								":D","happy.gif",
								":(","mad.gif",
								":x","raped.gif",
								"[smile=alien]", "alien.gif",
								"[smile=angry]", "angry.gif",
								"[smile=bigsmile]", "bigsmile.gif",
								"[smile=clown]", "clown.gif",
								"[smile=cool]", "cool.gif",
								"[smile=loving]", "loving.gif",
								"[smile=puke]", "puke.gif",
								"[smile=raped]", "raped.gif",
								"[smile=sick]", "sick.gif",
								"[smile=sleep]", "sleep.gif", 
								"[smile=smile]", "smile.gif",
								"[smile=smoking]", "smoking.gif",
								"[smile=twins]", "twins.gif",
								"[smile=wink]", "wink.gif"
						  );
						 $smilie_rep_count = 0;
						 while($array[$smilie_rep_count]!="")
						 {
							$smilie_rep_count2 = $smilie_rep_count + 1;
							$text = str_replace("$array[$smilie_rep_count]","<image border='0' src=\"$ort/$array[$smilie_rep_count2]\">",$text);
							$smilie_rep_count+=2;
						 }
	   				}
				}
		return $text;
	}
}
?>