<?php

/******************************************************************************
*
* Filename:     Photoshop_IRB.php
*
* Description:  Provides functions for reading and writing information to/from
*               the 'App 13' Photoshop Information Resource Block segment of
*               JPEG format files
*
* Author:       Evan Hunter
*
* Date:         23/7/2004
*
* Project:      PHP JPEG Metadata Toolkit
*
* Revision:     1.11
*
* Changes:      1.00 -> 1.02 : changed get_Photoshop_IRB to work with corrupted
*                              resource names which Photoshop can still read
*               1.02 -> 1.03 : Fixed put_Photoshop_IRB to output "Photoshop 3.0\x00"
*                              string with every APP13 segment, not just the first one
*               1.03 -> 1.10 : changed get_Photoshop_IRB to fix processing of embedded resource names,
*                              after discovering that Photoshop does not process
*                              resource names according to the standard :
*                              "Adobe Photoshop 6.0 File Formats Specification, Version 6.0, Release 2, November 2000"
*                              This is an update to the change 1.00 -> 1.02, which was not fully correct
*                              changed put_Photoshop_IRB to fix the writing of embedded resource name,
*                              to avoid creating blank resources, and to fix a problem
*                              causing the IRB block to be incorrectly positioned if no APP segments existed.
*                              changed get_Photoshop_IPTC to initialise the output array correctly.
*               1.10 -> 1.11 : Moved code out of get_Photoshop_IRB into new function unpack_Photoshop_IRB_Data
*                              to allow reading of IRB blocks embedded within EXIF (for TIFF Files)
*                              Moved code out of put_Photoshop_IRB into new function pack_Photoshop_IRB_Data
*                              to allow writing of IRB blocks embedded within EXIF (for TIFF Files)
*                              Enabled the usage of $GLOBALS['HIDE_UNKNOWN_TAGS'] to hide unknown resources
*                              changed Interpret_IRB_to_HTML to allow thumbnail links to work when
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

// Change: as of version 1.11 - added to ensure the HIDE_UNKNOWN_TAGS variable is set even if EXIF.php is not included
if ( !isset( $GLOBALS['HIDE_UNKNOWN_TAGS'] ) )     $GLOBALS['HIDE_UNKNOWN_TAGS']= FALSE;

include_once 'IPTC.php';
include_once 'Unicode.php';



// TODO: Many Photoshop IRB resources not interpeted
// TODO: Obtain a copy of the Photoshop CS File Format Specification
// TODO: Find out what Photoshop IRB resources 1061, 1062 & 1064 are
// TODO: Test get_Photoshop_IRB and put_Photoshop_IRB with multiple APP13 segments

/******************************************************************************
*
* Function:     get_Photoshop_IRB
*
* Description:  Retrieves the Photoshop Information Resource Block (IRB) information
*               from an App13 JPEG segment and returns it as an array. This may
*               include IPTC-NAA IIM Information. Uses information
*               supplied by the get_jpeg_header_data function
*
* Parameters:   jpeg_header_data - a JPEG header data array in the same format
*                                  as from get_jpeg_header_data
*
* Returns:      IRBdata - The array of Photoshop IRB records
*               FALSE - if an APP 13 Photoshop IRB segment could not be found,
*                       or if an error occured
*
******************************************************************************/

function get_Photoshop_IRB( $jpeg_header_data )
{
        // Photoshop Image Resource blocks can span several JPEG APP13 segments, so we need to join them up if there are more than one
        $joined_IRB = "";


        //Cycle through the header segments
        for( $i = 0; $i < count( $jpeg_header_data ); $i++ )
        {
                // If we find an APP13 header,
                if ( strcmp ( $jpeg_header_data[$i]['SegName'], "APP13" ) == 0 )
                {
                        // And if it has the photoshop label,
                        if( strncmp ( $jpeg_header_data[$i]['SegData'], "Photoshop 3.0\x00", 14) == 0 )
                        {
                                // join it to the other previous IRB data
                                $joined_IRB .= substr ( $jpeg_header_data[$i]['SegData'], 14 );
                        }
                }
        }

        // If there was some Photoshop IRB information found,
        if ( $joined_IRB != "" )
        {
                // Found a Photoshop Image Resource Block - extract it.
                // Change: Moved code into unpack_Photoshop_IRB_Data to allow TIFF reading as of 1.11
                return unpack_Photoshop_IRB_Data( $joined_IRB );

        }
        else
        {
                // No Photoshop IRB found
                return FALSE;
        }

}

/******************************************************************************
* End of Function:     get_Photoshop_IRB
******************************************************************************/










/******************************************************************************
*
* Function:     put_Photoshop_IRB
*
* Description:  Adds or modifies the Photoshop Information Resource Block (IRB)
*               information from an App13 JPEG segment. If a Photoshop IRB already
*               exists, it is replaced, otherwise a new one is inserted, using the
*               supplied data. Uses information supplied by the get_jpeg_header_data
*               function
*
* Parameters:   jpeg_header_data - a JPEG header data array in the same format
*                                  as from get_jpeg_header_data
*               new_IRB_data - an array of the data to be stored in the Photoshop
*                              IRB segment. Should be in the same format as received
*                              from get_Photoshop_IRB
*
* Returns:      jpeg_header_data - the JPEG header data array with the
*                                  Photoshop IRB added.
*               FALSE - if an error occured
*
******************************************************************************/

function put_Photoshop_IRB( $jpeg_header_data, $new_IRB_data )
{
        // Delete all existing Photoshop IRB blocks - the new one will replace them

        //Cycle through the header segments
        for( $i = 0; $i < count( $jpeg_header_data ) ; $i++ )
        {
                // If we find an APP13 header,
                if ( strcmp ( $jpeg_header_data[$i]['SegName'], "APP13" ) == 0 )
                {
                        // And if it has the photoshop label,
                        if( strncmp ( $jpeg_header_data[$i]['SegData'], "Photoshop 3.0\x00", 14) == 0 )
                        {
                                // Delete the block information - it needs to be rebuilt
                                array_splice( $jpeg_header_data, $i, 1 );
                        }
                }
        }


        // Now we have deleted the pre-existing blocks


        // Retrieve the Packed Photoshop IRB Data
        // Change: Moved code into pack_Photoshop_IRB_Data to allow TIFF writing as of 1.11
        $packed_IRB_data = pack_Photoshop_IRB_Data( $new_IRB_data );

        // Change : This section changed to fix incorrect positioning of IRB segment, as of revision 1.10
        //          when there are no APP segments present

        //Cycle through the header segments in reverse order (to find where to put the APP13 block - after any APP0 to APP12 blocks)
        $i = count( $jpeg_header_data ) - 1;
        while (( $i >= 0 ) && ( ( $jpeg_header_data[$i]['SegType'] > 0xED ) || ( $jpeg_header_data[$i]['SegType'] < 0xE0 ) ) )
        {
                $i--;
        }



        // Cycle through the packed output data until it's size is less than 32000 bytes, outputting each 32000 byte block to an APP13 segment
        while ( strlen( $packed_IRB_data ) > 32000 )
        {
                // Change: Fixed put_Photoshop_IRB to output "Photoshop 3.0\x00" string with every APP13 segment, not just the first one, as of 1.03

                // Write a 32000 byte APP13 segment
                array_splice($jpeg_header_data, $i +1  , 0, array(  "SegType" => 0xED,
                                                                "SegName" => "APP13",
                                                                "SegDesc" => $GLOBALS[ "JPEG_Segment_Descriptions" ][ 0xED ],
                                                                "SegData" => "Photoshop 3.0\x00" . substr( $packed_IRB_data,0,32000) ) );

                // Delete the 32000 bytes from the packed output data, that were just output
                $packed_IRB_data = substr_replace($packed_IRB_data, '', 0, 32000);
                $i++;
        }

        // Write the last block of packed output data to an APP13 segment - Note array_splice doesn't work with multidimensional arrays, hence inserting a blank string
        array_splice($jpeg_header_data, $i + 1 , 0, "" );
        $jpeg_header_data[$i + 1] =  array( "SegType" => 0xED,
                                        "SegName" => "APP13",
                                        "SegDesc" => $GLOBALS[ "JPEG_Segment_Descriptions" ][ 0xED ],
                                        "SegData" => "Photoshop 3.0\x00" . $packed_IRB_data );

        return $jpeg_header_data;
}

