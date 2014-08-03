<?php


/****************************************************************************
*
* Filename:     Edit_File_Info.php
*
* Description:  Allows the user to edit the metadata of an image over the internet
*               in the same way that Photoshop edits 'File Info' data
*               This file provides only the html for a form containing the file info
*               input fields. The rest of the html file must be provided by the calling script.
*               $outputfilename must always be defined - it is ne name of the file which
*               have the metadata changed after the form has been submitted
*
*               This file has several modes of operation:
*
*               1) If $new_ps_file_info_array is defined then it's data will be used
*                  to fill the fields.
*               2) If $new_ps_file_info_array is not defined but $filename is defined,
*                  then the file info fields will be filled from the metadata in the file specified
*               3) If $new_ps_file_info_array is not defined but $filename and $default_ps_file_info_array
*                  are defined, then the file info fields will be filled from the metadata
*                  in the file specified, but where fields are blank, they will be filled from $default_ps_file_info_array
*               4) Otherwise the fields will be blank
*
*               See Edit_File_Info_Example.php for an example of usage
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
***************************************************************************/



        include 'Toolkit_Version.php';          // Change: added as of version 1.11

        // Check for operation modes 2 or 3
        // i.e. $filename is defined, and $new_ps_file_info_array is not
        if ( ( ! isset( $new_ps_file_info_array ) ) &&
             ( isset( $filename ) ) &&
             ( is_string( $filename ) ) )
        {
                // Hide any unknown EXIF tags
                $GLOBALS['HIDE_UNKNOWN_TAGS'] = TRUE;

                // Accessing the existing file info for the specified file requires these includes
                include 'JPEG.php';
                include 'XMP.php';
                include 'Photoshop_IRB.php';
                include 'EXIF.php';
                include 'Photoshop_File_Info.php';

                // Retrieve the header information from the JPEG file
                $jpeg_header_data = get_jpeg_header_data( $filename );

                // Retrieve EXIF information from the JPEG file
                $Exif_array = get_EXIF_JPEG( $filename );

                // Retrieve XMP information from the JPEG file
                $XMP_array = read_XMP_array_from_text( get_XMP_text( $jpeg_header_data ) );

                // Retrieve Photoshop IRB information from the JPEG file
                $IRB_array = get_Photoshop_IRB( $jpeg_header_data );

                // Retrieve Photoshop File Info from the three previous arrays
                $new_ps_file_info_array = get_photoshop_file_info( $Exif_array, $XMP_array, $IRB_array );



                // Check if there is an array of defaults available
                if ( ( isset( $default_ps_file_info_array) ) &&
                     ( is_array( $default_ps_file_info_array) ) )
                {
                        // There are defaults defined

                        // Check if there is a default for the date defined
                        if ( ( ! array_key_exists( 'date', $default_ps_file_info_array ) ) ||
                             ( ( array_key_exists( 'date', $default_ps_file_info_array ) ) &&
                               ( $default_ps_file_info_array['date'] == '' ) ) )
                        {
                                // No default for the date defined
                                // figure out a default from the file

                                // Check if there is a EXIF Tag 36867 "Date and Time of Original"
                                if ( ( $Exif_array != FALSE ) &&
                                     ( array_key_exists( 0, $Exif_array ) ) &&
                                     ( array_key_exists( 34665, $Exif_array[0] ) ) &&
                                     ( array_key_exists( 0, $Exif_array[0][34665] ) ) &&
                                     ( array_key_exists( 36867, $Exif_array[0][34665][0] ) ) )
                                {
                                        // Tag "Date and Time of Original" found - use it for the default date
                                        $default_ps_file_info_array['date'] = $Exif_array[0][34665][0][36867]['Data'][0];
                                        $default_ps_file_info_array['date'] = preg_replace( "/(\d\d\d\d):(\d\d):(\d\d)( \d\d:\d\d:\d\d)/", "$1-$2-$3", $default_ps_file_info_array['date'] );
                                }
                               // Check if there is a EXIF Tag 36868 "Date and Time when Digitized"
                                else if ( ( $Exif_array != FALSE ) &&
                                     ( array_key_exists( 0, $Exif_array ) ) &&
                                     ( array_key_exists( 34665, $Exif_array[0] ) ) &&
                                     ( array_key_exists( 0, $Exif_array[0][34665] ) ) &&
                                     ( array_key_exists( 36868, $Exif_array[0][34665][0] ) ) )
                                {
                                        // Tag "Date and Time when Digitized" found - use it for the default date
                                        $default_ps_file_info_array['date'] = $Exif_array[0][34665][0][36868]['Data'][0];
                                        $default_ps_file_info_array['date'] = preg_replace( "/(\d\d\d\d):(\d\d):(\d\d)( \d\d:\d\d:\d\d)/", "$1-$2-$3", $default_ps_file_info_array['date'] );
                                }
                                // Check if there is a EXIF Tag 306 "Date and Time"
                                else if ( ( $Exif_array != FALSE ) &&
                                     ( array_key_exists( 0, $Exif_array ) ) &&
                                     ( array_key_exists( 306, $Exif_array[0] ) ) )
                                {
                                        // Tag "Date and Time" found - use it for the default date
                                        $default_ps_file_info_array['date'] = $Exif_array[0][306]['Data'][0];
                                        $default_ps_file_info_array['date'] = preg_replace( "/(\d\d\d\d):(\d\d):(\d\d)( \d\d:\d\d:\d\d)/", "$1-$2-$3", $default_ps_file_info_array['date'] );
                                }
                                else
                                {
                                        // Couldn't find an EXIF date in the image
                                        // Set default date as creation date of file
                                        $default_ps_file_info_array['date'] = date ("Y-m-d", filectime( $filename ));
                                }
                        }

                        // Cycle through all the elements of the default values array
                        foreach( $default_ps_file_info_array as $def_key =>$default_item )
                        {
                                // Check if the current element is Keywords or
                                // Supplemental Categories as these are arrays
                                // and need to be treated differently
                                if ( ( strcasecmp( $def_key, "keywords" ) == 0 ) ||
                                     ( strcasecmp( $def_key, "supplementalcategories" ) == 0 ) )
                                {
                                        // Keywords or Supplemental Categories found
                                        // Check if the File Info from the file is empty for this element
                                        // and if there are default values in this array element
                                        if ( ( count( $new_ps_file_info_array[ $def_key ] ) == 0 ) &&
                                             ( is_array( $default_item ) ) &&
                                             ( count( $default_item ) >= 0 ) )
                                        {
                                                // The existing file info is empty, and there are
                                                // defaults - add them
                                                $new_ps_file_info_array[ $def_key ] = $default_item;
                                        }
                                }
                                // Otherwise, this is not an array element, just check if it is blank in the existing file info
                                else if ( trim( $new_ps_file_info_array[ $def_key ] ) == "" )
                                {
                                        // The existing file info is blank, add the default value
                                        $new_ps_file_info_array[ $def_key ] = $default_item;
                                }

                        }
                }
        }
        // Check for operation mode 4 - $new_ps_file_info_array and $filename are not defined,
        else if ( ( ( !isset($new_ps_file_info_array) ) || ( ! is_array($new_ps_file_info_array) ) ) &&
                  ( ( !isset($filename) ) || ( ! is_string( $filename ) ) ) )
        {
                // No filename or new_ps_file_info_array defined, create a blank file info array to display
                $new_ps_file_info_array = array(
                      "title" => "",
                      "author" => "",
                      "authorsposition" => "",
                      "caption" => "",
                      "captionwriter" => "",
                      "jobname" => "",
                      "copyrightstatus" => "",
                      "copyrightnotice" => "",
                      "ownerurl" => "",
                      "keywords" => array(),
                      "category" => "",
                      "supplementalcategories" => array(),
                      "date" => "",
                      "city" => "",
                      "state" => "",
                      "country" => "",
                      "credit" => "",
                      "source" => "",
                      "headline" => "",
                      "instructions" => "",
                      "transmissionreference" => "",
                      "urgency" => "" );
        }



