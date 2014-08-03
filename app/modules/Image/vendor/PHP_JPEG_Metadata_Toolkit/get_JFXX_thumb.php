<?php

/******************************************************************************
*
* Filename:     get_JFXX_thumb.php
*
* Description:  This script extracts a JFXX (JPEG File Interchange Format
*               Extension) thumbnail from within a JPEG file and allows it
*               to be displayed
*
* Usage:        get_JFXX_thumb?filename=<filename>
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
        include 'JFIF.php';


        // retrieve the filename from the URL parameters
        
        $filename = $_GET['filename'];

        // Retrieve the JPEG header Data
        
        $jpeg_header_data = get_jpeg_header_data( $filename );

        // Retrieve any JFXX data in the file

        $JFXX_array = get_JFXX( $jpeg_header_data );

        // Check if JFXX data was retrieved

        if ( $JFXX_array === FALSE )
        {
                // No JFXX data could be retrieved - abort
                ob_end_clean ( );
                echo "<p>JFXX Data could not be retrieved</p>\n";
                return;
        }

        // Check the JFXX extension code which indicates what format
        // the thumbnail is encoded with
        
        if ( $JFXX_array['Extension_Code'] == 0x10 ) // JPEG Encoding
        {
                // JPEG Encoding - Output JPEG Data
                ob_end_clean ( );
                header("Content-type: image/jpeg");
                print $JFXX_array['ThumbData'];
                return;
        }
        else if ( $JFXX_array['Extension_Code'] == 0x11 ) // One Byte Per Pixel Encoding
        {
                // TODO: Implement decoding of One Byte Per Pixel encoded JFXX Thumbnail
                return;
        }
        else if ( $JFXX_array['Extension_Code'] == 0x13 ) // Three Bytes Per Pixel Encoding
        {
                // TODO: Implement decoding of Three Bytes Per Pixel encoded JFXX Thumbnail
                return;
        }
        else
        {
                // Invalid Extension Value - abort
                return;
        }



?>
