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

  require '../../lib/Cms/Legacy/Header.php';
  $CMS->RES->Admin();
  if ($CMS->RES->IsError()) {
    $CMS->Err_MFail(M_ERR_UNAUTHORISED, "Admin");
  }

  $blnCreate = false;
  $blnEdit   = false;
  
  $strAction = $_GET['action'];
  if ($strAction == "create") {
    $strPageTitle = "Create User";
    $blnCreate = true;
  } elseif ($strAction == "edit") {
    $strPageTitle = "Edit User";
    $blnEdit = true;
  } else {
    $CMS->Err_MFail(M_ERR_MISSINGPARAMS_SYSTEM, "strAction");
  }

  if ($blnEdit) {
    $intUserID = $CMS->FilterNumeric($_GET['id']);
    if (!$intUserID) {
      $CMS->Err_MFail(M_ERR_MISSINGPARAMS_SYSTEM, "ID");
    }
  }
  
  $CMS->AP->SetTitle($strPageTitle);

$displayName          = "";
  $strPassword          = "";
  $strForename          = "";
  $strSurname           = "";
  $strEmail             = "";
  $strLocation          = "";
  $strOccupation        = "";
  $strInterests         = "";
  $strHomepageLink      = "";
  $strHomepageText      = "";
  $intAvatarID          = 0;
  $dteJoinDate          = "";
  $strUserIP            = "";
  $strMissingName   = "";
  $strMissingPassword   = "";
  $strMissingEmail      = "";
  $strInvalidEmail      = "";
  
  // ** BEGIN POST DATA ** //
  
  $blnSubmitForm = true;
  
  if ($_POST) {
    $arrPostData = $CMS->ArrayAddSlashes($_POST);
    if (!empty($arrPostData['txtDisplayName'])) {
        $displayName = $arrPostData['txtDisplayName'];
    } else {
      $blnSubmitForm = false;
      $strMissingName = $CMS->AC->InvalidFormData("");
    }
    if ($blnCreate) {
      if (!empty($arrPostData['txtPassword'])) {
        $strPassword = $arrPostData['txtPassword'];
      } else {
        $blnSubmitForm = false;
        $strMissingPassword = $CMS->AC->InvalidFormData("");
      }
    }
    $strForename = $arrPostData['txtForename'];
    $strSurname  = $arrPostData['txtSurname'];
    $strGroupList = !empty($_POST['chkUserGroups']) ? $CMS->UG->BuildGroupList($_POST['chkUserGroups'], "chkUserGroups") : "";
    if (!empty($arrPostData['txtEmail'])) {
      $strEmail = $arrPostData['txtEmail'];
      if (!$CMS->IsValidEmail($strEmail)) {
        $blnSubmitForm = false;
        $strInvalidEmail = $CMS->AC->InvalidFormData(M_ERR_INVALID_EMAIL);
      }
    } else {
      $blnSubmitForm = false;
      $strMissingEmail = $CMS->AC->InvalidFormData("");
    }
    // Other fields
    $strLocation     = $arrPostData['txtLocation'];
    $strOccupation   = $arrPostData['txtOccupation'];
    $strInterests    = $arrPostData['txtInterests'];
    $strHomepageLink = $arrPostData['txtHomepageLink'];
    $strHomepageText = $arrPostData['txtHomepageText'];
    $intAvatarID     = $arrPostData['txtAvatarID'];
    $dteJoinDate     = empty($arrPostData['txtJoinDate']) ? $CMS->SYS->GetCurrentDateAndTime() : $arrPostData['txtJoinDate'];
    $strUserIP       = $arrPostData['txtIPAddress'];
    // Check if OK to proceed
    if ($blnSubmitForm) {
      $intUserIP = "";
      if ($blnCreate) {
        $CMS->US->Create(FN_ADM_USER, $displayName, $strPassword, $strForename, $strSurname, $strEmail, $strLocation, $strOccupation, $strInterests, $strHomepageLink, $strHomepageText, $intAvatarID, $dteJoinDate, $strUserIP, $strGroupList);
        $strMsg = "created";
      } elseif ($blnEdit) {
        $CMS->US->Edit($intUserID, $displayName, $strForename, $strSurname, $strEmail, $strLocation, $strOccupation, $strInterests, $strHomepageLink, $strHomepageText, $intAvatarID, $dteJoinDate, $strUserIP, $strGroupList);
        $strMsg = "updated";
      }
      $strHTML = "<h1>$strPageTitle</h1>\n<p>User was $strMsg successfully. <a href=\"{FN_ADM_USERS}\">View Users</a></p>";
      $CMS->AP->Display($strHTML);
    }
  }
  
  // ** END POST DATA ** //
  
  if ($_POST) {
    $arrUser = $CMS->ArrayStripSlashes($arrPostData);
      $displayName     = $arrUser['display_name'];
    $strForename     = $arrUser['txtForename'];
    $strSurname      = $arrUser['txtSurname'];
    $arrUserGroups   = explode("|", $strGroupList);
    $strEmail        = $arrUser['txtEmail'];
    $strLocation     = $arrUser['txtLocation'];
    $strOccupation   = $arrUser['txtOccupation'];
    $strInterests    = $arrUser['txtInterests'];
    $strHomepageLink = $arrUser['txtHomepageLink'];
    $strHomepageText = $arrUser['txtHomepageText'];
    $intAvatarID     = $arrUser['txtAvatarID'];
    $dteJoinDate     = $arrUser['txtJoinDate'];
    $strUserIP       = $arrUser['txtIPAddress'];
  } else {
    if ($blnEdit) {
      $arrUser = $CMS->US->Get($intUserID);
      if (count($arrUser) == 0) {
        $CMS->Err_MFail(M_ERR_NO_ROWS_RETURNED, "User: $intUserID");
      } else {
        $arrUser = $CMS->ArrayStripSlashes($arrUser);
      }
        $displayName     = $arrUser['display_name'];
      $strForename     = $arrUser['forename'];
      $strSurname      = $arrUser['surname'];
      $strGroupList    = $arrUser['user_groups'];
      $arrUserGroups   = explode("|", $strGroupList);
      $strEmail        = $arrUser['email'];
      $strLocation     = $arrUser['location'];
      $strOccupation   = $arrUser['occupation'];
      $strInterests    = $arrUser['interests'];
      $strHomepageLink = $arrUser['homepage_link'];
      $strHomepageText = $arrUser['homepage_text'];
      $intAvatarID     = $arrUser['avatar_id'];
      $dteJoinDate     = $arrUser['join_date'];
      $strUserIP       = $arrUser['ip_address'];
    }
  }

  $strSubmitButton = $CMS->AC->SubmitButton();
  $strCancelButton = $CMS->AC->CancelButton();
  
  if (empty($strGroupList)) {
    $arrUserGroups = "";
  } else {
    $arrUserGroups = explode("|", $strGroupList);
  }
  $strUserGroupHTML = $CMS->AC->DoCheckboxes($arrUserGroups, "UserGroups");
  
  if ($blnCreate) {
    $strFormTag = "<form action=\"{FN_ADM_USER}?action=create\" method=\"post\">";
  } elseif ($blnEdit) {
    $strFormTag = "<form action=\"{FN_ADM_USER}?action=edit&amp;id=$intUserID\" method=\"post\">";
  }

  if ($blnCreate) {
    $strPasswordField = <<<PasswordField
    <td>* <label for="txtPassword">Password:</label></td>
    <td>
      $strMissingPassword
      <input type="password" id="txtPassword" name="txtPassword" maxlength="45" size="45" />
    </td>

PasswordField;
  } elseif ($blnEdit) {
    $strPasswordField = <<<PasswordField
    <td><strong>Password:</strong></td>
    <td>
      <input type="button" value="Reset password" onclick="top.location.href = '{FN_ADM_USER_EDIT_PASSWORD}?id=$intUserID';" />
    </td>

PasswordField;
  }

  $strHTML = <<<END
