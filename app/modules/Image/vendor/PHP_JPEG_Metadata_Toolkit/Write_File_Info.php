<html>

<!--***************************************************************************
*
* Filename:     Write_File_Info.php
*
* Description:  An example file showing how a user can write the metadata of an
*               image over the internet in the same way that Photoshop
*               edits 'File Info' data.
*               This script pairs with Edit_File_Info_Example.php, receiving
*               and processing the data from the HTML form in that script
*
* Author:       Evan Hunter
*
* Date:         17/11/2004
*
* Project:      PHP JPEG Metadata Toolkit
*
* Revision:     1.11
*
* Changes:      1.10 -> 1.11 : Changed displayed toolkit version numbers to reference Toolkit_Version.php
*                              Changed error reporting to no errors
*                              Removed limitation on file being in current directory
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
***************************************************************************-->


        <head>
                <META HTTP-EQUIV="Content-Style-Type" CONTENT="text/css">
                <STYLE TYPE="text/css" MEDIA="screen, print, projection">
                <!--

                        BODY { background-color:#505050; color:#F0F0F0 }
                        a  { color:orange  }
                        .EXIF_Main_Heading { color:red }
                        .EXIF_Secondary_Heading{ color: orange}
                        .EXIF_Table {  border-collapse: collapse ; border: 1px solid #909000}
                        .EXIF_Table tbody td{border-width: 1px; border-style:solid; border-color: #909000;}

                -->
                </STYLE>

                <title>Writing Photoshop File Info Metadata</title>
        </head>

        <body>
                <?php include 'Toolkit_Version.php'; ?>
                <p>Powered by: <a href="http://www.ozhiker.com/electronics/pjmt/" >PHP JPEG Metadata Toolkit version <?php echo $GLOBALS['Toolkit_Version'] ?>, Copyright (C) 2004 Evan Hunter</a></p>                   <!-- Change: displayed toolkit version numbers to reference Toolkit_Version.php - as of version 1.11 -->
                <br>
                <br>

                <?php
                        // Turn off Error Reporting
                        error_reporting ( 0 );          // Change: changed to no reporting -  as of version 1.11

                        include 'Toolkit_Version.php';  // Change: added as of version 1.11

                        // Include the required files for reading and writing Photoshop File Info
                        include 'JPEG.php';
                        include 'XMP.php';
                        include 'Photoshop_IRB.php';
                        include 'EXIF.php';
                        include 'Photoshop_File_Info.php';


                        // Copy all of the HTML Posted variables into an array
                        $new_ps_file_info_array = $GLOBALS['HTTP_POST_VARS'];

                        // Some characters are escaped with backslashes in HTML Posted variable
                        // Cycle through each of the HTML Posted variables, and strip out the slashes
                        foreach( $new_ps_file_info_array as $var_key => $var_val )
                        {
                                $new_ps_file_info_array[ $var_key ] = stripslashes( $var_val );
                        }

                        // Keywords should be an array - explode it on newline boundarys
                        $new_ps_file_info_array[ 'keywords' ] = explode( "\n", trim( $new_ps_file_info_array[ 'keywords' ] ) );

                        // Supplemental Categories should be an array - explode it on newline boundarys
                        $new_ps_file_info_array[ 'supplementalcategories' ] = explode( "\n", trim( $new_ps_file_info_array[ 'supplementalcategories' ] ) );

                        // Make the filename easier to access
                        $filename = $new_ps_file_info_array[ 'filename' ];

                        // Protect against hackers editing other files
                        $path_parts = pathinfo( $filename );
                        if ( strcasecmp( $path_parts["extension"], "jpg" ) != 0 )
                        {
                                echo "Incorrect File Type - JPEG Only\n";
                                exit( );
                        }
                        // Change: removed limitation on file being in current directory - as of version 1.11

                        // Retrieve the header information
                        $jpeg_header_data = get_jpeg_header_data( $filename );

                        // Retreive the EXIF, XMP and Photoshop IRB information from
                        // the existing file, so that it can be updated
                        $Exif_array = get_EXIF_JPEG( $filename );
                        $XMP_array = read_XMP_array_from_text( get_XMP_text( $jpeg_header_data ) );
                        $IRB_array = get_Photoshop_IRB( $jpeg_header_data );

                        // Update the JPEG header information with the new Photoshop File Info
                        $jpeg_header_data = put_photoshop_file_info( $jpeg_header_data, $new_ps_file_info_array, $Exif_array, $XMP_array, $IRB_array );

                        // Check if the Update worked
                        if ( $jpeg_header_data == FALSE )
                        {
                                // Update of file info didn't work - output error message
                                echo "Error - Failure update Photoshop File Info : $filename <br>\n";

                                // Output HTML with the form and data which was
                                // sent, to allow the user to fix it

                                $outputfilename = $filename;
                                include "Edit_File_info.php";
                                echo "</body>\n";
                                echo "</html>\n";

                                // Abort processing
                                exit( );
                        }

                        // Attempt to write the new JPEG file
                        if ( FALSE == put_jpeg_header_data( $filename, $filename, $jpeg_header_data ) )
                        {
                                // Writing of the new file didn't work - output error message
                                echo "Error - Failure to write new JPEG : $filename <br>\n";

                                // Output HTML with the form and data which was
                                // sent, to allow the user to fix it

                                $outputfilename = $filename;
                                include "Edit_File_info.php";
                                echo "</body>\n";
                                echo "</html>\n";

                                // Abort processing
                                exit( );
                        }


                        // Writing of new JPEG succeeded

                        // Output information about new file

                        echo "<h1>DONE! - $filename updated</h1>\n";
                        echo "<p><a href=\"Example.php?jpeg_fname=$filename\" >View Full Metatdata Information</a></p>\n";
                        echo "<p><a href=\"Edit_File_Info_Example.php?jpeg_fname=$filename\" >Re-Edit Photoshop File Info</a></p>\n";
                        echo "<br><br>\n";
                        echo "<p>Below is the updated image, you can save it and look at the changed metadata in your favorite image editor</p>\n";
                        echo "<p><img src=\"$filename\" ></p>\n";


                ?>

                <br>
                <br>
                <br>
                <br>


                <p>Powered by: <a href="http://www.ozhiker.com/electronics/pjmt/" >PHP JPEG Metadata Toolkit version <?php echo $GLOBALS['Toolkit_Version'] ?>, Copyright (C) 2004 Evan Hunter</a></p>  <!-- Change: displayed toolkit version numbers to reference Toolkit_Version.php - as of version 1.11 -->

                <br>
                <br>

        </body>

</html>