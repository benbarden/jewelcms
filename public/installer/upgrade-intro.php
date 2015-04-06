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

    // Check files exist to prevent errors
    if (!file_exists('../data/secure/db_vars.php') || !file_exists('../data/secure/config.ini')) {
        header('Location: /installer');
        exit;
    }

  require 'InstallPage.php';
  $IJP = new InstallPage;
  
  require '../../lib/Cms/Legacy/Header.php';
  
  $CMS->Cache->ClearAll();
  
  $strVersion = $CMS->SYS->GetSysPref(C_PREF_CMS_VERSION);
  $strMaxVersion = C_SYS_LATEST_VERSION;
  $strPageTitle = "Jewel CMS - Upgrade to version $strMaxVersion";

  // Prevent upgrade to max version
  if ($strVersion == $strMaxVersion) {
    $IJP->Display("<p>Your site cannot be upgraded because you're already running Jewel CMS $strMaxVersion.</p>", 'Error');
  } else {
    $strWarning = "";
    if ($strMaxVersion == "x.x.x") {
        //
    }
    $IJP->Display("<p>You are about to upgrade your site from Jewel CMS $strVersion to Jewel CMS $strMaxVersion.</p>".
    '<p><a href="upgrade.php">Run the upgrade script</a>.</p>', 'Upgrade');
  }
  
