<?php

/******************************************************************************
*
* Filename:     fujifilm.php
*
* Description:  Fujifilm Makernote Parser
*               Provides functions to decode an Fujifilm EXIF makernote and to interpret
*               the resulting array into html.
*               This Makernote format is also used by one Nikon Camera
*
*               Fujifilm Makernote Format:
*
*               Field           Size            Description
*               ----------------------------------------------------------------
*               Header          8 Bytes         "FUJIFILM"
*               IFD Offset      4 Bytes         Intel Byte aligned offset to IFD from start of Makernote
*               IFD Data        Variable        NON-Standard IFD Data using Fujifilm Tags
*                                               Offsets are relative to start of makernote
*                                               Byte alignment is always Intel
*               ----------------------------------------------------------------
*
*
* Author:       Evan Hunter
*
* Date:         30/7/2004
*
* Project:      JPEG Metadata
*
* Revision:     1.00
*
* URL:          http://electronics.ozhiker.com
*
* Copyright:    Copyright Evan Hunter 2004
*               This file may be used freely for non-commercial purposes.For
*               commercial uses please contact the author: evan@ozhiker.com
*
******************************************************************************/



// Add the parser and interpreter functions to the list of Makernote parsers and interpreters.

$GLOBALS['Makernote_Function_Array']['Read_Makernote_Tag'][] = "get_Fujifilm_Makernote";
$GLOBALS['Makernote_Function_Array']['get_Makernote_Text_Value'][] = "get_Fujifilm_Text_Value";
$GLOBALS['Makernote_Function_Array']['Interpret_Makernote_to_HTML'][] = "get_Fujifilm_Makernote_Html";






/******************************************************************************
*
* Function:     get_Fujifilm_Makernote
*
* Description:  Decodes the Makernote tag and returns the new tag with the decoded
*               information attached. Returns false if this is not a makernote
*               that can be processed with this script
*
* Parameters:   Makernote_Tag - the element of an EXIF array containing the
*                               makernote, as returned from get_EXIF_JPEG
*               EXIF_Array - the entire EXIF array containing the
*                            makernote, as returned from get_EXIF_JPEG, in
*                            case more information is required for decoding
*               filehnd - an open file handle for the file containing the
*                         makernote - does not have to be positioned at the
*                         start of the makernote
*               Make_Field - The contents of the EXIF Make field, to aid
*                            determining whether this script can decode
*                            the makernote
*
*
* Returns:      Makernote_Tag - the Makernote_Tag from the parameters, but
*                               modified to contain the decoded information
*               FALSE - If this script could not decode the makernote, or if
*                       an error occured in decoding
*
******************************************************************************/

function get_Fujifilm_Makernote( $Makernote_Tag, $EXIF_Array, $filehnd, $Make_Field )
{

        // Check if the Make Field contains the word Fuji or Nikon (One Nikon camera uses this format Makernote)
        if ( ( stristr( $Make_Field, "Fuji" ) === FALSE ) &&
             ( stristr( $Make_Field, "Nikon" ) === FALSE ) )
        {
                // Couldn't find Fuji or Nikon in the maker name - abort
                return FALSE;
        }
        
        // Check if the header exists at the start of the Makernote
        if ( substr( $Makernote_Tag['Data'], 0, 8 ) != "FUJIFILM" )
        {
                // This isn't a Fuji Makernote, abort
                return FALSE;
        }

        // The 4 bytes after the header are the offset to the Fujifilm IFD
        // Get the offset of the IFD
        $ifd_offset = hexdec( bin2hex( strrev( substr( $Makernote_Tag['Data'], 8, 4 ) ) ) );

        // Seek to the start of the IFD
        fseek($filehnd, $Makernote_Tag['Tiff Offset'] + $Makernote_Tag['Offset'] + $ifd_offset );

        // Fuji Makernotes are always Intel Byte Aligned
        $Makernote_Tag['ByteAlign'] = "II";
        
        // Read the IFD(s) into an array
        $Makernote_Tag['Decoded Data'] = read_Multiple_IFDs( $filehnd, $Makernote_Tag['Tiff Offset'] + $Makernote_Tag['Offset'], $Makernote_Tag['ByteAlign'], "Fujifilm" );

        // Save some information into the Tag element to aid interpretation
        $Makernote_Tag['Decoded'] = TRUE;
        $Makernote_Tag['Makernote Type'] = "Fujifilm";
        $Makernote_Tag['Makernote Tags'] = "Fujifilm";


        // Return the new tag
        return $Makernote_Tag;

}

/******************************************************************************
* End of Function:     get_Fujifilm_Makernote
******************************************************************************/









/******************************************************************************
*
* Function:     get_Fujifilm_Text_Value
*
* Description:  Provides a text value for any tag marked as special for makernotes
*               that this script can decode. Returns false if this is not a makernote
*               that can be processed with this script
*
* Parameters:   Exif_Tag - the element of an the Makernote array containing the
*                          tag in question, as returned from get_Fujifilm_Makernote
*               Tag_Definitions_Name - The name of the Tag Definitions group
*                                      within the global array IFD_Tag_Definitions
*
*
* Returns:      output - the text value for the tag
*               FALSE - If this script could not decode the makernote, or if
*                       an error occured in decoding
*
******************************************************************************/

