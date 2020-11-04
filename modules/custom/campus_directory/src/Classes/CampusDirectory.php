<?php

namespace Drupal\campus_directory\Classes;

use Symfony\Component\HttpKernel\Log\Logger;

class CampusDirectory
{
  public static function getCampusDirData($strCruzids, $ldap_password)
  {
    $arrCruzids = explode(",", $strCruzids);
    $query = '(|';
    foreach ($arrCruzids as $cruzid) {
      $cruzid = trim($cruzid);
      $query .= "(uid={$cruzid})";
    }
    $query .= ")";
    $rli = ldap_connect("ldaps://ldap-blue.ucsc.edu/");
    if ($rli) {
      ldap_set_option($rli, LDAP_OPT_TIMELIMIT, 90);
      ldap_set_option($rli, LDAP_OPT_PROTOCOL_VERSION, 3);

      if (ldap_bind($rli, "cn=pantheon-webapps,ou=apps,dc=ucsc,dc=edu", $ldap_password)) {
        $sr = ldap_search($rli, "ou=people,dc=ucsc,dc=edu", "(|{$query})");
        $people = self::processSearchResults($rli, $sr);
        ldap_close($rli);
      }

      \Drupal::logger('campus_directory')->error("Hello?");
    }
    // foreach ($arrCruzids as $cruzid) {
    //   $directoryRet = json_decode(file_get_contents("https://campusdirectory.ucsc.edu/api/uid/" . trim($cruzid)));
    //   if ($directoryRet) {
    //     array_push($people, [
    //       "cn" => $directoryRet->cn[0],
    //       "title" => $directoryRet->title[0]
    //     ]);
    //   }
    // }

    return $people;
  }

  public static function processSearchResults($rli, $sr) {
    @ldap_sort($rli, $sr, "givenname");
    @ldap_sort($rli, $sr, "sn");

    $people = [];

    for ($entry = ldap_first_entry($rli, $sr); $entry != false; $entry = ldap_next_entry($rli, $entry)) {
      $attrs = ldap_get_attributes($rli, $entry);
      $person = array();
      if ((!empty($attrs["ucscPersonPubDisplay"]) && $attrs["ucscPersonPubDisplay"][0] === "TRUE") ||
        (!empty($attrs["ucscpersonpubdisplay"]) && $attrs["ucscpersonpubdisplay"][0] === "TRUE")) {
          for ($attr = ldap_first_attribute($rli, $entry); $attr != false; $attr = ldap_next_attribute($rli, $entry)) {
            $attr = strtolower($attr);
            $values = ldap_get_values($rli, $entry, $attr);
            $person[$attr] = $values;
          }
          // echo '<img src="data:image/jpeg;base64,' . base64_encode($person["jpegphoto"][0]) . '"/>';
          // echo "<pre>";
          // echo print_r($person, 1);
          // echo "<pre>";

          array_push($people, [
            "cn" => $person["cn"][0],
            "title" => $person["title"][0],
            "b64image" => base64_encode($person["jpegphoto"][0])
          ]);
      }
    }

    return $people;
  }
}