/******************************************************************************
* End of Function:     put_Photoshop_IRB
******************************************************************************/








/******************************************************************************
*
* Function:     get_Photoshop_IPTC
*
* Description:  Retrieves IPTC-NAA IIM information from within a Photoshop
*               IRB (if it is present) and returns it in an array. Uses
*               information supplied by the get_jpeg_header_data function
*
* Parameters:   Photoshop_IRB_data - an array of Photoshop IRB records, as
*                                    returned from get_Photoshop_IRB
*
* Returns:      IPTC_Data_Out - The array of IPTC-NAA IIM records
*               FALSE - if an IPTC-NAA IIM record could not be found, or if
*                       an error occured
*
******************************************************************************/

function get_Photoshop_IPTC( $Photoshop_IRB_data )
{

        // Change: Initialise array correctly, as of revision 1.10
        $IPTC_Data_Out = array();

        //Cycle through the Photoshop 8BIM records looking for the IPTC-NAA record
        for( $i = 0; $i < count( $Photoshop_IRB_data ); $i++ )
        {
                // Check if each record is a IPTC record (which has id 0x0404)
                if ( $Photoshop_IRB_data[$i]['ResID']  == 0x0404 )
                {
                        // We've found an IPTC block - Decode it
                        $IPTC_Data_Out = get_IPTC( $Photoshop_IRB_data[$i]['ResData'] );
                }
        }

        // If there was no records put into the output array,
        if ( count( $IPTC_Data_Out ) == 0 )
        {
                // Then return false
                return FALSE;
        }
        else
        {
                // Otherwise return the array
                return $IPTC_Data_Out;
        }

}
/******************************************************************************
* End of Function:     get_Photoshop_IPTC
******************************************************************************/






/******************************************************************************
*
* Function:     put_Photoshop_IPTC
*
* Description:  Inserts a new IPTC-NAA IIM resource into a Photoshop
*               IRB, or replaces an the existing resource if one is present.
*               Uses information supplied by the get_Photoshop_IRB function
*
* Parameters:   Photoshop_IRB_data - an array of Photoshop IRB records, as
*                                    returned from get_Photoshop_IRB, into
*                                    which the IPTC-NAA IIM record will be inserted
*               new_IPTC_block - an array of IPTC-NAA records in the same format
*                                as those returned by get_Photoshop_IPTC
*
* Returns:      Photoshop_IRB_data - The Photoshop IRB array with the
*                                     IPTC-NAA IIM resource inserted
*
******************************************************************************/

function put_Photoshop_IPTC( $Photoshop_IRB_data, $new_IPTC_block )
{
        $iptc_block_pos = -1;

        //Cycle through the 8BIM records looking for the IPTC-NAA record
        for( $i = 0; $i < count( $Photoshop_IRB_data ); $i++ )
        {
                // Check if each record is a IPTC record (which has id 0x0404)
                if ( $Photoshop_IRB_data[$i]['ResID']  == 0x0404 )
                {
                        // We've found an IPTC block - save the position
                        $iptc_block_pos = $i;
                }
        }

        // If no IPTC block was found, create a new one
        if ( $iptc_block_pos == -1 )
        {
                // New block position will be at the end of the array
                $iptc_block_pos = count( $Photoshop_IRB_data );
        }


        // Write the new IRB resource to the Photoshop IRB array with no data
        $Photoshop_IRB_data[$iptc_block_pos] = array(   "ResID" =>   0x0404,
                                                        "ResName" => $GLOBALS['Photoshop_ID_Names'][ 0x0404 ],
                                                        "ResDesc" => $GLOBALS[ "Photoshop_ID_Descriptions" ][ 0x0404 ],
                                                        "ResEmbeddedName" => "\x00\x00",
                                                        "ResData" => put_IPTC( $new_IPTC_block ) );


        // Return the modified IRB
        return $Photoshop_IRB_data;
}

/******************************************************************************
* End of Function:     put_Photoshop_IPTC
******************************************************************************/








/******************************************************************************
*
* Function:     Interpret_IRB_to_HTML
*
* Description:  Generates html showing the information contained in a Photoshop
*               IRB data array, as retrieved with get_Photoshop_IRB, including
*               any IPTC-NAA IIM records found.
*
*               Please note that the following resource numbers are not currently
*               decoded: ( Many of these do not apply to JPEG images)
*               0x03E9, 0x03EE, 0x03EF, 0x03F0, 0x03F1, 0x03F2, 0x03F6, 0x03F9,
*               0x03FA, 0x03FB, 0x03FD, 0x03FE, 0x0400, 0x0401, 0x0402, 0x0405,
*               0x040E, 0x040F, 0x0410, 0x0412, 0x0413, 0x0415, 0x0416, 0x0417,
*               0x041B, 0x041C, 0x041D, 0x0BB7
*
*               ( Also these Obsolete resource numbers)
*               0x03E8, 0x03EB, 0x03FC, 0x03FF, 0x0403
*
*
* Parameters:   IRB_array - a Photoshop IRB data array as from get_Photoshop_IRB
*               filename - the name of the JPEG file being processed ( used
*                          by the script which displays the Photoshop thumbnail)
*
*
* Returns:      output_str - the HTML string
*
******************************************************************************/

