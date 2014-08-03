<?php

/******************************************************************************
*
* Filename:     get_exif_thumb.php
*
* Description:  This script extracts a EXIF thumbnail from the first IFD of a
*               JPEG file and allows it to be displayed
*
* Usage:        get_exif_thumb?filename=<filename>
*
* Author:       Evan Hunter
*
* Date:         23/7/2004
*
* Project:      PHP JPEG Metadata Toolkit
*
* Revision:     1.12
*
* Changes:      1.00 -> 1.12 : changed to use _GET variable
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

        // Ensure that nothing can write to the standard io, before we get the header out
        ob_start( );
        
        
        include 'JPEG.php';
        include 'EXIF.php';


        // retrieve the filename from the URL parameters

        $filename = $_GET['filename'];

        // Retrieve any EXIF data in the file

        $Exif_array = get_EXIF_JPEG( $filename );

        // Check if EXIF data was retrieved

        if ( $Exif_array === FALSE )
        {
                // No EXIF data could be retrieved - abort
                ob_end_clean ( );
                echo "<p>EXIF segment could not be retrieved</p>\n";
                return;
        }


        // Check if the First IFD exists ( The First IFD is actually the second, since there is also the zeroth IFD)
        if ( count( $Exif_array ) < 2  )
        {
                ob_end_clean ( );
                echo "<p>Couldn't find Thumbnail IFD</p>\n";
                return;
        }

        // Check if the First IFD contains the Thumbnail tag
        if ( array_key_exists( 513, $Exif_array[1] ) )
        {
                // Output the thumbnail
                ob_end_clean ( );
                header("Content-type: image/jpeg");
                print $Exif_array[1][513]['Data'];
        }
        else
        {
                ob_end_clean ( );
                echo "<p>Couldn't find Thumbnail Tag</p>\n";
                return;
        }

?>
