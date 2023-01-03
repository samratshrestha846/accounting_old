<?php

// namespace App\Helpers;

use Illuminate\Support\Facades\Request;

function selectedMenu($param1 = null,$param2 = null,$param3 = null,$param4=null,$param5=Null,$param6=Null){
    if(!empty(Request::segment(1))){
        if(Request::segment(1) == $param1 || Request::segment(1) == $param2 || Request::segment(1) == $param3 || Request::segment(1) == $param4 ||  Request::segment(1) == $param5||  Request::segment(1) == $param6){
            return 'menu-is-opening menu-open';
        }
     }else{
        return '';
    }


}

function selectMenuwithroute($routeparam1 = Null,$routeparam2 = Null,$routeparam3 = Null ,$routeparam4 = Null,$routeparam5 =Null,$routeparam6=Null){
    $current = url()->current();
    if(!empty(Request::segment(1))){
        if($current == $routeparam1 || $current == $routeparam2 || $current== $routeparam3 || $current == $routeparam4 || $current ==$routeparam5 || $current == $routeparam6 ){
            return 'menu-is-opening menu-open';
        }
    }else{
        return '';
    }
}


function convertNumberToWord($num = false)
{
    $num = str_replace(array(',', ' '), '' , trim($num));
    if(! $num) {
        return false;
    }
    $num = (int) $num;
    $words = array();
    $list1 = array('', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten', 'eleven',
        'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'
    );
    $list2 = array('', 'ten', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety', 'hundred');
    $list3 = array('', 'thousand', 'million', 'billion', 'trillion', 'quadrillion', 'quintillion', 'sextillion', 'septillion',
        'octillion', 'nonillion', 'decillion', 'undecillion', 'duodecillion', 'tredecillion', 'quattuordecillion',
        'quindecillion', 'sexdecillion', 'septendecillion', 'octodecillion', 'novemdecillion', 'vigintillion'
    );
    $num_length = strlen($num);
    $levels = (int) (($num_length + 2) / 3);
    $max_length = $levels * 3;
    $num = substr('00' . $num, -$max_length);
    $num_levels = str_split($num, 3);
    for ($i = 0; $i < count($num_levels); $i++) {
        $levels--;
        $hundreds = (int) ($num_levels[$i] / 100);
        $hundreds = ($hundreds ? ' ' . $list1[$hundreds] . ' hundred' . ' ' : '');
        $tens = (int) ($num_levels[$i] % 100);
        $singles = '';
        if ( $tens < 20 ) {
            $tens = ($tens ? ' ' . $list1[$tens] . ' ' : '' );
        } else {
            $tens = (int)($tens / 10);
            $tens = ' ' . $list2[$tens] . ' ';
            $singles = (int) ($num_levels[$i] % 10);
            $singles = ' ' . $list1[$singles] . ' ';
        }
        $words[] = $hundreds . $tens . $singles . ( ( $levels && ( int ) ( $num_levels[$i] ) ) ? ' ' . $list3[$levels] . ' ' : '' );
    } //end for loop
    $commas = count($words);
    if ($commas > 1) {
        $commas = $commas - 1;
    }
    return implode(' ', $words);
}
