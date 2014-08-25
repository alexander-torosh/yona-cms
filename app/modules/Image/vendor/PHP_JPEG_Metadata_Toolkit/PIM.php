<?php

/******************************************************************************
*
* Filename:     PIM.php
*
* Description:  Provides functions for reading, writing and interpreting a
*               Print Image Matching information data block.
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


include_once "EXIF.php";

// TODO Find out definitions of Print Image Matching Info tags


/******************************************************************************
*
* Function:     Decode_PIM
*
* Description:  Decodes the contents of a EXIF tag containing Print Image
*               Matching information, and returns the contents as an array
*
* Parameters:   tag - An EXIF tag containing Print Image Matching information
*                     as from get_EXIF_JPEG
*               Tag_Definitions_Name - The name of the Tag Definitions group
*                                      within the global array IFD_Tag_Definitions
*
* Returns:      newtag - The EXIF tag, modified with the data field containing
*                        an array of the PIM contents
*
******************************************************************************/

function Decode_PIM( $tag, $Tag_Definitions_Name )
{

        // Create a new EXIF tag for the output
        $newtag = $tag;

        // Check that this tag is for Print Image Matching Info
        if ( $tag['Type'] == "PIM" )
        {

                // Check that the data starts with PrintIM
                if ( substr( $tag['Data'], 0, 8 ) == "PrintIM\x00" )
                {

                        // Find the end of the version string
                        if ( ( $ver_pos = strpos ( $tag['Data'], "\0", 8 ) ) == -1 )
                        {
                                // couldn't find the start of the version string
                                return $newtag;
                        }
                        
                        // Create an array to receive the Data
                        $newtag['Data'] = array( );

                        // Extract the PrintIM version
                        $newtag['Data']['Version'] = substr( $tag['Data'], 8, $ver_pos - 8 );
                        // Skip the position over the version
                        $count_pos =  $ver_pos+2;
                        
                        // Extract the count of tags - 2 bytes
                        $PI_tag_count = get_IFD_Data_Type( substr($tag['Data'], $count_pos, 2) , 3, $tag['Byte Align'] );

                        // Panasonic have put an extra Null after the Version, which
                        // causes the tag count to be wrong -
                        // check if it is zero - i.e. possibly wrong
                        if ( ( $PI_tag_count == 0 ) )
                        {
                                // Tag count is zero - try moving the position by one,
                                // then re-extracting the count
                                $count_pos++;
                                $PI_tag_count = get_IFD_Data_Type( substr($tag['Data'], $count_pos, 2) , 3, $tag['Byte Align'] );
                        }

                        // Extract the data part of the PrintIM block
                        $data_part = substr($tag['Data'], $count_pos+2);

                        // Cycle through each tag
                        for ( $a = 0; $a < $PI_tag_count; $a++ )
                        {
                                // Read the tag number - 2 bytes
                                $PI_tag = get_IFD_Data_Type( substr($data_part, $a*6, 2) , 3, $tag['Byte Align'] );
                                
                                // Read the tag data - 4 bytes
                                $newtag['Data'][ ] = array( 'Tag Number' => $PI_tag, 'Data' => substr($data_part, $a*6+2, 4) , 'Decoded' => False );
                        }
                }
                
        }

        // Return the updated tag
        return $newtag;
        
}

/******************************************************************************
* End of Function:     Decode_PIM
******************************************************************************/




/******************************************************************************
*
* Function:     Encode_PIM
*
* Description:  Encodes the contents of a EXIF tag containing Print Image
*               Matching information, and returns the contents as a packed binary string
*
* Parameters:   tag - An EXIF tag containing Print Image Matching information
*                     as from get_EXIF_JPEG
*               Byte_Align - the Byte alignment to use - "MM" or "II"
*
* Returns:      packed_data - The packed binary string representing the PIM data
*
******************************************************************************/

function Encode_PIM( $tag, $Byte_Align)
{

        // Create a string to receive the packed data
        $packed_data = "";

        // Check that this tag is for Print Image Matching Info
        if ( $tag['Type'] == "PIM" )
        {
                // Check that the tag has been decoded - otherwise we don't need to do anything
                if ( ( is_array( $tag['Data'] ) ) &&
                     ( count ( $tag['Data'] ) > 0 ) )
                {
                        // Add the header to the packed data
                        $packed_data .= "PrintIM\x00";
                        
                        // Add the version to the packed data
                        $packed_data .= $tag['Data']['Version'] . "\x00";

                        // Create a string to receive the tag data
                        $tag_data_str = "";
                        
                        // Cycle through each tag
                        $tag_count = 0;
                        foreach( $tag['Data'] as $key => $curr_tag )
                        {
                                // Make sure this is a tag and not supplementary info
                                if ( is_numeric( $key ) )
                                {
                                        // Count how many tags are created
                                        $tag_count++;

                                        // Add the tag number to the packed tag data
                                        $tag_data_str .= put_IFD_Data_Type( $curr_tag['Tag Number'], 3, $Byte_Align );

                                        // Add the tag data to the packed tag data
                                        $tag_data_str .= $curr_tag['Data'];
                                }
                        }
                        
                        // Add the tag count to the packed data
                        $packed_data .= put_IFD_Data_Type( $tag_count, 3, $Byte_Align );
                        
                        // Add the packed tag data to the packed data
                        $packed_data .= $tag_data_str;
                }
        }
                        
        // Return the resulting packed data
        return $packed_data;

}

/******************************************************************************
* End of Function:     Encode_PIM
******************************************************************************/










/******************************************************************************
*
* Function:     get_PIM_Text_Value
*
* Description:  Interprets the contents of a EXIF tag containing Print Image
*               Matching information, and returns content as as a text string
*
* Parameters:   tag - An EXIF tag containing Print Image Matching information
*                     as from get_EXIF_JPEG
*               Tag_Definitions_Name - The name of the Tag Definitions group
*                                      within the global array IFD_Tag_Definitions
*
* Returns:      output_str - The text string representing the PIM info
*
******************************************************************************/

function get_PIM_Text_Value( $Tag, $Tag_Definitions_Name )
{

        // Create a string to receive the output
        $output_str = "";
        
        // Check if the PIM tag has been decoded
        if ( ( is_array( $Tag['Data'] ) ) &&
             ( count ( $Tag['Data'] ) > 0 ) )
        {
                // The tag has been decoded

                // Add the Version to the output
                $output_str = "Version: " . $Tag['Data']['Version'] . "\n";
                
                // Check if the user wants to hide unknown tags
                if ( $GLOBALS['HIDE_UNKNOWN_TAGS'] == FALSE )
                {
                        // The user wants to see unknown tags
                        // Cycle through each tag
                        foreach ( $Tag['Data'] as $PIM_tag_Key => $PIM_tag )
                        {
                                // Check that the tag is not the version array element
                                if ( $PIM_tag_Key !== 'Version' )
                                {
                                        // Add the tag to the output
                                        $output_str .= "Unknown Tag " . $PIM_tag['Tag Number'] . ": (" . strlen( $PIM_tag['Data'] ) . " bytes of data)\n";
                                }
                        }
                }
        }
        
        // Return the output text
        return $output_str;
}

/******************************************************************************
* End of Function:     get_PIM_Text_Value
******************************************************************************/



?>
