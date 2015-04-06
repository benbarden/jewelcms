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
    $hasConfigIni = file_exists('../data/secure/config.ini');

    if ($hasConfigIni) {
        $pageIntro = "WARNING: You have an existing install. Don't click New Install unless you want to start again.";
        $guidesText = "Read the install/upgrade guides in the upload/guides folder for help on getting started.";
        $buttons  = '<a class="btn btn-lg btn-success" href="upgrade-intro.php" role="button">Upgrade</a>&nbsp;&nbsp;';
        $buttons .= '<a class="btn btn-lg btn-danger" href="install.php" role="button">New Install</a>';
    } else {
        $pageIntro = '';
        $guidesText = "Read the install guide in the upload/guides folder for help on getting started.";
        $buttons = '<a class="btn btn-lg btn-success" href="install.php" role="button">New Install</a>';
    }

    $strHTML = <<<PageContent
<p class="lead">$pageIntro</p>
<p>$guidesText</p>
<p>When you're ready:</p>
<p>
    $buttons
</p>

PageContent;
  $IJP->Display($strHTML, "Installer");
