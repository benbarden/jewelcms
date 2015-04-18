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

  require '../lib/Cms/Legacy/Header.php';
  if ($CMS->SYS->GetSysPref(C_PREF_USER_REGISTRATION) != "1") {
    $CMS->Err_MFail(M_ERR_REGISTRATION_DISABLED, "");
  }
  $CMS->RES->ValidateLoggedIn();
  if (!$CMS->RES->IsError()) {
    $CMS->Err_MFail(M_ERR_REGISTER_WHILE_LOGGED_IN, "");
  }
  $strPageTitle = "Register";

  $displayName = "";
  $email = "";
  $password = "";

$errorList = array();

  if ($_POST) {
    if (!empty($_POST['register-name'])) {
        $displayName = $CMS->AddSlashesIFW($CMS->FilterAlphanumeric($_POST['register-name'], C_CHARS_DISPLAY_NAME));
    }
    if (!empty($_POST['register-pass'])) {
        $password = $CMS->AddSlashesIFW($_POST['register-pass']);
    }
    if (!empty($_POST['register-email'])) {
        $email = $CMS->AddSlashesIFW($_POST['register-email']);
        $email = strip_tags($email);
    }
    $blnSubmitForm   = true;
      // Check for missing email
      if (!$email) {
          $errorList[] = 'Missing email';
          $blnSubmitForm = false;
      } else {
          // Check e-mail address isn't already in use
          if ($email) {
              if (!$CMS->US->IsUniqueEmail($email)) {
                  $errorList[] = 'Email already registered. Do you already have an account?';
                  $blnSubmitForm = false;
              }
          }
      }
    // Check for missing display name
    if (!$displayName) {
        $errorList[] = 'Missing display name';
      $blnSubmitForm = false;
    } else {
      // Check for invalid display name
      if ((strlen($displayName) >= 3) && (strlen($displayName) <= 100)) {
        $filteredName = $CMS->FilterAlphanumeric($displayName, C_CHARS_DISPLAY_NAME);
        if ($filteredName != $displayName) {
            $errorList[] = M_ERR_INVALID_CHARS;
          $blnSubmitForm = false;
            $displayName = $filteredName;
        }
      } else {
          $errorList[] = 'Display name must be at least 3 characters long';
          $blnSubmitForm = false;
      }
    }
    // Check for missing password
    if (!$password) {
        $errorList[] = 'Missing password';
      $blnSubmitForm = false;
    } else {
      // Check for invalid password characters
      $filteredPW = $CMS->FilterAlphanumeric($password, C_CHARS_DISPLAY_NAME);
      if ($filteredPW != $password) {
          $errorList[] = M_ERR_INVALID_CHARS;
        $blnSubmitForm = false;
          $password = "";
      }
    }
    // Proceed if there were no errors
    if ($blnSubmitForm) {
      $strPageTitle .= " - Results";
      $CMS->LP->SetTitle($strPageTitle);
      $dteJoinDate = $CMS->SYS->GetCurrentDateAndTime();
        if (isset($_SERVER['REMOTE_ADDR'])) {
            $userIP = $_SERVER['REMOTE_ADDR'];
        } else {
            $userIP = '';
        }
      $CMS->US->Create(FN_REGISTER, $CMS->AddSlashesIFW($displayName), $password, '', '', $email, "", "", "", "", "", 0, $dteJoinDate, $userIP, "");
      $strHTML = "<h1>$strPageTitle</h1>\n<p>Registration was successful.</p>\n<ul>\n<li><a href=\"{FN_LOGIN}\">Login</a></li>\n<li><a href=\"{FN_INDEX}\">Back to the home page</a></li>\n</ul>\n";
      $CMS->LP->Display($strHTML);
    }
  }

$bindings['ErrorList'] = $errorList;
$bindings['DisplayName'] = $displayName;
$bindings['Email'] = $email;

$engine = $cmsContainer->getService('Theme.Engine');
$outputHtml = $engine->render('auth/register.twig', $bindings);
print($outputHtml);
exit;
