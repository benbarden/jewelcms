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
  $CMS->RES->ValidateLoggedIn();
  if (!$CMS->RES->IsError()) {
    $CMS->Err_MFail(M_ERR_ALREADY_LOGGED_IN, "");
  }

  $blnGet = false;
  $blnSubmitForm = false;
  $strEmail = "";
  $strMissingEmail = "";
  
  if ($_POST) {
    // Check if OK to send activation key
    $blnSubmitForm = true;
    $strEmail    = $CMS->AddSlashesIFW($_POST['txtEmail']);
    if (!$strEmail) {
      $blnSubmitForm = false;
      $strMissingEmail = $CMS->AC->InvalidFormData("");
    }
    if ($blnSubmitForm) {
        // Check if user exists
        $repoUser = $cmsContainer->getServiceLocator()->getCmsEntityManager()->getRepository('Cms\Entity\User');
        $validUserEntity = $repoUser->getByEmail($strEmail);
        if ($validUserEntity) {
            $CMS->LP->SetTitle("Forgot Password - Results");
            // Make activation key
            $validUserId = $validUserEntity->getId();
            $displayName = $validUserEntity->getDisplayName();
            $activationKey = $CMS->US->MakeActivationKey($validUserId);
            // Send message
            $strEmailDomain = str_replace("www.", "", SVR_HOST);
            $strEmailFrom = "donotreply@".$strEmailDomain;
            $strEmailBody = "Hi $displayName,\r\n\r\n".
                "We have received a request to reset your password at $strEmailDomain.\r\n\r\n".
                "If you requested this, please click the following link:\r\n\r\n".
                "http://".SVR_HOST.FN_RESET_PW."?uid=$validUserId&key=$activationKey\r\n\r\n".
                "If you did not request this, please delete this email and do not click on the link.";
            $intSentEmail = $CMS->SendEmail($strEmail, "Password reset - $strEmailDomain", $strEmailBody, $strEmailFrom);
            // Confirmation page
            if ($intSentEmail == 1) {
              $strHTML = "<div id=\"pagecontent\">\n<h1>Forgot Password - Results</h1>\n<p>Thank you. A message has been sent to your email account with details of how to reset your password.</p>\n</div>\n";
            } else {
              $strHTML = "<div id=\"pagecontent\">\n<h1>Error</h1>\n<p>The message could not be delivered.</p>\n</div>\n";
            }
            $CMS->LP->Display($strHTML);
      } else {
        $CMS->Err_MFail('Email not found', "");
      }
    }
  }

  $strPageTitle = "Forgot Password";
  
  $strSubmitButton = $CMS->AC->ProceedButton();
  $strCancelButton = $CMS->AC->CancelButton();
  
  $strHTML = <<<END
<h1>$strPageTitle</h1>
<p>To reset your password, please enter your email address.</p>
<form action="{FN_FORGOT_PW}" method="post">
<table class="OptionTable NarrowTable" cellspacing="1">
  <colgroup>
    <col class="InfoColour NarrowCell" />
    <col class="BaseColour" />
  </colgroup>
  <tr>
    <td class="InfoColour"><label for="txtEmail">Email:</label></td>
    <td>
      $strMissingEmail
      <input type="text" id="txtEmail" name="txtEmail" maxlength="100" size="40" value="$strEmail" />
    </td>
  </tr>
  <tr>
    <td class="FootColour" colspan="2">$strSubmitButton $strCancelButton</td>
  </tr>
</table>
</form>

END;
  $CMS->LP->SetTitle("Forgot Password");
  $CMS->LP->Display($strHTML);
