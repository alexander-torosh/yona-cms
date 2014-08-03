<?php

/******************************************************************************
*
* Filename:     IPTC.php
*
* Description:  Provides functions for reading and writing IPTC-NAA Information
*               Interchange Model metadata
*
* Author:       Evan Hunter
*
* Date:         23/7/2004
*
* Project:      PHP JPEG Metadata Toolkit
*
* Revision:     1.10
*
* Changes:      1.00 -> 1.01 : changed get_IPTC to return partial data when error occurs
*               1.01 -> 1.10 : changed put_IPTC to check if the incoming IPTC block is valid
*                              changed Interpret_IPTC_to_HTML, adding nl2br functions for each text field,
*                              so that multiline text displays properly
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


// TODO: Not all of the IPTC fields have been tested properly

/******************************************************************************
*
* Function:     get_IPTC
*
* Description:  Extracts IPTC-NAA IIM data from the string provided, and returns
*               the information as an array
*
* Parameters:   Data_Str - the string containing the IPTC-NAA IIM records. Must
*                          be exact length of the IPTC-NAA IIM data.
*
* Returns:      OutputArray - Array of IPTC-NAA IIM records
*               FALSE - If an error occured in decoding
*
******************************************************************************/

function get_IPTC( $Data_Str )
{

        // Initialise the start position
        $pos = 0;
        // Create the array to receive the data
        $OutputArray = array( );

        // Cycle through the IPTC records, decoding and storing them
        while( $pos < strlen($Data_Str) )
        {
                // TODO - Extended Dataset record not supported

                // Check if there is sufficient data for reading the record
                if ( strlen( substr($Data_Str,$pos) ) < 5 )
                {
                        // Not enough data left for a record - Probably corrupt data - ERROR
                        // Change: changed to return partial data as of revision 1.01
                        return $OutputArray;
                }

                // Unpack data from IPTC record:
                // First byte - IPTC Tag Marker - always 28
                // Second byte - IPTC Record Number
                // Third byte - IPTC Dataset Number
                // Fourth and fifth bytes - two byte size value
                $iptc_raw = unpack( "CIPTC_Tag_Marker/CIPTC_Record_No/CIPTC_Dataset_No/nIPTC_Size", substr($Data_Str,$pos) );

                // Skip position over the unpacked data
                $pos += 5;

                // Construct the IPTC type string eg 2:105
                $iptctype = sprintf( "%01d:%02d", $iptc_raw['IPTC_Record_No'], $iptc_raw['IPTC_Dataset_No']);

                // Check if there is sufficient data for reading the record contents
                if ( strlen( substr( $Data_Str, $pos, $iptc_raw['IPTC_Size'] ) ) !== $iptc_raw['IPTC_Size'] )
                {
                        // Not enough data left for the record content - Probably corrupt data - ERROR
                        // Change: changed to return partial data as of revision 1.01
                        return $OutputArray;
                }

                // Add the IPTC record to the output array
                $OutputArray[] = array( "IPTC_Type" => $iptctype ,
                                        "RecName" => $GLOBALS[ "IPTC_Entry_Names" ][ $iptctype ],
                                        "RecDesc" => $GLOBALS[ "IPTC_Entry_Descriptions" ][ $iptctype ],
                                        "RecData" => substr( $Data_Str, $pos, $iptc_raw['IPTC_Size'] ) );

                // Skip over the IPTC record data
                $pos += $iptc_raw['IPTC_Size'];
        }
        return $OutputArray;

}


/******************************************************************************
* End of Function:     get_IPTC
******************************************************************************/




/******************************************************************************
*
* Function:     put_IPTC
*
* Description:  Encodes an array of IPTC-NAA records into a string encoded
*               as IPTC-NAA IIM. (The reverse of get_IPTC)
*
* Parameters:   new_IPTC_block - the IPTC-NAA array to be encoded. Should be
*                                the same format as that received from get_IPTC
*
* Returns:      iptc_packed_data - IPTC-NAA IIM encoded string
*
******************************************************************************/


