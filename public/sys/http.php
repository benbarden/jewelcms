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

    function httpRedirect($url)
    {
        if (headers_sent()) {
            echo "<script type='text/javascript'>location.href='$url';</script>";
        } else {
            header("Location: $url");
        }
        exit;
    }

    function httpRedirectPerm($url)
    {
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: $url");
        exit;
    }
