<?php

/******************************************************************************
*
* Filename:     JFIF.php
*
* Description:  Provides functions for reading and writing information to/from
*               JPEG File Interchange Format (JFIF) segments and
*               JFIF Extension (JFXX) segments within a JPEG file.
*
* Author:       Evan Hunter
*
* Date:         24/7/2004
*
* Project:      PHP JPEG Metadata Toolkit
*
* Revision:     1.11
*
* Changes:      1.00 -> 1.11 : changed Interpret_JFXX_to_HTML to allow thumbnail links to work when
*                              toolkit is portable across directories
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

include_once 'pjmt_utils.php';          // Change: as of version 1.11 - added to allow directory portability

/******************************************************************************
*
* Function:     get_JFIF
*
* Description:  Retrieves information from a JPEG File Interchange Format (JFIF)
*               segment and returns it in an array. Uses information supplied by
*               the get_jpeg_header_data function
*
* Parameters:   jpeg_header_data - a JPEG header data array in the same format
*                                  as from get_jpeg_header_data
*
* Returns:      JFIF_data - an array of JFIF data
*               FALSE - if a JFIF segment could not be found
*
******************************************************************************/

function get_JFIF( $jpeg_header_data )
{
        //Cycle through the header segments
        for( $i = 0; $i < count( $jpeg_header_data ); $i++ )
        {
                // If we find an APP0 header,
                if ( strcmp ( $jpeg_header_data[$i]['SegName'], "APP0" ) == 0 )
                {
                        // And if it has the JFIF label,
                        if( strncmp ( $jpeg_header_data[$i]['SegData'], "JFIF\x00", 5) == 0 )
                        {
                                // Found a JPEG File Interchange Format (JFIF) Block

                                // unpack the JFIF data from the incoming string
                                // First is the JFIF label string
                                // Then a two byte version number
                                // Then a byte, units identifier, ( 0 = aspect ration, 1 = dpi, 2 = dpcm)
                                // Then a two byte int X-Axis pixel Density (resolution)
                                // Then a two byte int Y-Axis pixel Density (resolution)
                                // Then a byte X-Axis JFIF thumbnail size
                                // Then a byte Y-Axis JFIF thumbnail size
                                // Then the uncompressed RGB JFIF thumbnail data

                                $JFIF_data = unpack( 'a5JFIF/C2Version/CUnits/nXDensity/nYDensity/CThumbX/CThumbY/a*ThumbData', $jpeg_header_data[$i]['SegData'] );

                                return $JFIF_data;
                        }
                }
        }
        return FALSE;
}

/******************************************************************************
* End of Function:     get_JFIF
******************************************************************************/




/******************************************************************************
*
* Function:     put_JFIF
*
* Description:  Creates a new JFIF segment from an array of JFIF data in the
*               same format as would be retrieved from get_JFIF, and inserts
*               this segment into the supplied JPEG header array
*
* Parameters:   jpeg_header_data - a JPEG header data array in the same format
*                                  as from get_jpeg_header_data, into which the
*                                  new JFIF segment will be put
*               new_JFIF_array - a JFIF information array in the same format as
*                                from get_JFIF, to create the new segment
*
* Returns:      jpeg_header_data - the JPEG header data array with the new
*                                  JFIF segment added
*
******************************************************************************/