function put_IPTC( $new_IPTC_block )
{
        // Check if the incoming IPTC block is valid
        if ( $new_IPTC_block == FALSE )
        {
                // Invalid IPTC block - abort
                return FALSE;
        }
        // Initialise the packed output data string
        $iptc_packed_data = "";

        // Cycle through each record in the new IPTC block
        foreach ($new_IPTC_block as $record)
        {
                // Extract the Record Number and Dataset Number from the IPTC_Type field
                list($IPTC_Record, $IPTC_Dataset) = sscanf( $record['IPTC_Type'], "%d:%d");

                // Write the IPTC-NAA IIM Tag Marker, Record Number, Dataset Number and Data Size to the packed output data string
                $iptc_packed_data .= pack( "CCCn", 28, $IPTC_Record, $IPTC_Dataset, strlen($record['RecData']) );

                // Write the IPTC-NAA IIM Data to the packed output data string
                $iptc_packed_data .= $record['RecData'];
        }

        // Return the IPTC-NAA IIM data
        return $iptc_packed_data;
}

/******************************************************************************
* End of Function:     put_IPTC
******************************************************************************/



/******************************************************************************
*
* Function:     Interpret_IPTC_to_HTML
*
* Description:  Generates html detailing the contents a IPTC-NAA IIM array
*               which was retrieved with the get_IPTC function
*
* Parameters:   IPTC_info - the IPTC-NAA IIM array,as read from get_IPTC
*
* Returns:      OutputStr - A string containing the HTML
*
******************************************************************************/