function Interpret_IRB_to_HTML( $IRB_array, $filename )
{
        // Create a string to receive the HTML
        $output_str = "";

        // Check if the Photoshop IRB array is valid
        if ( $IRB_array !== FALSE )
        {

                // Create another string to receive secondary HTML to be appended at the end
                $secondary_output_str = "";

                // Add the Heading to the HTML
                $output_str .= "<h2 class=\"Photoshop_Main_Heading\">Contains Photoshop Information Resource Block (IRB)</h2>";

                // Add Table to the HTML
                $output_str .= "<table class=\"Photoshop_Table\" border=1>\n";

                // Cycle through each of the Photoshop IRB records, creating HTML for each
                foreach( $IRB_array as $IRB_Resource )
                {
                        // Check if the entry is a known Photoshop IRB resource

                        // Get the Name of the Resource
                        if ( array_key_exists( $IRB_Resource['ResID'], $GLOBALS[ "Photoshop_ID_Names" ] ) )
                        {
                                $Resource_Name = $GLOBALS['Photoshop_ID_Names'][ $IRB_Resource['ResID'] ];
                        }
                        else
                        {
                                // Change: Added check for $GLOBALS['HIDE_UNKNOWN_TAGS'] to allow hiding of unknown resources as of 1.11
                                if ( $GLOBALS['HIDE_UNKNOWN_TAGS'] == TRUE )
                                {
                                        continue;
                                }
                                else
                                {
                                        // Unknown Resource - Make appropriate name
                                        $Resource_Name = "Unknown Resource (". $IRB_Resource['ResID'] .")";
                                }
                        }

                        // Add HTML for the resource as appropriate
                        switch ( $IRB_Resource['ResID'] )
                        {

                                case 0x0404 : // IPTC-NAA IIM Record
                                        $secondary_output_str .= Interpret_IPTC_to_HTML( get_IPTC( $IRB_Resource['ResData'] ) );
                                        break;

                                case 0x040B : // URL
                                        $output_str .= "<tr class=\"Photoshop_Table_Row\"><td class=\"Photoshop_Caption_Cell\">$Resource_Name</td><td class=\"Photoshop_Value_Cell\"><a href=\"" . $IRB_Resource['ResData'] . "\">" . htmlentities( $IRB_Resource['ResData'] ) ."</a></td></tr>\n";
                                        break;

                                case 0x040A : // Copyright Marked
                                        if ( hexdec( bin2hex( $IRB_Resource['ResData'] ) ) == 1 )
                                        {
                                                $output_str .= "<tr class=\"Photoshop_Table_Row\"><td class=\"Photoshop_Caption_Cell\">$Resource_Name</td><td class=\"Photoshop_Value_Cell\"><pre>Image is Copyrighted Material</pre></td></tr>\n";
                                        }
                                        else
                                        {
                                                $output_str .= "<tr class=\"Photoshop_Table_Row\"><td class=\"Photoshop_Caption_Cell\">$Resource_Name</td><td class=\"Photoshop_Value_Cell\"><pre>Image is Not Copyrighted Material</pre></td></tr>\n";
                                        }
                                        break;

                                case 0x040D : // Global Lighting Angle
                                        $output_str .= "<tr class=\"Photoshop_Table_Row\"><td class=\"Photoshop_Caption_Cell\">$Resource_Name</td><td class=\"Photoshop_Value_Cell\"><pre>Global lighting angle for effects layer = " . hexdec( bin2hex( $IRB_Resource['ResData'] ) ) . " degrees</pre></td></tr>\n";
                                        break;

                                case 0x0419 : // Global Altitude
                                        $output_str .= "<tr class=\"Photoshop_Table_Row\"><td class=\"Photoshop_Caption_Cell\">$Resource_Name</td><td class=\"Photoshop_Value_Cell\"><pre>Global Altitude = " . hexdec( bin2hex( $IRB_Resource['ResData'] ) ) . "</pre></td></tr>\n";
                                        break;

                                case 0x0421 : // Version Info
                                        $output_str .= "<tr class=\"Photoshop_Table_Row\"><td class=\"Photoshop_Caption_Cell\">$Resource_Name</td><td class=\"Photoshop_Value_Cell\"><pre>\n";
                                        $output_str .= "Version = " . hexdec( bin2hex( substr( $IRB_Resource['ResData'], 0, 4 ) ) ) . "\n";
                                        $output_str .= "Has Real Merged Data = " . ord( $IRB_Resource['ResData']{4} ) . "\n";
                                        $writer_size = hexdec( bin2hex( substr( $IRB_Resource['ResData'], 5, 4 ) ) ) * 2;

                                        $output_str .= "Writer Name = " . HTML_UTF16_Escape( substr( $IRB_Resource['ResData'], 9, $writer_size ), TRUE ) . "\n";
                                        $reader_size = hexdec( bin2hex( substr( $IRB_Resource['ResData'], 9 + $writer_size , 4 ) ) ) * 2;
                                        $output_str .= "Reader Name = " . HTML_UTF16_Escape( substr( $IRB_Resource['ResData'], 13 + $writer_size, $reader_size ), TRUE ) . "\n";
                                        $output_str .= "File Version = " . hexdec( bin2hex( substr( $IRB_Resource['ResData'], 13 + $writer_size + $reader_size, 4 ) ) ) . "\n";
                                        $output_str .=  "</pre></td></tr>\n";
                                        break;

                                case 0x0411 : // ICC Untagged
                                        if ( $IRB_Resource['ResData'] == "\x01" )
                                        {
                                                $output_str .= "<tr class=\"Photoshop_Table_Row\"><td class=\"Photoshop_Caption_Cell\">$Resource_Name</td><td class=\"Photoshop_Value_Cell\"><pre>Intentionally untagged - any assumed ICC profile handling disabled</pre></td></tr>\n";
                                        }
                                        else
                                        {
                                                $output_str .= "<tr class=\"Photoshop_Table_Row\"><td class=\"Photoshop_Caption_Cell\">$Resource_Name</td><td class=\"Photoshop_Value_Cell\"><pre>Unknown value (0x" .bin2hex( $IRB_Resource['ResData'] ). ")</pre></td></tr>\n";
                                        }
                                        break;

                                case 0x041A : // Slices
                                        $output_str .= "<tr class=\"Photoshop_Table_Row\"><td class=\"Photoshop_Caption_Cell\">$Resource_Name</td><td class=\"Photoshop_Value_Cell\">";

                                        // Unpack the first 24 bytes
                                        $Slices_Info = unpack("NVersion/NBound_top/NBound_left/NBound_bottom/NBound_right/NStringlen", $IRB_Resource['ResData'] );
                                        $output_str .= "Version = " . $Slices_Info['Version'] . "<br>\n";
                                        $output_str .= "Bounding Rectangle =  Top:" . $Slices_Info['Bound_top'] . ", Left:" . $Slices_Info['Bound_left'] . ", Bottom:" . $Slices_Info['Bound_bottom'] . ", Right:" . $Slices_Info['Bound_right'] . " (Pixels)<br>\n";
                                        $Slicepos = 24;

                                        // Extract a Unicode String
                                        $output_str .= "Text = '" . HTML_UTF16_Escape( substr( $IRB_Resource['ResData'], 24, $Slices_Info['Stringlen']*2), TRUE ) . "'<br>\n";
                                        $Slicepos += $Slices_Info['Stringlen'] * 2;

                                        // Unpack the number of Slices
                                        $Num_Slices = hexdec( bin2hex( substr( $IRB_Resource['ResData'], $Slicepos, 4 ) ) );
                                        $output_str .= "Number of Slices = " . $Num_Slices . "\n";
                                        $Slicepos += 4;

                                        // Cycle through the slices
                                        for( $i = 1; $i <= $Num_Slices; $i++ )
                                        {
                                                $output_str .= "<br><br>Slice $i:<br>\n";

                                                // Unpack the first 16 bytes of the slice
                                                $SliceA = unpack("NID/NGroupID/NOrigin/NStringlen", substr($IRB_Resource['ResData'], $Slicepos ) );
                                                $Slicepos += 16;
                                                $output_str .= "ID = " . $SliceA['ID'] . "<br>\n";
                                                $output_str .= "Group ID = " . $SliceA['GroupID'] . "<br>\n";
                                                $output_str .= "Origin = " . $SliceA['Origin'] . "<br>\n";

                                                // Extract a Unicode String
                                                $output_str .= "Text = '" . HTML_UTF16_Escape( substr( $IRB_Resource['ResData'], $Slicepos, $SliceA['Stringlen']*2), TRUE ) . "'<br>\n";
                                                $Slicepos += $SliceA['Stringlen'] * 2;

                                                // Unpack the next 24 bytes of the slice
                                                $SliceB = unpack("NType/NLeftPos/NTopPos/NRightPos/NBottomPos/NURLlen", substr($IRB_Resource['ResData'], $Slicepos )  );
                                                $Slicepos += 24;
                                                $output_str .= "Type = " . $SliceB['Type'] . "<br>\n";
                                                $output_str .= "Position =  Top:" . $SliceB['TopPos'] . ", Left:" . $SliceB['LeftPos'] . ", Bottom:" . $SliceB['BottomPos'] . ", Right:" . $SliceB['RightPos'] . " (Pixels)<br>\n";

                                                // Extract a Unicode String
                                                $output_str .= "URL = <a href='" . substr( $IRB_Resource['ResData'], $Slicepos, $SliceB['URLlen']*2) . "'>" . HTML_UTF16_Escape( substr( $IRB_Resource['ResData'], $Slicepos, $SliceB['URLlen']*2), TRUE ) . "</a><br>\n";
                                                $Slicepos += $SliceB['URLlen'] * 2;

                                                // Unpack the length of a Unicode String
                                                $Targetlen = hexdec( bin2hex( substr( $IRB_Resource['ResData'], $Slicepos, 4 ) ) );
                                                $Slicepos += 4;
                                                // Extract a Unicode String
                                                $output_str .= "Target = '" . HTML_UTF16_Escape( substr( $IRB_Resource['ResData'], $Slicepos, $Targetlen*2), TRUE ) . "'<br>\n";
                                                $Slicepos += $Targetlen * 2;

                                                // Unpack the length of a Unicode String
                                                $Messagelen = hexdec( bin2hex( substr( $IRB_Resource['ResData'], $Slicepos, 4 ) ) );
                                                $Slicepos += 4;
                                                // Extract a Unicode String
                                                $output_str .= "Message = '" . HTML_UTF16_Escape( substr( $IRB_Resource['ResData'], $Slicepos, $Messagelen*2), TRUE ) . "'<br>\n";
                                                $Slicepos += $Messagelen * 2;

                                                // Unpack the length of a Unicode String
                                                $AltTaglen = hexdec( bin2hex( substr( $IRB_Resource['ResData'], $Slicepos, 4 ) ) );
                                                $Slicepos += 4;
                                                // Extract a Unicode String
                                                $output_str .= "Alt Tag = '" . HTML_UTF16_Escape( substr( $IRB_Resource['ResData'], $Slicepos, $AltTaglen*2), TRUE ) . "'<br>\n";
                                                $Slicepos += $AltTaglen * 2;

                                                // Unpack the HTML flag
                                                if ( ord( $IRB_Resource['ResData']{ $Slicepos } ) === 0x01 )
                                                {
                                                        $output_str .= "Cell Text is HTML<br>\n";
                                                }
                                                else
                                                {
                                                        $output_str .= "Cell Text is NOT HTML<br>\n";
                                                }
                                                $Slicepos++;

                                                // Unpack the length of a Unicode String
                                                $CellTextlen = hexdec( bin2hex( substr( $IRB_Resource['ResData'], $Slicepos, 4 ) ) );
                                                $Slicepos += 4;
                                                // Extract a Unicode String
                                                $output_str .= "Cell Text = '" . HTML_UTF16_Escape( substr( $IRB_Resource['ResData'], $Slicepos, $CellTextlen*2), TRUE ) . "'<br>\n";
                                                $Slicepos += $CellTextlen * 2;


                                                // Unpack the last 12 bytes of the slice
                                                $SliceC = unpack("NAlignH/NAlignV/CAlpha/CRed/CGreen/CBlue", substr($IRB_Resource['ResData'], $Slicepos )  );
                                                $Slicepos += 12;
                                                $output_str .= "Alignment =  Horizontal:" . $SliceC['AlignH'] . ", Vertical:" . $SliceC['AlignV'] . "<br>\n";
                                                $output_str .= "Alpha Colour = " . $SliceC['Alpha'] . "<br>\n";
                                                $output_str .= "Red = " . $SliceC['Red'] . "<br>\n";
                                                $output_str .= "Green = " . $SliceC['Green'] . "<br>\n";
                                                $output_str .= "Blue = " . $SliceC['Blue'] . "\n";
                                        }

                                        $output_str .= "</td></tr>\n";

                                        break;


                                case 0x0408 : // Grid and Guides information
                                        $output_str .= "<tr class=\"Photoshop_Table_Row\"><td class=\"Photoshop_Caption_Cell\">$Resource_Name</td><td class=\"Photoshop_Value_Cell\">";

                                        // Unpack the Grids info
                                        $Grid_Info = unpack("NVersion/NGridCycleH/NGridCycleV/NGuideCount", $IRB_Resource['ResData'] );
                                        $output_str .= "Version = " . $Grid_Info['Version'] . "<br>\n";
                                        $output_str .= "Grid Cycle = " . $Grid_Info['GridCycleH']/32 . " Pixel(s)  x  " . $Grid_Info['GridCycleV']/32 . " Pixel(s)<br>\n";
                                        $output_str .= "Number of Guides = " . $Grid_Info['GuideCount'] . "\n";

                                        // Cycle through the Guides
                                        for( $i = 0; $i < $Grid_Info['GuideCount']; $i++ )
                                        {
                                                // Unpack the info for this guide
                                                $Guide_Info = unpack("NLocation/CDirection", substr($IRB_Resource['ResData'],16+$i*5,5) );
                                                $output_str .= "<br>Guide $i : Location = " . $Guide_Info['Location']/32 . " Pixel(s) from edge";
                                                if ( $Guide_Info['Direction'] === 0 )
                                                {
                                                        $output_str .= ", Vertical\n";
                                                }
                                                else
                                                {
                                                        $output_str .= ", Horizontal\n";
                                                }
                                        }
                                        break;
                                        $output_str .= "</td></tr>\n";

                                case 0x0406 : // JPEG Quality
                                        $Qual_Info = unpack("nQuality/nFormat/nScans/Cconst", $IRB_Resource['ResData'] );
                                        $output_str .= "<tr class=\"Photoshop_Table_Row\"><td class=\"Photoshop_Caption_Cell\">$Resource_Name</td><td class=\"Photoshop_Value_Cell\">";
                                        switch ( $Qual_Info['Quality'] )
                                        {
                                                case 0xFFFD:
                                                        $output_str .= "Quality 1 (Low)<br>\n";
                                                        break;
                                                case 0xFFFE:
                                                        $output_str .= "Quality 2 (Low)<br>\n";
                                                        break;
                                                case 0xFFFF:
                                                        $output_str .= "Quality 3 (Low)<br>\n";
                                                        break;
                                                case 0x0000:
                                                        $output_str .= "Quality 4 (Low)<br>\n";
                                                        break;
                                                case 0x0001:
                                                        $output_str .= "Quality 5 (Medium)<br>\n";
                                                        break;
                                                case 0x0002:
                                                        $output_str .= "Quality 6 (Medium)<br>\n";
                                                        break;
                                                case 0x0003:
                                                        $output_str .= "Quality 7 (Medium)<br>\n";
                                                        break;
                                                case 0x0004:
                                                        $output_str .= "Quality 8 (High)<br>\n";
                                                        break;
                                                case 0x0005:
                                                        $output_str .= "Quality 9 (High)<br>\n";
                                                        break;
                                                case 0x0006:
                                                        $output_str .= "Quality 10 (Maximum)<br>\n";
                                                        break;
                                                case 0x0007:
                                                        $output_str .= "Quality 11 (Maximum)<br>\n";
                                                        break;
                                                case 0x0008:
                                                        $output_str .= "Quality 12 (Maximum)<br>\n";
                                                        break;
                                                default:
                                                        $output_str .= "Unknown Quality (" . $Qual_Info['Quality'] . ")<br>\n";
                                                        break;
                                        }

                                        switch ( $Qual_Info['Format'] )
                                        {
                                                case 0x0000:
                                                        $output_str .= "Standard Format\n";
                                                        break;
                                                case 0x0001:
                                                        $output_str .= "Optimised Format\n";
                                                        break;
                                                case 0x0101:
                                                        $output_str .= "Progressive Format<br>\n";
                                                        break;
                                                default:
                                                        $output_str .= "Unknown Format (" . $Qual_Info['Format'] .")\n";
                                                        break;
                                        }
                                        if ( $Qual_Info['Format'] == 0x0101 )
                                        {
                                                switch ( $Qual_Info['Scans'] )
                                                {
                                                        case 0x0001:
                                                                $output_str .= "3 Scans\n";
                                                                break;
                                                        case 0x0002:
                                                                $output_str .= "4 Scans\n";
                                                                break;
                                                        case 0x0003:
                                                                $output_str .= "5 Scans\n";
                                                                break;
                                                        default:
                                                                $output_str .= "Unknown number of scans (" . $Qual_Info['Scans'] .")\n";
                                                                break;
                                                }
                                        }
                                        $output_str .= "</td></tr>\n";
                                        break;

                                case 0x0409 : // Thumbnail Resource
                                case 0x040C : // Thumbnail Resource
                                        $thumb_data = unpack("NFormat/NWidth/NHeight/NWidthBytes/NSize/NCompressedSize/nBitsPixel/nPlanes", $IRB_Resource['ResData'] );
                                        $output_str .= "<tr class=\"Photoshop_Table_Row\"><td class=\"Photoshop_Caption_Cell\">$Resource_Name</td><td class=\"Photoshop_Value_Cell\"><pre>\n";
                                        $output_str .= "Format = " . (( $thumb_data['Format'] == 1 ) ? "JPEG RGB\n" :  "Raw RGB\n");
                                        $output_str .= "Width = " . $thumb_data['Width'] . "\n";
                                        $output_str .= "Height = " . $thumb_data['Height'] . "\n";
                                        $output_str .= "Padded Row Bytes = " . $thumb_data['WidthBytes'] . " bytes\n";
                                        $output_str .= "Total Size = " . $thumb_data['Size'] . " bytes\n";
                                        $output_str .= "Compressed Size = " . $thumb_data['CompressedSize'] . " bytes\n";
                                        $output_str .= "Bits per Pixel = " . $thumb_data['BitsPixel'] . " bits\n";
                                        $output_str .= "Number of planes = " . $thumb_data['Planes'] . " bytes\n";

                                        // Change: as of version 1.11 - Changed to make thumbnail link portable across directories
                                        // Build the path of the thumbnail script and its filename parameter to put in a url
                                        $link_str = get_relative_path( dirname(__FILE__) . "/get_ps_thumb.php" , getcwd ( ) );
                                        $link_str .= "?filename=";
                                        $link_str .= get_relative_path( $filename, dirname(__FILE__) );

                                        // Add thumbnail link to html
                                        $output_str .= "Thumbnail Data:</pre><a class=\"Photoshop_Thumbnail_Link\" href=\"$link_str\"><img class=\"Photoshop_Thumbnail_Link\" src=\"$link_str\"></a>\n";

                                        $output_str .=  "</td></tr>\n";
                                        break;

                                case 0x0414 : // Document Specific ID's
                                        $output_str .= "<tr class=\"Photoshop_Table_Row\"><td class=\"Photoshop_Caption_Cell\">$Resource_Name</td><td class=\"Photoshop_Value_Cell\"><pre>" . hexdec( bin2hex( $IRB_Resource['ResData'] ) ) . "</pre></td></tr>\n";
                                        break;

                                case 0x041E : // URL List
                                        $URL_count = hexdec( bin2hex( substr( $IRB_Resource['ResData'], 0, 4 ) ) );
                                        $output_str .= "<tr class=\"Photoshop_Table_Row\"><td class=\"Photoshop_Caption_Cell\">$Resource_Name</td><td class=\"Photoshop_Value_Cell\">\n";
                                        $output_str .= "$URL_count URL's in list<br>\n";
                                        $urlstr = substr( $IRB_Resource['ResData'], 4 );
                                        // TODO: Check if URL List in Photoshop IRB works
                                        for( $i = 0; $i < $URL_count; $i++ )
                                        {
                                                $url_data = unpack( "NLong/NID/NURLSize", $urlstr );
                                                $output_str .= "URL $i info: long = " . $url_data['Long'] .", ";
                                                $output_str .= "ID = " . $url_data['ID'] . ", ";
                                                $urlstr = substr( $urlstr, 12 );
                                                $url = substr( $urlstr, 0, $url_data['URLSize'] );
                                                $output_str .= "URL = <a href=\"" . xml_UTF16_clean( $url, TRUE ) . "\">" . HTML_UTF16_Escape( $url, TRUE ) . "</a><br>\n";
                                        }
                                        $output_str .= "</td></tr>\n";
                                        break;
                                case 0x03F4 : // Grayscale and multichannel halftoning information.
                                        $output_str .= "<tr class=\"Photoshop_Table_Row\"><td class=\"Photoshop_Caption_Cell\">$Resource_Name</td><td class=\"Photoshop_Value_Cell\"><pre>\n";
                                        $output_str .= Interpret_Halftone( $IRB_Resource['ResData'] );
                                        $output_str .= "</pre></td></tr>\n";
                                        break;
                                case 0x03F5 : // Color halftoning information
                                        $output_str .= "<tr class=\"Photoshop_Table_Row\"><td class=\"Photoshop_Caption_Cell\">$Resource_Name</td><td class=\"Photoshop_Value_Cell\"><pre>\n";
                                        $output_str .= "Cyan Halftoning Info:\n" . Interpret_Halftone( substr( $IRB_Resource['ResData'], 0, 18 ) ) . "\n\n";
                                        $output_str .= "Magenta Halftoning Info:\n" . Interpret_Halftone( substr( $IRB_Resource['ResData'], 18, 18 ) ) . "\n\n";
                                        $output_str .= "Yellow Halftoning Info:\n" . Interpret_Halftone( substr( $IRB_Resource['ResData'], 36, 18 ) ) . "\n";
                                        $output_str .= "Black Halftoning Info:\n" . Interpret_Halftone( substr( $IRB_Resource['ResData'], 54, 18 ) ) . "\n";
                                        $output_str .= "</pre></td></tr>\n";
                                        break;

                                case 0x03F7 : // Grayscale and multichannel transfer function.
                                        $output_str .= "<tr class=\"Photoshop_Table_Row\"><td class=\"Photoshop_Caption_Cell\">$Resource_Name</td><td class=\"Photoshop_Value_Cell\"><pre>\n";
                                        $output_str .= Interpret_Transfer_Function( substr( $IRB_Resource['ResData'], 0, 28 ) ) ;
                                        $output_str .= "</pre></td></tr>\n";
                                        break;

                                case 0x03F8 : // Color transfer functions
                                        $output_str .= "<tr class=\"Photoshop_Table_Row\"><td class=\"Photoshop_Caption_Cell\">$Resource_Name</td><td class=\"Photoshop_Value_Cell\"><pre>\n";
                                        $output_str .= "Red Transfer Function:   \n" . Interpret_Transfer_Function( substr( $IRB_Resource['ResData'], 0, 28 ) ) . "\n\n";
                                        $output_str .= "Green Transfer Function: \n" . Interpret_Transfer_Function( substr( $IRB_Resource['ResData'], 28, 28 ) ) . "\n\n";
                                        $output_str .= "Blue Transfer Function:  \n" . Interpret_Transfer_Function( substr( $IRB_Resource['ResData'], 56, 28 ) ) . "\n";
                                        $output_str .= "</pre></td></tr>\n";
                                        break;

                                case 0x03F3 : // Print Flags
                                        $output_str .= "<tr class=\"Photoshop_Table_Row\"><td class=\"Photoshop_Caption_Cell\">$Resource_Name</td><td class=\"Photoshop_Value_Cell\"><pre>\n";
                                        if ( $IRB_Resource['ResData']{0} == "\x01" )
                                        {
                                                $output_str .= "Labels Selected\n";
                                        }
                                        else
                                        {
                                                $output_str .= "Labels Not Selected\n";
                                        }
                                        if ( $IRB_Resource['ResData']{1} == "\x01" )
                                        {
                                                $output_str .= "Crop Marks Selected\n";
                                        }
                                        else
                                        {
                                                $output_str .= "Crop Marks Not Selected\n";
                                        }
                                        if ( $IRB_Resource['ResData']{2} == "\x01" )
                                        {
                                                $output_str .= "Color Bars Selected\n";
                                        }
                                        else
                                        {
                                                $output_str .= "Color Bars Not Selected\n";
                                        }
                                        if ( $IRB_Resource['ResData']{3} == "\x01" )
                                        {
                                                $output_str .= "Registration Marks Selected\n";
                                        }
                                        else
                                        {
                                                $output_str .= "Registration Marks Not Selected\n";
                                        }
                                        if ( $IRB_Resource['ResData']{4} == "\x01" )
                                        {
                                                $output_str .= "Negative Selected\n";
                                        }
                                        else
                                        {
                                                $output_str .= "Negative Not Selected\n";
                                        }
                                        if ( $IRB_Resource['ResData']{5} == "\x01" )
                                        {
                                                $output_str .= "Flip Selected\n";
                                        }
                                        else
                                        {
                                                $output_str .= "Flip Not Selected\n";
                                        }
                                        if ( $IRB_Resource['ResData']{6} == "\x01" )
                                        {
                                                $output_str .= "Interpolate Selected\n";
                                        }
                                        else
                                        {
                                                $output_str .= "Interpolate Not Selected\n";
                                        }
                                        if ( $IRB_Resource['ResData']{7} == "\x01" )
                                        {
                                                $output_str .= "Caption Selected";
                                        }
                                        else
                                        {
                                                $output_str .= "Caption Not Selected";
                                        }
                                        $output_str .= "</pre></td></tr>\n";
                                        break;

                                case 0x2710 : // Print Flags Information
                                        $PrintFlags = unpack( "nVersion/CCentCrop/Cjunk/NBleedWidth/nBleedWidthScale", $IRB_Resource['ResData'] );
                                        $output_str .= "<tr class=\"Photoshop_Table_Row\"><td class=\"Photoshop_Caption_Cell\">$Resource_Name</td><td class=\"Photoshop_Value_Cell\"><pre>\n";
                                        $output_str .= "Version = " . $PrintFlags['Version'] . "\n";
                                        $output_str .= "Centre Crop Marks = " . $PrintFlags['CentCrop'] . "\n";
                                        $output_str .= "Bleed Width = " . $PrintFlags['BleedWidth'] . "\n";
                                        $output_str .= "Bleed Width Scale = " . $PrintFlags['BleedWidthScale'];
                                        $output_str .= "</pre></td></tr>\n";
                                        break;

                                case 0x03ED : // Resolution Info
                                        $ResInfo = unpack( "nhRes_int/nhResdec/nhResUnit/nwidthUnit/nvRes_int/nvResdec/nvResUnit/nheightUnit", $IRB_Resource['ResData'] );
                                        $output_str .= "<tr class=\"Photoshop_Table_Row\"><td class=\"Photoshop_Caption_Cell\">$Resource_Name</td><td class=\"Photoshop_Value_Cell\"><pre>\n";
                                        $output_str .= "Horizontal Resolution = " . ($ResInfo['hRes_int'] + $ResInfo['hResdec']/65536) . " pixels per Inch\n";
                                        $output_str .= "Vertical Resolution = " . ($ResInfo['vRes_int'] + $ResInfo['vResdec']/65536) . " pixels per Inch\n";
                                        if ( $ResInfo['hResUnit'] == 1 )
                                        {
                                                $output_str .= "Display units for Horizontal Resolution = Pixels per Inch\n";
                                        }
                                        elseif ( $ResInfo['hResUnit'] == 2 )
                                        {
                                                $output_str .= "Display units for Horizontal Resolution = Pixels per Centimetre\n";
                                        }
                                        else
                                        {
                                                $output_str .= "Display units for Horizontal Resolution = Unknown Value (". $ResInfo['hResUnit'] .")\n";
                                        }

                                        if ( $ResInfo['vResUnit'] == 1 )
                                        {
                                                $output_str .= "Display units for Vertical Resolution = Pixels per Inch\n";
                                        }
                                        elseif ( $ResInfo['vResUnit'] == 2 )
                                        {
                                                $output_str .= "Display units for Vertical Resolution = Pixels per Centimetre\n";
                                        }
                                        else
                                        {
                                                $output_str .= "Display units for Vertical Resolution = Unknown Value (". $ResInfo['vResUnit'] .")\n";
                                        }

                                        if ( $ResInfo['widthUnit'] == 1 )
                                        {
                                                $output_str .= "Display units for Image Width = Inches\n";
                                        }
                                        elseif ( $ResInfo['widthUnit'] == 2 )
                                        {
                                                $output_str .= "Display units for Image Width = Centimetres\n";
                                        }
                                        elseif ( $ResInfo['widthUnit'] == 3 )
                                        {
                                                $output_str .= "Display units for Image Width = Points\n";
                                        }
                                        elseif ( $ResInfo['widthUnit'] == 4 )
                                        {
                                                $output_str .= "Display units for Image Width = Picas\n";
                                        }
                                        elseif ( $ResInfo['widthUnit'] == 5 )
                                        {
                                                $output_str .= "Display units for Image Width = Columns\n";
                                        }
                                        else
                                        {
                                                $output_str .= "Display units for Image Width = Unknown Value (". $ResInfo['widthUnit'] .")\n";
                                        }

                                        if ( $ResInfo['heightUnit'] == 1 )
                                        {
                                                $output_str .= "Display units for Image Height = Inches";
                                        }
                                        elseif ( $ResInfo['heightUnit'] == 2 )
                                        {
                                                $output_str .= "Display units for Image Height = Centimetres";
                                        }
                                        elseif ( $ResInfo['heightUnit'] == 3 )
                                        {
                                                $output_str .= "Display units for Image Height = Points";
                                        }
                                        elseif ( $ResInfo['heightUnit'] == 4 )
                                        {
                                                $output_str .= "Display units for Image Height = Picas";
                                        }
                                        elseif ( $ResInfo['heightUnit'] == 5 )
                                        {
                                                $output_str .= "Display units for Image Height = Columns";
                                        }
                                        else
                                        {
                                                $output_str .= "Display units for Image Height = Unknown Value (". $ResInfo['heightUnit'] .")";
                                        }
                                        $output_str .= "</pre></td></tr>\n";
                                        break;

                                default : // All other records
                                        $output_str .= "<tr class=\"Photoshop_Table_Row\"><td class=\"Photoshop_Caption_Cell\">$Resource_Name</td><td class=\"Photoshop_Value_Cell\">RESOURCE DECODING NOT IMPLEMENTED YET<BR>" . strlen( $IRB_Resource['ResData'] ) . " bytes</td></tr>\n";

                        }

                }

                // Add the table end to the HTML
                $output_str .= "</table>\n";

                // Add any secondary output to the HTML
                $output_str .= $secondary_output_str;

        }

        // Return the HTML
        return $output_str;
}

