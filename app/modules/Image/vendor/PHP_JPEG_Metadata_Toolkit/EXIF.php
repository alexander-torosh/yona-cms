<?php

/******************************************************************************
*
* Filename:     EXIF.php
*
* Description:  Provides functions for reading and writing EXIF Information
*               to/from an APP1 segment of a JPEG file
*               Unfortunately, because EXIF data may be distributed anywhere
*               throughout an image file, rather than just being in one block,
*               it is impossible to pass just a string containing only the EXIF
*               information. Hence it is neccessary to be able to seek to
*               any point in the file. This causes the HTTP and FTP wrappers
*               not to work - i.e. the EXIF functions will only work with local
*               files.
*               To work on an internet file, copy it locally to start with:
*
*               $newfilename = tempnam ( $dir, "tmpexif" );
*               copy ( "http://whatever.com", $newfilename );
*
*
* Author:       Evan Hunter
*
* Date:         30/7/2004
*
* Project:      PHP JPEG Metadata Toolkit
*
* Revision:     1.11
*
* Changes:      1.00 -> 1.10 : added function get_EXIF_TIFF to allow extracting EXIF from a TIFF file
*               1.10 -> 1.11 : added functionality to allow decoding of XMP and Photoshop IRB information
*                              embedded within the EXIF data
*                              added checks for http and ftp wrappers, as these are not supported
*                              changed interpret_IFD to allow thumbnail links to work when
*                              toolkit is portable across directories
*
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


// TODO : Thoroughly test the functions for writing EXIF segments
// TODO : Figure out a way to allow EXIF to function normally with HTTP and FTP wrappers
// TODO : Implement EXIF decoding of Device Setting Description field
// TODO : Implement EXIF decoding of SpatialFrequencyResponse field
// TODO : Implement EXIF decoding of OECF field
// TODO : Implement EXIF decoding of SubjectArea field
// TODO : Add a put_EXIF_TIFF function

/******************************************************************************
*
* Initialisation
*
******************************************************************************/


if ( !isset( $GLOBALS['HIDE_UNKNOWN_TAGS'] ) )     $GLOBALS['HIDE_UNKNOWN_TAGS']= FALSE;
if ( !isset( $GLOBALS['SHOW_BINARY_DATA_HEX'] ) )  $GLOBALS['SHOW_BINARY_DATA_HEX'] = FALSE;
if ( !isset( $GLOBALS['SHOW_BINARY_DATA_TEXT'] ) ) $GLOBALS['SHOW_BINARY_DATA_TEXT'] = FALSE;


include_once 'EXIF_Tags.php';
include_once 'EXIF_Makernote.php';
include_once 'PIM.php';
include_once 'Unicode.php';
include_once 'JPEG.php';
include_once 'IPTC.php';
include_once 'Photoshop_IRB.php';       // Change: as of version 1.11  - Required for TIFF with embedded IRB
include_once 'XMP.php';                 // Change: as of version 1.11  - Required for TIFF with embedded XMP
include_once 'pjmt_utils.php';          // Change: as of version 1.11  - Required for directory portability








/******************************************************************************
*
* Function:     get_EXIF_JPEG
*
* Description:  Retrieves information from a Exchangeable Image File Format (EXIF)
*               APP1 segment and returns it in an array.
*
* Parameters:   filename - the filename of the JPEG image to process
*
* Returns:      OutputArray - Array of EXIF records
*               FALSE - If an error occured in decoding
*
******************************************************************************/

function get_EXIF_JPEG( $filename )
{
        // Change: Added as of version 1.11
        // Check if a wrapper is being used - these are not currently supported (see notes at top of file)
        if ( ( stristr ( $filename, "http://" ) != FALSE ) || ( stristr ( $filename, "ftp://" ) != FALSE ) )
        {
                // A HTTP or FTP wrapper is being used - show a warning and abort
                echo "HTTP and FTP wrappers are currently not supported with EXIF - See EXIF functionality documentation - a local file must be specified<br>";
                echo "To work on an internet file, copy it locally to start with:<br><br>\n";
                echo "\$newfilename = tempnam ( \$dir, \"tmpexif\" );<br>\n";
                echo "copy ( \"http://whatever.com\", \$newfilename );<br><br>\n";
                return FALSE;
        }

        // get the JPEG headers
        $jpeg_header_data = get_jpeg_header_data( $filename );


        // Flag that an EXIF segment has not been found yet
        $EXIF_Location = -1;

        //Cycle through the header segments
        for( $i = 0; $i < count( $jpeg_header_data ); $i++ )
        {
                // If we find an APP1 header,
                if ( strcmp ( $jpeg_header_data[$i]['SegName'], "APP1" ) == 0 )
                {
                        // And if it has the EXIF label,
                        if ( ( strncmp ( $jpeg_header_data[$i]['SegData'], "Exif\x00\x00", 6) == 0 ) ||
                             ( strncmp ( $jpeg_header_data[$i]['SegData'], "Exif\x00\xFF", 6) == 0 ) )          // For some reason, some files have a faulty EXIF name which has a 0xFF in it
                        {
                                // Save the location of the EXIF segment
                                $EXIF_Location = $i;
                        }
                }

        }

        // Check if an EXIF segment was found
        if ( $EXIF_Location == -1 )
        {
                // Couldn't find any EXIF block to decode
                return FALSE;
        }

        $filehnd = @fopen($filename, 'rb');

        // Check if the file opened successfully
        if ( ! $filehnd  )
        {
                // Could't open the file - exit
                echo "<p>Could not open file $filename</p>\n";
                return FALSE;
        }

        fseek( $filehnd, $jpeg_header_data[$EXIF_Location]['SegDataStart'] + 6  );

        // Decode the Exif segment into an array and return it
        $exif_data = process_TIFF_Header( $filehnd, "TIFF" );



        // Close File
        fclose($filehnd);
        return $exif_data;
}

/******************************************************************************
* End of Function:     get_EXIF_JPEG
******************************************************************************/



/******************************************************************************
*
* Function:     put_EXIF_JPEG
*
* Description:  Stores information into a Exchangeable Image File Format (EXIF)
*               APP1 segment from an EXIF array.
*
*               WARNING: Because the EXIF standard allows pointers to data
*               outside the APP1 segment, if there are any such pointers in
*               a makernote, this function will DAMAGE them since it will not
*               be aware that there is an external pointer. This will often
*               happen with Makernotes that include an embedded thumbnail.
*               This damage could be prevented where makernotes can be decoded,
*               but currently this is not implemented.
*
*
* Parameters:   exif_data - The array of EXIF data to insert into the JPEG header
*               jpeg_header_data - The JPEG header into which the EXIF data
*                                  should be stored, as from get_jpeg_header_data
*
* Returns:      jpeg_header_data - JPEG header array with the EXIF segment inserted
*               FALSE - If an error occured
*
******************************************************************************/

function put_EXIF_JPEG( $exif_data, $jpeg_header_data )
{
        // pack the EXIF data into its proper format for a JPEG file
        $packed_data = get_TIFF_Packed_Data( $exif_data );
        if ( $packed_data === FALSE )
        {
                return $jpeg_header_data;
        }

        $packed_data = "Exif\x00\x00$packed_data";

        //Cycle through the header segments
        for( $i = 0; $i < count( $jpeg_header_data ); $i++ )
        {
                // If we find an APP1 header,
                if ( strcmp ( $jpeg_header_data[$i]['SegName'], "APP1" ) == 0 )
                {
                        // And if it has the EXIF label,
                        if ( ( strncmp ( $jpeg_header_data[$i]['SegData'], "Exif\x00\x00", 6) == 0 ) ||
                             ( strncmp ( $jpeg_header_data[$i]['SegData'], "Exif\x00\xFF", 6) == 0 ) )          // For some reason, some files have a faulty EXIF name which has a 0xFF in it
                        {
                                // Found a preexisting EXIF block - Replace it with the new one and return.
                                $jpeg_header_data[$i]['SegData'] = $packed_data;
                                return $jpeg_header_data;
                        }
                }
        }

        // No preexisting segment segment found, insert a new one at the start of the header data.

        // Determine highest position of an APP segment at or below APP3, so we can put the
        // new APP3 at this position


        $highest_APP = -1;

        //Cycle through the header segments
        for( $i = 0; $i < count( $jpeg_header_data ); $i++ )
        {
                // Check if we have found an APP segment at or below APP3,
                if ( ( $jpeg_header_data[$i]['SegType'] >= 0xE0 ) && ( $jpeg_header_data[$i]['SegType'] <= 0xE3 ) )
                {
                        // Found an APP segment at or below APP12
                        $highest_APP = $i;
                }
        }

        // No preexisting EXIF block found, insert a new one at the start of the header data.
        array_splice($jpeg_header_data, $highest_APP + 1 , 0, array( array(   "SegType" => 0xE1,
                                                                              "SegName" => "APP1",
                                                                              "SegDesc" => $GLOBALS[ "JPEG_Segment_Descriptions" ][ 0xE1 ],
                                                                              "SegData" => $packed_data ) ) );
        return $jpeg_header_data;

}

/******************************************************************************
* End of Function:     put_EXIF_JPEG
******************************************************************************/




/******************************************************************************
*
* Function:     get_Meta_JPEG
*
* Description:  Retrieves information from a Meta APP3 segment and returns it
*               in an array. Uses information supplied by the
*               get_jpeg_header_data function.
*               The Meta segment has the same format as an EXIF segment, but
*               uses different tags
*
* Parameters:   filename - the filename of the JPEG image to process
*
* Returns:      OutputArray - Array of Meta records
*               FALSE - If an error occured in decoding
*
******************************************************************************/

function get_Meta_JPEG( $filename )
{
        // Change: Added as of version 1.11
        // Check if a wrapper is being used - these are not currently supported (see notes at top of file)
        if ( ( stristr ( $filename, "http://" ) != FALSE ) || ( stristr ( $filename, "ftp://" ) != FALSE ) )
        {
                // A HTTP or FTP wrapper is being used - show a warning and abort
                echo "HTTP and FTP wrappers are currently not supported with Meta - See EXIF/Meta functionality documentation - a local file must be specified<br>";
                echo "To work on an internet file, copy it locally to start with:<br><br>\n";
                echo "\$newfilename = tempnam ( \$dir, \"tmpmeta\" );<br>\n";
                echo "copy ( \"http://whatever.com\", \$newfilename );<br><br>\n";
                return FALSE;
        }

        // get the JPEG headers
        $jpeg_header_data = get_jpeg_header_data( $filename );


        // Flag that an Meta segment has not been found yet
        $Meta_Location = -1;

        //Cycle through the header segments
        for( $i = 0; $i < count( $jpeg_header_data ); $i++ )
        {
                // If we find an APP3 header,
                if  ( strcmp ( $jpeg_header_data[$i]['SegName'], "APP3" ) == 0 )
                {
                        // And if it has the Meta label,
                        if ( ( strncmp ( $jpeg_header_data[$i]['SegData'], "Meta\x00\x00", 6) == 0 ) ||
                             ( strncmp ( $jpeg_header_data[$i]['SegData'], "META\x00\x00", 6) == 0 ) )
                        {
                                // Save the location of the Meta segment
                                $Meta_Location = $i;
                        }
                }
        }

        // Check if an EXIF segment was found
        if ( $Meta_Location == -1 )
        {
                // Couldn't find any Meta block to decode
                return FALSE;
        }


        $filehnd = @fopen($filename, 'rb');

        // Check if the file opened successfully
        if ( ! $filehnd  )
        {
                // Could't open the file - exit
                echo "<p>Could not open file $filename</p>\n";
                return FALSE;
        }

        fseek( $filehnd, $jpeg_header_data[$Meta_Location]['SegDataStart'] + 6 );

        // Decode the Meta segment into an array and return it
        $meta = process_TIFF_Header( $filehnd, "Meta" );

         // Close File
        fclose($filehnd);

        return $meta;
}