function Interpret_IPTC_to_HTML( $IPTC_info )
{
        // Create a string to receive the HTML
        $output_str ="";

        // Check if the IPTC
        if ( $IPTC_info !== FALSE )
        {


                // Add Heading to HTML
                $output_str .= "<h3 class=\"IPTC_Main_Heading\">IPTC-NAA Record</h3>\n";

                // Add Table to HTML
                $output_str .= "\n<table class=\"IPTC_Table\" border=1>\n";

                // Cycle through each of the IPTC-NAA IIM records
                foreach( $IPTC_info as $IPTC_Record )
                {
                        // Check if the record is a known IPTC field
                        $Record_Name = $IPTC_Record['RecName'];
                        if ( $Record_Name == "" )
                        {
                                // Record is an unknown field - add message to HTML
                                $output_str .= "<tr class=\"IPTC_Table_Row\"><td class=\"IPTC_Caption_Cell\">Unknown IPTC field '". htmlentities( $IPTC_Record['IPTC_Type'] ). "' :</td><td class=\"IPTC_Value_Cell\">" . nl2br( HTML_UTF8_Escape( $IPTC_Record['RecData'] ) ) ."</td></tr>\n";
                        }
                        else
                        {
                                // Record is a recognised IPTC field - Process it accordingly

                                switch ( $IPTC_Record['IPTC_Type'] )
                                {
                                        case "1:00":    // Envelope Record:Model Version
                                        case "1:22":    // Envelope Record:File Format Version
                                                $output_str .= "<tr class=\"IPTC_Table_Row\"><td class=\"IPTC_Caption_Cell\">$Record_Name</td><td class=\"IPTC_Value_Cell\">" . hexdec( bin2hex( $IPTC_Record['RecData'] ) ) ."</td></tr>\n";
                                                break;

                                        case "1:90":    // Envelope Record:Coded Character Set
                                                $output_str .= "<tr class=\"IPTC_Table_Row\"><td class=\"IPTC_Caption_Cell\">$Record_Name</td><td class=\"IPTC_Value_Cell\">Decoding not yet implemented<br>\n (Hex Data: " . bin2hex( $IPTC_Record['RecData'] )  .")</td></tr>\n";
                                                break;
                                                // TODO: Implement decoding of IPTC record 1:90

                                        case "1:20":    // Envelope Record:File Format

                                                $formatno = hexdec( bin2hex( $IPTC_Record['RecData'] ) );

                                                // Lookup file format from lookup-table
                                                if ( array_key_exists( $formatno, $GLOBALS[ "IPTC_File Formats" ] ) )
                                                {
                                                        // Entry was found in lookup table - add it to HTML
                                                        $output_str .= "<tr class=\"IPTC_Table_Row\"><td class=\"IPTC_Caption_Cell\">File Format</td><td class=\"IPTC_Value_Cell\">". $GLOBALS[ "IPTC_File Formats" ][$formatno] . "</td></tr>\n";
                                                }
                                                else
                                                {
                                                        // No matching entry was found in lookup table - add message to html
                                                        $output_str .= "<tr class=\"IPTC_Table_Row\"><td class=\"IPTC_Caption_Cell\">File Format</td><td class=\"IPTC_Value_Cell\">Unknown File Format ($formatno)</td></tr>\n";
                                                }
                                                break;


                                        case "2:00":    // Application Record:Record Version
                                                $output_str .= "<tr class=\"IPTC_Table_Row\"><td class=\"IPTC_Caption_Cell\">IPTC Version</td><td class=\"IPTC_Value_Cell\">" . hexdec( bin2hex( $IPTC_Record['RecData'] ) ) ."</td></tr>\n";
                                                break;

                                        case "2:42":    // Application Record: Action Advised

                                                // Looup Action
                                                if ( $IPTC_Record['RecData'] == "01" )
                                                {
                                                        $output_str .= "<tr class=\"IPTC_Table_Row\"><td class=\"IPTC_Caption_Cell\">$Record_Name</td><td class=\"IPTC_Value_Cell\">Kill</td></tr>\n";
                                                }
                                                elseif ( $IPTC_Record['RecData'] == "02" )
                                                {
                                                        $output_str .= "<tr class=\"IPTC_Table_Row\"><td class=\"IPTC_Caption_Cell\">$Record_Name</td><td class=\"IPTC_Value_Cell\">Replace</td></tr>\n";
                                                }
                                                elseif ( $IPTC_Record['RecData'] == "03" )
                                                {
                                                        $output_str .= "<tr class=\"IPTC_Table_Row\"><td class=\"IPTC_Caption_Cell\">$Record_Name</td><td class=\"IPTC_Value_Cell\">Append</td></tr>\n";
                                                }
                                                elseif ( $IPTC_Record['RecData'] == "04" )
                                                {
                                                        $output_str .= "<tr class=\"IPTC_Table_Row\"><td class=\"IPTC_Caption_Cell\">$Record_Name</td><td class=\"IPTC_Value_Cell\">Reference</td></tr>\n";
                                                }
                                                else
                                                {
                                                        // Unknown Action
                                                        $output_str .= "<tr class=\"IPTC_Table_Row\"><td class=\"IPTC_Caption_Cell\">$Record_Name</td><td class=\"IPTC_Value_Cell\">Unknown : " . nl2br( HTML_UTF8_Escape( $IPTC_Record['RecData'] ) ) ."</td></tr>\n";
                                                }
                                                break;

                                        case "2:08":    // Application Record:Editorial Update
                                                if ( $IPTC_Record['RecData'] == "01" )
                                                {
                                                        // Additional Language
                                                        $output_str .= "<tr class=\"IPTC_Table_Row\"><td class=\"IPTC_Caption_Cell\">$Record_Name</td><td class=\"IPTC_Value_Cell\">Additional language</td></tr>\n";
                                                }
                                                else
                                                {
                                                        // Unknown Value
                                                        $output_str .= "<tr class=\"IPTC_Table_Row\"><td class=\"IPTC_Caption_Cell\">$Record_Name</td><td class=\"IPTC_Value_Cell\">Unknown : " . nl2br( HTML_UTF8_Escape( $IPTC_Record['RecData'] ) ) ."</td></tr>\n";
                                                }
                                                break;

                                        case "2:30":    // Application Record:Release Date
                                        case "2:37":    // Application Record:Expiration Date
                                        case "2:47":    // Application Record:Reference Date
                                        case "2:55":    // Application Record:Date Created
                                        case "2:62":    // Application Record:Digital Creation Date
                                        case "1:70":    // Envelope Record:Date Sent
                                                $date_array = unpack( "a4Year/a2Month/A2Day", $IPTC_Record['RecData'] );
                                                $output_str .= "<tr class=\"IPTC_Table_Row\"><td class=\"IPTC_Caption_Cell\">$Record_Name</td><td class=\"IPTC_Value_Cell\">" . nl2br( HTML_UTF8_Escape( $date_array['Day'] . "/" . $date_array['Month'] . "/" . $date_array['Year'] ) ) ."</td></tr>\n";
                                                break;

                                        case "2:35":    // Application Record:Release Time
                                        case "2:38":    // Application Record:Expiration Time
                                        case "2:60":    // Application Record:Time Created
                                        case "2:63":    // Application Record:Digital Creation Time
                                        case "1:80":    // Envelope Record:Time Sent
                                                $time_array = unpack( "a2Hour/a2Minute/A2Second/APlusMinus/A4Timezone", $IPTC_Record['RecData'] );
                                                $output_str .= "<tr class=\"IPTC_Table_Row\"><td class=\"IPTC_Caption_Cell\">$Record_Name</td><td class=\"IPTC_Value_Cell\">" . nl2br( HTML_UTF8_Escape( $time_array['Hour'] . ":" . $time_array['Minute'] . ":" . $time_array['Second'] . " ". $time_array['PlusMinus'] . $time_array['Timezone'] ) ) ."</td></tr>\n";
                                                break;

                                        case "2:75":    // Application Record:Object Cycle
                                                // Lookup Value
                                                if ( $IPTC_Record['RecData'] == "a" )
                                                {
                                                        $output_str .= "<tr class=\"IPTC_Table_Row\"><td class=\"IPTC_Caption_Cell\">$Record_Name</td><td class=\"IPTC_Value_Cell\">Morning</td></tr>\n";
                                                }
                                                elseif ( $IPTC_Record['RecData'] == "p" )
                                                {
                                                        $output_str .= "<tr class=\"IPTC_Table_Row\"><td class=\"IPTC_Caption_Cell\">$Record_Name</td><td class=\"IPTC_Value_Cell\">Evening</td></tr>\n";
                                                }
                                                elseif ( $IPTC_Record['RecData'] == "b" )
                                                {
                                                        $output_str .= "<tr class=\"IPTC_Table_Row\"><td class=\"IPTC_Caption_Cell\">$Record_Name</td><td class=\"IPTC_Value_Cell\">Both Morning and Evening</td></tr>\n";
                                                }
                                                else
                                                {
                                                        // Unknown Value
                                                        $output_str .= "<tr class=\"IPTC_Table_Row\"><td class=\"IPTC_Caption_Cell\">$Record_Name</td><td class=\"IPTC_Value_Cell\">Unknown : " . nl2br( HTML_UTF8_Escape( $IPTC_Record['RecData'] ) ) ."</td></tr>\n";
                                                }
                                                break;

                                        case "2:125":   // Application Record:Rasterised Caption
                                                $output_str .= "<tr class=\"IPTC_Table_Row\"><td class=\"IPTC_Caption_Cell\">$Record_Name</td><td class=\"IPTC_Value_Cell\">460x128 pixel black and white caption image</td></tr>\n";
                                                break;
                                                // TODO: Display Rasterised Caption for IPTC record 2:125

                                        case "2:130":   // Application Record:Image Type
                                                // Lookup Number of Components
                                                if ( $IPTC_Record['RecData']{0} == "0" )
                                                {
                                                        $output_str .= "<tr class=\"IPTC_Table_Row\"><td class=\"IPTC_Caption_Cell\">$Record_Name</td><td class=\"IPTC_Value_Cell\">No Objectdata";
                                                }
                                                elseif ( $IPTC_Record['RecData']{0} == "9" )
                                                {
                                                        $output_str .= "<tr class=\"IPTC_Table_Row\"><td class=\"IPTC_Caption_Cell\">$Record_Name</td><td class=\"IPTC_Value_Cell\">Supplemental objects related to other objectdata";
                                                }
                                                else
                                                {
                                                        $output_str .= "<tr class=\"IPTC_Table_Row\"><td class=\"IPTC_Caption_Cell\">$Record_Name</td><td class=\"IPTC_Value_Cell\">Number of Colour Components : " . nl2br( HTML_UTF8_Escape( $IPTC_Record['RecData']{0} ) );
                                                }

                                                // Lookup current objectdata colour
                                                if ( $GLOBALS['ImageType_Names'][ $IPTC_Record['RecData']{1} ] == "" )
                                                {
                                                        $output_str .= ", Unknown : " . nl2br( HTML_UTF8_Escape( $IPTC_Record['RecData']{1} ) );
                                                }
                                                else
                                                {
                                                        $output_str .= ", " . nl2br( HTML_UTF8_Escape( $GLOBALS['ImageType_Names'][ $IPTC_Record['RecData']{1} ] ) );
                                                }
                                                $output_str .= "</td></tr>\n";
                                                break;

                                        case "2:131":   // Application Record:Image Orientation
                                                // Lookup value
                                                if ( $IPTC_Record['RecData'] == "L" )
                                                {
                                                        $output_str .= "<tr class=\"IPTC_Table_Row\"><td class=\"IPTC_Caption_Cell\">$Record_Name</td><td class=\"IPTC_Value_Cell\">Landscape</td></tr>\n";
                                                }
                                                elseif ( $IPTC_Record['RecData'] == "P" )
                                                {
                                                        $output_str .= "<tr class=\"IPTC_Table_Row\"><td class=\"IPTC_Caption_Cell\">$Record_Name</td><td class=\"IPTC_Value_Cell\">Portrait</td></tr>\n";
                                                }
                                                elseif ( $IPTC_Record['RecData'] == "S" )
                                                {
                                                        $output_str .= "<tr class=\"IPTC_Table_Row\"><td class=\"IPTC_Caption_Cell\">$Record_Name</td><td class=\"IPTC_Value_Cell\">Square</td></tr>\n";
                                                }
                                                else
                                                {
                                                        // Unknown Orientation Value
                                                        $output_str .= "<tr class=\"IPTC_Table_Row\"><td class=\"IPTC_Caption_Cell\">$Record_Name</td><td class=\"IPTC_Value_Cell\">Unknown : " . nl2br( HTML_UTF8_Escape( $IPTC_Record['RecData'] ) ) ."</td></tr>\n";
                                                }
                                                break;

                                        default:        // All other records
                                                $output_str .= "<tr class=\"IPTC_Table_Row\"><td class=\"IPTC_Caption_Cell\">$Record_Name</td><td class=\"IPTC_Value_Cell\">" .nl2br( HTML_UTF8_Escape( $IPTC_Record['RecData'] ) ) ."</td></tr>\n";
                                                break;
                                }
                        }
                }

                // Add Table End to HTML
                $output_str .= "</table><br>\n";
        }

        // Return HTML
        return $output_str;
}


