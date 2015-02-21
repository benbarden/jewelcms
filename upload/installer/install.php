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

  require 'InstallPage.php';
  $IJP = new InstallPage;
  $intStep = isset($_GET['step']) ? $_GET['step'] : null;
  if (!$intStep) {
    $intStep = 1;
  }
  $strPageTitle = "Jewel CMS Installer - Step $intStep";
  // Initially, some external files will be missing
  switch ($intStep) {
    case 1:
    case 2:
    case 3:
      break;
    case 4:
      require '../sys/header.php';
      break;
  }
  // Instantiate
switch ($intStep) {
    case 2:
        $installDbHost   = "";
        $installDbSchema = "";
        $installDbUser   = "";
        $installDbPass   = "";
        $installCmsUser  = "";
        $installCmsPass  = "";
        break;
}

  // POST stuff
  if ($_POST) {
    switch ($intStep) {
      case 1:
      case 2:
        $installDbHost    = $_POST['install-db-host'];
        $installDbSchema  = $_POST['install-db-name'];
        $installDbUser    = $_POST['install-db-user'];
        $installDbPass    = $_POST['install-db-pass'];
        $installCmsUser   = $_POST['install-cms-user'];
        $installCmsPass   = $_POST['install-cms-pass'];
        $blnSubmitForm    = true;
        $blnMissingHost   = false;
        $blnMissingSchema = false;
        $blnMissingUser   = false;
        $blnMissingPass   = false;
        $blnMissingMajesticUser = false;
        $blnMissingCmsPass = false;
        if (!$installDbHost) {
          $blnMissingHost = true;
          $blnSubmitForm = false;
        }
        if (!$installDbSchema) {
          $blnMissingSchema = true;
          $blnSubmitForm = false;
        }
        if (!$installDbUser) {
          $blnMissingUser = true;
          $blnSubmitForm = false;
        }
        if (!$installCmsUser) {
          $blnMissingCmsUser = true;
          $blnSubmitForm = false;
        }
        if (!$installCmsPass) {
          $blnMissingCmsPass = true;
          $blnSubmitForm = false;
        }
        if ($blnSubmitForm) {
          $intStep = 3; // OK to proceed
        } else {
          $intStep = 2;
        }
        break;
      case 3:
      case 4:
        break;
    }
  }
  
    // Do the dirty work!
    switch ($intStep) {
        
        case 1:
            
            // Create .htaccess
            @ $blnCreatedFile = touch("../.htaccess");
            if (!$blnCreatedFile) {
                $IJP->Display("<p>Cannot write to file: .htaccess</p>
                <p>Please check the permissions on the Jewel CMS installation directory.</p>
                <p><em>Source: &lt;install.php, step $intStep&gt;</em></p>
                ", "Installation Error");
            }
            
            // Default .htaccess data
            $strHtaccessDefault = <<<htaccess
# BEGIN Jewel CMS
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^(.*)$ index.php/$1 [QSA,L]
</IfModule>

Options -Indexes

# END Jewel CMS

htaccess;
            
            // Build htaccess file
            @ $strHtaccessData = file_get_contents("../.htaccess");
            if (empty($strHtaccessData)) {
                $strOutput = $strHtaccessDefault;
                $strPathInfo = "Path 1";
            } else {
                if ((strpos($strHtaccessData, "# BEGIN Jewel CMS") !== false) &&
                    (strpos($strHtaccessData, "# END Jewel CMS")   !== false)) {
                    $arrStart = explode("# BEGIN Jewel CMS", $strHtaccessData);
                    $arrEnd   = explode("# END Jewel CMS",   $strHtaccessData);
                    $strOutput = $arrStart[0].$strHtaccessDefault.$arrEnd[1];
                    $strPathInfo = "Path 2";
                } else {
                    $strOutput = $strHtaccessDefault.$strHtaccessData;
                    $strPathInfo = "Path 3";
                }
            }
            @ $objFile = fopen("../.htaccess", "w");
            fwrite($objFile, $strOutput);
            @ fclose($objFile);
            
            // Get current directory
            $arrInstallRelURL = explode("installer", $_SERVER['PHP_SELF']);
            $strInstallRelURL = $arrInstallRelURL[0];
            // Only allow installation into a top-level domain
            if ($strInstallRelURL != "/") {
                $IJP->Display("<p>Jewel CMS does not support installation in a subfolder.</p>
                <p><em>Source: &lt;install.php, step $intStep&gt;</em></p>
                ", "Installation Error");
            }
            // Confirm folder is ok
            $IJP->Display('<p>Install location confirmed.</p>
            <p><a href="?step=2" class="btn btn-lg btn-success" role="button">Proceed to step 2</a></p>
            ', "Step 1");
            break;
            
    case 2:
      // PROCESSING FOR STEP 2:
      if (!$installDbHost) {
        $installDbHost = "localhost";
      }
      $strTDStyles = "background-color: #fff; color: #000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 100%; padding: 5px; width: 200px;";
      $strInputStyles = "font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 100%;";
      $strErrorStyles = "background-color: transparent; color: #f00; display: none; font-size: 100%; font-style: italic;";
      $strHTML = <<<STEP2
    <p>Enter your MySQL database credentials below. Then choose a username and password for your Jewel CMS site.</p>
    <form class="form-horizontal" name="frmInstall2" action="?step=2" method="post">
        <div class="form-group">
            <label for="install-db-host" class="col-sm-4 control-label">Database host</label>
            <div class="col-sm-6">
                <input type="text" class="form-control" id="install-db-host" name="install-db-host" placeholder="Database host" size="20" maxlength="50" value="$installDbHost">
            </div>
        </div>
        <div class="form-group">
            <label for="install-db-name" class="col-sm-4 control-label">Database name</label>
            <div class="col-sm-6">
                <input type="text" class="form-control" id="install-db-name" name="install-db-name" placeholder="Database name" size="20" maxlength="50" value="$installDbSchema">
            </div>
        </div>
        <div class="form-group">
            <label for="install-db-user" class="col-sm-4 control-label">Database user</label>
            <div class="col-sm-6">
                <input type="text" class="form-control" id="install-db-user" name="install-db-user" placeholder="Database user" size="20" maxlength="50" value="$installDbUser">
            </div>
        </div>
        <div class="form-group">
            <label for="install-db-pass" class="col-sm-4 control-label">Database pass</label>
            <div class="col-sm-6">
                <input type="text" class="form-control" id="install-db-pass" name="install-db-pass" placeholder="Database pass" size="20" maxlength="50" value="$installDbPass">
            </div>
        </div>
        <div class="form-group">
            <label for="install-cms-user" class="col-sm-4 control-label">Jewel CMS user</label>
            <div class="col-sm-6">
                <input type="text" class="form-control" id="install-cms-user" name="install-cms-user" placeholder="CMS user" size="20" maxlength="50" value="$installCmsUser">
            </div>
        </div>
        <div class="form-group">
            <label for="install-cms-pass" class="col-sm-4 control-label">Jewel CMS pass</label>
            <div class="col-sm-6">
                <input type="text" class="form-control" id="install-cms-pass" name="install-cms-pass" placeholder="CMS pass" size="20" maxlength="50" value="$installCmsPass">
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-success">Submit</button>
            </div>
        </div>
    </form>
STEP2;
      /*if ($_POST) {
        $strHTML .= "<script type=\"text/javascript\">\n";
        if ($blnMissingHost) {
          $strHTML .= "  document.getElementById('strMissingHost').style.display = 'block';\n";
        }
        if ($blnMissingSchema) {
          $strHTML .= "  document.getElementById('strMissingSchema').style.display = 'block';\n";
        }
        if ($blnMissingUser) {
          $strHTML .= "  document.getElementById('strMissingUser').style.display = 'block';\n";
        }
        if ($blnMissingPass) {
          $strHTML .= "  document.getElementById('strMissingPass').style.display = 'block';\n";
        }
        if ($blnMissingCmsUser) {
          $strHTML .= "  document.getElementById('strMissingMajesticUser').style.display = 'block';\n";
        }
        if ($blnMissingCmsPass) {
          $strHTML .= "  document.getElementById('strMissingMajesticPass').style.display = 'block';\n";
        }
        $strHTML .= "</script>\n";
      }*/
        $IJP->Display($strHTML, "Step 2");
      break;
    case 3:
      // PROCESSING FOR STEP 3:
      // Create file
      $strFileURL = '../data/secure/db_vars.php';
      touch($strFileURL);
      // Create file data
      //$installDbHost    = "'".$installDbHost."'";
      //$installDbSchema  = "'".$installDbSchema."'";
      //$installDbUser    = "'".$installDbUser."'";
      //$installDbPass    = "'".$installDbPass."'";
      $strFile = <<<DBVARS
<?php
  // Database variables
  \$strDBHost       = '$installDbHost';
  \$strDBSchema     = '$installDbSchema';
  \$strDBAdminUser  = '$installDbUser';
  \$strDBAdminPass  = '$installDbPass';
?>
DBVARS;
      // Write to file
      @ $cmsFile = fopen($strFileURL, 'w');
      if (!$cmsFile) {
          $IJP->Display("<p>db_vars.php cannot be written to.
            Please check the permissions on the /data/secure/ directory and try again.</p>
                <p><em>Source: &lt;install.php, step $intStep&gt;</em></p>
                ", "Installation Error");
      }
      fwrite($cmsFile, $strFile);
      fclose($cmsFile);
      // Also create config file
      $configIniUrl = '../data/secure/config.ini';
      touch($configIniUrl);
      $configIniData = <<<configIni
[Database]
DSN = 'mysql:host=$installDbHost;dbname=$installDbSchema'
User = $installDbUser
Pass = $installDbPass

[Theme]
Current = jewelcms
Cache = Off

[CP]
ItemsPerPage = 25
LogLimit = 3000

configIni;
      $configSaved = file_put_contents($configIniUrl, $configIniData);
        if (!$configSaved) {
            $IJP->Display("<p>config.ini cannot be written to.
            Please check the permissions on the /data/secure/ directory and try again.</p>
                <p><em>Source: &lt;install.php, step $intStep&gt;</em></p>
                ", "Installation Error");
        }
      // Test connection
      include $strFileURL;
      @ mysql_connect($installDbHost, $installDbUser, $installDbPass)
        or die($IJP->Display("<p>Access denied for user $installDbUser at host $installDbHost.</p>
                <p>Please <a href=\"javascript:history.go(-1);\">go back</a> and try again.</p>
                <p><em>Source: &lt;install.php, step $intStep&gt;</em></p>
                ", "Installation Error"));
      mysql_select_db($installDbSchema)
        or die($IJP->Display("<p>Cannot select database $installDbSchema.</p>
                <p>Please <a href=\"javascript:history.go(-1);\">go back</a> and try again.</p>
                <p><em>Source: &lt;install.php, step $intStep&gt;</em></p>
                ", "Installation Error"));
      // Store username and password for next step
      $newCmsUser = $_POST['install-cms-user'];
      $newCmsPass = $_POST['install-cms-pass'];
      // Success
      $IJP->Display("<p>The installer has successfully connected to the database.</p>".
        '<form name="frmInstall3" action="?step=4" method="post">'.
        '<input type="hidden" name="install-cms-user" value="'.$newCmsUser.'" />'.
        '<input type="hidden" name="install-cms-pass" value="'.$newCmsPass.'" />'.
        '<button type="submit" class="btn btn-success">Proceed to Step 4</button></form>', "Step 3");
      break;
    case 4:
      // PROCESSING FOR STEP 4:
      $strFile = "install_base.sql";
      @ $strInstallFile = file_get_contents($strFile);
      if (!$strInstallFile) {
          $IJP->Display("<p>Cannot open $strFile.</p>
                <p><em>Source: &lt;install.php, step $intStep&gt;</em></p>
                ", "Installation Error");
      }
      $blnSuccess = $CMS->MultiQuery($strInstallFile);
      if (!$blnSuccess) {
          $IJP->Display("<p>Base install failed.</p>
                <p><em>Source: &lt;install.php, step $intStep&gt;</em></p>
                ", "Installation Error");
      }
      // Store username and password
      $newCmsUser = addslashes($_POST['install-cms-user']);
      $pwHash = password_hash($_POST['install-cms-pass'], PASSWORD_BCRYPT);
      // Create user
      $intNewUserID = $CMS->Query("
        INSERT INTO {IFW_TBL_USERS}(username, userpass, forename, surname, email, location, occupation, interests, homepage_link, homepage_text, avatar_id, user_groups, user_moderate)
        VALUES ('$newCmsUser', '$pwHash', '', '', 'admin@yoursite.com', '', '', '', '', '', 0, '1|2|3', 'N')
      ", basename(__FILE__), __LINE__);
      // Build link mapping table
      //$CMS->UM->rebuildAll();
      // ** Confirm completion ** //
      $IJP->Display("<p>Installation completed successfully.</p>
      <p><b>IMPORTANT:</b> Please complete the following steps before continuing:</p>
      <p>1. CHMOD the data/secure directory to 755</p>
      <p>2. Delete the installer directory</p>".
      '<p><a class="btn btn-lg btn-success" href="/login.php" role="button">Login to your site!</a></p>', "Step 4");

      break;
  } // end of switch statement