/******************************************************************************
* End of Function:     Interpret_IRB_to_HTML
******************************************************************************/






/******************************************************************************
*
*         INTERNAL FUNCTIONS
*
******************************************************************************/







/******************************************************************************
*
* Function:     unpack_Photoshop_IRB_Data
*
* Description:  Extracts Photoshop Information Resource Block (IRB) information
*               from a binary string containing the IRB, as read from a file
*
* Parameters:   IRB_Data - The binary string containing the IRB
*
* Returns:      IRBdata - The array of Photoshop IRB records
*
******************************************************************************/

function unpack_Photoshop_IRB_Data( $IRB_Data )
{
        $pos = 0;

        // Cycle through the IRB and extract its records - Records are started with 8BIM, so cycle until no more instances of 8BIM can be found
        while ( ( $pos < strlen( $IRB_Data ) ) && ( ($pos = strpos( $IRB_Data, "8BIM", $pos) ) !== FALSE ) )
        {
                // Skip the position over the 8BIM characters
                $pos += 4;

                // Next two characters are the record ID - denoting what type of record it is.
                $ID = ord( $IRB_Data{ $pos } ) * 256 + ord( $IRB_Data{ $pos +1 } );

                // Skip the positionover the two record ID characters
                $pos += 2;

                // Next comes a Record Name - usually not used, but it should be a null terminated string, padded with 0x00 to be an even length
                $namestartpos = $pos;

                // Change: Fixed processing of embedded resource names, as of revision 1.10

                // NOTE: Photoshop does not process resource names according to the standard :
                // "Adobe Photoshop 6.0 File Formats Specification, Version 6.0, Release 2, November 2000"
                //
                // The resource name is actually formatted as follows:
                // One byte name length, followed by the null terminated ascii name string.
                // The field is then padded with a Null character if required, to ensure that the
                // total length of the name length and name is even.

                // Name - process it
                // Get the length
                $namelen = ord ( $IRB_Data{ $namestartpos } );

                // Total length of name and length info must be even, hence name length must be odd
                // Check if the name length is even,
                if ( $namelen % 2 == 0 )
                {
                        // add one to length to make it odd
                        $namelen ++;
                }
                // Extract the name
                $resembeddedname = trim( substr ( $IRB_Data, $namestartpos+1,  $namelen) );
                $pos += $namelen + 1;


                // Next is a four byte size field indicating the size in bytes of the record's data  - MSB first
                $datasize =     ord( $IRB_Data{ $pos } ) * 16777216 + ord( $IRB_Data{ $pos + 1 } ) * 65536 +
                                ord( $IRB_Data{ $pos + 2 } ) * 256 + ord( $IRB_Data{ $pos + 3 } );
                $pos += 4;

                // The record is stored padded with 0x00 characters to make the size even, so we need to calculate the stored size
                $storedsize =  $datasize + ($datasize % 2);

                $resdata = substr ( $IRB_Data, $pos, $datasize );

                // Get the description for this resource
                // Check if this is a Path information Resource, since they have a range of ID's
                if ( ( $ID >= 0x07D0 ) && ( $ID <= 0x0BB6 ) )
                {
                        $ResDesc = "ID Info : Path Information (saved paths).";
                }
                else
                {
                        if ( array_key_exists( $ID, $GLOBALS[ "Photoshop_ID_Descriptions" ] ) )
                        {
                                $ResDesc = $GLOBALS[ "Photoshop_ID_Descriptions" ][ $ID ];
                        }
                        else
                        {
                                $ResDesc = "";
                        }
                }

                // Get the Name of the Resource
                if ( array_key_exists( $ID, $GLOBALS[ "Photoshop_ID_Names" ] ) )
                {
                        $ResName = $GLOBALS['Photoshop_ID_Names'][ $ID ];
                }
                else
                {
                        $ResName = "";
                }


                // Store the Resource in the array to be returned

                $IRB_Array[] = array(     "ResID" => $ID,
                                        "ResName" => $ResName,
                                        "ResDesc" => $ResDesc,
                                        "ResEmbeddedName" => $resembeddedname,
                                        "ResData" => $resdata );

                // Jump over the data to the next record
                $pos += $storedsize;
        }

        // Return the array created
        return $IRB_Array;
}

