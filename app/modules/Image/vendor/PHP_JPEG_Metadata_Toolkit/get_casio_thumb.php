<?php

/******************************************************************************
*
* Filename:     get_ps_thumb.php
*
* Description:  This script extracts a Casio Type 2 EXIF Makernote Thumbnail
*               from within a JPEG file and allows it to be displayed
*
* Usage:        get_casio_thumb?filename=<filename>
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


        include 'EXIF.php';


        // retrieve the filename from the URL parameters

        $filename = $_GET['filename'];


        // Retrieve any EXIF data in the file

        $Exif_array = get_EXIF_JPEG( $filename );

        // Check if any EXIF data was retrieved
        if ( $Exif_array === FALSE )
        {
                // No EXIF data was found - abort
                ob_end_clean ( );
                echo "<p>Error getting EXIF Information</p>\n";
                return;
        }


        // Check that there is at least the Zeroth IFD in the array
        if ( count( $Exif_array ) < 1  )
        {
                // Couldn't find the zeroth IFD
                return;
        }
        
        
        // Check that the EXIF IFD exists
        if ( array_key_exists( 34665, $Exif_array[0] ) )
        {
                // Found the EXIF IFD,
                
                // Check that the makernote tag exists
                if ( array_key_exists( 37500, $Exif_array[0][34665]['Data'][0] ) )
                {
                        // Makernote Exists
                
                        // Check that the Makernote is Casio Type 2
                        if  ( $Exif_array[0][34665]['Data'][0][37500]['Makernote Tags'] == "Casio Type 2" )
                        {
                                // Makernote is Casio Type 2
                                // Check if an IFD exists for the makernote
                                if ( array_key_exists( 0, $Exif_array[0][34665]['Data'][0][37500]['Decoded Data'] ) )
                                {
                                        // Check if the Thumbnail offset tag 8192 exists
                                        if ( array_key_exists( 8192, $Exif_array[0][34665]['Data'][0][37500]['Decoded Data'][0] ) )
                                        {
                                                // Found Thumbnail - Display it
                                                ob_end_clean ( );
                                                header("Content-type: image/jpeg");
                                                print $Exif_array[0][34665]['Data'][0][37500]['Decoded Data'][0][8192]['Data'];
                                        }
                                                // Check if the Thumbnail offset tag 4 exists
                                        else if ( array_key_exists( 4, $Exif_array[0][34665]['Data'][0][37500]['Decoded Data'][0] ) )
                                        {
                                                // Found Thumbnail - Display it
                                                ob_end_clean ( );
                                                header("Content-type: image/jpeg");
                                                print $Exif_array[0][34665]['Data'][0][37500]['Decoded Data'][0][4]['Data'];
                                        }
                                }

                        }
                }
        }
        else
        {
        }

        return;
        
?>