/******************************************************************************
* End of Function:     get_Meta
******************************************************************************/







/******************************************************************************
*
* Function:     put_Meta_JPEG
*
* Description:  Stores information into a Meta APP3 segment from a Meta array.
*
*
*               WARNING: Because the Meta (EXIF) standard allows pointers to data
*               outside the APP1 segment, if there are any such pointers in
*               a makernote, this function will DAMAGE them since it will not
*               be aware that there is an external pointer. This will often
*               happen with Makernotes that include an embedded thumbnail.
*               This damage could be prevented where makernotes can be decoded,
*               but currently this is not implemented.
*
*
* Parameters:   meta_data - The array of Meta data to insert into the JPEG header
*               jpeg_header_data - The JPEG header into which the Meta data
*                                  should be stored, as from get_jpeg_header_data
*
* Returns:      jpeg_header_data - JPEG header array with the Meta segment inserted
*               FALSE - If an error occured
*
******************************************************************************/

function put_Meta_JPEG( $meta_data, $jpeg_header_data )
{
        // pack the Meta data into its proper format for a JPEG file
        $packed_data = get_TIFF_Packed_Data( $meta_data );
        if ( $packed_data === FALSE )
        {
                return $jpeg_header_data;
        }

        $packed_data = "Meta\x00\x00$packed_data";

        //Cycle through the header segments
        for( $i = 0; $i < count( $jpeg_header_data ); $i++ )
        {
                // If we find an APP1 header,
                if ( strcmp ( $jpeg_header_data[$i]['SegName'], "APP3" ) == 0 )
                {
                        // And if it has the Meta label,
                        if ( ( strncmp ( $jpeg_header_data[$i]['SegData'], "Meta\x00\x00", 6) == 0 ) ||
                             ( strncmp ( $jpeg_header_data[$i]['SegData'], "META\x00\x00", 6) == 0 ) )
                        {
                                // Found a preexisting Meta block - Replace it with the new one and return.
                                $jpeg_header_data[$i]['SegData'] = $packed_data;
                                return $jpeg_header_data;
                        }
                }
        }
        // No preexisting segment segment found, insert a new one at the start of the header data.

        // Determine highest position of an APP segment at or below APP3, so we can put the
        // new APP3 at this position


        $highest_APP = -1;

        //Cycle through the header segments
        for( $i = 0; $i < count( $jpeg_header_data ); $i++ )
        {
                // Check if we have found an APP segment at or below APP3,
                if ( ( $jpeg_header_data[$i]['SegType'] >= 0xE0 ) && ( $jpeg_header_data[$i]['SegType'] <= 0xE3 ) )
                {
                        // Found an APP segment at or below APP12
                        $highest_APP = $i;
                }
        }

        // No preexisting Meta block found, insert a new one at the start of the header data.
        array_splice($jpeg_header_data, $highest_APP + 1 , 0, array( array(     "SegType" => 0xE3,
                                                                                "SegName" => "APP3",
                                                                                "SegDesc" => $GLOBALS[ "JPEG_Segment_Descriptions" ][ 0xE1 ],
                                                                                "SegData" => $packed_data ) ) );
        return $jpeg_header_data;

}

/******************************************************************************
* End of Function:     put_Meta_JPEG
******************************************************************************/



/******************************************************************************
*
* Function:     get_EXIF_TIFF
*
* Description:  Retrieves information from a Exchangeable Image File Format (EXIF)
*               within a TIFF file and returns it in an array.
*
* Parameters:   filename - the filename of the TIFF image to process
*
* Returns:      OutputArray - Array of EXIF records
*               FALSE - If an error occured in decoding
*
******************************************************************************/

function get_EXIF_TIFF( $filename )
{
        // Change: Added as of version 1.11
        // Check if a wrapper is being used - these are not currently supported (see notes at top of file)
        if ( ( stristr ( $filename, "http://" ) != FALSE ) || ( stristr ( $filename, "ftp://" ) != FALSE ) )
        {
                // A HTTP or FTP wrapper is being used - show a warning and abort
                echo "HTTP and FTP wrappers are currently not supported with TIFF - See EXIF/TIFF functionality documentation - a local file must be specified<br>";
                echo "To work on an internet file, copy it locally to start with:<br><br>\n";
                echo "\$newfilename = tempnam ( \$dir, \"tmptiff\" );<br>\n";
                echo "copy ( \"http://whatever.com\", \$newfilename );<br><br>\n";
                return FALSE;
        }


        $filehnd = @fopen($filename, 'rb');

        // Check if the file opened successfully
        if ( ! $filehnd  )
        {
                // Could't open the file - exit
                echo "<p>Could not open file $filename</p>\n";
                return FALSE;
        }

        // Decode the Exif segment into an array and return it
        $exif_data = process_TIFF_Header( $filehnd, "TIFF" );

        // Close File
        fclose($filehnd);
        return $exif_data;
}

/******************************************************************************
* End of Function:     get_EXIF_TIFF
******************************************************************************/




/******************************************************************************
*
* Function:     Interpret_EXIF_to_HTML
*
* Description:  Generates html detailing the contents an APP1 EXIF array
*               which was retrieved with a get_EXIF_.... function.
*               Can also be used for APP3 Meta arrays.
*
* Parameters:   Exif_array - the EXIF array,as read from get_EXIF_....
*               filename - the name of the Image file being processed ( used
*                          by scripts which displays EXIF thumbnails)
*
* Returns:      output_str - A string containing the HTML
*
******************************************************************************/

function Interpret_EXIF_to_HTML( $Exif_array, $filename )
{
        // Create the string to receive the html output
        $output_str = "";

        // Check if the array to process is valid
        if ( $Exif_array === FALSE )
        {
                // Exif Array is not valid - abort processing
                return $output_str;
        }

        // Ouput the heading according to what type of tags were used in processing
        if ( $Exif_array[ 'Tags Name' ] == "TIFF" )
        {
                $output_str .= "<h2 class=\"EXIF_Main_Heading\">Contains Exchangeable Image File Format (EXIF) Information</h2>\n";
        }
        else if ( $Exif_array[ 'Tags Name' ] == "Meta" )
        {
                $output_str .= "<h2 class=\"EXIF_Main_Heading\">Contains META Information (APP3)</h2>\n";
        }
        else
        {
                $output_str .= "<h2 class=\"EXIF_Main_Heading\">Contains " . $Exif_array[ 'Tags Name' ] . " Information</h2>\n";
        }


        // Check that there are actually items to process in the array
        if ( count( $Exif_array ) < 1 )
        {
                // No items to process in array - abort processing
                return $output_str;
        }

        // Output secondary heading
        $output_str .= "<h3 class=\"EXIF_Secondary_Heading\">Main Image Information</h2>\n";

        // Interpret the zeroth IFD to html
        $output_str .= interpret_IFD( $Exif_array[0], $filename, $Exif_array['Byte_Align'] );

        // Check if there is a first IFD to process
        if ( array_key_exists( 1, $Exif_array ) )
        {
                // There is a first IFD for a thumbnail
                // Add a heading for it to the output
                $output_str .= "<h3 class=\"EXIF_Secondary_Heading\">Thumbnail Information</h2>\n";

                // Interpret the IFD to html and add it to the output
                $output_str .= interpret_IFD( $Exif_array[1], $filename, $Exif_array['Byte_Align'] );
        }

        // Cycle through any other IFD's
        $i = 2;
        while ( array_key_exists( $i, $Exif_array ) )
        {
                // Add a heading for the IFD
                $output_str .= "<h3  class=\"EXIF_Secondary_Heading\">Image File Directory (IFD) $i Information</h2>\n";

                // Interpret the IFD to html and add it to the output
                $output_str .= interpret_IFD( $Exif_array[$i], $filename, $Exif_array['Byte_Align'] );
                $i++;
        }

        // Return the resulting HTML
        return $output_str;
}

/******************************************************************************
* End of Function:     Interpret_EXIF_to_HTML
******************************************************************************/
















/******************************************************************************
*
*         INTERNAL FUNCTIONS
*
******************************************************************************/











/******************************************************************************
*
* Internal Function:     get_TIFF_Packed_Data
*
* Description:  Packs TIFF IFD data from EXIF or Meta into a form ready for
*               either a JPEG EXIF/Meta segment or a TIFF file
*               This function attempts to protect the contents of an EXIF makernote,
*               by ensuring that it remains in the same position relative to the
*               TIFF header
*
* Parameters:   tiff_data - the EXIF array,as read from get_EXIF_JPEG or get_Meta_JPEG
*
* Returns:      packed_data - A string containing packed segment
*
******************************************************************************/

function get_TIFF_Packed_Data( $tiff_data )
{
        // Check that the segment is valid
        if ( $tiff_data === FALSE )
        {
                return FALSE;
        }

        // Get the byte alignment
        $Byte_Align = $tiff_data['Byte_Align'];

        // Add the Byte Alignment to the Packed data
        $packed_data = $Byte_Align;

        // Add the TIFF ID to the Packed Data
        $packed_data .= put_IFD_Data_Type( 42, 3, $Byte_Align );

        // Create a string for the makernote
        $makernote = "";

        // Check if the makernote exists
        if ( $tiff_data[ 'Makernote_Tag' ] !== FALSE )
        {
                // A makernote exists - We need to ensure that it stays in the same position as it was
                // Put the Makernote before any of the IFD's by padding zeros to the correct offset
                $makernote .= str_repeat("\x00",( $tiff_data[ 'Makernote_Tag' ][ 'Offset' ] - 8 ) );
                $makernote .= $tiff_data[ 'Makernote_Tag' ]['Data'];
        }

        // Calculage where the zeroth ifd will be
        $ifd_offset = strlen( $makernote ) + 8;

        // Add the Zeroth IFD pointer to the packed data
        $packed_data .= put_IFD_Data_Type( $ifd_offset, 4, $Byte_Align );

        // Add the makernote to the packed data (if there was one)
        $packed_data .= $makernote;

        //Add the IFD's to the packed data
        $packed_data .= get_IFD_Array_Packed_Data( $tiff_data, $ifd_offset, $Byte_Align );

        // Return the result
        return $packed_data;
}

/******************************************************************************
* End of Function:     get_TIFF_Packed_Data
******************************************************************************/




/******************************************************************************
*
* Internal Function:     get_IFD_Array_Packed_Data
*
* Description:  Packs a chain of IFD's from EXIF or Meta segments into a form
*               ready for either a JPEG EXIF/Meta segment or a TIFF file
*
* Parameters:   ifd_data - the IFD chain array, as read from get_EXIF_JPEG or get_Meta_JPEG
*               Zero_IFD_offset - The offset to the first IFD from the start of the TIFF header
*               Byte_Align - the Byte alignment to use - "MM" or "II"
*
* Returns:      packed_data - A string containing packed IFD's
*
******************************************************************************/

