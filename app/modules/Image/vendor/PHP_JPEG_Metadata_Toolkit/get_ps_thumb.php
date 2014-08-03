<?php

/******************************************************************************
*
* Filename:     get_ps_thumb.php
*
* Description:  This script extracts a Photoshop IRB (Image Resource Block)
*               thumbnail from within a JPEG file and allows it to be displayed
*
* Usage:        get_ps_thumb?filename=<filename>
*
* Author:       Evan Hunter
*
* Date:         23/7/2004
*
* Project:      PHP JPEG Metadata Toolkit
*
* Revision:     1.12
*
*               1.00 -> 1.11 : Added support for Photoshop IRB thumbnails which are
*                              embedded within EXIF information (used in TIFF files)
*               1.11 -> 1.12 : changed to use _GET variable
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


        // retrieve the filename from the URL parameters

        $filename = $_GET['filename'];

        // Change: Check for file extension rather than assuming JPEG as of 1.11
        // Retrieve the Filename Extension
        $path_parts = pathinfo( $filename );

        // Check if the Extension is JPEG
        if ( ( strcasecmp( $path_parts["extension"], "jpg" ) == 0 ) ||
             ( strcasecmp( $path_parts["extension"], "jpeg" ) == 0 ) )
        {
                // JPEG Extension

                include 'JPEG.php';
                include 'Photoshop_IRB.php';

                // Retrieve the JPEG header Data

                $jpeg_header_data = get_jpeg_header_data( $filename );

                // Retrieve any Photoshop IRB data in the file

                $IRB_array = get_Photoshop_IRB( $jpeg_header_data );

                // Check if Photoshop IRB data was retrieved

                if ( $IRB_array === FALSE )
                {
                        // No Photoshop IRB data could be retrieved - abort
                        ob_end_clean ( );
                        echo "<p>Photoshop IRB could not be retrieved from the JPEG file</p>\n";
                        return;
                }

                // Cycle through the resources in the Photoshop IRB
                // Until either a thumbnail resource is found or
                // there are no more resources
                $i = 0;
                while ( ( $i < count( $IRB_array ) ) &&
                        ( $IRB_array[$i]['ResID'] != 0x0409 ) &&
                        ( $IRB_array[$i]['ResID'] != 0x040C ) )
                {
                        $i++;
                }


                // Check if a thumbnail was found
                if ( $i < count( $IRB_array ) )
                {
                        // A thumbnail was found, Display it
                        ob_end_clean ( );
                        header("Content-type: image/jpeg");
                        print substr( $IRB_array[$i]['ResData'] , 28 );
                }
        }
        // Change: Add support for TIFF Photoshop IRB thumbnails as of 1.11
        // Check if file has TIFF extension
        else if ( ( strcasecmp( $path_parts["extension"], "tif" ) == 0 ) ||
                  ( strcasecmp( $path_parts["extension"], "tiff" ) == 0 ) )
        {
                // TIFF Extension

                include 'EXIF.php';

                // Retrieve the EXIF info
                $exif_array = get_EXIF_TIFF( $filename );

                // Retrieve any Photoshop IRB data in the EXIF
                if ( ( array_key_exists( 0, $exif_array ) ) &&
                     ( array_key_exists( 34377, $exif_array[0] ) ) &&
                     ( array_key_exists( 'Data', $exif_array[0][34377] ) ) )
                {
                        $IRB_array = $exif_array[0][34377]['Data'];

                        // Check if Photoshop IRB data was retrieved

                        if ( $IRB_array === FALSE )
                        {
                                // No Photoshop IRB data could be retrieved - abort
                                ob_end_clean ( );
                                echo "<p>Photoshop IRB could not be retrieved from the TIFF file</p>\n";
                                return;
                        }

                        // Cycle through the resources in the Photoshop IRB
                        // Until either a thumbnail resource is found or
                        // there are no more resources
                        $i = 0;
                        while ( ( $i < count( $IRB_array ) ) &&
                                ( $IRB_array[$i]['ResID'] != 0x0409 ) &&
                                ( $IRB_array[$i]['ResID'] != 0x040C ) )
                        {
                                $i++;
                        }


                        // Check if a thumbnail was found
                        if ( $i < count( $IRB_array ) )
                        {
                                // A thumbnail was found, Display it
                                ob_end_clean ( );
                                header("Content-type: image/jpeg");
                                print substr( $IRB_array[$i]['ResData'] , 28 );
                        }
                }
                else
                {
                        // Embedded Photoshop IRB block not found
                        ob_end_clean ( );
                        echo "No Photoshop IRB found within EXIF";
                }
        }
        else
        {
                // Unknown extension
                ob_end_clean ( );
                echo "Unknown file Type";
        }


?>