/***************************************************************************
*
* Now output the actual HTML form
*
***************************************************************************/

?>




        <form name="EditJPEG" action="Write_File_Info.php" method="post">


        <?php echo "<input name=\"filename\" type=\"hidden\" value=\"$outputfilename\">"; ?>

                <table>

                        <tr>
                                <td>
                                        Title
                                </td>
                                <td>
                                        <?php
                                        echo "<input size=49 name=\"title\" type=\"text\" value=\"". $new_ps_file_info_array[ 'title' ] ."\">";
                                        ?>
                                </td>
                        </tr>

                        <tr>
                                <td>
                                        Author
                                </td>
                                <td>
                                        <?php
                                        echo "<input size=49 name=\"author\" type=\"text\" value=\"". $new_ps_file_info_array[ 'author' ] ."\">";
                                        ?>
                                </td>
                        </tr>

                        <tr>
                                <td>
                                        Authors Position
                                </td>
                                <td>
                                        <?php
                                                echo "<input size=49 name=\"authorsposition\" type=\"text\" value=\"". $new_ps_file_info_array[ 'authorsposition' ] ."\"> - Note: not used in Photoshop 7 or higher";
                                        ?>
                                </td>
                        </tr>

                        <tr>
                                <td>
                                        Description
                                </td>
                                <td>
                                        <textarea name="caption" rows=3 cols=37 wrap="off"><?php echo $new_ps_file_info_array[ 'caption' ]; ?></textarea>
                                </td>
                        </tr>

                        <tr>
                                <td>
                                        Description Writer
                                </td>
                                <td>
                                        <?php
                                        echo "<input size=49 name=\"captionwriter\" type=\"text\" value=\"". $new_ps_file_info_array[ 'captionwriter' ] ."\">";
                                        ?>
                                </td>
                        </tr>


                        <tr>
                                <td>
                                        Keywords
                                </td>
                                <td>
                                        <textarea name="keywords" rows=3 cols=37 wrap="off"><?php
                                                                                                foreach( $new_ps_file_info_array[ 'keywords' ] as $keyword )
                                                                                                {
                                                                                                        echo "$keyword&#xA;";
                                                                                                }
                                                                                            ?></textarea>
                                </td>
                        </tr>


                        <tr>
                                <td>
                                        Copyright Status
                                </td>
                                <td>
                                        <select size=1  name="copyrightstatus">
                                                <?php
                                                        $copystatus = $new_ps_file_info_array[ 'copyrightstatus' ];
                                                        if ( $copystatus == "Unknown" )
                                                        {
                                                                echo "<option value=\"Unknown\" SELECTED >Unknown</option>\n";
                                                        }
                                                        else
                                                        {
                                                                echo "<option value=\"Unknown\">Unknown</option>\n";
                                                        }

                                                        if ( $copystatus == "Copyrighted Work" )
                                                        {
                                                                echo "<option value=\"Copyrighted Work\" SELECTED >Copyrighted Work</option>\n";
                                                        }
                                                        else
                                                        {
                                                                echo "<option value=\"Copyrighted Work\">Copyrighted Work</option>\n";
                                                        }

                                                        if ( $copystatus == "Public Domain" )
                                                        {
                                                                echo "<option value=\"Public Domain\" SELECTED >Public Domain</option>\n";
                                                        }
                                                        else
                                                        {
                                                                echo "<option value=\"Public Domain\">Public Domain</option>\n";
                                                        }
                                                ?>
                                        </select>
                                </td>
                        </tr>


                        <tr>
                                <td>
                                        Copyright Notice
                                </td>
                                <td>
                                        <textarea name="copyrightnotice" rows=3 cols=37 wrap="off"><?php echo $new_ps_file_info_array[ 'copyrightnotice' ]; ?></textarea>
                                </td>
                        </tr>


                        <tr>
                                <td>
                                        Copyright Info URL
                                </td>
                                <td>
                                        <?php
                                        echo "<input size=49 name=\"ownerurl\" type=\"text\" value=\"". $new_ps_file_info_array[ 'ownerurl' ] ."\">\n";
                                        if ($new_ps_file_info_array[ 'ownerurl' ] != "" )
                                        {
                                                echo "<a href=\"". $new_ps_file_info_array[ 'ownerurl' ] ."\" > (". $new_ps_file_info_array[ 'ownerurl' ] .")</a>\n";
                                        }
                                        ?>

                                </td>
                        </tr>


                        <tr>
                                <td>
                                        Category
                                </td>
                                <td>
                                        <?php
                                        echo "<input size=49 name=\"category\" type=\"text\" value=\"". $new_ps_file_info_array[ 'category' ] ."\">\n";
                                        ?>

                                </td>
                        </tr>

                        <tr>
                                <td>
                                        Supplemental Categories
                                </td>
                                <td>
                                        <textarea name="supplementalcategories" rows=3 cols=37 wrap="off"><?php
                                                                                                foreach( $new_ps_file_info_array[ 'supplementalcategories' ] as $supcat )
                                                                                                {
                                                                                                        echo "$supcat&#xA;";
                                                                                                }
                                                                                            ?>
                                        </textarea>
                                </td>
                        </tr>



                        <tr>
                                <td>
                                        Date Created
                                </td>
                                <td>
                                        <?php
                                                echo "<input size=49 name=\"date\" type=\"text\" value=\"". $new_ps_file_info_array[ 'date' ] ."\">";
                                        ?>
                                        (Note date must be YYYY-MM-DD format)
                                </td>
                        </tr>

                        <tr>
                                <td>
                                        City
                                </td>
                                <td>
                                        <?php
                                                echo "<input size=49 name=\"city\" type=\"text\" value=\"". $new_ps_file_info_array[ 'city' ] ."\">";
                                        ?>
                                </td>
                        </tr>


                        <tr>
                                <td>
                                        State
                                </td>
                                <td>
                                        <?php
                                                echo "<input size=49 name=\"state\" type=\"text\" value=\"". $new_ps_file_info_array[ 'state' ] ."\">";
                                        ?>
                                </td>
                        </tr>


                        <tr>
                                <td>
                                        Country
                                </td>
                                <td>
                                        <?php
                                                echo "<input size=49 name=\"country\" type=\"text\" value=\"". $new_ps_file_info_array[ 'country' ] ."\">";
                                        ?>
                                </td>
                        </tr>



                        <tr>
                                <td>
                                        Credit
                                </td>
                                <td>
                                        <?php
                                                echo "<input size=49 name=\"credit\" type=\"text\" value=\"". $new_ps_file_info_array[ 'credit' ] ."\">";
                                        ?>
                                </td>
                        </tr>


                        <tr>
                                <td>
                                        Source
                                </td>
                                <td>
                                        <?php
                                                echo "<input size=49 name=\"source\" type=\"text\" value=\"". $new_ps_file_info_array[ 'source' ] ."\">";
                                        ?>
                                </td>
                        </tr>



                        <tr>
                                <td>
                                        Headline
                                </td>
                                <td>
                                        <textarea name="headline" rows=3 cols=37 wrap="off"><?php echo $new_ps_file_info_array[ 'headline' ]; ?></textarea>
                                </td>
                        </tr>



                        <tr>
                                <td>
                                        Instructions
                                </td>
                                <td>
                                        <textarea name="instructions" rows=3 cols=37 wrap="off"><?php echo $new_ps_file_info_array[ 'instructions' ]; ?></textarea>
                                </td>
                        </tr>


                        <tr>
                                <td>
                                        Transmission Reference
                                </td>
                                <td>
                                        <textarea name="transmissionreference" rows=3 cols=37 wrap="off"><?php echo $new_ps_file_info_array[ 'transmissionreference' ]; ?></textarea>
                                </td>
                        </tr>

                        <tr>
                                <td>
                                        Job Name
                                </td>
                                <td>
                                        <?php
                                                echo "<input size=49 name=\"jobname\" type=\"text\" value=\"". $new_ps_file_info_array[ 'jobname' ] ."\"> - Note: not used in Photoshop CS";
                                        ?>
                                </td>
                        </tr>

                        <tr>
                                <td>
                                        Urgency
                                </td>
                                <td>
                                        <select size="1" name="urgency">
                                                <?php
                                                        for( $i = 1; $i <= 8; $i++ )
                                                        {
                                                                echo "<option value=\"$i\"";
                                                                if ( $new_ps_file_info_array[ 'urgency' ] == $i )
                                                                {
                                                                        echo " SELECTED ";
                                                                }
                                                                echo ">";
                                                                if ( $i == 1 )
                                                                {
                                                                        echo "High";
                                                                }
                                                                else if ( $i == 5 )
                                                                {
                                                                        echo "Normal";
                                                                }
                                                                else if ( $i == 8 )
                                                                {
                                                                        echo "Low";
                                                                }
                                                                else
                                                                {
                                                                        echo "$i";
                                                                }
                                                                echo "</option>\n";
                                                        }
                                                        if ( $new_ps_file_info_array[ 'urgency' ] == "none" )
                                                        {
                                                                echo "<option value=\"none\" SELECTED >None</option>";
                                                        }
                                                        else
                                                        {
                                                                echo "<option value=\"none\" >None</option>";
                                                        }
                                                 ?>

                                        </select>
                                </td>
                        </tr>

                </table>
                <br>
                <input type="submit" value="Update!">


        </form>

        <br>
        <br>
        <p>Powered by: <a href="http://www.ozhiker.com/electronics/pjmt/" >PHP JPEG Metadata Toolkit version <?php echo $GLOBALS['Toolkit_Version'] ?>, Copyright (C) 2004 Evan Hunter</a></p>     <!-- Change: displayed toolkit version numbers to reference Toolkit_Version.php - as of version 1.11 -->
        <br>
        <br>