<?php

/******************************************************************************
*
* Filename:     PictureInfo.php
*
* Description:  Provides functions for reading and writing information to/from
*               the 'App 12' Picture Info segment of JPEG format files
*
* Author:       Evan Hunter
*
* Date:         23/7/2004
*
* Project:      PHP JPEG Metadata Toolkit
*
* Revision:     1.00
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

include_once 'Unicode.php';

/******************************************************************************
*
* Function:     get_jpeg_App12_Pic_Info
*
* Description:  Retrieves the Picture Info text information from an App12
*               JPEG segment and returns it as a string. Uses information
*               supplied by the get_jpeg_header_data function
*
* Parameters:   jpeg_header_data - a JPEG header data array in the same format
*                                  as from get_jpeg_header_data
*
* Returns:      App12_Head - The text preceeding the Picture Info (often
*                            the camera manufacturer's name)
*               App12_Text - The Picture Info Text
*               FALSE, FALSE - if an APP 12 Picture Info segment could not be found
*
******************************************************************************/

function get_jpeg_App12_Pic_Info( $jpeg_header_data )
{
        // Flag that an APP12 segment has not been found yet
        $App12_PI_Location = -1;

        //Cycle through the header segments
        for( $i = 0; $i < count( $jpeg_header_data ); $i++ )
        {
                // Check if we have found an APP12 header,
                if ( strcmp ( $jpeg_header_data[$i]['SegName'], "APP12" ) == 0 )
                {
                        // Found an APP12 segment
                        // Check if the APP12 has one of the correct labels (headers)
                        // for a picture info segment
                        if ( ( strncmp ( $jpeg_header_data[$i]['SegData'], "[picture info]", 14) == 0 ) ||
                             ( strncmp ( $jpeg_header_data[$i]['SegData'], "\x0a\x09\x09\x09\x09[picture info]", 19) == 0 ) ||
                             ( strncmp ( $jpeg_header_data[$i]['SegData'], "SEIKO EPSON CORP.  \00", 20) == 0 ) ||
                             ( strncmp ( $jpeg_header_data[$i]['SegData'], "Agfa Gevaert   \x00", 16) == 0 ) ||
                             ( strncmp ( $jpeg_header_data[$i]['SegData'], "SanyoElectricDSC\x00", 17) == 0 ) ||
                             ( strncmp ( substr($jpeg_header_data[$i]['SegData'],1,3), "\x00\x00\x00", 3) == 0 ) ||
                             ( strncmp ( $jpeg_header_data[$i]['SegData'], "Type=", 5) == 0 ) ||
                             ( strncmp ( $jpeg_header_data[$i]['SegData'], "OLYMPUS OPTICAL CO.,LTD.", 24) == 0 )  )
                        {
                                // A Picture Info segment was found, mark this position
                                $App12_PI_Location = $i;
                        }
                }
        }

        // Check if a Picture Info Segment was found
        if ( $App12_PI_Location != -1 )
        {
                // A picture Info Segment was found - Process it

                // Determine the length of the header if there is one
                $head_length = 0;

                if ( strncmp ( $jpeg_header_data[$App12_PI_Location]['SegData'], "App12 Gevaert   \x00", 16) == 0 )
                {
                        $head_length = 16;
                }
                else if ( strncmp ( $jpeg_header_data[$App12_PI_Location]['SegData'], "OLYMPUS OPTICAL CO.,LTD.", 24) == 0 )
                {
                        $head_length = 25;
                }
                else if ( strncmp ( $jpeg_header_data[$App12_PI_Location]['SegData'], "SEIKO EPSON CORP.  \00", 20) == 0 )
                {
                        $head_length = 20;
                }
                else if ( strncmp ( $jpeg_header_data[$App12_PI_Location]['SegData'], "\x0a\x09\x09\x09\x09[picture info]", 19) == 0 )
                {
                        $head_length = 5;
                }
                else if ( strncmp ( substr($jpeg_header_data[$App12_PI_Location]['SegData'],1,3), "\x00\x00\x00", 3) == 0 ) // HP
                {
                        $head_length = 0;
                }
                else if ( strncmp ( $jpeg_header_data[$App12_PI_Location]['SegData'], "SanyoElectricDSC\x00", 17) == 0 )
                {
                        $head_length = 17;
                }
                else
                {
                        $head_length = 0;
                }

                // Extract the header and the Picture Info Text from the APP12 segment
                $App12_PI_Head = substr( $jpeg_header_data[$App12_PI_Location]['SegData'], 0, $head_length );
                $App12_PI_Text = substr( $jpeg_header_data[$App12_PI_Location]['SegData'], $head_length );

                
                // Return the text which was extracted

                if ( ($pos = strpos ( $App12_PI_Text, "[end]" ) ) !== FALSE )
                {
                        return array( "Header" => $App12_PI_Head, "Picture Info" => substr( $App12_PI_Text, 0, $pos + 5 ) );
                }
                else
                {
                        return array( "Header" => $App12_PI_Head, "Picture Info" => $App12_PI_Text );
                }
        }

        // No Picture Info Segment Found - Return False
        return array( FALSE, FALSE );
}

