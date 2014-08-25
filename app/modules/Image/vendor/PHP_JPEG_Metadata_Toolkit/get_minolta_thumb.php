<?php

/******************************************************************************
*
* Filename:     get_minolta_thumb.php
*
* Description:  This script extracts a Minolta EXIF Makernote Thumbnail
*               from within a JPEG file and allows it to be displayed
*
* Usage:        get_minolta_thumb?filename=<filename>
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
                ob_end_clean ( );
                echo "<p>Couldn't find TIFF IFD 0</p>\n";
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

                        // Check that the Makernote is Olympus
                        if  ( $Exif_array[0][34665]['Data'][0][37500]['Makernote Tags'] == "Olympus" )
                        {
                                // Makernote is Olympus
                                // Check if an IFD exists for the makernote
                                if ( array_key_exists( 0, $Exif_array[0][34665]['Data'][0][37500]['Decoded Data'] ) )
                                {
                                        // Check if the Thumbnail tag 0x0088 exists
                                        if ( array_key_exists( 0x0088, $Exif_array[0][34665]['Data'][0][37500]['Decoded Data'][0] ) )
                                        {
                                                // Found a Thumbnail
                                                // Get the data
                                                $data = $Exif_array[0][34665]['Data'][0][37500]['Decoded Data'][0][0x0088]['Data'];
                                                
                                                // Sometimes the Minolta thumbnails are corrupt as there is no data
                                                // Check that the data is OK
                                                if ( $data !== FALSE )
                                                {
                                                        // Minolta thumbnails seem to have the first byte incorrect - this could possibly be a counter in case the thumbnail needs to span more than one tag
                                                        // Restore the first byte of the jpeg thumbnail
                                                        $data{0} = "\xff";

                                                        // Display the thumbnail
                                                        ob_end_clean ( );
                                                        header("Content-type: image/jpeg");
                                                        print $data;
                                                }
                                                else
                                                {
                                                        // Thumbnail data is missing - display message
                                                        ob_end_clean ( );
                                                        echo "<p>Thumbnail missing</p>\n";
                                                }
                                        }
                                                // Check if the Thumbnail tag 0x0081 exists
                                        else if ( array_key_exists( 0x0081, $Exif_array[0][34665]['Data'][0][37500]['Decoded Data'][0] ) )
                                        {
                                                // Found a Thumbnail
                                                // Get the data
                                                $data = $Exif_array[0][34665]['Data'][0][37500]['Decoded Data'][0][0x0081]['Data'];

                                                // Sometimes the Minolta thumbnails are corrupt as there is no data
                                                // Check that the data is OK
                                                if ( $data !== FALSE )
                                                {
                                                        // Minolta thumbnails seem to have the first byte incorrect - this could possibly be a counter in case the thumbnail needs to span more than one tag
                                                        // Restore the first byte of the jpeg thumbnail
                                                        $data{0} = "\xff";

                                                        // Display the thumbnail
                                                        ob_end_clean ( );
                                                        header("Content-type: image/jpeg");
                                                        print $data ;
                                                }
                                                else
                                                {
                                                        // Thumbnail data is missing - display message
                                                        ob_end_clean ( );
                                                        echo "<p>Thumbnail missing</p>\n";
                                                }
                                        }
                                        else
                                        {
                                                // Couldn't find a Minolta thumbnail tag - display message
                                                ob_end_clean ( );
                                                echo "<p>Couldn't find Minolta Thumbnail Tag</p>\n";
                                        }
                                }
                                else
                                {
                                        // Couldn't find an IFD in the Makernote tag - display message
                                        ob_end_clean ( );
                                        echo "<p>Makernote Doesn't contain IFD 0</p>\n";
                                }

                        }
                        else
                        {
                                // Makernote does not use Olympus tags - display message
                                ob_end_clean ( );
                                echo "<p>Not an Olympus Makernote</p>\n";
                        }
                }
                else
                {
                        // Couldn't find Makernote tag - display message
                        ob_end_clean ( );
                        echo "<p>Couldn't find Makernote</p>\n";
                }
        }
        else
        {
                // Couldn't find the EXIF IFD - display message
                ob_end_clean ( );
                echo "<p>Couldn't find Exif IFD</p>\n";
        }


        return;
        
?>
