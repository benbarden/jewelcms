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

  $blnSubmitForm = false;
  $strMissingEmail = "";
  $strMissingPassword = "";
  $strEmail = "";
  $strPassword = "";
  $formReferrer = "";

$errorMsg = "";
  
  if ($_POST) {
    $blnSubmitForm = true;
    if (!empty($_POST['login-email'])) {
      $strEmail = $CMS->AddSlashesIFW($CMS->FilterAlphanumeric($_POST['login-email'], C_CHARS_DISPLAY_NAME));
      $strEmail = strip_tags($strEmail);
    }
    if (!empty($_POST['login-pass'])) {
      $strPassword = $CMS->AddSlashesIFW($CMS->FilterAlphanumeric($_POST['login-pass'], C_CHARS_DISPLAY_NAME));
      $strPassword = strip_tags($strPassword);
    }
    if (!$strEmail) {
      $blnSubmitForm = false;
        $errorMsg .= $CMS->AC->InvalidFormData("");
    }
    if (!$strPassword) {
      $blnSubmitForm = false;
        $errorMsg .= $CMS->AC->InvalidFormData("");
    }
    $formReferrer = empty($_POST['form-referrer']) ? "" : $CMS->FilterAlphanumeric($_POST['form-referrer'], "\:\/\.");
  } else {
    $formReferrer = empty($_SERVER['HTTP_REFERER']) ? "" : $CMS->FilterAlphanumeric($_SERVER['HTTP_REFERER'], "\:\/\.");
    if (strpos($formReferrer, 'install.php') !== false) {
      $formReferrer = "";
    }
  }

  $blnAlreadyLoggedIn = false;
  $CMS->RES->ValidateLoggedIn();
  if (!$CMS->RES->IsError()) {
    if (!empty($_GET['redir'])) {
      $blnAlreadyLoggedIn = true;
      $intCurrentUserID = $CMS->RES->GetCurrentUserID();
    } else {
        $errorMsg .= M_ERR_ALREADY_LOGGED_IN;
      //$CMS->Err_MFail(M_ERR_ALREADY_LOGGED_IN, "");
    }
  }

  if ($blnSubmitForm) {
    $intUserID = $CMS->US->ValidateLogin($strEmail, $strPassword);
    if ($intUserID) {
      if ($CMS->US->IsSuspended($intUserID)) {
        //$CMS->Err_MFail(M_ERR_USER_SUSPENDED, "");
          $errorMsg .= M_ERR_USER_SUSPENDED;
      }
      if (empty($_GET['redir'])) {
        $strRedirectURL = "";
      } else {
        $strRedirectURL = "http://".SVR_HOST.URL_ROOT.$_GET['redir'];
      }
      if ($blnAlreadyLoggedIn) {
        if ($intCurrentUserID != $intUserID) {
          $CMS->US->Login($intUserID);
        }
      } else {
        $CMS->US->Login($intUserID);
      }
      if ($strRedirectURL) {
        httpRedirect($strRedirectURL);
        exit;
      }
      // ** Go back link ** //
      $formReferrer = str_replace('http://'.SVR_HOST.URL_ROOT, '', $formReferrer);
      if ($formReferrer) {
        $strGoBack = "<li><a href=\"$formReferrer\">Go back to the page you were just viewing</a></li>";
      } else {
        $strGoBack = "";
      }
      // ** Display results ** //
      $strHTML = <<<END
<h1>Login Results</h1>
<p>You have successfully logged in.</p>
<ul>
$strGoBack
<li><a href="/">Go to the home page</a></li>
<li><a href="{FN_ADM_INDEX}">View or modify your account settings</a></li>
</ul>

END;
      $CMS->LP->SetTitle("Login Results");
      $CMS->LP->Display($strHTML);
    } else {
      $CMS->SYS->CreateAccessLog("Email: $strEmail", AL_TAG_USER_LOGIN_FAIL, 0, "");
      if ($CMS->RES->IsError()) {
        //$CMS->Err_MFail(M_ERR_LOGIN_FAILED, "");
          $errorMsg .= M_ERR_LOGIN_FAILED;
      }
    }
  }

  $strPageTitle = "Please enter your login details";
  
  $strLoginButton = $CMS->AC->LoginButton();
  $strCancelButton = $CMS->AC->CancelButton();

if ($errorMsg) {
    $errorHtml = '<div class="alert alert-danger" role="alert">'.$errorMsg.'</div>';
} else {
    $errorHtml = '';
}

$loginPageHtml = <<<loginPage

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Login</title>

    <!-- Bootstrap core CSS -->
    <link href="/assets/css/bootstrap/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="/assets/css/bootstrap/signin.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <div class="container">

      <form class="form-signin" action="/login.php" method="post">
        <input type="hidden" id="form-referrer" name="form-referrer" value="$formReferrer" />
        <h2 class="form-signin-heading">Login</h2>
        $errorHtml
        <label for="login-email" class="sr-only">Email</label>
        <input type="text" id="login-email" name="login-email" class="form-control" placeholder="Email" required autofocus>
        <label for="login-pass" class="sr-only">Password</label>
        <input type="password" id="login-pass" name="login-pass" class="form-control" placeholder="Password" required>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
      </form>

      <p style="text-align: center;"><em>Powered by Jewel CMS</em></p>

    </div> <!-- /container -->

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="/assets/js/bootstrap/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>

loginPage;
print($loginPageHtml);
exit;
