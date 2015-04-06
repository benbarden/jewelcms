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
  $strFeed = empty($_GET['name']) ? "" : $CMS->FilterAlphanumeric($_GET['name'], "");
  $RSS = new RSSBuilder;
  switch ($strFeed) {
    case "articles":
      $intAreaID = empty($_GET['id']) ? "" : $CMS->FilterNumeric($_GET['id']);
      header('Content-type: text/xml');
      print($RSS->GetArticleRSS($intAreaID));
      exit;
      break;
    default: $CMS->Err_MFail(M_ERR_MISSINGPARAMS_SYSTEM, "name");
  }
  