/******************************************************************************
* End of Function:     unpack_Photoshop_IRB_Data
******************************************************************************/











/******************************************************************************
*
* Function:     pack_Photoshop_IRB_Data
*
* Description:  Packs a Photoshop Information Resource Block (IRB) array into it's
*               binary form, which can be written to a file
*
* Parameters:   IRB_data - an Photoshop IRB array to be converted. Should be in
*                          the same format as received from get_Photoshop_IRB
*
* Returns:      packed_IRB_data - the binary string of packed IRB data
*
******************************************************************************/

function pack_Photoshop_IRB_Data( $IRB_data )
{
        $packed_IRB_data = "";

        // Cycle through each resource in the IRB,
        foreach ($IRB_data as $resource)
        {

                // Change: Fix to avoid creating blank resources, as of revision 1.10

                // Check if there is actually any data for this resource
                if( strlen( $resource['ResData'] ) == 0 )
                {
                        // No data for resource - skip it
                        continue;
                }

                // Append the 8BIM tag, and resource ID to the packed output data
                $packed_IRB_data .= pack("a4n", "8BIM", $resource['ResID'] );


                // Change: Fixed processing of embedded resource names, as of revision 1.10

                // NOTE: Photoshop does not process resource names according to the standard :
                // "Adobe Photoshop 6.0 File Formats Specification, Version 6.0, Release 2, November 2000"
                //
                // The resource name is actually formatted as follows:
                // One byte name length, followed by the null terminated ascii name string.
                // The field is then padded with a Null character if required, to ensure that the
                // total length of the name length and name is even.

                // Append Name Size
                $packed_IRB_data .= pack( "c", strlen(trim($resource['ResEmbeddedName'])));

                // Append the Resource Name to the packed output data
                $packed_IRB_data .= trim($resource['ResEmbeddedName']);

                // If the resource name is even length, then with the addition of
                // the size it becomes odd and needs to be padded to an even number
                if ( strlen( trim($resource['ResEmbeddedName']) ) % 2 == 0 )
                {
                        // then it needs to be evened up by appending another null
                        $packed_IRB_data .= "\x00";
                }

                // Append the resource data size to the packed output data
                $packed_IRB_data .= pack("N", strlen( $resource['ResData'] ) );

                // Append the resource data to the packed output data
                $packed_IRB_data .= $resource['ResData'];

                // If the resource data is odd length,
                if ( strlen( $resource['ResData'] ) % 2 == 1 )
                {
                        // then it needs to be evened up by appending another null
                        $packed_IRB_data .= "\x00";
                }
        }

        // Return the packed data string
        return $packed_IRB_data;
}