/******************************************************************************
* End of Function:     Interpret_IPTC_to_HTML
******************************************************************************/



/******************************************************************************
* Global Variable:      IPTC_Entry_Names
*
* Contents:     The names of the IPTC-NAA IIM fields
*
******************************************************************************/

$GLOBALS[ "IPTC_Entry_Names" ] = array(
// Envelope Record
"1:00" => "Model Version",
"1:05" => "Destination",
"1:20" => "File Format",
"1:22" => "File Format Version",
"1:30" => "Service Identifier",
"1:40" => "Envelope Number",
"1:50" => "Product ID",
"1:60" => "Envelope Priority",
"1:70" => "Date Sent",
"1:80" => "Time Sent",
"1:90" => "Coded Character Set",
"1:100" => "UNO (Unique Name of Object)",
"1:120" => "ARM Identifier",
"1:122" => "ARM Version",

// Application Record
"2:00" => "Record Version",
"2:03" => "Object Type Reference",
"2:05" => "Object Name (Title)",
"2:07" => "Edit Status",
"2:08" => "Editorial Update",
"2:10" => "Urgency",
"2:12" => "Subject Reference",
"2:15" => "Category",
"2:20" => "Supplemental Category",
"2:22" => "Fixture Identifier",
"2:25" => "Keywords",
"2:26" => "Content Location Code",
"2:27" => "Content Location Name",
"2:30" => "Release Date",
"2:35" => "Release Time",
"2:37" => "Expiration Date",
"2:35" => "Expiration Time",
"2:40" => "Special Instructions",
"2:42" => "Action Advised",
"2:45" => "Reference Service",
"2:47" => "Reference Date",
"2:50" => "Reference Number",
"2:55" => "Date Created",
"2:60" => "Time Created",
"2:62" => "Digital Creation Date",
"2:63" => "Digital Creation Time",
"2:65" => "Originating Program",
"2:70" => "Program Version",
"2:75" => "Object Cycle",
"2:80" => "By-Line (Author)",
"2:85" => "By-Line Title (Author Position) [Not used in Photoshop 7]",
"2:90" => "City",
"2:92" => "Sub-Location",
"2:95" => "Province/State",
"2:100" => "Country/Primary Location Code",
"2:101" => "Country/Primary Location Name",
"2:103" => "Original Transmission Reference",
"2:105" => "Headline",
"2:110" => "Credit",
"2:115" => "Source",
"2:116" => "Copyright Notice",
"2:118" => "Contact",
"2:120" => "Caption/Abstract",
"2:122" => "Caption Writer/Editor",
"2:125" => "Rasterized Caption",
"2:130" => "Image Type",
"2:131" => "Image Orientation",
"2:135" => "Language Identifier",
"2:150" => "Audio Type",
"2:151" => "Audio Sampling Rate",
"2:152" => "Audio Sampling Resolution",
"2:153" => "Audio Duration",
"2:154" => "Audio Outcue",
"2:200" => "ObjectData Preview File Format",
"2:201" => "ObjectData Preview File Format Version",
"2:202" => "ObjectData Preview Data",

// Pre-ObjectData Descriptor Record
"7:10"  => "Size Mode",
"7:20"  => "Max Subfile Size",
"7:90"  => "ObjectData Size Announced",
"7:95"  => "Maximum ObjectData Size",

// ObjectData Record
"8:10"  => "Subfile",

// Post ObjectData Descriptor Record
"9:10"  => "Confirmed ObjectData Size"

);

