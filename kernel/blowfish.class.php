<?php
    /* PHP Implementation of Blowfish (www.php-einfach.de)
     *
     * Blowfish was designed in 1993 by Bruce Schneier as a fast, 
     * free alternative to existing encryption algorithms.
     * 
     * It is a 64-bit Feistel cipher, consisting of 16 rounds. 
     * Blowfish has a key length of anywhere from 32 bits up to 448 bits. 
     * 
     * Blowfish uses a large key-dependent S-boxes, a complex key shedule and a 18-entry P-Box 
     * 
     * Blowfish is unpatented and license-free, and is available free for all uses.
     * 
     * ***********************
     * Diese Implementierung darf frei verwendet werden, der Author uebernimmt keine
     * Haftung fuer die Richtigkeit, Fehlerfreiheit oder die Funktionsfaehigkeit dieses Scripts.
     * Benutzung auf eigene Gefahr.    
     *
     * Ueber einen Link auf www.php-einfach.de wuerden wir uns freuen.
     * 
     * ************************
     * Usage:
     * <?php
     * include("blowfish.class.php");
     *
     * $blowfish = new Blowfish("secret Key");
     * $cipher = $blowfish->Encrypt("Hello World"); //Encrypts 'Hello World'
     * $plain = $blowfish->Decrypt($cipher); //Decrypts the cipher text
     * 
     * echo $plain;
     * ?>
     */

include("kernel/blowfish.box.php");
define("CBC", 1);

class Blowfish {

   var $pbox, $sbox0, $sbox1, $sbox2, $sbox3;

   function Blowfish($key) {
      $this->KeySetup($key);
   }
   
   //Verschluesseln
   function Encrypt($text) {
      $n = strlen($text);
      if($n%8 != 0) $lng = ($n+(8-($n%8)));
      else $lng = 0;

      $text = str_pad($text, $lng, ' ');
      $text = $this->_str2long($text);

      //Initialization vector: IV
      if(CBC == 1) {
         $cipher[0][0] = time();
         $cipher[0][1] = (double)microtime()*1000000;
      }

      $a = 1;
      for($i = 0; $i<count($text); $i+=2) {
         if(CBC == 1) {
            //$text mit letztem Geheimtext XOR Verknuepfen
            //$text is XORed with the previous ciphertext
            $text[$i] ^= $cipher[$a-1][0];
            $text[$i+1] ^= $cipher[$a-1][1];
         }

         $cipher[] = $this->block_encrypt($text[$i],$text[$i+1]);
         $a++;
      }

      $output = "";
      for($i = 0; $i<count($cipher); $i++) {
         $output .= $this->_long2str($cipher[$i][0]);
         $output .= $this->_long2str($cipher[$i][1]);
      }

      return base64_encode($output);
   }




   //Entschluesseln
   function Decrypt($text) {
        $plain = array();
      $cipher = $this->_str2long(base64_decode($text));

      if(CBC == 1)
         $i = 2; //Message start at second block
      else
         $i = 0; //Message start at first block

      for($i; $i<count($cipher); $i+=2) {
         $return = $this->block_decrypt($cipher[$i],$cipher[$i+1]);

         //Xor Verknuepfung von $return und Geheimtext aus von den letzten beiden Bloecken
         //XORed $return with the previous ciphertext
         if(CBC == 1)
            $plain[] = array($return[0]^$cipher[$i-2],$return[1]^$cipher[$i-1]);
         else          //EBC Mode
            $plain[] = $return;
      }

      for($i = 0; $i<count($plain); $i++) {
         $output .= $this->_long2str($plain[$i][0]);
         $output .= $this->_long2str($plain[$i][1]);
      }

      return $output;
   }