<h1 class="page-header">$strPageTitle</h1>
<p>* denotes a required field</p>
$strFormTag
<div class="table-responsive">
<table class="table table-striped">
  <tr>
    <td>* <label for="txtDisplayName">Display name</label>:</td>
    <td>
      $strMissingName
      <input type="text" id="txtDisplayName" name="txtDisplayName" maxlength="45" size="45" value="$displayName" />
    </td>
  </tr>
  <tr>
    $strPasswordField
  </tr>
  <tr>
    <td><label for="txtForename">Forename:</label></td>
    <td>
      <input type="text" id="txtForename" name="txtForename" maxlength="45" size="45" value="$strForename" />
    </td>
  </tr>
  <tr>
    <td><label for="txtSurname">Surname:</label></td>
    <td>
      <input type="text" id="txtSurname" name="txtSurname" maxlength="45" size="45" value="$strSurname" />
    </td>
  </tr>
  <tr>
    <td><strong>User Roles:</strong></td>
    <td>
      $strUserGroupHTML
    </td>
  </tr>
  <tr>
    <td>* <label for="txtEmail">Email:</label></td>
    <td>
      $strMissingEmail
      $strInvalidEmail
      <input type="text" id="txtEmail" name="txtEmail" maxlength="100" size="45" value="$strEmail" />
    </td>
  </tr>
  <tr>
    <td><label for="txtLocation">Location:</label></td>
    <td><input type="text" id="txtLocation" name="txtLocation" maxlength="100" size="45" value="$strLocation" /></td>
  </tr>
  <tr>
    <td><label for="txtOccupation">Occupation:</label></td>
    <td><input type="text" id="txtOccupation" name="txtOccupation" maxlength="100" size="45" value="$strOccupation" /></td>
  </tr>
  <tr>
    <td><label for="txtInterests">Interests:</label></td>
    <td><textarea id="txtInterests" name="txtInterests" cols="30" rows="5">$strInterests</textarea></td>
  </tr>
  <tr>
    <td><label for="txtHomepageLink">Homepage Link:</label></td>
    <td><input type="text" id="txtHomepageLink" name="txtHomepageLink" maxlength="150" size="45" value="$strHomepageLink" /></td>
  </tr>
  <tr>
    <td><label for="txtHomepageText">Homepage Text:</label></td>
    <td><input type="text" id="txtHomepageText" name="txtHomepageText" maxlength="100" size="45" value="$strHomepageText" /></td>
  </tr>
  <tr>
    <td><label for="txtAvatarID">Avatar ID:</label></td>
    <td><input type="text" id="txtAvatarID" name="txtAvatarID" size="5" value="$intAvatarID" /></td>
  </tr>
  <tr>
    <td><label for="txtJoinDate">Join Date:</label><br /><i>(YYYY-MM-DD HH:MM:SS)</i></td>
    <td>
      <input type="text" id="txtJoinDate" name="txtJoinDate" size="20" value="$dteJoinDate" />
    </td>
  </tr>
  <tr>
    <td><label for="txtIPAddress">IP Address:</label></td>
    <td><input type="text" id="txtIPAddress" name="txtIPAddress" size="20" value="$strUserIP" /></td>
  </tr>
  <tr>
    <td class="FootColour SpanCell Centre" colspan="2">
      $strSubmitButton $strCancelButton
    </td>
  </tr>
</table>
</form>

END;

  $CMS->AP->Display($strHTML);
