<?php
include( dirname ( __FILE__ ) . DIRECTORY_SEPARATOR . 'markdown' . DIRECTORY_SEPARATOR . 'markdown.php' );

class Parser{

  CONST lineBreak = '----';
  CONST keySeperator = '::';

  var $rawContent = '';
  var $vals = Array();
  var $stripHtml = TRUE;
  var $useMarkdown = TRUE;

  function __construct($content, $useMarkdown=TRUE){
    $this->userMarkdown = $useMarkdown;

    if( $this->stripHtml ){
      $this->rawContent = strip_tags($content);
    }else{
      $this->rawContent = $content;
    }

    $this->parseRootElements();
    $this->parseArrayItems();
  }

  function parseRootElements(){
    $rootContent = preg_replace( "/\[\[[^\)]+?\/]\]/", "", $this->rawContent );
    $this->vals = $this->parseBlock( $rootContent );
  }

  function parseArrayItems(){
    $arrayItems = explode("[[", $this->rawContent);

    foreach ($arrayItems as $arrayItem) {
      $item = explode("]]",$arrayItem);

      if( strpos($arrayItem, ']]') !== FALSE and strpos($arrayItem, '/]]') === FALSE ){
        $key = $item[0];
        $block = $this->parseBlock($item[1]);

        if( !isset( $this->vals[$key] )){
          $this->vals[$key] = Array();
        }
        array_push( $this->vals[$key], $block);
      }
    }
  }

  function parseBlock($block){
    $arr = explode( self::lineBreak, $block);
    $vals = Array();

    foreach( $arr as $keyVal ){
      $exp = explode( self::keySeperator, $keyVal );
      if( !empty($exp[1]) ){
        $vals[trim($exp[0])] = $this->format($exp[1]);
      }
    }
    return $vals;
  }

  function format($string){

    $raw = false;
    $str = $string;

    if( strpos($str,'!') === 0 ){
      $raw = TRUE;
      $str = ltrim($str, '!');
    }

    if( $this->useMarkdown and !$raw){
      $str = Markdown($str);
      $str = trim($str);

      if(substr_count($str,'<p>') == 1 ){
        $str = ltrim($str,'<p>');
        $str = rtrim($str,'</p>');
      }
      return $str;
    }else{
      $str = trim($str);
      return $str;
    }

  }
  // wrote two accessor functions so that you never end up running on null...
  function val($key, $context=null){
    if( !$context ){
      $context = $this->vals;
    }
    if( $context[$key] ){
      return $context[$key];
    }else{
      return '';
    }
  }

  function arr($key){
    if( $this->vals[$key] and is_array($this->vals[$key]) ){
      return $this->vals[$key];
    }else{
      return Array();
    }
  }

}

?>