/******************************************************************************
* End of Global Variable:     IPTC_Entry_Names
******************************************************************************/





/******************************************************************************
* Global Variable:      IPTC_Entry_Descriptions
*
* Contents:     The Descriptions of the IPTC-NAA IIM fields
*
******************************************************************************/

$GLOBALS[ "IPTC_Entry_Descriptions" ] = array(
// Envelope Record
"1:00" => "2 byte binary version number",
"1:05" => "Max 1024 characters of Destination",
"1:20" => "2 byte binary file format number, see IPTC-NAA V4 Appendix A",
"1:22" => "Binary version number of file format",
"1:30" => "Max 10 characters of Service Identifier",
"1:40" => "8 Character Envelope Number",
"1:50" => "Product ID - Max 32 characters",
"1:60" => "Envelope Priority - 1 numeric characters",
"1:70" => "Date Sent - 8 numeric characters CCYYMMDD",
"1:80" => "Time Sent - 11 characters HHMMSS±HHMM",
"1:90" => "Coded Character Set - Max 32 characters",
"1:100" => "UNO (Unique Name of Object) - 14 to 80 characters",
"1:120" => "ARM Identifier - 2 byte binary number",
"1:122" => "ARM Version - 2 byte binary number",

// Application Record
"2:00" => "Record Version - 2 byte binary number",
"2:03" => "Object Type Reference -  3 plus 0 to 64 Characters",
"2:05" => "Object Name (Title) - Max 64 characters",
"2:07" => "Edit Status - Max 64 characters",
"2:08" => "Editorial Update - 2 numeric characters",
"2:10" => "Urgency - 1 numeric character",
"2:12" => "Subject Reference - 13 to 236 characters",
"2:15" => "Category - Max 3 characters",
"2:20" => "Supplemental Category - Max 32 characters",
"2:22" => "Fixture Identifier - Max 32 characters",
"2:25" => "Keywords - Max 64 characters",
"2:26" => "Content Location Code - 3 characters",
"2:27" => "Content Location Name - Max 64 characters",
"2:30" => "Release Date - 8 numeric characters CCYYMMDD",
"2:35" => "Release Time - 11 characters HHMMSS±HHMM",
"2:37" => "Expiration Date - 8 numeric characters CCYYMMDD",
"2:35" => "Expiration Time - 11 characters HHMMSS±HHMM",
"2:40" => "Special Instructions - Max 256 Characters",
"2:42" => "Action Advised - 2 numeric characters",
"2:45" => "Reference Service - Max 10 characters",
"2:47" => "Reference Date - 8 numeric characters CCYYMMDD",
"2:50" => "Reference Number - 8 characters",
"2:55" => "Date Created - 8 numeric characters CCYYMMDD",
"2:60" => "Time Created - 11 characters HHMMSS±HHMM",
"2:62" => "Digital Creation Date - 8 numeric characters CCYYMMDD",
"2:63" => "Digital Creation Time - 11 characters HHMMSS±HHMM",
"2:65" => "Originating Program - Max 32 characters",
"2:70" => "Program Version - Max 10 characters",
"2:75" => "Object Cycle - 1 character",
"2:80" => "By-Line (Author) - Max 32 Characters",
"2:85" => "By-Line Title (Author Position) - Max 32 characters",
"2:90" => "City - Max 32 Characters",
"2:92" => "Sub-Location - Max 32 characters",
"2:95" => "Province/State - Max 32 Characters",
"2:100" => "Country/Primary Location Code - 3 alphabetic characters",
"2:101" => "Country/Primary Location Name - Max 64 characters",
"2:103" => "Original Transmission Reference - Max 32 characters",
"2:105" => "Headline - Max 256 Characters",
"2:110" => "Credit - Max 32 Characters",
"2:115" => "Source - Max 32 Characters",
"2:116" => "Copyright Notice - Max 128 Characters",
"2:118" => "Contact - Max 128 characters",
"2:120" => "Caption/Abstract - Max 2000 Characters",
"2:122" => "Caption Writer/Editor - Max 32 Characters",
"2:125" => "Rasterized Caption - 7360 bytes, 1 bit per pixel, 460x128pixel image",
"2:130" => "Image Type - 2 characters",
"2:131" => "Image Orientation - 1 alphabetic character",
"2:135" => "Language Identifier - 2 or 3 aphabetic characters",
"2:150" => "Audio Type - 2 characters",
"2:151" => "Audio Sampling Rate - 6 numeric characters",
"2:152" => "Audio Sampling Resolution - 2 numeric characters",
"2:153" => "Audio Duration - 6 numeric characters",
"2:154" => "Audio Outcue - Max 64 characters",
"2:200" => "ObjectData Preview File Format - 2 byte binary number",
"2:201" => "ObjectData Preview File Format Version - 2 byte binary number",
"2:202" => "ObjectData Preview Data - Max 256000 binary bytes",

// Pre-ObjectData Descriptor Record
"7:10"  => "Size Mode - 1 numeric character",
"7:20"  => "Max Subfile Size",
"7:90"  => "ObjectData Size Announced",
"7:95"  => "Maximum ObjectData Size",

// ObjectData Record
"8:10"  => "Subfile",

// Post ObjectData Descriptor Record
"9:10"  => "Confirmed ObjectData Size"

);