/******************************************************************************
* End of Function:     pack_Photoshop_IRB_Data
******************************************************************************/








/******************************************************************************
*
* Internal Function:     Interpret_Transfer_Function
*
* Description:  Used by Interpret_IRB_to_HTML to interpret Color transfer functions
*               for Photoshop IRB resource 0x03F8. Converts the transfer function
*               information to a human readable version.
*
* Parameters:   Transfer_Function_Binary - a 28 byte Ink curves structure string
*
* Returns:      output_str - the text string containing the transfer function
*                            information
*
******************************************************************************/

function Interpret_Transfer_Function( $Transfer_Function_Binary )
{
        // Unpack the Transfer function information
        $Trans_vals = unpack ( "n13Curve/nOverride",  $Transfer_Function_Binary );

        $output_str = "Transfer Function Points: ";

        // Cycle through each of the Transfer function array values
        foreach ( $Trans_vals as $Key => $val )
        {
                // Check if the value should be negative
                if ($val > 32768 )
                {
                        // Value should be negative - make it so
                        $val = $val - 65536;
                }
                // Check that the Override item is not getting in this list, and
                // that the value is not -1, which means ignored
                if ( ( $Key != "Override" ) && ( $val != -1 ) )
                {
                        // This is a valid transfer function point, output it
                        $output_str .= $val/10 . "%, ";
                }
        }

        // Output the override info
        if ( $Trans_vals['Override'] == 0 )
        {
                $output_str .= "\nOverride: Let printer supply curve";
        }
        else
        {
                $output_str .= "\nOverride: Override printer�s default transfer curve";
        }

        // Return the result
        return $output_str;
}