/******************************************************************************
* End of Function:     get_jpeg_header_data
******************************************************************************/





/******************************************************************************
*
* Function:     put_jpeg_App12_Pic_Info
*
* Description:  Writes Picture Info text into an App12 JPEG segment. Uses information
*               supplied by the get_jpeg_header_data function. If no App12 exists
*               already a new one is created, otherwise it replaces the old one
*
* Parameters:   jpeg_header_data - a JPEG header data array in the same format
*                                  as from get_jpeg_header_data
*               new_Pic_Info_Text - The Picture Info Text, including any header
*                                   that is required
*
* Returns:      jpeg_header_data - the JPEG header array with the new Picture
*                                  info segment inserted
*               FALSE - if an error occured
*
******************************************************************************/

function put_jpeg_App12_Pic_Info( $jpeg_header_data, $new_Pic_Info_Text )
{

        //Cycle through the header segments
        for( $i = 0; $i < count( $jpeg_header_data ); $i++ )
        {
                // Check if we have found an APP12 header,
                if ( strcmp ( $jpeg_header_data[$i][SegName], "APP12" ) == 0 )
                {
                        // Found an APP12 segment
                        // Check if the APP12 has one of the correct labels (headers)
                        // for a picture info segment
                        if ( ( strncmp ( $jpeg_header_data[$i]['SegData'], "[picture info]", 14) == 0 ) ||
                             ( strncmp ( $jpeg_header_data[$i]['SegData'], "\x0a\x09\x09\x09\x09[picture info]", 19) == 0 ) ||
                             ( strncmp ( $jpeg_header_data[$i]['SegData'], "SEIKO EPSON CORP.  \x00", 20) == 0 ) ||
                             ( strncmp ( $jpeg_header_data[$i]['SegData'], "Agfa Gevaert   \x00", 16) == 0 ) ||
                             ( strncmp ( $jpeg_header_data[$i]['SegData'], "SanyoElectricDSC\x00", 17) == 0 ) ||
                             ( strncmp ( $jpeg_header_data[$i]['SegData'], "Type=", 5) == 0 ) ||
                             ( strncmp ( $jpeg_header_data[$i]['SegData'], "OLYMPUS OPTICAL CO.,LTD.", 24) == 0 )  )
                        {
                                // Found a preexisting Picture Info segment - Replace it with the new one and return.
                                $jpeg_header_data[$i][SegData] = $new_Pic_Info_Text;
                                return $jpeg_header_data;
                        }
                }
        }

        // No preexisting Picture Info segment found, insert a new one at the start of the header data.

        // Determine highest position of an APP segment at or below APP12, so we can put the
        // new APP12 at this position
        

        $highest_APP = -1;

        //Cycle through the header segments
        for( $i = 0; $i < count( $jpeg_header_data ); $i++ )
        {
                // Check if we have found an APP segment at or below APP12,
                if ( ( $jpeg_header_data[$i]['SegType'] >= 0xE0 ) && ( $jpeg_header_data[$i]['SegType'] <= 0xEC ) )
                {
                        // Found an APP segment at or below APP12
                        $highest_APP = $i;
                }
        }

        // Insert the new Picture Info segment
        array_splice($jpeg_header_data, $highest_APP + 1 , 0, array( array(     "SegType" => 0xEC,
                                                                                "SegName" => "APP12",
                                                                                "SegDesc" => $GLOBALS[ "JPEG_Segment_Descriptions" ][ 0xEC ],
                                                                                "SegData" => $new_Pic_Info_Text ) ) );

        return $jpeg_header_data;


}

/******************************************************************************
* End of Function:     put_jpeg_header_data
******************************************************************************/



/******************************************************************************
*
* Function:     Interpret_App12_Pic_Info_to_HTML
*
* Description:  Generates html showing the contents of any JPEG App12 Picture
*               Info segment
*
* Parameters:   jpeg_header_data - the JPEG header data, as retrieved
*                                  from the get_jpeg_header_data function
*
* Returns:      output - the HTML
*
******************************************************************************/

function Interpret_App12_Pic_Info_to_HTML( $jpeg_header_data )
{
        // Create a string to receive the output
        $output = "";

        // read the App12 Picture Info segment
        $PI = get_jpeg_App12_Pic_Info( $jpeg_header_data );

        // Check if the Picture Info segment was valid
        if ( $PI !== array(FALSE, FALSE) )
        {
                // Picture Info exists - add it to the output
                $output .= "<h2 class=\"Picture_Info_Main_Heading\">Picture Info Text</h2>\n";
                $output .= "<p><span class=\"Picture_Info_Caption_Text\">Header: </span><span class=\"Picture_Info_Value_Text\">" . HTML_UTF8_Escape( $PI['Header'] ) . "</span></p>\n";
                $output .= "<p class=\"Picture_Info_Caption_Text\">Picture Info Text:</p><pre class=\"Picture_Info_Value_Text\">" . HTML_UTF8_Escape( $PI['Picture Info'] ) . "</pre>\n";
        }

        // Return the result
        return $output;
}

/******************************************************************************
* End of Function:     Interpret_App12_Pic_Info_to_HTML
******************************************************************************/



?>