function put_JFIF( $jpeg_header_data, $new_JFIF_array )
{
        // pack the JFIF data into its proper format for a JPEG file
        $packed_data = pack( 'a5CCCnnCCa*',"JFIF\x00", $new_JFIF_array['Version1'], $new_JFIF_array['Version2'], $new_JFIF_array['Units'], $new_JFIF_array['XDensity'], $new_JFIF_array['YDensity'], $new_JFIF_array['ThumbX'], $new_JFIF_array['ThumbY'], $new_JFIF_array['ThumbData'] );

        //Cycle through the header segments
        for( $i = 0; $i < count( $jpeg_header_data ); $i++ )
        {
                // If we find an APP0 header,
                if ( strcmp ( $jpeg_header_data[$i]['SegName'], "APP0" ) == 0 )
                {
                        // And if it has the JFIF label,
                        if( strncmp ( $jpeg_header_data[$i]['SegData'], "JFIF\x00", 5) == 0 )
                        {
                                // Found a preexisting JFIF block - Replace it with the new one and return.
                                $jpeg_header_data[$i]['SegData'] = $packed_data;
                                return $jpeg_header_data;
                        }
                }
        }

        // No preexisting JFIF block found, insert a new one at the start of the header data.
        array_splice($jpeg_header_data, 0 , 0, array( array(   "SegType" => 0xE0,
                                                                "SegName" => "APP0",
                                                                "SegDesc" => $GLOBALS[ "JPEG_Segment_Descriptions" ][ 0xE0 ],
                                                                "SegData" => $packed_data ) ) );
        return $jpeg_header_data;
}

/******************************************************************************
* End of Function:     put_JFIF
******************************************************************************/








/******************************************************************************
*
* Function:     Interpret_JFIF_to_HTML
*
* Description:  Generates html showing the JFIF information contained in
*               a JFIF data array, as retrieved with get_JFIF
*
* Parameters:   JFIF_array - a JFIF data array, as from get_JFIF
*               filename - the name of the JPEG file being processed ( used
*                          by the script which displays the JFIF thumbnail)
*
*
* Returns:      output - the HTML string
*
******************************************************************************/

function Interpret_JFIF_to_HTML( $JFIF_array, $filename )
{
        $output = "";
        if ( $JFIF_array !== FALSE )
        {
                $output .= "<H2 class=\"JFIF_Main_Heading\">Contains JPEG File Interchange Format (JFIF) Information</H2>\n";
                $output .= "\n<table class=\"JFIF_Table\" border=1>\n";
                $output .= "<tr class=\"JFIF_Table_Row\"><td class=\"JFIF_Caption_Cell\">JFIF version: </td><td class=\"JFIF_Value_Cell\">". sprintf( "%d.%02d", $JFIF_array['Version1'], $JFIF_array['Version2'] ) . "</td></tr>\n";
                if ( $JFIF_array['Units'] == 0 )
                {
                        $output .= "<tr class=\"JFIF_Table_Row\"><td class=\"JFIF_Caption_Cell\">Pixel Aspect Ratio: </td><td class=\"JFIF_Value_Cell\">" . $JFIF_array['XDensity'] ." x " . $JFIF_array['YDensity'] . "</td></tr>\n";
                }
                elseif ( $JFIF_array['Units'] == 1 )
                {
                        $output .= "<tr class=\"JFIF_Table_Row\"><td class=\"JFIF_Caption_Cell\">Resolution: </td><td class=\"JFIF_Value_Cell\">" . $JFIF_array['XDensity'] ." x " . $JFIF_array['YDensity'] . " pixels per inch</td></tr>\n";
                }
                elseif ( $JFIF_array['Units'] == 2 )
                {
                        $output .= "<tr class=\"JFIF_Table_Row\"><td class=\"JFIF_Caption_Cell\">Resolution: </td><td class=\"JFIF_Value_Cell\">" . $JFIF_array['XDensity'] ." x " . $JFIF_array['YDensity'] . " pixels per cm</td></tr>\n";
                }

                $output .= "<tr class=\"JFIF_Table_Row\"><td class=\"JFIF_Caption_Cell\">JFIF (uncompressed) thumbnail: </td><td class=\"JFIF_Value_Cell\">";
                if ( ( $JFIF_array['ThumbX'] != 0 ) && ( $JFIF_array['ThumbY'] != 0 ) )
                {
                        $output .= $JFIF_array['ThumbX'] ." x " . $JFIF_array['ThumbY'] . " pixels, Thumbnail Display Not Yet Implemented</td></tr>\n";
                        // TODO Implement JFIF Thumbnail display
                }
                else
                {
                        $output .= "None</td></tr>\n";
                }

                $output .= "</table><br>\n";
        }

        return $output;

}


