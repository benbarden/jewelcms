<?php
/*
  Jewel CMS
  Copyright (c) 2015 Ben Barden

  This program is free software: you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation, either version 3 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

  class Autocode extends Helper {
    // ** For missing fields, invalid data etc ** //
    function InvalidFormData($strDescription) {
      if (!$strDescription) {
        $strDescription = M_ERR_FIELD_REQUIRED;
      }
      return "<div class=\"errortext\">$strDescription</div>";
    }
    // ** Standard Buttons ** //
    function Submit($strValue) {
      $strClass = str_replace(" ", "-", $strValue);
      $strClass = strtolower($strClass);
      $strClass = "submit-".$strClass;
      return "<input class=\"button $strClass\" type=\"submit\" value=\"$strValue\" />";
    }
    function SubmitButton() {
      return $this->Submit(M_BTN_SAVE_CHANGES);
    }
    function ProceedButton() {
      return $this->Submit(M_BTN_PROCEED);
    }
    function LoginButton() {
      return $this->Submit(M_BTN_LOGIN);
    }
    function RegisterButton() {
      return $this->Submit(M_BTN_REGISTER);
    }
    function SearchButton() {
      return $this->Submit(M_BTN_SEARCH);
    }
    function SQLButton() {
      return $this->Submit(M_BTN_SUBMIT_QUERY);
    }
    function CancelButton() {
      $strHTML = "<input class=\"button\" type=\"button\" value=\"".M_BTN_CANCEL."\" onclick=\"javascript:history.go(-1);\" />";
      return $strHTML;
    }
    function LocationButton($strText, $strURL) {
      return "<input class=\"button\" type=\"button\" value=\"$strText\" onclick=\"top.location.href = '$strURL';\" />";
    }
    // ** Post Buttons ** //
    function PostButtons($strContentField) {
      $strHTML = <<<PostButtons
      <input type="button" value="B" onclick="doSimpleCMSCode('$strContentField', 'bold', 'b');" style="font-weight: bold;" />
      <input type="button" value="i" onclick="doSimpleCMSCode('$strContentField', 'italic', 'i');" style="font-style: italic;" />
      <input type="button" value="link" onclick="doLinkCMSCode('$strContentField');" />
      <input type="button" value="image" onclick="doImageCMSCode('$strContentField');" />
      <input type="button" value="quote" onclick="doSimpleCMSCode('$strContentField', 'quote', 'quote');" />
      <input type="button" value="code" onclick="doSimpleCMSCode('$strContentField', 'code', 'code');" />
PostButtons;
      return $strHTML;
    }
    // ** Permission Checkboxes ** //
    function DoCheckboxes($arrSelectedGroups, $strName) {
      global $CMS;
      $dteStartTime = $this->MicrotimeFloat();
      $arrGroups = $CMS->UG->GetAll();
      $blnGuestsAllowed = false;
      if ($arrSelectedGroups) {
        if (($strName == "ViewArea") ||
            ($strName == "AddComment") ||
            ($strName == "CustomLinkGroups")) {
          for ($i=0; $i<count($arrSelectedGroups); $i++) {
            if ($arrSelectedGroups[$i] == "0") {
              $blnGuestsAllowed = true;
            }
          }
        }
      }
      $strHTML = "";
      for ($i=0; $i<count($arrGroups); $i++) {
        $intGroupID   = $arrGroups[$i]['id'];
        $strGroupName = $arrGroups[$i]['name'];
        $strIsAdmin   = $arrGroups[$i]['is_admin'];
        $blnFlag = false;
        if (!empty($arrSelectedGroups)) {
          for ($j=0; $j<count($arrSelectedGroups); $j++) {
            $intSelectedGroupID = $arrSelectedGroups[$j];
            if ($intGroupID == $intSelectedGroupID) {
              $strHTML .= "<span style=\"white-space: nowrap;\"><input type=\"checkbox\" name=\"chk".$strName."[]\" id=\"chk$strName$intGroupID\" value=\"chk$strName$intGroupID\" checked=\"checked\" /> <label id=\"lblchk$strName$intGroupID\" for=\"chk$strName$intGroupID\">$strGroupName</label></span> \n";
              $blnFlag = true;
            }
          }
        }
        if (($strName == "PerArticlePermissions") && ($strIsAdmin == "Y")) {
          $blnFlag = true; // Don't do admin checkbox for per-file permissions
        }
        if (!$blnFlag) {
          $strHTML .= "<span style=\"white-space: nowrap;\"><input type=\"checkbox\" name=\"chk".$strName."[]\" id=\"chk$strName$intGroupID\" value=\"chk$strName$intGroupID\" /> <label id=\"lblchk$strName$intGroupID\" for=\"chk$strName$intGroupID\">$strGroupName</label></span> \n";
        }
      }
      if (($strName == "ViewArea") ||
          ($strName == "AddComment") ||
          ($strName == "CustomLinkGroups")) {
        $strFieldName = "chk$strName"."0";
        if ($blnGuestsAllowed) {
          $strGuestChecked = "checked=\"checked\"";
        } else {
          $strGuestChecked = "";
        }
        $strGuestHTML = "<span style=\"white-space: nowrap;\"><input type=\"checkbox\" name=\"chk".$strName."[]\" id=\"$strFieldName\" value=\"$strFieldName\" $strGuestChecked /> <label id=\"lbl$strFieldName\" for=\"$strFieldName\">Guest</label></span> \n";
      } else {
        $strGuestHTML = "";
      }
      $dteEndTime = $this->MicrotimeFloat();
      $this->SetExecutionTime($dteStartTime, $dteEndTime, __CLASS__ . "::" . __FUNCTION__, __LINE__);
      return $strGuestHTML.$strHTML;
    }
    // ** RSS Builder ** //
    function RSSHeader($strSiteTitle, $strRSSLinkAbout, $strSiteDesc, $dteToday) {
      global $CMS;
      $dteToday = date('r', strtotime($dteToday));
      $strVersion   = $CMS->SYS->GetSysPref(C_PREF_CMS_VERSION);
      $strHTML  = '<?xml version="1.0" encoding="utf-8"?>'."\n";
      $strHTML .= <<<RSSHeader
<rss version="2.0" xmlns:wfw="http://wellformedweb.org/CommentAPI/" xmlns:dc="http://purl.org/dc/elements/1.1/">
  <channel>
    <title>$strSiteTitle</title>
    <link>$strRSSLinkAbout</link>
    <description>$strSiteDesc</description>
    <language>en-us</language>
    <pubDate>$dteToday</pubDate>
    <generator>{PRD_PRODUCT_NAME} $strVersion</generator>

RSSHeader;
      return $strHTML;
    }
    // ** Date ** //
    function DateListsShort($dteFullDate) {
      $dteStartTime = $this->MicrotimeFloat();
      // Format: 2007-07-28 15:15:42
      $arrDateTime = explode(" ", $dteFullDate);
      $arrDate   = explode("-", $arrDateTime[0]);
      $dteYear   = $arrDate[0];
      $dteMonth  = $arrDate[1];
      $dteDay    = $arrDate[2];
      $arrTime   = explode(":", $arrDateTime[1]);
      $strHour   = $arrTime[0];
      $strMinute = $arrTime[1];
      $strSecond = $arrTime[2];
      // Reset
      $strHTML = "";
      // Month
      $arrMonths = array(1=>"January", 2=>"February", 3=>"March", 4=>"April", 5=>"May", 6=>"June", 7=>"July", 8=>"August", 9=>"September", 10=>"October", 11=>"November", 12=>"December");
      $strHTML .= "<select id=\"optMonth\" name=\"optMonth\">\n";
      for ($i=1; $i<13; $i++) {
        $strMonth = $arrMonths[$i];
        if ($i < 10) {
          $intValue = "0".$i;
        } else {
          $intValue = $i;
        }
        if ($intValue == $dteMonth) {
          $strHTML .= "<option value=\"$intValue\" selected=\"selected\">$strMonth</option>\n";
        } else {
          $strHTML .= "<option value=\"$intValue\">$strMonth</option>\n";
        }
      }
      $strHTML .= "</select>\n";
      // Day
      $strHTML .= <<<DayField
<input id="optDay" name="optDay" type="text" size="2" maxlength="2" value="$dteDay" />

DayField;
      // Year
      $strHTML .= <<<YearField
<input id="optYear" name="optYear" type="text" size="4" maxlength="4" value="$dteYear" />

YearField;
      // Hours
      $strHTML .= <<<HourField
<input id="optHour" name="optHour" type="text" size="2" maxlength="2" value="$strHour" />

HourField;
      // Minutes
      $strHTML .= <<<MinuteField
<input id="optMinute" name="optMinute" type="text" size="2" maxlength="2" value="$strMinute" />

MinuteField;
      // Seconds
      $strHTML .= <<<SecondField
<input id="optSecond" name="optSecond" type="text" size="2" maxlength="2" value="$strSecond" />

SecondField;
      return $strHTML;
    }
    function DateLists($dteFullDate) {
      $dteStartTime = $this->MicrotimeFloat();
      // Format: 2007-07-28 15:15:42
      $arrDateTime = explode(" ", $dteFullDate);
      $arrDate   = explode("-", $arrDateTime[0]);
      $dteYear   = $arrDate[0];
      $dteMonth  = $arrDate[1];
      $dteDay    = $arrDate[2];
      $arrTime   = explode(":", $arrDateTime[1]);
      $strHour   = $arrTime[0];
      $strMinute = $arrTime[1];
      $strSecond = $arrTime[2];
      // Reset
      $strHTML = "";
      // Month
      $arrMonths = array(1=>"January", 2=>"February", 3=>"March", 4=>"April", 5=>"May", 6=>"June", 7=>"July", 8=>"August", 9=>"September", 10=>"October", 11=>"November", 12=>"December");
      $strHTML .= "<select id=\"optMonth\" name=\"optMonth\">\n";
      for ($i=1; $i<13; $i++) {
        $strMonth = $arrMonths[$i];
        if ($i < 10) {
          $intValue = "0".$i;
        } else {
          $intValue = $i;
        }
        if ($intValue == $dteMonth) {
          $strHTML .= "<option value=\"$intValue\" selected=\"selected\">$strMonth</option>\n";
        } else {
          $strHTML .= "<option value=\"$intValue\">$strMonth</option>\n";
        }
      }
      $strHTML .= "</select>\n";
      // Day
      $strHTML .= "<select id=\"optDay\" name=\"optDay\">\n";
      for ($i=1; $i<32; $i++) {
        if ($i < 10) {
          $intValue = "0".$i;
        } else {
          $intValue = $i;
        }
        if ($intValue == $dteDay) {
          $strHTML .= "<option value=\"$intValue\" selected=\"selected\">$i</option>\n";
        } else {
          $strHTML .= "<option value=\"$intValue\">$i</option>\n";
        }
      }
      $strHTML .= "</select>\n";
      // Year
      $strHTML .= "<select id=\"optYear\" name=\"optYear\">\n";
      for ($i=1990; $i<2020; $i++) {
        if ($i == $dteYear) {
          $strHTML .= "<option value=\"$i\" selected=\"selected\">$i</option>\n";
        } else {
          $strHTML .= "<option value=\"$i\">$i</option>\n";
        }
      }
      $strHTML .= "</select>\n";
      // Hours
      $strHTML .= "<select id=\"optHour\" name=\"optHour\">\n";
      for ($i=0; $i<24; $i++) {
        if ($i < 10) {
          $intValue = "0".$i;
        } else {
          $intValue = $i;
        }
        if ($intValue == $strHour) {
          $strHTML .= "<option value=\"$intValue\" selected=\"selected\">$intValue</option>\n";
        } else {
          $strHTML .= "<option value=\"$intValue\">$intValue</option>\n";
        }
      }
      $strHTML .= "</select>\n";
      // Minutes
      $strHTML .= "<select id=\"optMinute\" name=\"optMinute\">\n";
      for ($i=0; $i<60; $i++) {
        if ($i < 10) {
          $intValue = "0".$i;
        } else {
          $intValue = $i;
        }
        if ($intValue == $strMinute) {
          $strHTML .= "<option value=\"$intValue\" selected=\"selected\">$intValue</option>\n";
        } else {
          $strHTML .= "<option value=\"$intValue\">$intValue</option>\n";
        }
      }
      $strHTML .= "</select>\n";
      // Seconds
      $strHTML .= "<select id=\"optSecond\" name=\"optSecond\">\n";
      for ($i=0; $i<60; $i++) {
        if ($i < 10) {
          $intValue = "0".$i;
        } else {
          $intValue = $i;
        }
        if ($intValue == $strSecond) {
          $strHTML .= "<option value=\"$intValue\" selected=\"selected\">$intValue</option>\n";
        } else {
          $strHTML .= "<option value=\"$intValue\">$intValue</option>\n";
        }
      }
      $strHTML .= "</select>\n";
      // Output HTML
      //$strHTML .= "<input type=\"hidden\" name=\"txtHHMMSS\" value=\"$dteTime\" />";
      $dteEndTime = $this->MicrotimeFloat();
      $this->SetExecutionTime($dteStartTime, $dteEndTime, __CLASS__ . "::" . __FUNCTION__, __LINE__);
      return $strHTML;
    }
    // ** Permission Quick Links ** //
    function PermissionQuickLinks() {
      global $CMS;
      $dteStartTime = $this->MicrotimeFloat();
      $strSelectGroups = "<h2>Quick Links</h2>\n";
      $arrGroups = $CMS->UG->GetAll();
      for ($i=0; $i<count($arrGroups); $i++) {
        $strName = $arrGroups[$i]['name'];
        if ($i == 0) {
          $strSelectGroups .= "<ul>\n";
        }
        $strSelectGroups .= "<li><a href=\"#\" onclick=\"TogglePermissionCheckboxes('$strName', true);\">$strName - select all</a></li>\n";
        $strSelectGroups .= "<li><a href=\"#\" onclick=\"TogglePermissionCheckboxes('$strName', false);\">$strName - deselect all</a></li>\n";
      }
      if ($strSelectGroups) {
        $strSelectGroups .= "</ul>\n";
      }
      $dteEndTime = $this->MicrotimeFloat();
      $this->SetExecutionTime($dteStartTime, $dteEndTime, __CLASS__ . "::" . __FUNCTION__, __LINE__);
      return $strSelectGroups;
    }
    // ** Search Form ** //
    function SearchForm() {
      $dteStartTime = $this->MicrotimeFloat();
      $strButton = $this->SearchButton();
      $strSearchURL = FN_SEARCH;
      $strForm = <<<SearchForm
<div class="SearchForm">
<form action="$strSearchURL" method="get">
<p>
<input type="hidden" name="go" value="yes" />
<input class="text-q" type="text" id="q" name="q" size="20" />
$strButton
</p>
</form>
</div>

SearchForm;
      $dteEndTime = $this->MicrotimeFloat();
      $this->SetExecutionTime($dteStartTime, $dteEndTime, __CLASS__ . "::" . __FUNCTION__, __LINE__);
      return $strForm;
    }
    
    /**
     * Read More - code to use in TinyMCE
     * @return string
     */
    function ReadMoreEditor() {
        
        $strHTML = 
            '<img src="'.URL_ROOT.'sys/images/icons/application_tile_vertical.png" alt="" />';
        
        return $strHTML;
        
    }
    
    /**
     * Read More - code to use on the site
     * @return string
     */
    function ReadMorePublic() {
        
        $strHTML = "<!-- Jewel CMS: Read More -->";
        
        return $strHTML;
        
    }
    
}
