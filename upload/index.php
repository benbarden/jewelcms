<?php
/*
  Injader - Content management for everyone
  Copyright (c) 2005-2009 Ben Barden
  Please go to http://www.injader.com if you have questions or need help.

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
    
    // Check if installed
    if (file_exists("data/secure/db_vars.php") == false) {
        header("Location: installer/");
    }
    
    require 'sys/header.php';

    // If there's a subfolder in the URL, don't treat this as part of the URL array
    $currentUrl = $_SERVER['REQUEST_URI'];
    $posFolder = strpos($currentUrl, URL_ROOT);
    if ($posFolder !== false) {
        $currentUrl = substr($currentUrl, $posFolder + strlen(URL_ROOT));
    }

    // Load special CMS pages
    $currentUrlArray = explode("/", $currentUrl);
    $urlBit1 = array_key_exists(0, $currentUrlArray) ? $currentUrlArray[0] : "";
    $urlBit2 = array_key_exists(1, $currentUrlArray) ? $currentUrlArray[1] : "";
    $urlBit3 = array_key_exists(2, $currentUrlArray) ? $currentUrlArray[2] : "";
    $urlBit4 = array_key_exists(3, $currentUrlArray) ? $currentUrlArray[3] : "";
    if (($urlBit1 == 'cms') && ($urlBit2 == 'archives')) {
        $strHTML = $CMS->pages_Archives->build($urlBit3, $urlBit4);
        $archivesWrapper = '<div id="archives-page" class="archives-page">'."\n";
        $CMS->TH->SetSysWrapperStart($archivesWrapper);
        $CMS->MV->DefaultPageAllowRobots("Archives", $strHTML);
        exit;
    }

    // Homepage
    $homeUrlArray = array('', 'index.php');
    $isHomePage = in_array($currentUrl, $homeUrlArray);

    if ($isHomePage) {

        $strObject = 'area';
        $intItemID = $CMS->AR->GetDefaultAreaID();

    } else {

        // ** Find this page in the database ** //
        $arrPageObject = $CMS->UM->getByUrl($_SERVER['REQUEST_URI']);

        // ** Does it exist? ** //
        if (!is_array($arrPageObject)) {
            $CMS->Err_MFail(M_ERR_NO_ROWS_RETURNED, "Not found: ".$_SERVER['REQUEST_URI']);
        }

        // ** Verify whether this is a valid URL ** //
        if ($arrPageObject[0]['is_active'] == "N") {

            // Nope, not active. Do we have any others?
            if (!empty($arrPageObject[0]['article_id'])) {
                $strErrText       = "Article ".$arrPageObject[0]['article_id'];
                $arrNewPageObject = $CMS->UM->getActiveArticle($arrPageObject[0]['article_id']);
                $blnRedirected    = true;
            } elseif (!empty($arrPageObject[0]['area_id'])) {
                $strErrText       = "Area ".$arrPageObject[0]['area_id'];
                $arrNewPageObject = $CMS->UM->getActiveArea($arrPageObject[0]['area_id']);
                $blnRedirected    = true;
            } else {
                $CMS->Err_MFail(M_ERR_NO_ROWS_RETURNED, "No article or area id for this url!");
            }
            if ($blnRedirected) {
                if (!is_array($arrNewPageObject)) {
                    $CMS->Err_MFail(M_ERR_NO_ROWS_RETURNED, "No active entry for item: $strErrText");
                }
                httpRedirectPerm($arrNewPageObject[0]['relative_url']);
            }

        }

        // We have an active URL, so off we go!
        if (!empty($arrPageObject[0]['article_id'])) {
            $strObject = "article"; $intItemID = $arrPageObject[0]['article_id'];
        } elseif (!empty($arrPageObject[0]['area_id'])) {
            $strObject = "area"; $intItemID = $arrPageObject[0]['area_id'];
        } else {
            $CMS->Err_MFail(M_ERR_NO_ROWS_RETURNED, "No article or area id for this url!");
        }

    }
    
    // ** Caching ** //
    switch ($strObject) {
        case "area":
        case "article":
        case "file":
        case "user":
            if ($CMS->RES->GetCurrentUserID()) {
                // Don't cache for logged-in users
                header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
                header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");   // Date in the past
            } else {
                // Expires in 1 day
                $dteDate = mktime(date("H"), date("i"), date("s"), date("m"), date("d")+1, date("Y"));
                $dteExpiryDate = date('r', $dteDate);
                header("Expires: ".$dteExpiryDate);
            }
            break;
        default:
            $CMS->Err_MFail(M_ERR_INVALID_VIEW_PARAM, $strObject);
            break;
    }

    // Theme renderer
    $themeRenderer = new \Cms\Theme\Renderer($cmsContainer);
    switch ($strObject) {
        case "area":
        case "category":
            $themeRenderer->setObjectCategory();
            // Pagination
            $pageNo = null;
            if (isset($_GET['page'])) {
                $pageNo = (int) $_GET['page'];
            }
            if (!$pageNo) {
                $pageNo = 1;
            }
            $themeRenderer->setPageNo($pageNo);
            break;
        case "article":
            $themeRenderer->setObjectArticle();
            break;
        case "file":
            $themeRenderer->setObjectFile();
            break;
        case "user":
            $themeRenderer->setObjectUser();
            break;
    }
    $themeRenderer->setItemId($intItemID);
    $themeRenderer->render();