function get_Fujifilm_Text_Value( $Exif_Tag, $Tag_Definitions_Name )
{
        // Check that this tag uses the Fujifilm tag Definitions, otherwise it can't be decoded here
        if ( $Tag_Definitions_Name == "Fujifilm" )
        {
                // No special Tags at this time
                return FALSE;
        }
        
        return FALSE;
}

/******************************************************************************
* End of Function:     get_Fujifilm_Text_Value
******************************************************************************/










/******************************************************************************
*
* Function:     get_Fujifilm_Makernote_Html
*
* Description:  Attempts to interpret a makernote into html. Returns false if
*               it is not a makernote that can be processed with this script
*
* Parameters:   Makernote_Tag - the element of an EXIF array containing the
*                               makernote, as returned from get_EXIF_JPEG
*               filename - the name of the JPEG file being processed ( used
*                          by scripts which display embedded thumbnails)
*
*
* Returns:      output - the html representing the makernote
*               FALSE - If this script could not interpret the makernote, or if
*                       an error occured in decoding
*
******************************************************************************/

function get_Fujifilm_Makernote_Html( $Makernote_tag, $filename )
{
        // Check that this tag uses the Fujifilm tags, otherwise it can't be interpreted here
        if ( $Makernote_tag['Makernote Type'] != "Fujifilm" )
        {
                // Not Fujifilm tags - can't interpret with this function
                return FALSE;
        }
        
        // Interpret the IFD normally
        return interpret_IFD( $Makernote_tag['Decoded Data'][0], $filename );

}

/******************************************************************************
* End of Function:     get_Fujifilm_Makernote_Html
******************************************************************************/













/******************************************************************************
* Global Variable:      IFD_Tag_Definitions, Fujifilm
*
* Contents:     This global variable provides definitions of the known Fujifilm
*               Makernote tags, indexed by their tag number.
*
******************************************************************************/

$GLOBALS[ "IFD_Tag_Definitions" ]["Fujifilm"] = array(

0 => array(     'Name' => "Version",
                'Type' => "String" ),

4096 => array(  'Name' => "Quality",
                'Type' => "String" ),

4097 => array(  'Name' => "Sharpness",
                'Type' => "Lookup",
                1 => "Softest",
                2 => "Soft",
                3 => "Normal",
                4 => "Hard",
                5 => "Hardest" ),

4098 => array(  'Name' => "White Balance",
                'Type' => "Lookup",
                0 => "Auto",
                256 => "Daylight",
                512 => "Cloudy",
                768 => "DaylightColour-fluorescence",
                769 => "DaywhiteColour-fluorescence",
                770 => "White-fluorescence",
                1024 => "Incandenscense",
                3840 => "Custom white balance" ),

4099 => array(  'Name' => "Colour Saturation",
                'Type' => "Lookup",
                0 => "Normal",
                256 => "High",
                512 => "Low" ),

4100 => array(  'Name' => "Tone (Contrast)",
                'Type' => "Lookup",
                0 => "Normal",
                256 => "High",
                512 => "Low" ),

4112 => array(  'Name' => "Flash Mode",
                'Type' => "Lookup",
                0 => "Auto",
                1 => "On",
                2 => "Off",
                3 => "Red-eye Reduction" ),

4113 => array(  'Name' => "Flash Strength",
                'Type' => "Numeric",
                'Units' => "EV" ),

4128 => array(  'Name' => "Macro",
                'Type' => "Lookup",
                0 => "Off",
                1 => "On" ),

4129 => array(  'Name' => "Focus Mode",
                'Type' => "Lookup",
                0 => "Auto Focus",
                1 => "Manual Focus" ),

4144 => array(  'Name' => "Slow Sync",
                'Type' => "Lookup",
                0 => "Off",
                1 => "On" ),

4145 => array(  'Name' => "Picture Mode",
                'Type' => "Lookup",
                0 => "Auto",
                1 => "Portrait Scene",
                2 => "Landscape Scene",
                4 => "Sports Scene",
                5 => "Night Scene",
                6 => "Program AE",
                256 => "Aperture priority AE",
                512 => "Shutter priority AE",
                768 => "Manual Exposure" ),

4352 => array(  'Name' => "Continuous taking or auto bracketing mode",
                'Type' => "Lookup",
                0 => "Off",
                1 => "On" ),

4864 => array(  'Name' => "Blur Warning",
                'Type' => "Lookup",
                0 => "No Blur Warning",
                1 => "Blur Warning" ),

4865 => array(  'Name' => "Focus warning",
                'Type' => "Lookup",
                0 => "Auto Focus Good",
                1 => "Out of Focus" ),

4866 => array(  'Name' => "Auto Exposure Warning",
                'Type' => "Lookup",
                0 => "Auto Exposure Good",
                1 => "Over exposure (>1/1000s,F11)" )

);

/******************************************************************************
* End of Global Variable:     IFD_Tag_Definitions, Fujifilm
******************************************************************************/





?>
