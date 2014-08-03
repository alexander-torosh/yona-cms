<html>

<!--***************************************************************************
*
* Filename:     TIFFExample.php
*
* Description:  An example of how the PHP JPEG Metadata Toolkit can be used to
*               display TIFF Metadata.
*
* Author:       Evan Hunter
*
* Date:         30/7/2004
*
* Project:      PHP JPEG Metadata Toolkit
*
* Revision:     1.12
*
* Changes:      1.10 -> 1.11 : Changed displayed toolkit version numbers to reference Toolkit_Version.php
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

                        // Hide any unknown EXIF tags
                        $GLOBALS['HIDE_UNKNOWN_TAGS'] = TRUE;

                        include 'Toolkit_Version.php';
                        include 'EXIF.php';

                        // Retrieve the TIFF image filename from the http url request
                        if ( ( !array_key_exists( 'tiff_fname', $_GET ) ) ||
                             ( $_GET['tiff_fname'] == "" ) )
                        {
                                echo "<title>No image filename defined</title>\n";
                                echo "</head>\n";
                                echo "<body>\n";
                                echo "<p>No image filename defined - use GET method with field: tiff_fname</p>\n";
                                echo "<p><a href=\"http://www.ozhiker.com/electronics/pjmt/\" >PHP JPEG Metadata Toolkit version " . $GLOBALS['Toolkit_Version'] . ", Copyright (C) 2004 Evan Hunter</a></p>\n";         // Change: displayed toolkit version numbers to reference Toolkit_Version.php - as of version 1.11
                                echo "</body>\n";
                                exit( );
                        }
                        else
                        {
                                $filename = $_GET['tiff_fname'];

                                // Sanitize the filename to remove any hack attempts
                                if ( 0 == preg_match ( '/^\.?\/?([_A-Za-z0-9]+\.tif?f)$/i', $filename ) )
                                {
                                    echo "<title>Bad image filename defined</title>\n";
                                    echo "</head>\n";
                                    echo "<body>\n";
                                    echo "<p>Bad image filename defined - Must be tif or tiff</p>\n";
                                    echo "<p><a href=\"http://www.ozhiker.com/electronics/pjmt/\" >PHP JPEG Metadata Toolkit version " . $GLOBALS['Toolkit_Version'] . ", Copyright (C) 2004 Evan Hunter</a></p>\n";         // Change: displayed toolkit version numbers to reference Toolkit_Version.php - as of version 1.11
                                    echo "</body>\n";
                                    exit( );
                                }
                        }


                        // Output the title
                        echo "<title>Metadata details for $filename</title>";



                 ?>

        </head>

        <body>

                <p >Interpreted using: <a href="http://www.ozhiker.com/electronics/pjmt/">PHP JPEG Metadata Toolkit version <?php echo $GLOBALS['Toolkit_Version'] ?>, Copyright (C) 2004 Evan Hunter</a></p>                <!-- Change: displayed toolkit version numbers to reference Toolkit_Version.php - as of version 1.11 -->
                <br>

                <h2><B><U>Metadata for &quot;<?php echo $filename; ?>&quot;</U></B></h2>


                <!-- Output the EXIF Information -->
                <?php echo Interpret_EXIF_to_HTML( get_EXIF_TIFF( $filename ), $filename );  ?>

                <BR>
                <BR>
                <BR>
                <p>Interpreted using:</p>
                <p><a href="http://www.ozhiker.com/electronics/pjmt/" >PHP JPEG Metadata Toolkit version <?php echo $GLOBALS['Toolkit_Version'] ?>, Copyright (C) 2004 Evan Hunter</a></p>              <!-- Change: displayed toolkit version numbers to reference Toolkit_Version.php - as of version 1.11 -->

        </body>

</html>