/******************************************************************************
* End of Function:     Interpret_JFIF_to_HTML
******************************************************************************/










/******************************************************************************
*
* Function:     get_JFXX
*
* Description:  Retrieves information from a JPEG File Interchange Format Extension (JFXX)
*               segment and returns it in an array. Uses information supplied by
*               the get_jpeg_header_data function
*
* Parameters:   jpeg_header_data - a JPEG header data array in the same format
*                                  as from get_jpeg_header_data
*
* Returns:      JFXX_data - an array of JFXX data
*               FALSE - if a JFXX segment could not be found
*
******************************************************************************/

function get_JFXX( $jpeg_header_data )
{
        //Cycle through the header segments
        for( $i = 0; $i < count( $jpeg_header_data ); $i++ )
        {
                // If we find an APP0 header,
                if ( strcmp ( $jpeg_header_data[$i]['SegName'], "APP0" ) == 0 )
                {
                        // And if it has the JFIF label,
                        if( strncmp ( $jpeg_header_data[$i]['SegData'], "JFXX\x00", 5) == 0 )
                        {
                                // Found a JPEG File Interchange Format Extension (JFXX) Block

                                // unpack the JFXX data from the incoming string
                                // First is the 5 byte JFXX label string
                                // Then a 1 byte Extension code, indicating Thumbnail Format
                                // Then the thumbnail data

                                $JFXX_data = unpack( 'a5JFXX/CExtension_Code/a*ThumbData', $jpeg_header_data[$i]['SegData'] );
                                return $JFXX_data;
                        }
                }
        }
        return FALSE;
}

/******************************************************************************
* End of Function:     get_JFXX
******************************************************************************/




/******************************************************************************
*
* Function:     put_JFXX
*
* Description:  Creates a new JFXX segment from an array of JFXX data in the
*               same format as would be retrieved from get_JFXX, and inserts
*               this segment into the supplied JPEG header array
*
* Parameters:   jpeg_header_data - a JPEG header data array in the same format
*                                  as from get_jpeg_header_data, into which the
*                                  new JFXX segment will be put
*               new_JFXX_array - a JFXX information array in the same format as
*                                from get_JFXX, to create the new segment
*
* Returns:      jpeg_header_data - the JPEG header data array with the new
*                                  JFXX segment added
*
******************************************************************************/

function put_JFXX( $jpeg_header_data, $new_JFXX_array )
{
        // pack the JFXX data into its proper format for a JPEG file
        $packed_data = pack( 'a5Ca*',"JFXX\x00", $new_JFXX_array['Extension_Code'], $new_JFXX_array['ThumbData'] );

        $JFIF_pos = -1;

        //Cycle through the header segments
        for( $i = 0; $i < count( $jpeg_header_data ); $i++ )
        {
                // If we find an APP0 header,
                if ( strcmp ( $jpeg_header_data[$i]['SegName'], "APP0" ) == 0 )
                {
                        // And if it has the JFXX label,
                        if( strncmp ( $jpeg_header_data[$i]['SegData'], "JFXX\x00", 5) == 0 )
                        {
                                // Found a preexisting JFXX block - Replace it with the new one and return.
                                $jpeg_header_data[$i]['SegData'] = $packed_data;
                                return $jpeg_header_data;
                        }

                        // if it has the JFIF label,
                        if( strncmp ( $jpeg_header_data[$i][SegData], "JFIF\x00", 5) == 0 )
                        {
                                // Found a preexisting JFIF block - Mark it in case we need to insert the JFXX after it
                                $JFIF_pos = $i;
                        }
                }
        }


        // No preexisting JFXX block found

        // Check if a JFIF segment was found,
        if ( $JFIF_pos !== -1 )
        {
                // A pre-existing JFIF segment was found,
                // insert the new JFXX segment after it.
                array_splice($jpeg_header_data, $JFIF_pos +1 , 0, array ( array(        "SegType" => 0xE0,
                                                                                        "SegName" => "APP0",
                                                                                        "SegDesc" => $GLOBALS[ "JPEG_Segment_Descriptions" ][ 0xE0 ],
                                                                                        "SegData" => $packed_data ) ) );

        }
        else
        {
                // No pre-existing JFIF segment was found,
                // insert a new JFIF and the new JFXX segment at the start of the array.

                // Insert new JFXX segment
                array_splice($jpeg_header_data, 0 , 0, array( array(   "SegType" => 0xE0,
                                                                        "SegName" => "APP0",
                                                                        "SegDesc" => $GLOBALS[ "JPEG_Segment_Descriptions" ][ 0xE0 ],
                                                                        "SegData" => $packed_data ) ) );

                // Create a new JFIF to be inserted at the start of
                // the array, with generic values
                $packed_data = pack( 'a5CCCnnCCa*',"JFIF\x00", 1, 2, 1, 72, 72, 0, 0, "" );

                array_splice($jpeg_header_data, 0 , 0, array( array(   "SegType" => 0xE0,
                                                                        "SegName" => "APP0",
                                                                        "SegDesc" => $GLOBALS[ "JPEG_Segment_Descriptions" ][ 0xE0 ],
                                                                        "SegData" => $packed_data ) ) );
        }


        return $jpeg_header_data;
}

