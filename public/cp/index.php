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
  $CMS->RES->ValidateLoggedIn();
  if ($CMS->RES->IsError()) {
    $CMS->Err_MFail(M_ERR_NOT_LOGGED_IN, "");
  }
  $strSiteTitle = $CMS->SYS->GetSysPref(C_PREF_SITE_TITLE);
  $strPageTitle = "Dashboard";

// Purge access log
// @todo move this to a cron
$cpLogLimit = $cmsContainer->getServiceLocator()->getCmsConfig()->getByKey('CP.LogLimit');
$repoAccessLog = $cmsContainer->getService('Repo.AccessLog');
$repoAccessLog->purgeEntries($cpLogLimit);

// Twig templating for CPanel
$cpBindings = array(); //array_merge($globalBindings, $userBindings);

$cpBindings['CP']['Title'] = $strPageTitle;

// User access
$authCurrentUser = $cmsContainer->getServiceLocator()->getAuthCurrentUser();
if ($authCurrentUser) {
    $userArray = array(
        'Id' => $authCurrentUser->getId(),
        'Name' => $authCurrentUser->getDisplayName()
    );
    $cpBindings['Login']['User'] = $userArray;
}

$cpBindings['Auth']['IsAdmin'] = $CMS->RES->IsAdmin();
$cpBindings['Auth']['CanWriteContent'] = $CMS->RES->CanAddContent();

  // ** Quick Stats ** //
  if (!$CMS->RES->IsAdmin()) {
    $intArticleCount     = "-";
    $intCommentCount     = "-";
    $intSiteFileCount    = "-";
    $intUserCount        = "-";
  } else {
    // Article count
    $arrArticleCount = $CMS->ResultQuery("
    SELECT count(*) AS count FROM {IFW_TBL_CONTENT}
    ", basename(__FILE__), __LINE__);
      $cpBindings['Page']['ArticleCount'] = $arrArticleCount[0]['count'];
    
          // Site file count
    $arrSiteFileCount = $CMS->ResultQuery("SELECT count(*) AS count FROM {IFW_TBL_UPLOADS} WHERE is_siteimage = 'Y' AND is_avatar = 'N'", basename(__FILE__), __LINE__);
    $cpBindings['Page']['SiteFileCount'] = $arrSiteFileCount[0]['count'];
    
    // Member count
    $arrUserCount = $CMS->ResultQuery("SELECT count(*) AS count FROM {IFW_TBL_USERS}", basename(__FILE__), __LINE__);
    $cpBindings['Page']['UserCount'] = $arrUserCount[0]['count'];
    
  }
  
  // Recent Drafts
  $recentDrafts = $CMS->ResultQuery("
    SELECT id, title, create_date FROM Cms_Content WHERE content_status = 'Draft'
    ORDER BY id DESC LIMIT 5
  ", basename(__FILE__), __LINE__);
  $cpBindings['Page']['RecentDrafts'] = $recentDrafts;

    // Build the page
    $cmsDbVersion = $CMS->SYS->GetSysPref(C_PREF_CMS_VERSION);
    $cpBindings['Page']['CmsVersion'] = $cmsDbVersion;
    $cpBindings['Page']['ThisYear'] = date('Y'); // Current year
    $cpBindings['Page']['SiteTitle'] = $CMS->SYS->GetSysPref(C_PREF_SITE_TITLE);

// Database update
if ($cmsDbVersion != C_SYS_LATEST_VERSION) {
    $cpBindings['Page']['NewerVersion'] = C_SYS_LATEST_VERSION;
}

// Twig templating for CPanel
$engine = $cmsContainer->getService('Theme.EngineCPanel');
$outputHtml = $engine->render('index.twig', $cpBindings);
print($outputHtml);
exit;
