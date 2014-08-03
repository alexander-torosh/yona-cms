<html>

<!--***************************************************************************
*
* Filename:     Example.php
*
* Description:  An example of how the PHP JPEG Metadata Toolkit can be used to
*               display JPEG Metadata.
*
* Author:       Evan Hunter
*
* Date:         30/7/2004
*
* Project:      PHP JPEG Metadata Toolkit
*
* Revision:     1.12
*
* Changes:      1.00 -> 1.10 : Changed name of GET parameter from 'filename' to 'jpeg_fname'
*                              to stop script-kiddies using the google command 'allinurl:*.php?filename=*'
*                              to find servers to attack
*                              Changed behavior when no filename is given, to be cleaner
*               1.10 -> 1.11 : Changed displayed toolkit version numbers to reference Toolkit_Version.php
*                              Changed this example file to be easily relocatable
*               1.11 -> 1.12 : Added parsing of filename to prevent attacks, changed to use _GET variable
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


                <?php
                        // Turn off Error Reporting
                        error_reporting ( 0 );

                        // Change: Allow this example file to be easily relocatable - as of version 1.11
                        $Toolkit_Dir = "./";     // Ensure dir name includes trailing slash

                        // Hide any unknown EXIF tags
                        $GLOBALS['HIDE_UNKNOWN_TAGS'] = TRUE;

                        include $Toolkit_Dir . 'Toolkit_Version.php';          // Change: added as of version 1.11
                        include $Toolkit_Dir . 'JPEG.php';                     // Change: Allow this example file to be easily relocatable - as of version 1.11
                        include $Toolkit_Dir . 'JFIF.php';
                        include $Toolkit_Dir . 'PictureInfo.php';
                        include $Toolkit_Dir . 'XMP.php';
                        include $Toolkit_Dir . 'Photoshop_IRB.php';
                        include $Toolkit_Dir . 'EXIF.php';

                        // Retrieve the JPEG image filename from the http url request
                        if ( ( !array_key_exists( 'jpeg_fname', $_GET ) ) ||
                             ( $_GET['jpeg_fname'] == "" ) )
                        {
                                echo "<title>No image filename defined</title>\n";
                                echo "</head>\n";
                                echo "<body>\n";
                                echo "<p>No image filename defined - use GET method with field: jpeg_fname</p>\n";
                                echo "<p><a href=\"http://www.ozhiker.com/electronics/pjmt/\" >PHP JPEG Metadata Toolkit version " . $GLOBALS['Toolkit_Version'] . ", Copyright (C) 2004 Evan Hunter</a></p>\n";         // Change: displayed toolkit version numbers to reference Toolkit_Version.php - as of version 1.11
                                echo "</body>\n";
                                exit( );
                        }
                        else
                        {
                                $filename = $_GET['jpeg_fname'];

                                // Sanitize the filename to remove any hack attempts
                                if ( 0 == preg_match ( '/^\.?\/?([_A-Za-z0-9]+\.jpe?g)$/i', $filename ) )
                                {
                                    echo "<title>Bad image filename defined</title>\n";
                                    echo "</head>\n";
                                    echo "<body>\n";
                                    echo "<p>Bad image filename defined - Must be jpg or jpeg</p>\n";
                                    echo "<p><a href=\"http://www.ozhiker.com/electronics/pjmt/\" >PHP JPEG Metadata Toolkit version " . $GLOBALS['Toolkit_Version'] . ", Copyright (C) 2004 Evan Hunter</a></p>\n";         // Change: displayed toolkit version numbers to reference Toolkit_Version.php - as of version 1.11
                                    echo "</body>\n";
                                    exit( );
                                }
                        }


                        // Output the title
                        echo "<title>Metadata details for $filename</title>";

                        // Retrieve the header information
                        $jpeg_header_data = get_jpeg_header_data( $filename );

                 ?>

        </head>

        <body>

                <p>Interpreted using: <a href="http://www.ozhiker.com/electronics/pjmt/" >PHP JPEG Metadata Toolkit version <?php echo $GLOBALS['Toolkit_Version'] ?>, Copyright (C) 2004 Evan Hunter</a></p>    <!-- Change: displayed toolkit version numbers to reference Toolkit_Version.php - as of version 1.11 -->
                <br>
                <br>

                <h1><B><U>Metadata for &quot;<?php echo $filename; ?>&quot;</U></B></h1>
                <br>

                <!-- Output a link allowing user to edit the Photoshop File Info
                     Change: Allow this example file to be easily relocatable - as of version 1.11
                -->
                <?php  $relative_filename = get_relative_path( $filename, $Toolkit_Dir );  ?>
                <h4><a href="<?php echo $Toolkit_Dir."Edit_File_Info_Example.php?jpeg_fname=$relative_filename"; ?>" >Click here to edit the Photoshop File Info for this file</a></h4>
                <br>



                <!-- Output the information about the APP segments -->
                <?php   echo Generate_JPEG_APP_Segment_HTML( $jpeg_header_data ); ?>

                <BR>
                <HR>
                <BR>

                <!-- Output the Intrinsic JPEG Information -->
                <?php   echo Interpret_intrinsic_values_to_HTML( get_jpeg_intrinsic_values( $jpeg_header_data ) );  ?>

                <BR>
                <HR>
                <BR>

                <!-- Output the JPEG Comment -->
                <?php   echo Interpret_Comment_to_HTML( $jpeg_header_data ); ?>

                <BR>
                <HR>
                <BR>

                <!-- Output the JPEG File Interchange Format Information -->
                <?php   echo Interpret_JFIF_to_HTML( get_JFIF( $jpeg_header_data ), $filename ); ?>

                <BR>
                <HR>
                <BR>

                <!-- Output the JFIF Extension Information -->
                <?php   echo Interpret_JFXX_to_HTML( get_JFXX( $jpeg_header_data ), $filename ); ?>

                <BR>
                <HR>
                <BR>

                <!-- Output the Picture Info Text -->
                <?php   echo Interpret_App12_Pic_Info_to_HTML( $jpeg_header_data ); ?>

                <BR>
                <HR>
                <BR>

                <!-- Output the EXIF Information -->
                <?php   echo Interpret_EXIF_to_HTML( get_EXIF_JPEG( $filename ), $filename );  ?>

                <BR>
                <HR>
                <BR>

                <!-- Output the XMP Information -->
                <?php   echo Interpret_XMP_to_HTML( read_XMP_array_from_text( get_XMP_text( $jpeg_header_data ) ) ); ?>

                <BR>
                <HR>
                <BR>

                <!-- Output the Photoshop IRB (including the IPTC-NAA info -->
                <?php   echo Interpret_IRB_to_HTML( get_Photoshop_IRB( $jpeg_header_data ), $filename ); ?>

                <BR>
                <HR>
                <BR>

                <!-- Output the Meta Information -->
                <?php   echo Interpret_EXIF_to_HTML( get_Meta_JPEG( $filename ), $filename );  ?>

                <BR>
                <HR>
                <BR>

                <!-- Display the original image -->

                <h2>Original Image</h2>
                <?php   echo "<img src=\"$filename\">";  ?>


                <BR>
                <BR>
                <BR>
                <p>Interpreted using:</p>
                <p><a href="http://www.ozhiker.com/electronics/pjmt/" >PHP JPEG Metadata Toolkit version <?php echo $GLOBALS['Toolkit_Version'] ?>, Copyright (C) 2004 Evan Hunter</a></p>     <!-- Change: displayed toolkit version numbers to reference Toolkit_Version.php - as of version 1.11 -->

        </body>

</html>