function get_IFD_Array_Packed_Data( $ifd_data, $Zero_IFD_offset, $Byte_Align )
{
        // Create a string to receive the packed output
        $packed_data = "";

        // Count the IFDs
        $ifd_count = 0;
        foreach( $ifd_data as $key => $IFD )
        {
                // Make sure we only count the IFD's, not other information keys
                if ( is_numeric( $key ) )
                {
                        $ifd_count++;
                }
        }


        // Cycle through each IFD,
        for ( $ifdno = 0; $ifdno < $ifd_count; $ifdno++ )
        {
                // Check if this IFD is the last one
                if ( $ifdno == $ifd_count - 1 )
                {
                        // This IFD is the last one, get it's packed data
                        $packed_data .= get_IFD_Packed_Data( $ifd_data[ $ifdno ], $Zero_IFD_offset +strlen($packed_data), $Byte_Align, FALSE );
                }
                else
                {
                        // This IFD is NOT the last one, get it's packed data
                        $packed_data .= get_IFD_Packed_Data( $ifd_data[ $ifdno ], $Zero_IFD_offset +strlen($packed_data), $Byte_Align, TRUE );
                }

        }

        // Return the packed output
        return $packed_data;
}

/******************************************************************************
* End of Function:     get_IFD_Array_Packed_Data
******************************************************************************/



/******************************************************************************
*
* Internal Function:     get_IFD_Packed_Data
*
* Description:  Packs an IFD from EXIF or Meta segments into a form
*               ready for either a JPEG EXIF/Meta segment or a TIFF file
*
* Parameters:   ifd_data - the IFD chain array, as read from get_EXIF_JPEG or get_Meta_JPEG
*               IFD_offset - The offset to the IFD from the start of the TIFF header
*               Byte_Align - the Byte alignment to use - "MM" or "II"
*               Another_IFD - boolean - false if this is the last IFD in the chain
*                                     - true if it is not the last
*
* Returns:      packed_data - A string containing packed IFD's
*
******************************************************************************/

function get_IFD_Packed_Data( $ifd_data, $IFD_offset, $Byte_Align, $Another_IFD )
{

        $ifd_body_str = "";
        $ifd_data_str = "";

        $Tag_Definitions_Name = $ifd_data[ 'Tags Name' ];


        // Count the Tags in this IFD
        $tag_count = 0;
        foreach( $ifd_data as $key => $tag )
        {
                // Make sure we only count the Tags, not other information keys
                if ( is_numeric( $key ) )
                {
                        $tag_count++;
                }
        }

        // Add the Tag count to the packed data
        $packed_data = put_IFD_Data_Type( $tag_count, 3, $Byte_Align );

        // Calculate the total length of the IFD (without the offset data)
        $IFD_len = 2 + $tag_count * 12 + 4;


        // Cycle through each tag
        foreach( $ifd_data as $key => $tag )
        {
                // Make sure this is a tag, not another information key
                if ( is_numeric( $key ) )
                {

                        // Add the tag number to the packed data
                        $ifd_body_str .= put_IFD_Data_Type( $tag[ 'Tag Number' ], 3, $Byte_Align );

                        // Add the Data type to the packed data
                        $ifd_body_str .= put_IFD_Data_Type( $tag['Data Type'], 3, $Byte_Align );

                        // Check if this is a Print Image Matching entry
                        if ( $tag['Type'] == "PIM" )
                        {
                                // This is a Print Image Matching entry,
                                // encode it
                                $data = Encode_PIM( $tag, $Byte_Align );
                        }
                                // Check if this is a IPTC/NAA Record within the EXIF IFD
                        else if ( ( ( $Tag_Definitions_Name == "EXIF" ) || ( $Tag_Definitions_Name == "TIFF" ) ) &&
                                  ( $tag[ 'Tag Number' ] == 33723 ) )
                        {
                                // This is a IPTC/NAA Record, encode it
                                $data = put_IPTC( $tag['Data'] );
                        }
                                // Change: Check for embedded XMP as of version 1.11
                                // Check if this is a XMP Record within the EXIF IFD
                        else if ( ( ( $Tag_Definitions_Name == "EXIF" ) || ( $Tag_Definitions_Name == "TIFF" ) ) &&
                                  ( $tag[ 'Tag Number' ] == 700 ) )
                        {
                                // This is a XMP Record, encode it
                                $data = write_XMP_array_to_text( $tag['Data'] );
                        }
                                // Change: Check for embedded IRB as of version 1.11
                                // Check if this is a Photoshop IRB Record within the EXIF IFD
                        else if ( ( ( $Tag_Definitions_Name == "EXIF" ) || ( $Tag_Definitions_Name == "TIFF" ) ) &&
                                  ( $tag[ 'Tag Number' ] == 34377 ) )
                        {
                                // This is a Photoshop IRB Record, encode it
                                $data = pack_Photoshop_IRB_Data( $tag['Data'] );
                        }
                                // Exif Thumbnail Offset
                        else if ( ( $tag[ 'Tag Number' ] == 513 ) && ( $Tag_Definitions_Name == "TIFF" ) )
                        {
                                        // The Exif Thumbnail Offset is a pointer but of type Long, not Unknown
                                        // Hence we need to put the data into the packed string separately
                                        // Calculate the thumbnail offset
                                        $data_offset = $IFD_offset + $IFD_len + strlen($ifd_data_str);

                                        // Create the Offset for the IFD
                                        $data = put_IFD_Data_Type( $data_offset, 4, $Byte_Align );

                                        // Store the thumbnail
                                        $ifd_data_str .= $tag['Data'];
                        }
                                // Exif Thumbnail Length
                        else if ( ( $tag[ 'Tag Number' ] == 514 ) && ( $Tag_Definitions_Name == "TIFF" ) )
                        {
                                        // Encode the Thumbnail Length
                                        $data = put_IFD_Data_Type( strlen($ifd_data[513]['Data']), 4, $Byte_Align );
                        }
                                // Sub-IFD
                        else if ( $tag['Type'] == "SubIFD" )
                        {
                                        // This is a Sub-IFD
                                        // Calculate the offset to the start of the Sub-IFD
                                        $data_offset = $IFD_offset + $IFD_len + strlen($ifd_data_str);
                                        // Get the packed data for the IFD chain as the data for this tag
                                        $data = get_IFD_Array_Packed_Data( $tag['Data'], $data_offset, $Byte_Align );
                        }
                        else
                        {
                                // Not a special tag

                                // Create a string to receive the data
                                $data = "";

                                // Check if this is a type Unknown tag
                                if ( $tag['Data Type'] != 7 )
                                {
                                        // NOT type Unknown
                                        // Cycle through each data value and add it to the data string
                                        foreach( $tag[ 'Data' ] as $data_val )
                                        {
                                                $data .= put_IFD_Data_Type( $data_val, $tag['Data Type'], $Byte_Align );
                                        }
                                }
                                else
                                {
                                        // This is a type Unknown - just add the data as is to the data string
                                        $data .= $tag[ 'Data' ];
                                }
                        }

                        // Pad the data string out to at least 4 bytes
                        $data = str_pad ( $data, 4, "\x00" );


                        // Check if the data type is an ASCII String or type Unknown
                        if ( ( $tag['Data Type'] == 2 ) || ( $tag['Data Type'] == 7 ) )
                        {
                                // This is an ASCII String or type Unknown
                                // Add the Length of the string to the packed data as the Count
                                $ifd_body_str .= put_IFD_Data_Type( strlen($data), 4, $Byte_Align );
                        }
                        else
                        {
                                // Add the array count to the packed data as the Count
                                $ifd_body_str .= put_IFD_Data_Type( count($tag[ 'Data' ]), 4, $Byte_Align );
                        }


                        // Check if the data is over 4 bytes long
                        if ( strlen( $data ) > 4 )
                        {
                                // Data is longer than 4 bytes - it needs to be offset
                                // Check if this entry is the Maker Note
                                if ( ( $Tag_Definitions_Name == "EXIF" ) && ( $tag[ 'Tag Number' ] == 37500 ) )
                                {
                                        // This is the makernote - It will have already been stored
                                        // at its original offset to help preserve it
                                        // all we need to do is add the Offset to the IFD packed data
                                        $data_offset = $tag[ 'Offset' ];

                                        $ifd_body_str .= put_IFD_Data_Type( $data_offset, 4, $Byte_Align );
                                }
                                else
                                {
                                        // This is NOT the makernote
                                        // Calculate the data offset
                                        $data_offset = $IFD_offset + $IFD_len + strlen($ifd_data_str);

                                        // Add the offset to the IFD packed data
                                        $ifd_body_str .= put_IFD_Data_Type( $data_offset, 4, $Byte_Align );

                                        // Add the data to the offset packed data
                                        $ifd_data_str .= $data;
                                }
                        }
                        else
                        {
                                // Data is less than or equal to 4 bytes - Add it to the packed IFD data as is
                                $ifd_body_str .= $data;
                        }

                }
        }

        // Assemble the IFD body onto the packed data
        $packed_data .= $ifd_body_str;

        // Check if there is another IFD after this one
        if( $Another_IFD === TRUE )
        {
                // There is another IFD after this
                // Calculate the Next-IFD offset so that it goes immediately after this IFD
                $next_ifd_offset = $IFD_offset + $IFD_len + strlen($ifd_data_str);
        }
        else
        {
                // There is NO IFD after this - indicate with offset=0
                $next_ifd_offset = 0;
        }

        // Add the Next-IFD offset to the packed data
        $packed_data .= put_IFD_Data_Type( $next_ifd_offset, 4, $Byte_Align );

        // Add the offset data to the packed data
        $packed_data .= $ifd_data_str;

        // Return the resulting packed data
        return $packed_data;
}

/******************************************************************************
* End of Function:     get_IFD_Packed_Data
******************************************************************************/





/******************************************************************************
*
* Internal Function:     process_TIFF_Header
*
* Description:  Decodes the information stored in a TIFF header and it's
*               Image File Directories (IFD's). This information is returned
*               in an array
*
* Parameters:   filehnd - The handle of a open image file, positioned at the
*                          start of the TIFF header
*               Tag_Definitions_Name - The name of the Tag Definitions group
*                                      within the global array IFD_Tag_Definitions
*
*
* Returns:      OutputArray - Array of IFD records
*               FALSE - If an error occured in decoding
*
******************************************************************************/

function process_TIFF_Header( $filehnd, $Tag_Definitions_Name )
{


        // Save the file position where the TIFF header starts, as offsets are relative to this position
        $Tiff_start_pos = ftell( $filehnd );



        // Read the eight bytes of the TIFF header
        $DataStr = network_safe_fread( $filehnd, 8 );

        // Check that we did get all eight bytes
        if ( strlen( $DataStr ) != 8 )
        {
                return FALSE;   // Couldn't read the TIFF header properly
        }

        $pos = 0;
        // First two bytes indicate the byte alignment - should be 'II' or 'MM'
        // II = Intel (LSB first, MSB last - Little Endian)
        // MM = Motorola (MSB first, LSB last - Big Endian)
        $Byte_Align = substr( $DataStr, $pos, 2 );



        // Check the Byte Align Characters for validity
        if ( ( $Byte_Align != "II" ) && ( $Byte_Align != "MM" ) )
        {
                // Byte align field is invalid - we won't be able to decode file
                return FALSE;
        }

        // Skip over the Byte Align field which was just read
        $pos += 2;

        // Next two bytes are TIFF ID - should be value 42 with the appropriate byte alignment
        $TIFF_ID = substr( $DataStr, $pos, 2 );

        if ( get_IFD_Data_Type( $TIFF_ID, 3, $Byte_Align ) != 42 )
        {
                // TIFF header ID not found
                return FALSE;
        }

        // Skip over the TIFF ID field which was just read
        $pos += 2;


        // Next four bytes are the offset to the first IFD
        $offset_str = substr( $DataStr, $pos, 4 );
        $offset = get_IFD_Data_Type( $offset_str, 4, $Byte_Align );

        // Done reading TIFF Header


        // Move to first IFD

        if ( fseek( $filehnd, $Tiff_start_pos + $offset ) !== 0 )
        {
                // Error seeking to position of first IFD
                return FALSE;
        }



        // Flag that a makernote has not been found yet
        $GLOBALS[ "Maker_Note_Tag" ] = FALSE;

        // Read the IFD chain into an array
        $Output_Array = read_Multiple_IFDs( $filehnd, $Tiff_start_pos, $Byte_Align, $Tag_Definitions_Name );

        // Check if a makernote was found
        if ( $GLOBALS[ "Maker_Note_Tag" ] != FALSE )
        {
                // Makernote was found - Process it
                // The makernote needs to be processed after all other
                // tags as it may require some of the other tags in order
                // to be processed properly
                $GLOBALS[ "Maker_Note_Tag" ] = Read_Makernote_Tag( $GLOBALS[ "Maker_Note_Tag" ], $Output_Array, $filehnd );

        }

        $Output_Array[ 'Makernote_Tag' ] = $GLOBALS[ "Maker_Note_Tag" ];

        // Save the Name of the Tags used in the output array
        $Output_Array[ 'Tags Name' ] = $Tag_Definitions_Name;



        // Save the Byte alignment
        $Output_Array['Byte_Align'] = $Byte_Align;


        // Return the output array
        return $Output_Array ;
}