/******************************************************************************
* End of Function:     Interpret_Transfer_Function
******************************************************************************/





/******************************************************************************
*
* Internal Function:     Interpret_Halftone
*
* Description:  Used by Interpret_IRB_to_HTML to interpret Color halftoning information
*               for Photoshop IRB resource 0x03F5. Converts the halftoning info
*               to a human readable version.
*
* Parameters:   Transfer_Function_Binary - a 18 byte Halftone screen parameter
&                                          structure string
*
* Returns:      output_str - the text string containing the transfer function
*                            information
*
******************************************************************************/

function Interpret_Halftone( $Halftone_Binary )
{
        // Create a string to receive the output
        $output_str = "";

        // Unpack the binary data into an array
        $HalftoneInfo = unpack( "nFreqVal_int/nFreqVal_dec/nFreqScale/nAngle_int/nAngle_dec/nShapeCode/NMisc/CAccurate/CDefault", $Halftone_Binary );

        // Interpret Ink Screen Frequency
        $output_str .= "Ink Screen Frequency = " . ($HalftoneInfo['FreqVal_int'] + $HalftoneInfo['FreqVal_dec']/65536) . " lines per Inch\n";
        if ( $HalftoneInfo['FreqScale'] == 1 )
        {
                $output_str .= "Display units for Ink Screen Frequency = Inches\n";
        }
        else
        {
                $output_str .= "Display units for Ink Screen Frequency = Centimetres\n";
        }

        // Interpret Angle for screen
        $output_str .= "Angle for screen = " . ($HalftoneInfo['Angle_int'] + $HalftoneInfo['Angle_dec']/65536) . " degrees\n";

        // Interpret Shape of Halftone Dots
        if ($HalftoneInfo['ShapeCode'] > 32768 )
        {
                $HalftoneInfo['ShapeCode'] = $HalftoneInfo['ShapeCode'] - 65536;
        }
        if ( $HalftoneInfo['ShapeCode'] == 0 )
        {
                $output_str .= "Shape of Halftone Dots = Round\n";
        }
        elseif ( $HalftoneInfo['ShapeCode'] == 1 )
        {
                $output_str .= "Shape of Halftone Dots = Ellipse\n";
        }
        elseif ( $HalftoneInfo['ShapeCode'] == 2 )
        {
                $output_str .= "Shape of Halftone Dots = Line\n";
        }
        elseif ( $HalftoneInfo['ShapeCode'] == 3 )
        {
                $output_str .= "Shape of Halftone Dots = Square\n";
        }
        elseif ( $HalftoneInfo['ShapeCode'] == 4 )
        {
                $output_str .= "Shape of Halftone Dots = Cross\n";
        }
        elseif ( $HalftoneInfo['ShapeCode'] == 6 )
        {
                $output_str .= "Shape of Halftone Dots = Diamond\n";
        }
        else
        {
                $output_str .= "Shape of Halftone Dots = Unknown shape (" . $HalftoneInfo['ShapeCode'] . ")\n";
        }

        // Interpret Accurate Screens
        if ( $HalftoneInfo['Accurate'] == 1 )
        {
                $output_str .= "Use Accurate Screens Selected\n";
        }
        else
        {
                $output_str .= "Use Other (not Accurate) Screens Selected\n";
        }

        // Interpret Printer Default Screens
        if ( $HalftoneInfo['Default'] == 1 )
        {
                $output_str .= "Use printer�s default screens\n";
        }
        else
        {
                $output_str .= "Use Other (not Printer Default) Screens Selected\n";
        }

        // Return Text
        return $output_str;

}

