<?php
//  class.ValidationTools.php


  function isEmail($input) {
    //  Matches Email addresses.  (Found out how to break up a RedEx so that it can be nicly commited.)
    return (preg_match(
      "~(^                    ##  Match starts at the beginning of the string.
         (?>[[:alnum:]._-])+  ##  Matches any alpha numaric character plus the '.', '_', and '-'
                              ##    For the name part of an address. 
         (?>@)                ##  Matches the at sign.
         (?>[[:alnum:]])+     ##  Matches any alpha numaric character
                              ##    For the Place part of the address.
      
         (?>                                          ##  To match things like 'uk.com 'or just '.com'
          (?:\.[[:alpha:]]{2,3}\.[[:alpha:]]{2,3})  ##  Matches a '.' then 2-3 alphabet characters
                                                      ##    then another '.' and 2-3 alphabet characters.
          |                                           ##  Or Matches
          (?:\.[[:alpha:]]{2,3})                     ##  a single '.' then 2-3 alphabet characters.
         )
         $                                            ##  Match to the end of the string.
        )~x", $input));
  }

  function isLength($input, $max) {
    return (strlen($input) <= $max);
  }

  function isUserName($input) {
    return (preg_match("/(^(?:[[:alnum:]]+[_.-]?(?:[[:alnum:]]+[_.-]?)*)$)/", $input));
  }

  function isPassword($input) {
    return (preg_match("/(^[[:alnum:][:punct:] ]{8,24}$)/", $input));
  }

  function isUserPrivilege($input) {
    return (preg_match("~(A|U)~", $input));
  }

  function isDateTime($input) {
    return (preg_match("~(^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$)~", $input));
  }

  function isEmpty($input) {
    return (preg_match("/(^$)/", $input));
  }

  function isSafer($input) {
    return (preg_match("/[\\/\\\?<>]+/", $input));
  }

  function makeSafer($input) {
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input, ENT_NOQUOTES, 'UTF-8');
    $input = trim($input);
    return $input;
  }

  function isWholeNumber($input) {
    return (preg_match("/(^\\d+$)/", $input));
  }

  function isColorName($input) {
    //  Matches a "word", "word " or "word-" multiple times
    return (preg_match("/(^(?:(?:[a-zA-Z]+)(?:[ -]?))+$)/", $input));
  }

  function isPartID($input) {
    //  Matches a "numbers", "numbersLetters", "numbersLettersNumbersLettersNumbers"
    return (preg_match("/(^(?:[1-9][0-9]+?)(?:[a-z]*\\d*[a-z]*\\d*)?$)/", $input));
  }

  function isPartCategory($input) {
    //  Matches a "word" or "word " multiple times
    return (preg_match("~(^(?:(?:[a-zA-Z]+)(?:[ ]?))+$)~", $input));
  }

  function isPartSize($input) {
    return (preg_match("~(^[[:alnum:] .\/-]+$)~", $input));
  }

  function isPartDescription($input) {
    return (preg_match("~(^[[:alnum:] .,\x{00B0}\x{00BA}\[\]\(\)\/-]+$)~", $input));
  }

  function isSetID($input) {
    return (preg_match("~(^[1-9][0-9-]+$)~", $input));
  }

  function isSetName($input) {
    //  Matches a "word" or "word " multiple times
    return (preg_match("~(^[[:alnum:] -]+$)~", $input));
  }

?>