/******************************************************************************
* End of Function:     put_JFIF
******************************************************************************/



/******************************************************************************
*
* Function:     Interpret_JFXX_to_HTML
*
* Description:  Generates html showing the JFXX thumbnail contained in
*               a JFXX data array, as retrieved with get_JFXX
*
* Parameters:   JFXX_array - a JFXX information array in the same format as
*                            from get_JFXX, to create the new segment
*               filename - the name of the JPEG file being processed ( used
*                          by the script which displays the JFXX thumbnail)
*
* Returns:      output - the Html string
*
******************************************************************************/

function Interpret_JFXX_to_HTML( $JFXX_array, $filename )
{
        $output = "";
        if ( $JFXX_array !== FALSE )
        {
                $output .= "<H2 class=\"JFXX_Main_Heading\">Contains JPEG File Interchange Extension Format  (JFXX) Thumbnail</H2>\n";
                switch ( $JFXX_array['Extension_Code'] )
                {
                        case 0x10 :     $output .= "<p class=\"JFXX_Text\">JFXX Thumbnail is JPEG Encoded</p>\n";

                                        // Change: as of version 1.11 - Changed to make thumbnail link portable across directories
                                        // Build the path of the thumbnail script and its filename parameter to put in a url
                                        $link_str = get_relative_path( dirname(__FILE__) . "/get_JFXX_thumb.php" , getcwd ( ) );
                                        $link_str .= "?filename=";
                                        $link_str .= get_relative_path( $filename, dirname(__FILE__) );

                                        // Add thumbnail link to html
                                        $output .= "<a class=\"JFXX_Thumbnail_Link\" href=\"$link_str\"><img  class=\"JFXX_Thumbnail\" src=\"$link_str\"></a>\n";
                                        break;
                        case 0x11 :     $output .= "<p class=\"JFXX_Text\">JFXX Thumbnail is Encoded 1 byte/pixel</p>\n";
                                        $output .= "<p class=\"JFXX_Text\">Thumbnail Display Not Implemented Yet</p>\n";
                                        break;
                        case 0x13 :     $output .= "<p class=\"JFXX_Text\">JFXX Thumbnail is Encoded 3 bytes/pixel</p>\n";
                                        $output .= "<p class=\"JFXX_Text\">Thumbnail Display Not Implemented Yet</p>\n";
                                        break;
                        default :       $output .= "<p class=\"JFXX_Text\">JFXX Thumbnail is Encoded with Unknown format</p>\n";
                                        break;

                        // TODO: Implement JFXX one and three bytes per pixel thumbnail decoding
                }

        }

        return $output;

}

/******************************************************************************
* End of Function:     Interpret_JFXX_to_HTML
******************************************************************************/




?>