/******************************************************************************
* End of Function:     process_TIFF_Header
******************************************************************************/






/******************************************************************************
*
* Internal Function:     read_Multiple_IFDs
*
* Description:  Reads and interprets a chain of standard Image File Directories (IFD's),
*               and returns the entries in an array. This chain is made up from IFD's
*               which have a pointer to the next IFD. IFD's are read until the next
*               pointer indicates there are no more
*
* Parameters:   filehnd - a handle for the image file being read, positioned at the
*                         start of the IFD chain
*               Tiff_offset - The offset of the TIFF header from the start of the file
*               Byte_Align - either "MM" or "II" indicating Motorola or Intel Byte alignment
*               Tag_Definitions_Name - The name of the Tag Definitions group within the global array IFD_Tag_Definitions
*               local_offsets - True indicates that offset data should be interpreted as being relative to the start of the currrent entry
*                               False (normal) indicates offests are relative to start of Tiff header as per IFD standard
*               read_next_ptr - True (normal) indicates that a pointer to the next IFD should be read at the end of the IFD
*                               False indicates that no pointer follows the IFD
*
*
* Returns:      OutputArray - Array of IFD entries
*
******************************************************************************/

function read_Multiple_IFDs( $filehnd, $Tiff_offset, $Byte_Align, $Tag_Definitions_Name, $local_offsets = FALSE, $read_next_ptr = TRUE )
{
        // Start at the offset of the first IFD
        $Next_Offset = 0;

        do
        {
                // Read an IFD
                list($IFD_Array , $Next_Offset) = read_IFD_universal( $filehnd, $Tiff_offset, $Byte_Align, $Tag_Definitions_Name, $local_offsets, $read_next_ptr );

                // Move to the position of the next IFD
                if ( fseek( $filehnd, $Tiff_offset + $Next_Offset ) !== 0 )
                {
                        // Error seeking to position of next IFD
                        echo "<p>Error: Corrupted EXIF</p>\n";
                        return FALSE;
                }

                $Output_Array[] = $IFD_Array;


        } while ( $Next_Offset != 0 );      // Until the Next IFD Offset is zero


        // return resulting array

        return $Output_Array ;
}

/******************************************************************************
* End of Function:     read_Multiple_IFDs
******************************************************************************/







/******************************************************************************
*
* Internal Function:     read_IFD_universal
*
* Description:  Reads and interprets a standard or Non-standard Image File
*               Directory (IFD), and returns the entries in an array
*
* Parameters:   filehnd - a handle for the image file being read, positioned at the start
*                         of the IFD
*               Tiff_offset - The offset of the TIFF header from the start of the file
*               Byte_Align - either "MM" or "II" indicating Motorola or Intel Byte alignment
*               Tag_Definitions_Name - The name of the Tag Definitions group within the global array IFD_Tag_Definitions
*               local_offsets - True indicates that offset data should be interpreted as being relative to the start of the currrent entry
*                               False (normal) indicates offests are relative to start of Tiff header as per IFD standard
*               read_next_ptr - True (normal) indicates that a pointer to the next IFD should be read at the end of the IFD
*                               False indicates that no pointer follows the IFD
*
* Returns:      OutputArray - Array of IFD entries
*               Next_Offset - Offset to next IFD (zero = no next IFD)
*
******************************************************************************/

