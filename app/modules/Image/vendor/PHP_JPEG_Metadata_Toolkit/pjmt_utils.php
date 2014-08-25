<?php
/******************************************************************************
*
* Filename:     pjmt_utils.php
*
* Description:  Provides various utility functions for the toolkit
*
* Author:       Evan Hunter
*
* Date:         20/1/2005
*
* Project:      JPEG Metadata
*
* Revision:     1.11
*
* NOTE:         This file will change with every revision update, hence will not
*               be shown in the changes list
*
* URL:          http://electronics.ozhiker.com
*
* Copyright:    Copyright Evan Hunter 2004
*
* License:      This file is part of the PHP JPEG Metadata Toolkit.
*
*               The PHP JPEG Metadata Toolkit is free software; you can
*               redistribute it and/or modify it under the terms of the
*               GNU General Public License as published by the Free Software
*               Foundation; either version 2 of the License, or (at your
*               option) any later version.
*
*               The PHP JPEG Metadata Toolkit is distributed in the hope
*               that it will be useful, but WITHOUT ANY WARRANTY; without
*               even the implied warranty of MERCHANTABILITY or FITNESS
*               FOR A PARTICULAR PURPOSE.  See the GNU General Public License
*               for more details.
*
*               You should have received a copy of the GNU General Public
*               License along with the PHP JPEG Metadata Toolkit; if not,
*               write to the Free Software Foundation, Inc., 59 Temple
*               Place, Suite 330, Boston, MA  02111-1307  USA
*
*               If you require a different license for commercial or other
*               purposes, please contact the author: evan@ozhiker.com
*
******************************************************************************/


/******************************************************************************
*
* Function:     get_relative_path
*
* Description:  Creates a relative path version of a file or directiroy name,
*               given a directory that it will be relative to.
*
* Parameters:   target - a file or directory name which will be made relative
*               fromdir - the directory which the returned path is relative to
*
* Returns:      output - the relative path
*
******************************************************************************/

function get_relative_path( $target, $fromdir )
{
        // Check that the fromdir has a trailing slash, otherwise realpath will
        // strip the last directory name off
        if ( ( $fromdir[ strlen( $fromdir ) - 1 ] != "\\" ) &&
             ( $fromdir[ strlen( $fromdir ) - 1 ] != "/" ) )
        {
                $fromdir .= "/";
        }

        // get a real directory name for each of the target and from directory
        $from = realpath( $fromdir );
        $target = realpath( $target );
        $to = dirname( $target  );

        // Can't get relative path with drive in path - remove it
        if ( ( $colonpos = strpos( $target, ":" ) ) != FALSE )
        {
                $target = substr( $target, $colonpos+1 );
        }
        if ( ( $colonpos = strpos( $from, ":" ) ) != FALSE )
        {
                $from = substr( $from, $colonpos+1 );
        }
        if ( ( $colonpos = strpos( $to, ":" ) ) != FALSE )
        {
                $to = substr( $to, $colonpos+1 );
        }


        $path = "../";
        $posval = 0;
        // Step through the paths until a difference is found (ignore slash, backslash differences
        // or the end of one is found
        while ( ( ( $from[$posval] == $to[$posval] ) ||
                  ( ( $from[$posval] == "\\" ) && ( $to[$posval] == "/" ) ) ||
                  ( ( $from[$posval] == "/" ) && ( $to[$posval] == "\\" ) ) ) &&
                ( $from[$posval] && $to[$posval] ) )
        {
                $posval++;
        }
        // Save the position of the first difference
        $diffpos = $posval;

        // Check if the directories are the same or
        // the if target is in a subdirectory of the fromdir
        if ( ( ! $from[$posval] ) &&
             ( $to[$posval] == "/" || $to[$posval] == "\\" || !$to[$posval] ) )
        {
                // target is in fromdir or a subdirectory
                // Build relative path starting with a ./
                return ( "./" . substr( $target, $posval+1, strlen( $target ) ) );
        }
        else
        {
                // target is outside the fromdir branch
                // find out how many "../"'s are necessary
                // Step through the fromdir path, checking for slashes
                // each slash encountered requires a "../"
                while ( $from[++$posval] )
                {
                        // Check for slash
                        if ( ( $from[$posval] == "/" ) || ( $from[$posval] == "\\" ) )
                        {
                                // Found a slash, add a "../"
                                $path .= "../";
                        }
                }

                // Search backwards to find where the first common directory
                // as some letters in the first different directory names
                // may have been the same
                $diffpos--;
                while ( ( $to[$diffpos] != "/" ) && ( $to[$diffpos] != "\\" ) && $to[$diffpos] )
                {
                        $diffpos--;
                }
                // Build relative path to return

                return ( $path . substr( $target, $diffpos+1, strlen( $target ) ) );
        }
}

/******************************************************************************
* End of Function:     get_relative_path
******************************************************************************/


?>