   //Bereitet den Key zum ver/entschluesseln vor
   function KeySetup($key) {
      global $pbox,$sbox0,$sbox1,$sbox2,$sbox3;

      $this->pbox = $pbox;
      $this->sbox0 = $sbox0;
      $this->sbox1 = $sbox1;
      $this->sbox2 = $sbox2;
      $this->sbox3 = $sbox3;



      if(!isset($key) || strlen($key) == 0)
         $key = array(0);
      else
         $key = $this->_str2long(str_pad($key, strlen($key)+strlen($key)%4, $key));

      # XOR Pbox1 with the first 32 bits of the key, XOR P2 with the second 32-bits of the key,
      for($i=0;$i<count($this->pbox);$i++)
         $this->pbox[$i] ^= $key[$i%count($key)];



      $v[0] = 0x00000000;
      $v[1] = 0x00000000;

      //P-Box durch verschluesselte Nullbit Bloecke ersetzen. In der niechsten Runde das Resultat erneut verschluesseln
      //Encrypt Nullbit Blocks and replace the Pbox with the Chiffre. Next round, encrypt the result
      for($i=0;$i<count($pbox);$i+=2) {
         $v = $this->block_encrypt($v[0],$v[1]);
         $this->pbox[$i] = $v[0];
         $this->pbox[$i+1] = $v[1];
      }




      //S-Box [0 bis 3] durch verschloesselte Bloecke ersetzen
      //Replace S-Box [0 to 3] entries with encrypted blocks
      for($i=0;$i<count($sbox0);$i+=2) {
         $v = $this->block_encrypt($v[0],$v[1]);
         $this->sbox0[$i] = $v[0];
         $this->sbox0[$i+1] = $v[1];
      }

      //S-Box1
      for($i=0;$i<count($sbox1);$i+=2) {
         $v = $this->block_encrypt($v[0],$v[1]);
         $this->sbox1[$i] = $v[0];
         $this->sbox1[$i+1] = $v[1];
      }

      //S-Box2
      for($i=0;$i<count($sbox2);$i+=2) {
         $v = $this->block_encrypt($v[0],$v[1]);
         $this->sbox2[$i] = $v[0];
         $this->sbox2[$i+1] = $v[1];
      }

      //S-Box3
      for($i=0;$i<count($sbox3);$i+=2) {
         $v = $this->block_encrypt($v[0],$v[1]);
         $this->sbox3[$i] = $v[0];
         $this->sbox3[$i+1] = $v[1];
      }
   }


   function block_encrypt($v0, $v1) {
      if ($v0 < 0)
         $v0 += 4294967296;

      if ($v1 < 0)
         $v1 += 4294967296;



      for ($i = 0; $i < 16; $i++) {
         $temp = $v0 ^ $this->pbox[$i];
         if ($temp < 0)
            $temp += 4294967296;

         $v0 = ((($this->sbox0[($temp >> 24) & 0xFF]
               + $this->sbox1[($temp >> 16) & 0xFF]
               ) ^ $this->sbox2[($temp >> 8) & 0xFF]
               ) + $this->sbox3[$temp & 0xFF]
               ) ^ $v1;

         $v1 = $temp;
      }

      $v1 = $this->_xor($v0, $this->pbox[16]);
      $v0 = $this->_xor($temp, $this->pbox[17]);


      return array($v0, $v1);
   }

   function block_decrypt($v0, $v1) {
        if ($v0 < 0)
            $v0 += 4294967296;

        if ($v1 < 0)
            $v1 += 4294967296;


        for ($i = 17; $i > 1; $i--) {
            $temp = $v0 ^ $this->pbox[$i];
            if ($temp < 0)
                $temp += 4294967296;


            $v0 = ((($this->sbox0[($temp >> 24) & 0xFF]
                     + $this->sbox1[($temp >> 16) & 0xFF]
                    ) ^ $this->sbox2[($temp >> 8) & 0xFF]
                   ) + $this->sbox3[$temp & 0xFF]
                  ) ^ $v1;
            $v1 = $temp;
        }
        $v1 = $this->_xor($v0, $this->pbox[1]);
        $v0 = $this->_xor($temp, $this->pbox[0]);

        return array($v0, $v1);
    }



    function _xor($l, $r)
    {
        $x = (($l < 0) ? (float)($l + 4294967296) : (float)$l)
             ^ (($r < 0) ? (float)($r + 4294967296) : (float)$r);

        return (float)(($x < 0) ? $x + 4294967296 : $x);
    }


   //Einen Text in Longzahlen umwandeln
   //Covert a string into longinteger
   function _str2long($data) {
       $n = strlen($data);
       $tmp = unpack('N*', $data);
       $data_long = array();
       $j = 0;

       foreach ($tmp as $value) $data_long[$j++] = $value;
       return $data_long;
   }

   //Longzahlen in Text umwandeln
   //Convert a longinteger into a string
   function _long2str($l){
       return pack('N', $l);
   }

}

?>