function read_IFD_universal( $filehnd, $Tiff_offset, $Byte_Align, $Tag_Definitions_Name, $local_offsets = FALSE, $read_next_ptr = TRUE )
{
        if ( ( $filehnd == NULL ) || ( feof( $filehnd ) ) )
        {
                return array (FALSE , 0);
        }

        // Record the Name of the Tag Group used for this IFD in the output array
        $OutputArray[ 'Tags Name' ] = $Tag_Definitions_Name;

        // Record the offset of the TIFF header in the output array
        $OutputArray[ 'Tiff Offset' ] = $Tiff_offset;

        // First 2 bytes of IFD are number of entries in the IFD
        $No_Entries_str = network_safe_fread( $filehnd, 2 );
        $No_Entries = get_IFD_Data_Type( $No_Entries_str, 3, $Byte_Align );


        // If the data is corrupt, the number of entries may be huge, which will cause errors
        // This is often caused by a lack of a Next-IFD pointer
        if ( $No_Entries> 10000 )
        {
                // Huge number of entries - abort
                echo "<p>Error: huge number of EXIF entries - EXIF is probably Corrupted</p>\n";

                return array ( FALSE , 0);
        }

        // If the data is corrupt or just stupid, the number of entries may zero,
        // Indicate this by returning false
        if ( $No_Entries === 0 )
        {
                // No entries - abort
                return array ( FALSE , 0);
        }

        // Save the file position where first IFD record starts as non-standard offsets
        // need to know this to calculate an absolute offset
        $IFD_first_rec_pos = ftell( $filehnd );


        // Read in the IFD structure
        $IFD_Data = network_safe_fread( $filehnd, 12 * $No_Entries );

        // Check if the entire IFD was able to be read
        if ( strlen( $IFD_Data ) != (12 * $No_Entries) )
        {
                // Couldn't read the IFD Data properly, Some Casio files have no Next IFD pointer, hence cause this error
                echo "<p>Error: EXIF Corrupted</p>\n";
                return array(FALSE, 0);
        }


        // Last 4 bytes of a standard IFD are the offset to the next IFD
        // Some NON-Standard IFD implementations do not have this, hence causing problems if it is read

        // If the Next IFD pointer has been requested to be read,
        if ( $read_next_ptr )
        {
                // Read the pointer to the next IFD

                $Next_Offset_str = network_safe_fread( $filehnd, 4 );
                $Next_Offset = get_IFD_Data_Type( $Next_Offset_str, 4, $Byte_Align );
        }
        else
        {
                // Otherwise set the pointer to zero ( no next IFD )
                $Next_Offset = 0;
        }



        // Initialise current position to the start
        $pos = 0;


        // Loop for reading IFD entries

        for ( $i = 0; $i < $No_Entries; $i++ )
        {
                // First 2 bytes of IFD entry are the tag number ( Unsigned Short )
                $Tag_No_str = substr( $IFD_Data, $pos, 2 );
                $Tag_No = get_IFD_Data_Type( $Tag_No_str, 3, $Byte_Align );
                $pos += 2;

                // Next 2 bytes of IFD entry are the data format ( Unsigned Short )
                $Data_Type_str = substr( $IFD_Data, $pos, 2 );
                $Data_Type = get_IFD_Data_Type( $Data_Type_str, 3, $Byte_Align );
                $pos += 2;

                // If Datatype is not between 1 and 12, then skip this entry, it is probably corrupted or custom
                if (( $Data_Type > 12 ) || ( $Data_Type < 1 ) )
                {
                        $pos += 8;
                        continue 1;  // Stop trying to process the tag any further and skip to the next one
                }

                // Next 4 bytes of IFD entry are the data count ( Unsigned Long )
                $Data_Count_str = substr( $IFD_Data, $pos, 4 );
                $Data_Count = get_IFD_Data_Type( $Data_Count_str, 4, $Byte_Align );
                $pos += 4;

                if ( $Data_Count > 100000 )
                {
                        echo "<p>Error: huge EXIF data count - EXIF is probably Corrupted</p>\n";

                        // Some Casio files have no Next IFD pointer, hence cause errors

                        return array ( FALSE , 0);
                }

                // Total Data size is the Data Count multiplied by the size of the Data Type
                $Total_Data_Size = $GLOBALS['IFD_Data_Sizes'][ $Data_Type ] * $Data_Count;

                $Data_Start_pos = -1;

                // If the total data size is larger than 4 bytes, then the data part is the offset to the real data
                if ( $Total_Data_Size > 4 )
                {
                        // Not enough room for data - offset provided instead
                        $Data_Offset_str = substr( $IFD_Data, $pos, 4 );
                        $Data_Start_pos = get_IFD_Data_Type( $Data_Offset_str, 4, $Byte_Align );


                        // In some NON-STANDARD makernotes, the offset is relative to the start of the current IFD entry
                        if ( $local_offsets )
                        {
                                // This is a NON-Standard IFD, seek relative to the start of the current tag
                                fseek( $filehnd, $IFD_first_rec_pos +  $pos - 8 + $Data_Start_pos );
                        }
                        else
                        {
                                // This is a normal IFD, seek relative to the start of the TIFF header
                                fseek( $filehnd, $Tiff_offset + $Data_Start_pos );
                        }

                        // Read the data block from the offset position
                        $DataStr = network_safe_fread( $filehnd, $Total_Data_Size );
                }
                else
                {
                        // The data block is less than 4 bytes, and is provided in the IFD entry, so read it
                        $DataStr = substr( $IFD_Data, $pos, $Total_Data_Size );
                }

                // Increment the position past the data
                $pos += 4;


                // Now create the entry for output array

                $Data_Array = array( );


                // Read the data items from the data block

                if ( ( $Data_Type != 2 ) && ( $Data_Type != 7 ) )
                {
                        // The data type is Numerical, Read the data items from the data block
                        for ( $j = 0; $j < $Data_Count; $j++ )
                        {
                                $Part_Data_Str = substr( $DataStr, $j * $GLOBALS['IFD_Data_Sizes'][ $Data_Type ], $GLOBALS['IFD_Data_Sizes'][ $Data_Type ] );
                                $Data_Array[] = get_IFD_Data_Type( $Part_Data_Str, $Data_Type, $Byte_Align );
                        }
                }
                elseif ( $Data_Type == 2 )
                {
                        // The data type is String(s)   (type 2)

                        // Strip the last terminating Null
                        $DataStr = substr( $DataStr, 0, strlen($DataStr)-1 );

                        // Split the data block into multiple strings whereever there is a Null
                        $Data_Array = explode( "\x00", $DataStr );
                }
                else
                {
                        // The data type is Unknown (type 7)
                        // Do nothing to data
                        $Data_Array = $DataStr;
                }


                // If this is a Sub-IFD entry,
                if ( ( array_key_exists( $Tag_No, $GLOBALS[ "IFD_Tag_Definitions" ][$Tag_Definitions_Name] ) ) &&
                     ( "SubIFD" == $GLOBALS[ "IFD_Tag_Definitions" ][$Tag_Definitions_Name][ $Tag_No ]['Type'] ) )
                {
                        // This is a Sub-IFD entry, go and process the data forming Sub-IFD and use its output array as the new data for this entry
                        fseek( $filehnd, $Tiff_offset + $Data_Array[0] );
                        $Data_Array = read_Multiple_IFDs( $filehnd, $Tiff_offset, $Byte_Align, $GLOBALS[ "IFD_Tag_Definitions" ][$Tag_Definitions_Name][ $Tag_No ]['Tags Name'] );
                }

                $desc = "";
                $units = "";

                // Check if this tag exists in the list of tag definitions,

                if ( array_key_exists ( $Tag_No, $GLOBALS[ "IFD_Tag_Definitions" ][$Tag_Definitions_Name]) )
                {

                        if ( array_key_exists ( 'Description', $GLOBALS[ "IFD_Tag_Definitions" ][$Tag_Definitions_Name][ $Tag_No ] ) )
                        {
                                $desc = $GLOBALS[ "IFD_Tag_Definitions" ][$Tag_Definitions_Name][ $Tag_No ]['Description'];
                        }

                        if ( array_key_exists ( 'Units', $GLOBALS[ "IFD_Tag_Definitions" ][$Tag_Definitions_Name][ $Tag_No ] ) )
                        {
                                $units = $GLOBALS[ "IFD_Tag_Definitions" ][$Tag_Definitions_Name][ $Tag_No ]['Units'];
                        }

                        // Tag exists in definitions, append details to output array
                        $OutputArray[ $Tag_No ] = array (       "Tag Number"      => $Tag_No,
                                                                "Tag Name"        => $GLOBALS[ "IFD_Tag_Definitions" ][$Tag_Definitions_Name][ $Tag_No ]['Name'],
                                                                "Tag Description" => $desc,
                                                                "Data Type"       => $Data_Type,
                                                                "Type"            => $GLOBALS[ "IFD_Tag_Definitions" ][$Tag_Definitions_Name][ $Tag_No ]['Type'],
                                                                "Units"           => $units,
                                                                "Data"            => $Data_Array );

                }
                else
                {
                        // Tag doesnt exist in definitions, append unknown details to output array

                        $OutputArray[ $Tag_No ] = array (       "Tag Number"      => $Tag_No,
                                                                "Tag Name"        => "Unknown Tag #" . $Tag_No,
                                                                "Tag Description" => "",
                                                                "Data Type"       => $Data_Type,
                                                                "Type"            => "Unknown",
                                                                "Units"           => "",
                                                                "Data"            => $Data_Array );
                }



                // Some information of type "Unknown" (type 7) might require information about
                // how it's position and byte alignment in order to be decoded
                if ( $Data_Type == 7 )
                {
                        $OutputArray[ $Tag_No ]['Offset'] = $Data_Start_pos;
                        $OutputArray[ $Tag_No ]['Byte Align'] = $Byte_Align;
                }


                ////////////////////////////////////////////////////////////////////////
                // Special Data handling
                ////////////////////////////////////////////////////////////////////////


                // Check if this is a Print Image Matching entry
                if ( $OutputArray[ $Tag_No ]['Type'] == "PIM" )
                {
                        // This is a Print Image Matching entry, decode it.
                        $OutputArray[ $Tag_No ] = Decode_PIM( $OutputArray[ $Tag_No ], $Tag_Definitions_Name );
                }


                // Interpret the entry into a text string using a custom interpreter
                $text_val = get_Tag_Text_Value( $OutputArray[ $Tag_No ], $Tag_Definitions_Name );

                // Check if a text string was generated
                if ( $text_val !== FALSE )
                {
                        // A string was generated, append it to the output array entry
                        $OutputArray[ $Tag_No ]['Text Value'] = $text_val;
                        $OutputArray[ $Tag_No ]['Decoded'] = TRUE;
                }
                else
                {
                        // A string was NOT generated, append a generic string to the output array entry
                        $OutputArray[ $Tag_No ]['Text Value'] = get_IFD_value_as_text( $OutputArray[ $Tag_No ] )  . " " . $units;
                        $OutputArray[ $Tag_No ]['Decoded'] = FALSE;
                }




                // Check if this entry is the Maker Note
                if ( ( $Tag_Definitions_Name == "EXIF" ) && ( $Tag_No == 37500 ) )
                {

                        // Save some extra information which will allow Makernote Decoding with the output array entry
                        $OutputArray[ $Tag_No ]['Offset'] = $Data_Start_pos;
                        $OutputArray[ $Tag_No ][ 'Tiff Offset' ] = $Tiff_offset;
                        $OutputArray[ $Tag_No ]['ByteAlign'] = $Byte_Align;

                        // Save a pointer to this entry for Maker note processing later
                        $GLOBALS[ "Maker_Note_Tag" ] = & $OutputArray[ $Tag_No ];
                }


                // Check if this is a IPTC/NAA Record within the EXIF IFD
                if ( ( ( $Tag_Definitions_Name == "EXIF" ) || ( $Tag_Definitions_Name == "TIFF" ) ) &&
                     ( $Tag_No == 33723 ) )
                {
                        // This is a IPTC/NAA Record, interpret it and put result in the data for this entry
                        $OutputArray[ $Tag_No ]['Data'] = get_IPTC( $DataStr );
                        $OutputArray[ $Tag_No ]['Decoded'] = TRUE;
                }
                // Change: Check for embedded XMP as of version 1.11
                // Check if this is a XMP Record within the EXIF IFD
                if ( ( ( $Tag_Definitions_Name == "EXIF" ) || ( $Tag_Definitions_Name == "TIFF" ) ) &&
                     ( $Tag_No == 700 ) )
                {
                        // This is a XMP Record, interpret it and put result in the data for this entry
                        $OutputArray[ $Tag_No ]['Data'] =  read_XMP_array_from_text( $DataStr );
                        $OutputArray[ $Tag_No ]['Decoded'] = TRUE;
                }

                // Change: Check for embedded IRB as of version 1.11
                // Check if this is a Photoshop IRB Record within the EXIF IFD
                if ( ( ( $Tag_Definitions_Name == "EXIF" ) || ( $Tag_Definitions_Name == "TIFF" ) ) &&
                     ( $Tag_No == 34377 ) )
                {
                        // This is a Photoshop IRB Record, interpret it and put result in the data for this entry
                        $OutputArray[ $Tag_No ]['Data'] = unpack_Photoshop_IRB_Data( $DataStr );
                        $OutputArray[ $Tag_No ]['Decoded'] = TRUE;
                }

                // Exif Thumbnail
                // Check that both the thumbnail length and offset entries have been processed,
                // and that this is one of them
                if ( ( ( ( $Tag_No == 513 ) && ( array_key_exists( 514, $OutputArray ) ) ) ||
                       ( ( $Tag_No == 514 ) && ( array_key_exists( 513, $OutputArray ) ) ) )  &&
                     ( $Tag_Definitions_Name == "TIFF" ) )
                {
                        // Seek to the start of the thumbnail using the offset entry
                        fseek( $filehnd, $Tiff_offset + $OutputArray[513]['Data'][0] );

                        // Read the thumbnail data, and replace the offset data with the thumbnail
                        $OutputArray[513]['Data'] = network_safe_fread( $filehnd, $OutputArray[514]['Data'][0] );
                }


                // Casio Thumbnail
                // Check that both the thumbnail length and offset entries have been processed,
                // and that this is one of them
                if ( ( ( ( $Tag_No == 0x0004 ) && ( array_key_exists( 0x0003, $OutputArray ) ) ) ||
                       ( ( $Tag_No == 0x0003 ) && ( array_key_exists( 0x0004, $OutputArray ) ) ) )  &&
                     ( $Tag_Definitions_Name == "Casio Type 2" ) )
                {
                        // Seek to the start of the thumbnail using the offset entry
                        fseek( $filehnd, $Tiff_offset + $OutputArray[0x0004]['Data'][0] );

                        // Read the thumbnail data, and replace the offset data with the thumbnail
                        $OutputArray[0x0004]['Data'] = network_safe_fread( $filehnd, $OutputArray[0x0003]['Data'][0] );
                }

                // Minolta Thumbnail
                // Check that both the thumbnail length and offset entries have been processed,
                // and that this is one of them
                if ( ( ( ( $Tag_No == 0x0088 ) && ( array_key_exists( 0x0089, $OutputArray ) ) ) ||
                       ( ( $Tag_No == 0x0089 ) && ( array_key_exists( 0x0088, $OutputArray ) ) ) )  &&
                     ( $Tag_Definitions_Name == "Olympus" ) )
                {

                        // Seek to the start of the thumbnail using the offset entry
                        fseek( $filehnd, $Tiff_offset + $OutputArray[0x0088]['Data'][0] );

                        // Read the thumbnail data, and replace the offset data with the thumbnail
                        $OutputArray[0x0088]['Data'] = network_safe_fread( $filehnd, $OutputArray[0x0089]['Data'][0] );

                        // Sometimes the minolta thumbnail data is empty (or the offset is corrupt, which results in the same thing)

                        // Check if the thumbnail data exists
                        if ( $OutputArray[0x0088]['Data'] != "" )
                        {
                                // Thumbnail exists

                                // Minolta Thumbnails are missing their first 0xFF for some reason,
                                // which is replaced with some weird character, so fix this
                                $OutputArray[0x0088]['Data']{0} = "\xFF";
                        }
                        else
                        {
                                // Thumbnail doesnt exist - make it obvious
                                $OutputArray[0x0088]['Data'] = FALSE;
                        }
                }

        }







        // Return the array of IFD entries and the offset to the next IFD

        return array ($OutputArray , $Next_Offset);
}



/******************************************************************************
* End of Function:     read_IFD_universal
******************************************************************************/












/******************************************************************************
*
* Internal Function:     get_Tag_Text_Value
*
* Description:  Attempts to interpret an IFD entry into a text string using the
*               information in the IFD_Tag_Definitions global array.
*
* Parameters:   Tag - The IFD entry to process
*               Tag_Definitions_Name - The name of the tag definitions to use from within the IFD_Tag_Definitions global array
*
* Returns:      String - if the tag was successfully decoded into a text string
*               FALSE - if the tag could not be decoded using the information
*                       in the IFD_Tag_Definitions global array
*
******************************************************************************/