/******************************************************************************
* End of Global Variable:     Interpret_Halftone
******************************************************************************/












/******************************************************************************
* Global Variable:      Photoshop_ID_Names
*
* Contents:     The Names of the Photoshop IRB resources, indexed by their
*               resource number
*
******************************************************************************/

$GLOBALS[ "Photoshop_ID_Names" ] = array(
0x03E8 => "Number of channels, rows, columns, depth, and mode. (Obsolete)",
0x03E9 => "Macintosh print manager info ",
0x03EB => "Indexed color table (Obsolete)",
0x03ED => "Resolution Info",
0x03EE => "Alpha Channel Names",
0x03EF => "Display Info",
0x03F0 => "Caption String",
0x03F1 => "Border information",
0x03F2 => "Background color",
0x03F3 => "Print flags",
0x03F4 => "Grayscale and multichannel halftoning information",
0x03F5 => "Color halftoning information",
0x03F6 => "Duotone halftoning information",
0x03F7 => "Grayscale and multichannel transfer function",
0x03F8 => "Color transfer functions",
0x03F9 => "Duotone transfer functions",
0x03FA => "Duotone image information",
0x03FB => "Black and white values",
0x03FC => "Obsolete Resource.",
0x03FD => "EPS options",
0x03FE => "Quick Mask information",
0x03FF => "Obsolete Resource",
0x0400 => "Layer state information",
0x0401 => "Working path (not saved)",
0x0402 => "Layers group information",
0x0403 => "Obsolete Resource",
0x0404 => "IPTC-NAA record",
0x0405 => "Raw Format Image mode",
0x0406 => "JPEG quality",
0x0408 => "Grid and guides information",
0x0409 => "Thumbnail resource",
0x040A => "Copyright flag",
0x040B => "URL",
0x040C => "Thumbnail resource",
0x040D => "Global Angle",
0x040E => "Color samplers resource",
0x040F => "ICC Profile",
0x0410 => "Watermark",
0x0411 => "ICC Untagged",
0x0412 => "Effects visible",
0x0413 => "Spot Halftone",
0x0414 => "Document Specific IDs",
0x0415 => "Unicode Alpha Names",
0x0416 => "Indexed Color Table Count",
0x0417 => "Tansparent Index. Index of transparent color, if any.",
0x0419 => "Global Altitude",
0x041A => "Slices",
0x041B => "Workflow URL",
0x041C => "Jump To XPEP",
0x041D => "Alpha Identifiers",
0x041E => "URL List",
0x0421 => "Version Info",
0x0BB7 => "Name of clipping path.",
0x2710 => "Print flags information"
);

/******************************************************************************
* End of Global Variable:     Photoshop_ID_Names
******************************************************************************/





/******************************************************************************
* Global Variable:      Photoshop_ID_Descriptions
*
* Contents:     The Descriptions of the Photoshop IRB resources, indexed by their
*               resource number
*
******************************************************************************/

$GLOBALS[ "Photoshop_ID_Descriptions" ] = array(
0x03E8 => "Obsolete�Photoshop 2.0 only. number of channels, rows, columns, depth, and mode.",
0x03E9 => "Optional. Macintosh print manager print info record.",
0x03EB => "Obsolete�Photoshop 2.0 only. Contains the indexed color table.",
0x03ED => "ResolutionInfo structure. See Appendix A in Photoshop SDK Guide.pdf",
0x03EE => "Names of the alpha channels as a series of Pascal strings.",
0x03EF => "DisplayInfo structure. See Appendix A in Photoshop SDK Guide.pdf",
0x03F0 => "Optional. The caption as a Pascal string.",
0x03F1 => "Border information. border width, border units",
0x03F2 => "Background color.",
0x03F3 => "Print flags. labels, crop marks, color bars, registration marks, negative, flip, interpolate, caption.",
0x03F4 => "Grayscale and multichannel halftoning information.",
0x03F5 => "Color halftoning information.",
0x03F6 => "Duotone halftoning information.",
0x03F7 => "Grayscale and multichannel transfer function.",
0x03F8 => "Color transfer functions.",
0x03F9 => "Duotone transfer functions.",
0x03FA => "Duotone image information.",
0x03FB => "Effective black and white values for the dot range.",
0x03FC => "Obsolete Resource.",
0x03FD => "EPS options.",
0x03FE => "Quick Mask information. Quick Mask channel ID, Mask initially empty.",
0x03FF => "Obsolete Resource.",
0x0400 => "Layer state information. Index of target layer.",
0x0401 => "Working path (not saved).",
0x0402 => "Layers group information. Group ID for the dragging groups. Layers in a group have the same group ID.",
0x0403 => "Obsolete Resource.",
0x0404 => "IPTC-NAA record. This contains the File Info... information. See the IIMV4.pdf document.",
0x0405 => "Image mode for raw format files.",
0x0406 => "JPEG quality. Private.",
0x0408 => "Grid and guides information.",
0x0409 => "Thumbnail resource.",
0x040A => "Copyright flag. Boolean indicating whether image is copyrighted. Can be set via Property suite or by user in File Info...",
0x040B => "URL. Handle of a text string with uniform resource locator. Can be set via Property suite or by user in File Info...",
0x040C => "Thumbnail resource.",
0x040D => "Global Angle. Global lighting angle for effects layer.",
0x040E => "Color samplers resource.",
0x040F => "ICC Profile. The raw bytes of an ICC format profile, see the ICC34.pdf and ICC34.h files from the Internation Color Consortium.",
0x0410 => "Watermark.",
0x0411 => "ICC Untagged. Disables any assumed profile handling when opening the file. 1 = intentionally untagged.",
0x0412 => "Effects visible. Show/hide all the effects layer.",
0x0413 => "Spot Halftone. Version, length, variable length data.",
0x0414 => "Document specific IDs for layer identification",
0x0415 => "Unicode Alpha Names. Length and the string",
0x0416 => "Indexed Color Table Count. Number of colors in table that are actually defined",
0x0417 => "Transparent Index. Index of transparent color, if any.",
0x0419 => "Global Altitude.",
0x041A => "Slices.",
0x041B => "Workflow URL. Length, string.",
0x041C => "Jump To XPEP. Major version, Minor version, Count. Table which can include: Dirty flag, Mod date.",
0x041D => "Alpha Identifiers.",
0x041E => "URL List. Count of URLs, IDs, and strings",
0x0421 => "Version Info. Version, HasRealMergedData, string of writer name, string of reader name, file version.",
0x0BB7 => "Name of clipping path.",
0x2710 => "Print flags information. Version, Center crop marks, Bleed width value, Bleed width scale."
);

/******************************************************************************
* End of Global Variable:     Photoshop_ID_Descriptions
******************************************************************************/






?>