/******************************************************************************
* End of Global Variable:     IPTC_Entry_Descriptions
******************************************************************************/




/******************************************************************************
* Global Variable:      IPTC_File Formats
*
* Contents:     The names of the IPTC-NAA IIM File Formats for field 1:20
*
******************************************************************************/

$GLOBALS[ "IPTC_File Formats" ] = array(
00 => "No ObjectData",
01 => "IPTC-NAA Digital Newsphoto Parameter Record",
02 => "IPTC7901 Recommended Message Format",
03 => "Tagged Image File Format (Adobe/Aldus Image data)",
04 => "Illustrator (Adobe Graphics data)",
05 => "AppleSingle (Apple Computer Inc)",
06 => "NAA 89-3 (ANPA 1312)",
07 => "MacBinary II",
08 => "IPTC Unstructured Character Oriented File Format (UCOFF)",
09 => "United Press International ANPA 1312 variant",
10 => "United Press International Down-Load Message",
11 => "JPEG File Interchange (JFIF)",
12 => "Photo-CD Image-Pac (Eastman Kodak)",
13 => "Microsoft Bit Mapped Graphics File [*.BMP]",
14 => "Digital Audio File [*.WAV] (Microsoft & Creative Labs)",
15 => "Audio plus Moving Video [*.AVI] (Microsoft)",
16 => "PC DOS/Windows Executable Files [*.COM][*.EXE]",
17 => "Compressed Binary File [*.ZIP] (PKWare Inc)",
18 => "Audio Interchange File Format AIFF (Apple Computer Inc)",
19 => "RIFF Wave (Microsoft Corporation)",
20 => "Freehand (Macromedia/Aldus)",
21 => "Hypertext Markup Language - HTML (The Internet Society)",
22 => "MPEG 2 Audio Layer 2 (Musicom), ISO/IEC",
23 => "MPEG 2 Audio Layer 3, ISO/IEC",
24 => "Portable Document File (*.PDF) Adobe",
25 => "News Industry Text Format (NITF)",
26 => "Tape Archive (*.TAR)",
27 => "Tidningarnas Telegrambyrå NITF version (TTNITF DTD)",
28 => "Ritzaus Bureau NITF version (RBNITF DTD)",
29 => "Corel Draw [*.CDR]"
);


/******************************************************************************
* End of Global Variable:     IPTC_File Formats
******************************************************************************/

/******************************************************************************
* Global Variable:      ImageType_Names
*
* Contents:     The names of the colour components for IPTC-NAA IIM field 2:130
*
******************************************************************************/

$GLOBALS['ImageType_Names'] = array(    "M" => "Monochrome",
                                        "Y" => "Yellow Component",
                                        "M" => "Magenta Component",
                                        "C" => "Cyan Component",
                                        "K" => "Black Component",
                                        "R" => "Red Component",
                                        "G" => "Green Component",
                                        "B" => "Blue Component",
                                        "T" => "Text Only",
                                        "F" => "Full colour composite, frame sequential",
                                        "L" => "Full colour composite, line sequential",
                                        "P" => "Full colour composite, pixel sequential",
                                        "S" => "Full colour composite, special interleaving" );



/******************************************************************************
* End of Global Variable:     ImageType_Names
******************************************************************************/

?>