function get_Tag_Text_Value( $Tag, $Tag_Definitions_Name )
{
        // Check what format the entry is specified as

        if ( $Tag['Type'] == "String" )
        {
                // Format is Text String

                // If "Unknown" (type 7) data type,
                if ( $Tag['Data Type'] == 7 )
                {
                        // Return data as is.
                        return $Tag['Data'];
                }
                else
                {
                        // Otherwise return the default string value of the datatype
                        return get_IFD_value_as_text( $Tag );
                }
        }
        else if ( $Tag['Type'] == "Character Coded String" )
        {
                // Format is Character Coded String (First 8 characters indicate coding scheme)

                // Convert Data to a string
                if ( $Tag['Data Type'] == 7 )
                {
                        // If it is type "Unknown" (type 7) use data as is
                        $data =  $Tag['Data'];
                }
                else
                {
                        // Otherwise use the default string value of the datatype
                        $data = get_IFD_value_as_text( $Tag );
                }

                // Some implementations allow completely data with no Coding Scheme Name,
                // so we need to handle this to avoid errors
                if ( trim( $data ) == "" )
                {
                        return "";
                }

                // Extract the Coding Scheme Name from the first 8 characters
                $char_code = substr( $data, 0, 8 );

                // Extract the Data part from after the first 8 characters
                $characters = substr( $data, 8 );

                // Check coding scheme and interpret as neccessary

                if ( $char_code === "ASCII\x00\x00\x00" )
                {
                        // ASCII coding - return data as is.
                        return $characters;
                }
                elseif ( ( $char_code === "UNICODE\x00" ) ||
                         ( $char_code === "Unicode\x00" ) )             // Note lowercase is non standard
                {
                        // Unicode coding - interpret and return result.
                        return xml_UTF16_clean( $characters, TRUE );
                }
                else
                {
                        // Unknown coding - return string indicating this
                        return "Unsupported character coding : \"$char_code\"\n\"" . trim($characters) . "\"";
                }
                break;
        }
        else if ( $Tag['Type'] == "Numeric" )
        {
                // Format is numeric - return default text value with any required units text appended
                if ( array_key_exists ( 'Units', $GLOBALS[ "IFD_Tag_Definitions" ][$Tag_Definitions_Name][ $Tag["Tag Number"] ] ) )
                {
                        $units = $GLOBALS[ "IFD_Tag_Definitions" ][$Tag_Definitions_Name][ $Tag["Tag Number"] ]['Units'];
                }
                else
                {
                        $units = "";
                }
                return get_IFD_value_as_text( $Tag )  . " " . $units;
        }
        else if  ( $Tag['Type'] == "Lookup" )
        {
                // Format is a Lookup Table

                // Get a numeric value to use in lookup

                if ( is_array( $Tag['Data'] ) )
                {
                        // If data is an array, use first element
                        $first_val = $Tag['Data'][0];
                }
                else if ( is_string( $Tag['Data'] ) )
                {
                        // If data is a string, use the first character
                        $first_val = ord($Tag['Data']{0});
                }
                else
                {
                        // Otherwise use the data as is
                        $first_val = $Tag['Data'];
                }

                // Check if the data value exists in the lookup table for this IFD entry
                if ( array_key_exists( $first_val, $GLOBALS[ "IFD_Tag_Definitions" ][$Tag_Definitions_Name][ $Tag["Tag Number"] ] ) )
                {
                        // Data value exists in lookup table - return the matching string
                        return $GLOBALS[ "IFD_Tag_Definitions" ][$Tag_Definitions_Name][ $Tag["Tag Number"] ][ $first_val ];
                }
                else
                {
                        // Data value doesnt exist in lookup table - return explanation string
                        return "Unknown Reserved value $first_val ";
                }
        }
        else if  ( $Tag['Type'] == "Special" )
        {
                // Format is special - interpret to text with special handlers
                return get_Special_Tag_Text_Value( $Tag, $Tag_Definitions_Name );
        }
        else if  ( $Tag['Type'] == "PIM" )
        {
                // Format is Print Image Matching info - interpret with custom handler
                return get_PIM_Text_Value( $Tag, $Tag_Definitions_Name );
        }
        else if  ( $Tag['Type'] == "SubIFD" )
        {
                // Format is a Sub-IFD - this has no text value
                return "";
        }
        else
        {
                // Unknown Format - Couldn't interpret using the IFD_Tag_Definitions global array information
                return FALSE;
        }
}

/******************************************************************************
* End of Function:     get_Tag_Text_Value
******************************************************************************/






/******************************************************************************
*
* Internal Function:     get_Special_Tag_Text_Value
*
* Description:  Interprets an IFD entry marked as "Special" in the IFD_Tag_Definitions
*               global array into a text string using custom handlers
*
* Parameters:   Tag - The IFD entry to process
*               Tag_Definitions_Name - The name of the tag definitions to use from within the IFD_Tag_Definitions global array
*
* Returns:      String - if the tag was successfully decoded into a text string
*               FALSE - if the tag could not be decoded
*
******************************************************************************/

function get_Special_Tag_Text_Value( $Tag, $Tag_Definitions_Name )
{
        // Check what type of IFD is being decoded

        if ( $Tag_Definitions_Name == "TIFF" )
        {
                // This is a TIFF IFD (bottom level)

                // Check what tag number the IFD entry has.
                switch ( $Tag['Tag Number'] )
                {
                        case 530:  // YCbCr Sub Sampling Entry

                                // Data contains two numerical values

                                if ( ( $Tag['Data'][0] == 2 ) && ( $Tag['Data'][1] == 1 ) )
                                {
                                        // Values are 2,1 - hence YCbCr 4:2:2
                                        return "YCbCr 4:2:2 ratio of chrominance components to the luminance components";
                                }
                                elseif ( ( $Tag['Data'][0] == 2 ) && ( $Tag['Data'][1] == 2 ) )
                                {
                                        // Values are 2,2 - hence YCbCr 4:2:0
                                        return "YCbCr 4:2:0 ratio of chrominance components to the luminance components";
                                }
                                else
                                {
                                        // Other values are unknown
                                        return "Unknown Reserved value (" . $Tag['Data'][0] . ")";
                                }
                                break;

                        default:
                                return FALSE;
                }
        }
        else if ( $Tag_Definitions_Name == "EXIF" )
        {
                // This is an EXIF IFD

                // Check what tag number the IFD entry has.
                switch ( $Tag['Tag Number'] )
                {

                        case 37121: // Components configuration

                                // Data contains 4 numerical values indicating component type

                                $output_str = "";

                                // Cycle through each component
                                for ( $Num = 0; $Num < 4; $Num++ )
                                {
                                        // Construct first part of text string
                                        $output_str .= "Component " . ( $Num + 1 ) . ": ";

                                        // Construct second part of text string via
                                        // lookup using numerical value

                                        $value = ord( $Tag['Data']{$Num} );
                                        switch( $value )
                                        {
                                                case 0:
                                                        $output_str .= "Does not exist\n";
                                                        break;
                                                case 1:
                                                        $output_str .= "Y (Luminance)\n";
                                                        break;
                                                case 2:
                                                        $output_str .= "Cb (Chroma minus Blue)\n";
                                                        break;
                                                case 3:
                                                        $output_str .= "Cr (Chroma minus Red)\n";
                                                        break;
                                                case 4:
                                                        $output_str .= "Red\n";
                                                        break;
                                                case 5:
                                                        $output_str .= "Green\n";
                                                        break;
                                                case 6:
                                                        $output_str .= "Blue\n";
                                                        break;
                                                default:
                                                        $output_str .= "Unknown value $value\n";
                                        };
                                }

                                // Return the completed string

                                return $output_str;
                                break;



                        case 41730: // Colour Filter Array Pattern

                                // The first two characters are a SHORT for Horizontal repeat pixel unit -
                                $n_max = get_IFD_Data_Type( substr( $Tag['Data'], 0, 2 ), 3, $Tag['Byte Align'] );

                                // The next two characters are a SHORT for Vertical repeat pixel unit -
                                $m_max = get_IFD_Data_Type( substr( $Tag['Data'], 2, 2 ), 3, $Tag['Byte Align'] );


                                // At least one camera type appears to have byte reversed values for N_Max and M_Max
                                // Check if they need reversing
                                if ( $n_max > 256 )
                                {
                                        $n_max = $n_max/256 + 256*($n_max%256);
                                }

                                if ( $m_max > 256 )
                                {
                                        $m_max = $m_max/256 + 256*($m_max%256);
                                }


                                $output_str = "";


                                // Cycle through all the elements in the resulting 2 dimensional array,
                                for( $m = 1; $m <= $m_max; $m++ )
                                {
                                        for( $n = 1; $n <= $n_max; $n++ )
                                        {

                                                // Append text from a lookup table according to
                                                // the value read for this element

                                                switch ( ord($Tag['Data']{($n_max*($m-1)+$n+3)}) )
                                                {
                                                        case 0:
                                                                $output_str .= "RED     ";
                                                                break;
                                                        case 1:
                                                                $output_str .= "GREEN   ";
                                                                break;
                                                        case 2:
                                                                $output_str .= "BLUE    ";
                                                                break;
                                                        case 3:
                                                                $output_str .= "CYAN    ";
                                                                break;
                                                        case 4:
                                                                $output_str .= "MAGENTA ";
                                                                break;
                                                        case 5:
                                                                $output_str .= "YELLOW  ";
                                                                break;
                                                        case 6:
                                                                $output_str .= "WHITE   ";
                                                                break;
                                                        default:
                                                                $output_str .= "Unknown ";
                                                                break;
                                                };
                                        };
                                        $output_str .= "\n";
                                };

                                // Return the resulting string
                                return $output_str;
                                break;

                        default:
                                return FALSE;
                }
        }
        else
        {
                // Unknown IFD type, see if it is part of a makernote
                return get_Makernote_Text_Value( $Tag, $Tag_Definitions_Name );
        }


}

/******************************************************************************
* End of Function:     get_Tag_Text_Value
******************************************************************************/








/******************************************************************************
*
* Function:     interpret_IFD
*
* Description:  Generates html detailing the contents a single IFD.
*
* Parameters:   IFD_array - the array containing an IFD
*               filename - the name of the Image file being processed ( used
*                          by scripts which displays EXIF thumbnails)
*
* Returns:      output_str - A string containing the HTML
*
******************************************************************************/

