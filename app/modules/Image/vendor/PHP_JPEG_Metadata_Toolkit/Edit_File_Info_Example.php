<html>

<!--***************************************************************************
*
* Filename:     Edit_File_Info_Example.php
*
* Description:  An example file showing how edit_file_info allows the user to edit
*               the metadata of an image over the internet in the same way
*               that Photoshop edits 'File Info' data
*
* Author:       Evan Hunter
*
* Date:         17/11/2004
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

                        include 'Toolkit_Version.php';          // Change: added as of version 1.11

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
                 ?>


                <title>Edit Photoshop File Info details for <?php $filename ?></title>
        </head>

        <body >
                <p>Powered by: <a href="http://www.ozhiker.com/electronics/pjmt/" >PHP JPEG Metadata Toolkit version <?php echo $GLOBALS['Toolkit_Version'] ?>, Copyright (C) 2004 Evan Hunter</a></p>    <!-- Change: displayed toolkit version numbers to reference Toolkit_Version.php - as of version 1.11 -->
                <br>
                <br>


                <?php
                        // Output a heading
                        echo "<H1>Edit Photoshop File Info details for $filename</H1>";

                        // Output a link to display the full metadata
                        echo "<p><a href=\"Example.php?jpeg_fname=" . $filename . "\" >View Full Metatdata Information</a></p>\n";


                        // Display a small copy of the image
                        echo "<p><img src=\"$filename\" height=\"50%\"></p>";

                        // Define defaults for the fields - These are only used where the image has blank fields
                        $default_ps_file_info_array = array (
                                                                'title'                 => "",
                                                                'author'                => "Evan Hunter",
                                                                'authorsposition'       => "",
                                                                'caption'               => "",
                                                                'captionwriter'         => "Evan Hunter",
                                                                'jobname'               => "",
                                                                'copyrightstatus'       => "Copyrighted Work",
                                                                'copyrightnotice'       => "Copyright (c) Evan Hunter 2004",
                                                                'ownerurl'              => "http://www.ozhiker.com",
                                                                'keywords'              => array(),
                                                                'category'              => "",
                                                                'supplementalcategories'=> array(),
                                                                'date'                  => "",
                                                                'city'                  => "",
                                                                'state'                 => "Tasmania",
                                                                'country'               => "Australia",
                                                                'credit'                => "Evan Hunter",
                                                                'source'                => "Evan Hunter",
                                                                'headline'              => "",
                                                                'instructions'          => "",
                                                                'transmissionreference' => "",
                                                                'urgency'               => ""
                                                                );

                        // outputfilename must always be defined, as it specifies the
                        // file which will be changed

                        // These two lines create a temporary copy of the file
                        // which will be the one that is edited, keeping
                        // the original intact. - This would not be required if you wanted
                        // to change the original - in that case just set $outputfilename = $filename
                        $outputfilename = get_next_filename( );
                        copy( $filename, $outputfilename );




                        // Include the File Info Editor.

                        include "Edit_File_Info.php";

                ?>


        </body>

</html>


















<?php

/******************************************************************************
*
* Function:     get_next_filename
*
* Description:  Simple function to cycle through temporary filenames ( a to z )
*               This means that there will only be a maximum of 26 temporary files,
*               hence avoiding filling up the server or having a cron job to remove them.
*
*               NOTE: This function would not normally be required, and is just
*                     to protect my website (and others) from filling up with
*                     temporary files whilst demonstrating the toolkit
*
* Parameters:   none
*
* Returns:      TRUE - on Success
*               FALSE - on Failure
*
******************************************************************************/

function get_next_filename( )
{
        // Read the letter of the next temp file from disk
        $filename = file( "next_temp_file.dat" );
        // If it wasn't read - start at 'a'
        if ( $filename == FALSE )
        {
                $filename = 'a';
        }
        else
        {
                $filename = $filename{0};
        }

        // Ensure the filename letter is valid
        if ( ( $filename < 'a' ) || ( $filename > 'z' ) )
        {
                $filename = 'a';
        }


        // Check if the names are up to 'z'
        if( $filename == 'z' )
        {
                // Name is at z - the next one should be 'a'
                $new_filename = 'a';
        }
        else
        {
                // The name is not 'z' add one to it to get the next value
                $new_filename = chr( ord( $filename ) + 1 );
        }

        // Write the next temp file letter back into the file
        $Fhnd = fopen ("next_temp_file.dat", "w");
        fwrite ($Fhnd, $new_filename);
        fclose ($Fhnd);

        // return the filename
        return "temp_$filename.jpg";
}

/******************************************************************************
* End of Function:     get_next_filename
******************************************************************************/



?>