function interpret_IFD( $IFD_array, $filename )
{
        // Create the output string with the table tag
        $output_str = "<table class=\"EXIF_Table\" border=1>\n";

        // Create an extra output string to receive any supplementary html
        // which cannot go inside the table
        $extra_IFD_str = "";

        // Check that the IFD array is valid
        if ( ( $IFD_array === FALSE ) || ( $IFD_array === NULL ) )
        {
                // the IFD array is NOT valid - exit
                return "";
        }

        // Check if this is an EXIF IFD and if there is a makernote present
        if ( ( $IFD_array['Tags Name'] === "EXIF" ) &&
             ( ! array_key_exists( 37500, $IFD_array ) ) )
        {

                // This is an EXIF IFD but NO makernote is present - Add a message to the output
                $extra_IFD_str .= "<h3 class=\"EXIF_Secondary_Heading\">No Makernote Present</h3>";
        }

        // Cycle through each tag in the IFD

        foreach( $IFD_array as $Tag_ID => $Exif_Tag )
        {

                // Ignore the non numeric elements - they aren't tags
                if ( ! is_numeric ( $Tag_ID ) )
                {
                        // Skip Tags Name
                }
                        // Check if the Tag has been decoded successfully
                else if ( $Exif_Tag['Decoded'] == TRUE )
                {
                        // This tag has been successfully decoded

                        // Table cells won't get drawn with nothing in them -
                        // Ensure that at least a non breaking space exists in them

                        if ( trim($Exif_Tag['Text Value']) == "" )
                        {
                                $Exif_Tag['Text Value'] = "&nbsp;";
                        }

                        // Check if the tag is a sub-IFD
                        if ( $Exif_Tag['Type'] == "SubIFD" )
                        {
                                // This is a sub-IFD tag
                                // Add a sub-heading for the sub-IFD
                                $extra_IFD_str .= "<h3 class=\"EXIF_Secondary_Heading\">" . $Exif_Tag['Tag Name'] . " contents</h3>";

                                // Cycle through each sub-IFD in the chain
                                foreach ( $Exif_Tag['Data'] as $subIFD )
                                {
                                        // Interpret this sub-IFD and add the html to the secondary output
                                        $extra_IFD_str .= interpret_IFD( $subIFD, $filename );
                                }
                        }
                                // Check if the tag is a makernote
                        else if ( $Exif_Tag['Type'] == "Maker Note" )
                        {
                                // This is a Makernote Tag
                                // Add a sub-heading for the Makernote
                                $extra_IFD_str .= "<h3 class=\"EXIF_Secondary_Heading\">Maker Note Contents</h3>";

                                // Interpret the Makernote and add the html to the secondary output
                                $extra_IFD_str .= Interpret_Makernote_to_HTML( $Exif_Tag, $filename );
                        }
                                // Check if this is a IPTC/NAA Record within the EXIF IFD
                        else if ( $Exif_Tag['Type'] == "IPTC" )
                        {
                                // This is a IPTC/NAA Record, interpret it and output to the secondary html
                                $extra_IFD_str .= "<h3 class=\"EXIF_Secondary_Heading\">Contains IPTC/NAA Embedded in EXIF</h3>";
                                $extra_IFD_str .=Interpret_IPTC_to_HTML( $Exif_Tag['Data'] );
                        }
                                // Change: Check for embedded XMP as of version 1.11
                                // Check if this is a XMP Record within the EXIF IFD
                        else if ( $Exif_Tag['Type'] == "XMP" )
                        {
                                // This is a XMP Record, interpret it and output to the secondary html
                                $extra_IFD_str .= "<h3 class=\"EXIF_Secondary_Heading\">Contains XMP Embedded in EXIF</h3>";
                                $extra_IFD_str .= Interpret_XMP_to_HTML( $Exif_Tag['Data'] );
                        }
                                // Change: Check for embedded IRB as of version 1.11
                                // Check if this is a Photoshop IRB Record within the EXIF IFD
                        else if ( $Exif_Tag['Type'] == "IRB" )
                        {
                                // This is a Photoshop IRB Record, interpret it and output to the secondary html
                                $extra_IFD_str .= "<h3 class=\"EXIF_Secondary_Heading\">Contains Photoshop IRB Embedded in EXIF</h3>";
                                $extra_IFD_str .= Interpret_IRB_to_HTML( $Exif_Tag['Data'], $filename );
                        }
                                // Check if the tag is Numeric
                        else if ( $Exif_Tag['Type'] == "Numeric" )
                        {
                                // Numeric Tag - Output text value as is.
                                $output_str .= "<tr class=\"EXIF_Table_Row\"><td class=\"EXIF_Caption_Cell\">" . $Exif_Tag['Tag Name'] . "</td><td class=\"EXIF_Value_Cell\">" . $Exif_Tag['Text Value'] . "</td></tr>\n";
                        }
                        else
                        {
                                // Other tag - Output text as preformatted
                                $output_str .= "<tr class=\"EXIF_Table_Row\"><td class=\"EXIF_Caption_Cell\">" . $Exif_Tag['Tag Name'] . "</td><td class=\"EXIF_Value_Cell\"><pre>" . trim( $Exif_Tag['Text Value']) . "</pre></td></tr>\n";
                        }

                }
                else
                {
                        // Tag has NOT been decoded successfully
                        // Hence it is either an unknown tag, or one which
                        // requires processing at the time of html construction

                        // Table cells won't get drawn with nothing in them -
                        // Ensure that at least a non breaking space exists in them

                        if ( trim($Exif_Tag['Text Value']) == "" )
                        {
                                $Exif_Tag['Text Value'] = "&nbsp;";
                        }

                        // Check if this tag is the first IFD Thumbnail
                        if ( ( $IFD_array['Tags Name'] == "TIFF" ) &&
                             ( $Tag_ID == 513 ) )
                        {
                                // This is the first IFD thumbnail - Add html to the output

                                // Change: as of version 1.11 - Changed to make thumbnail link portable across directories
                                // Build the path of the thumbnail script and its filename parameter to put in a url
                                $link_str = get_relative_path( dirname(__FILE__) . "/get_exif_thumb.php" , getcwd ( ) );
                                $link_str .= "?filename=";
                                $link_str .= get_relative_path( $filename, dirname(__FILE__) );

                                // Add thumbnail link to html
                                $output_str .= "<tr class=\"EXIF_Table_Row\"><td class=\"EXIF_Caption_Cell\">" . $Exif_Tag['Tag Name'] . "</td><td class=\"EXIF_Value_Cell\"><a class=\"EXIF_First_IFD_Thumb_Link\" href=\"$link_str\"><img class=\"EXIF_First_IFD_Thumb\" src=\"$link_str\"></a></td></tr>\n";
                        }
                                // Check if this is the Makernote
                        else if ( $Exif_Tag['Type'] == "Maker Note" )
                        {
                                // This is the makernote, but has not been decoded
                                // Add a message to the secondary output
                                $extra_IFD_str .= "<h3 class=\"EXIF_Secondary_Heading\">Makernote Coding Unknown</h3>\n";
                        }
                        else
                        {
                                // This is an Unknown Tag

                                // Check if the user wants to hide unknown tags
                                if ( $GLOBALS['HIDE_UNKNOWN_TAGS'] === FALSE )
                                {
                                        // User wants to display unknown tags

                                        // Check if the Data is an ascii string
                                        if ( $Exif_Tag['Data Type'] == 2 )
                                        {
                                                // This is a Ascii String field - add it preformatted to the output
                                                $output_str .= "<tr class=\"EXIF_Table_Row\"><td class=\"EXIF_Caption_Cell\">" . $Exif_Tag['Tag Name'] . "</td><td class=\"EXIF_Value_Cell\"><pre>" . trim( $Exif_Tag['Text Value'] ) . "</pre></td></tr>\n";
                                        }
                                        else
                                        {
                                                // Not an ASCII string - add it as is to the output
                                                $output_str .= "<tr class=\"EXIF_Table_Row\"><td class=\"EXIF_Caption_Cell\">" . $Exif_Tag['Tag Name'] . "</td><td class=\"EXIF_Value_Cell\">" . trim( $Exif_Tag['Text Value'] ) . "</td></tr>\n";
                                        }
                                }
                        }
                }
        }

        // Close the table in the output
        $output_str .= "</table>\n";

        // Add the secondary output at the end of the main output
        $output_str .= "$extra_IFD_str\n";

        // Return the resulting html
        return $output_str;
}

/******************************************************************************
* End of Function:     interpret_IFD
******************************************************************************/















/******************************************************************************
*
* Function:     get_IFD_Data_Type
*
* Description:  Decodes an IFD field value from a binary data string, using
*               information supplied about the data type and byte alignment of
*               the stored data.
*               This function should be used for all datatypes except ASCII strings
*
* Parameters:   input_data - a binary data string containing the IFD value,
*                            must be exact length of the value
*               data_type - a number representing the IFD datatype as per the
*                           TIFF 6.0 specification:
*                               1 = Unsigned 8-bit Byte
*                               2 = ASCII String
*                               3 = Unsigned 16-bit Short
*                               4 = Unsigned 32-bit Long
*                               5 = Unsigned 2x32-bit Rational
*                               6 = Signed 8-bit Byte
*                               7 = Undefined
*                               8 = Signed 16-bit Short
*                               9 = Signed 32-bit Long
*                               10 = Signed 2x32-bit Rational
*                               11 = 32-bit Float
*                               12 = 64-bit Double
*               Byte_Align - Indicates the byte alignment of the data.
*                            MM = Motorola, MSB first, Big Endian
*                            II = Intel, LSB first, Little Endian
*
* Returns:      output - the value of the data (string or numeric)
*
******************************************************************************/

function get_IFD_Data_Type( $input_data, $data_type, $Byte_Align )
{
        // Check if this is a Unsigned Byte, Unsigned Short or Unsigned Long
        if (( $data_type == 1 ) || ( $data_type == 3 ) || ( $data_type == 4 ))
        {
                // This is a Unsigned Byte, Unsigned Short or Unsigned Long

                // Check the byte alignment to see if the bytes need tp be reversed
                if ( $Byte_Align == "II" )
                {
                        // This is in Intel format, reverse it
                        $input_data = strrev ( $input_data );
                }

                // Convert the binary string to a number and return it
                return hexdec( bin2hex( $input_data ) );
        }
                // Check if this is a ASCII string type
        elseif ( $data_type == 2 )
        {
                // Null terminated ASCII string(s)
                // The input data may represent multiple strings, as the
                // 'count' field represents the total bytes, not the number of strings
                // Hence this should not be processed here, as it would have
                // to return multiple values instead of a single value

                echo "<p>Error - ASCII Strings should not be processed in get_IFD_Data_Type</p>\n";
                return "Error Should never get here"; //explode( "\x00", $input_data );
        }
                // Check if this is a Unsigned rational type
        elseif ( $data_type == 5 )
        {
                // This is a Unsigned rational type

                // Check the byte alignment to see if the bytes need to be reversed
                if ( $Byte_Align == "MM" )
                {
                        // Motorola MSB first byte aligment
                        // Unpack the Numerator and denominator and return them
                        return unpack( 'NNumerator/NDenominator', $input_data );
                }
                else
                {
                        // Intel LSB first byte aligment
                        // Unpack the Numerator and denominator and return them
                        return unpack( 'VNumerator/VDenominator', $input_data );
                }
        }
                // Check if this is a Signed Byte, Signed Short or Signed Long
        elseif ( ( $data_type == 6 ) || ( $data_type == 8 ) || ( $data_type == 9 ) )
        {
                // This is a Signed Byte, Signed Short or Signed Long

                // Check the byte alignment to see if the bytes need to be reversed
                if ( $Byte_Align == "II" )
                {
                        //Intel format, reverse the bytes
                        $input_data = strrev ( $input_data );
                }

                // Convert the binary string to an Unsigned number
                $value = hexdec( bin2hex( $input_data ) );

                // Convert to signed number

                // Check if it is a Byte above 128 (i.e. a negative number)
                if ( ( $data_type == 6 ) && ( $value > 128 ) )
                {
                        // number should be negative - make it negative
                        return  $value - 256;
                }

                // Check if it is a Short above 32767 (i.e. a negative number)
                if ( ( $data_type == 8 ) && ( $value > 32767 ) )
                {
                        // number should be negative - make it negative
                        return  $value - 65536;
                }

                // Check if it is a Long above 2147483648 (i.e. a negative number)
                if ( ( $data_type == 9 ) && ( $value > 2147483648 ) )
                {
                        // number should be negative - make it negative
                        return  $value - 4294967296;
                }

                // Return the signed number
                return $value;
        }
                // Check if this is Undefined type
        elseif ( $data_type == 7 )
        {
                // Custom Data - Do nothing
                return $input_data;
        }
                // Check if this is a Signed Rational type
        elseif ( $data_type == 10 )
        {
                // This is a Signed Rational type

                // Signed Long not available with endian in unpack , use unsigned and convert

                // Check the byte alignment to see if the bytes need to be reversed
                if ( $Byte_Align == "MM" )
                {
                        // Motorola MSB first byte aligment
                        // Unpack the Numerator and denominator
                        $value = unpack( 'NNumerator/NDenominator', $input_data );
                }
                else
                {
                        // Intel LSB first byte aligment
                        // Unpack the Numerator and denominator
                        $value = unpack( 'VNumerator/VDenominator', $input_data );
                }

                // Convert the numerator to a signed number
                // Check if it is above 2147483648 (i.e. a negative number)
                if ( $value['Numerator'] > 2147483648 )
                {
                        // number is negative
                        $value['Numerator'] -= 4294967296;
                }

                // Convert the denominator to a signed number
                // Check if it is above 2147483648 (i.e. a negative number)
                if ( $value['Denominator'] > 2147483648 )
                {
                        // number is negative
                        $value['Denominator'] -= 4294967296;
                }

                // Return the Signed Rational value
                return $value;
        }
                // Check if this is a Float type
        elseif ( $data_type == 11 )
        {
                // IEEE 754 Float
                // TODO - EXIF - IFD datatype Float not implemented yet
                return "FLOAT NOT IMPLEMENTED YET";
        }
                // Check if this is a Double type
        elseif ( $data_type == 12 )
        {
                // IEEE 754 Double
                // TODO - EXIF - IFD datatype Double not implemented yet
                return "DOUBLE NOT IMPLEMENTED YET";
        }
        else
        {
                // Error - Invalid Datatype
                return "Invalid Datatype $data_type";

        }

}

/******************************************************************************
* End of Function:     get_IFD_Data_Type
******************************************************************************/






/******************************************************************************
*
* Function:     put_IFD_Data_Type
*
* Description:  Encodes an IFD field from a value to a binary data string, using
*               information supplied about the data type and byte alignment of
*               the stored data.
*
* Parameters:   input_data - an IFD data value, numeric or string
*               data_type - a number representing the IFD datatype as per the
*                           TIFF 6.0 specification:
*                               1 = Unsigned 8-bit Byte
*                               2 = ASCII String
*                               3 = Unsigned 16-bit Short
*                               4 = Unsigned 32-bit Long
*                               5 = Unsigned 2x32-bit Rational
*                               6 = Signed 8-bit Byte
*                               7 = Undefined
*                               8 = Signed 16-bit Short
*                               9 = Signed 32-bit Long
*                               10 = Signed 2x32-bit Rational
*                               11 = 32-bit Float
*                               12 = 64-bit Double
*               Byte_Align - Indicates the byte alignment of the data.
*                            MM = Motorola, MSB first, Big Endian
*                            II = Intel, LSB first, Little Endian
*
* Returns:      output - the packed binary string of the data
*
******************************************************************************/

function put_IFD_Data_Type( $input_data, $data_type, $Byte_Align )
{
        // Process according to the datatype
        switch ( $data_type )
        {
                case 1: // Unsigned Byte - return character as is
                        return chr($input_data);
                        break;

                case 2: // ASCII String
                        // Return the string with terminating null
                        return $input_data . "\x00";
                        break;

                case 3: // Unsigned Short
                        // Check byte alignment
                        if ( $Byte_Align == "II" )
                        {
                                // Intel/Little Endian - pack the short and return
                                return pack( "v", $input_data );
                        }
                        else
                        {
                                // Motorola/Big Endian - pack the short and return
                                return pack( "n", $input_data );
                        }
                        break;

                case 4: // Unsigned Long
                        // Check byte alignment
                        if ( $Byte_Align == "II" )
                        {
                                // Intel/Little Endian - pack the long and return
                                return pack( "V", $input_data );
                        }
                        else
                        {
                                // Motorola/Big Endian - pack the long and return
                                return pack( "N", $input_data );
                        }
                        break;

                case 5: // Unsigned Rational
                        // Check byte alignment
                        if ( $Byte_Align == "II" )
                        {
                                // Intel/Little Endian - pack the two longs and return
                                return pack( "VV", $input_data['Numerator'], $input_data['Denominator'] );
                        }
                        else
                        {
                                // Motorola/Big Endian - pack the two longs and return
                                return pack( "NN", $input_data['Numerator'], $input_data['Denominator'] );
                        }
                        break;

                case 6: // Signed Byte
                        // Check if number is negative
                        if ( $input_data < 0 )
                        {
                                // Number is negative - return signed character
                                return chr( $input_data + 256 );
                        }
                        else
                        {
                                // Number is positive - return character
                                return chr( $input_data );
                        }
                        break;

                case 7: // Unknown - return as is
                        return $input_data;
                        break;

                case 8: // Signed Short
                        // Check if number is negative
                        if (  $input_data < 0 )
                        {
                                // Number is negative - make signed value
                                $input_data = $input_data + 65536;
                        }
                        // Check byte alignment
                        if ( $Byte_Align == "II" )
                        {
                                // Intel/Little Endian - pack the short and return
                                return pack( "v", $input_data );
                        }
                        else
                        {
                                // Motorola/Big Endian - pack the short and return
                                return pack( "n", $input_data );
                        }
                        break;

                case 9: // Signed Long
                        // Check if number is negative
                        if (  $input_data < 0 )
                        {
                                // Number is negative - make signed value
                                $input_data = $input_data + 4294967296;
                        }
                        // Check byte alignment
                        if ( $Byte_Align == "II" )
                        {
                                // Intel/Little Endian - pack the long and return
                                return pack( "v", $input_data );
                        }
                        else
                        {
                                // Motorola/Big Endian - pack the long and return
                                return pack( "n", $input_data );
                        }
                        break;

                case 10: // Signed Rational
                        // Check if numerator is negative
                        if (  $input_data['Numerator'] < 0 )
                        {
                                // Number is numerator - make signed value
                                $input_data['Numerator'] = $input_data['Numerator'] + 4294967296;
                        }
                        // Check if denominator is negative
                        if (  $input_data['Denominator'] < 0 )
                        {
                                // Number is denominator - make signed value
                                $input_data['Denominator'] = $input_data['Denominator'] + 4294967296;
                        }
                        // Check byte alignment
                        if ( $Byte_Align == "II" )
                        {
                                // Intel/Little Endian - pack the two longs and return
                                return pack( "VV", $input_data['Numerator'], $input_data['Denominator'] );
                        }
                        else
                        {
                                // Motorola/Big Endian - pack the two longs and return
                                return pack( "NN", $input_data['Numerator'], $input_data['Denominator'] );
                        }
                        break;

                case 11: // Float
                        // IEEE 754 Float
                        // TODO - EXIF - IFD datatype Float not implemented yet
                        return "FLOAT NOT IMPLEMENTED YET";
                        break;

                case 12: // Double
                        // IEEE 754 Double
                        // TODO - EXIF - IFD datatype Double not implemented yet
                        return "DOUBLE NOT IMPLEMENTED YET";
                        break;

                default:
                        // Error - Invalid Datatype
                        return "Invalid Datatype $data_type";
                        break;

        }

        // Shouldn't get here
        return FALSE;
}

/******************************************************************************
* End of Function:     put_IFD_Data_Type
******************************************************************************/





/******************************************************************************
*
* Function:     get_IFD_value_as_text
*
* Description:  Decodes an IFD field value from a binary data string, using
*               information supplied about the data type and byte alignment of
*               the stored data.
*               This function should be used for all datatypes except ASCII strings
*
* Parameters:   input_data - a binary data string containing the IFD value,
*                            must be exact length of the value
*               data_type - a number representing the IFD datatype as per the
*                           TIFF 6.0 specification:
*                               1 = Unsigned 8-bit Byte
*                               2 = ASCII String
*                               3 = Unsigned 16-bit Short
*                               4 = Unsigned 32-bit Long
*                               5 = Unsigned 2x32-bit Rational
*                               6 = Signed 8-bit Byte
*                               7 = Undefined
*                               8 = Signed 16-bit Short
*                               9 = Signed 32-bit Long
*                               10 = Signed 2x32-bit Rational
*                               11 = 32-bit Float
*                               12 = 64-bit Double
*               Byte_Align - Indicates the byte alignment of the data.
*                            MM = Motorola, MSB first, Big Endian
*                            II = Intel, LSB first, Little Endian
*
* Returns:      output - the value of the data (string or numeric)
*
******************************************************************************/

function get_IFD_value_as_text( $Exif_Tag )
{
        // Create a string to receive the output text
        $output_str = "";

        // Select Processing method according to the datatype
        switch  ($Exif_Tag['Data Type'])
        {
                case 1 : // Unsigned Byte
                case 3 : // Unsigned Short
                case 4 : // Unsigned Long
                case 6 : // Signed Byte
                case 8 : // Signed Short
                case 9 : // Signed Long

                        // Cycle through each of the values for this tag
                        foreach ( $Exif_Tag['Data'] as $val )
                        {
                                // Check that this isn't the first value,
                                if ( $output_str != "" )
                                {
                                        // This isn't the first value, Add a Comma and Newline to the output
                                        $output_str .= ",\n";
                                }
                                // Add the Value to the output
                                $output_str .= $val;
                        }
                        break;

                case 2 : // ASCII
                        // Append all the strings together, separated by Newlines
                        $output_str .= implode ( "\n", $Exif_Tag['Data']);
                        break;

                case 5 : // Unsigned Rational
                case 10: // Signed Rational

                        // Cycle through each of the values for this tag
                        foreach ( $Exif_Tag['Data'] as $val )
                        {
                                // Check that this isn't the first value,
                                if ( $output_str != "" )
                                {
                                        // This isn't the first value, Add a Comma and Newline to the output
                                        $output_str .= ",\n";
                                }

                                // Add the Full Value to the output
                                $output_str .= $val['Numerator'] ."/" . $val['Denominator'];

                                // Check if division by zero might be a problem
                                if ( $val['Denominator'] != 0 )
                                {
                                        // Denominator is not zero, Add the Decimal Value to the output text
                                        $output_str .= " (" . ($val['Numerator'] / $val['Denominator']) . ")";
                                }
                        }
                        break;

                case 11: // Float
                case 12: // Double
                        // TODO - EXIF - IFD datatype Double and Float not implemented yet
                        $output_str .= "Float and Double not implemented yet";
                        break;

                case 7 : // Undefined
                        // Unless the User has asked to see the raw binary data, this
                        // type should not be displayed

                        // Check if the user has requested to see the binary data in hex
                        if ( $GLOBALS['SHOW_BINARY_DATA_HEX'] == TRUE)
                        {
                                // User has requested to see the binary data in hex
                                // Add the value in hex
                                $output_str .= "( " . strlen( $Exif_Tag['Data'] ) . " bytes of binary data ): " . bin2hex( $Exif_Tag['Data'] )  ;
                        }
                                // Check if the user has requested to see the binary data as is
                        else if ( $GLOBALS['SHOW_BINARY_DATA_TEXT'] == TRUE)
                        {
                                // User has requested to see the binary data as is
                                // Add the value as is
                                $output_str .= "( " . strlen( $Exif_Tag['Data'] ) . " bytes of binary data ): " . $Exif_Tag['Data']  ;
                        }
                        else
                        {
                                // User has NOT requested to see binary data,
                                // Add a message indicating the number of bytes to the output
                                $output_str .= "( " . strlen( $Exif_Tag['Data'] ) . " bytes of binary data ) "  ;
                        }
                        break;

                default :
                        // Error - Unknown IFD datatype
                        $output_str .= "Error - Exif tag data type (" . $Exif_Tag['Data Type'] .") is invalid";
                        break;
        }

        // Return the resulting text string
        return $output_str;
}

/******************************************************************************
* End of Function:     get_IFD_value_as_text
******************************************************************************/




/******************************************************************************
* Global Variable:      IFD_Data_Sizes
*
* Contents:     The sizes (in bytes) of each EXIF IFD Datatype, indexed by
*               their datatype number
*
******************************************************************************/

$GLOBALS['IFD_Data_Sizes'] = array(     1 => 1,         // Unsigned Byte
                                        2 => 1,         // ASCII String
                                        3 => 2,         // Unsigned Short
                                        4 => 4,         // Unsigned Long
                                        5 => 8,         // Unsigned Rational
                                        6 => 1,         // Signed Byte
                                        7 => 1,         // Undefined
                                        8 => 2,         // Signed Short
                                        9 => 4,         // Signed Long
                                        10 => 8,        // Signed Rational
                                        11 => 4,        // Float
                                        12 => 8 );      // Double

/******************************************************************************
* End of Global Variable:     IFD_Data_Sizes
******************************************************************************/






?>