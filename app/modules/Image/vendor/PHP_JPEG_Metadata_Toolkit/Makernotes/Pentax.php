<?php

/******************************************************************************
*
* Filename:     pentax.php
*
* Description:  Pentax (Asahi) Makernote Parser
*               Provides functions to decode an Pentax (Asahi) EXIF makernote and to interpret
*               the resulting array into html.
*
*               Pentax Makernote Format:
*
*               Type 1
*
*               Field           Size            Description
*               ----------------------------------------------------------------
*               IFD Data        Variable        NON-Standard IFD Data using Pentax Tags
*                                               IFD has no Next-IFD pointer at end of IFD,
*                                               and Offsets are relative to the start
*                                               of the current IFD tag, not the TIFF header
*               ----------------------------------------------------------------
*
*
*               Type 2
*
*               Field           Size            Description
*               ----------------------------------------------------------------
*               Header          4 Bytes         "AOC\x00"
*               Unknown         2 Bytes         Unknown field
*               IFD Data        Variable        NON-Standard IFD Data using Casio Type 2 Tags
*                                               IFD has no Next-IFD pointer at end of IFD,
*                                               and Offsets are relative to the start
*                                               of the current IFD tag, not the TIFF header
*               ----------------------------------------------------------------
*
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


// Pentax Type 2 makernote uses Casio Type 2 tags - ensure they are included

include_once 'casio.php';



// Add the parser and interpreter functions to the list of Makernote parsers and interpreters.

$GLOBALS['Makernote_Function_Array']['Read_Makernote_Tag'][] = "get_Pentax_Makernote";
$GLOBALS['Makernote_Function_Array']['get_Makernote_Text_Value'][] = "get_Pentax_Text_Value";
$GLOBALS['Makernote_Function_Array']['Interpret_Makernote_to_HTML'][] = "get_Pentax_Makernote_Html";







/******************************************************************************
*
* Function:     get_Pentax_Makernote
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

function get_Pentax_Makernote( $Makernote_Tag, $EXIF_Array, $filehnd, $Make_Field )
{
        // Check if the Make Field contains the word Pentax or Asahi
        if ( ( stristr( $Make_Field, "Pentax" ) === FALSE ) &&
             ( stristr( $Make_Field, "Asahi" ) === FALSE ) )
        {
                // Couldn't find Pentax or Asahi in the maker - abort
                return FALSE;
        }

        // Check if the header exists at the start of the Makernote
        if ( substr( $Makernote_Tag['Data'], 0, 4 ) == "AOC\x00" )
        {
                // Type 2 Pentax Makernote
                
                // Seek to the start of the IFD
                fseek($filehnd, $Makernote_Tag['Tiff Offset'] + $Makernote_Tag['Offset'] + 6 );

                // Read the IFD(s) into an array
                $Makernote_Tag['Decoded Data'] = read_Multiple_IFDs( $filehnd, $Makernote_Tag['Tiff Offset'], $Makernote_Tag['ByteAlign'], "Casio Type 2" );

                // Save some information into the Tag element to aid interpretation
                $Makernote_Tag['Decoded'] = TRUE;
                $Makernote_Tag['Makernote Type'] = "Casio Type 2";
                $Makernote_Tag['Makernote Tags'] = "Casio Type 2";

                // Return the new tag
                return $Makernote_Tag;
        }
        else
        {
                // Type 1 Penax Makernote
                
                // Seek to the start of the IFD
                fseek($filehnd, $Makernote_Tag['Tiff Offset'] + $Makernote_Tag['Offset'] + 0 );

                // Read the IFD(s) into an array
                $Makernote_Tag['Decoded Data'] = read_Multiple_IFDs( $filehnd, $Makernote_Tag['Tiff Offset'], $Makernote_Tag['ByteAlign'], "Pentax" );

                // Save some information into the Tag element to aid interpretation
                $Makernote_Tag['Decoded'] = TRUE;
                $Makernote_Tag['Makernote Type'] = "Pentax";
                $Makernote_Tag['Makernote Tags'] = "Pentax";

                // Return the new tag
                return $Makernote_Tag;
        }


        // Shouldn't get here
        return FALSE;
}

/******************************************************************************
* End of Function:     get_Pentax_Makernote
******************************************************************************/







/******************************************************************************
*
* Function:     get_Pentax_Text_Value
*
* Description:  Provides a text value for any tag marked as special for makernotes
*               that this script can decode. Returns false if this is not a makernote
*               that can be processed with this script
*
* Parameters:   Exif_Tag - the element of an the Makernote array containing the
*                          tag in question, as returned from get_Pentax_Makernote
*               Tag_Definitions_Name - The name of the Tag Definitions group
*                                      within the global array IFD_Tag_Definitions
*
*
* Returns:      output - the text value for the tag
*               FALSE - If this script could not decode the makernote, or if
*                       an error occured in decoding
*
******************************************************************************/

function get_Pentax_Text_Value( $Exif_Tag, $Tag_Definitions_Name )
{
        // Check that this tag uses the Pentax tags, otherwise it can't be interpreted here
        if ( $Tag_Definitions_Name == "Pentax" )
        {
                // No Special Tags so far
                return FALSE;
        }

        return FALSE;
}

/******************************************************************************
* End of Function:     get_Pentax_Text_Value
******************************************************************************/






/******************************************************************************
*
* Function:     get_Pentax_Makernote_Html
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

function get_Pentax_Makernote_Html( $Makernote_tag, $filename )
{
        // Check that this is a Pentax type makernote
        if ( $Makernote_tag['Makernote Type'] != "Pentax" )
        {
                // Not a Pentax makernote - abort
                return False;
        }

        // Interpret the IFD and return the html
        return interpret_IFD( $Makernote_tag['Decoded Data'][0], $filename );

}

/******************************************************************************
* End of Function:     get_Pentax_Makernote_Html
******************************************************************************/










/******************************************************************************
* Global Variable:      IFD_Tag_Definitions, Pentax
*
* Contents:     This global variable provides definitions of the known Pentax Type 1
*               Makernote tags, indexed by their tag number.
*
******************************************************************************/

$GLOBALS[ "IFD_Tag_Definitions" ]["Pentax"] = array(


0x0001 => array(        'Name' => "Capture Mode",
                        'Type' => "Lookup",
                        0 => "Auto",
                        1 => "Night-scene",
                        2 => "Manual",
                        4 => "Multiple" ),

0x0002 => array(        'Name' => "Quality Level",
                        'Type' => "Lookup",
                        0 => "Good",
                        1 => "Better",
                        2 => "Best" ),

0x0003 => array(        'Name' => "Focus Mode",
                        'Type' => "Lookup",
                        2 => "Custom",
                        3 => "Auto" ),

0x0004 => array(        'Name' => "Flash Mode",
                        'Type' => "Lookup",
                        1 => "Auto",
                        2 => "Flash on",
                        4 => "Flash off",
                        6 => "Red-eye Reduction" ),

0x0007 => array(        'Name' => "White Balance",
                        'Type' => "Lookup",
                        0 => "Auto",
                        1 => "Daylight",
                        2 => "Shade",
                        3 => "Tungsten",
                        4 => "Fluorescent",
                        5 => "Manual" ),


0x000a => array(        'Name' => "Digital Zoom",
                        'Type' => "Numeric",
                        'Units' => "  (0 = Off)" ),

0x000b => array(        'Name' => "Sharpness",
                        'Type' => "Lookup",
                        0 => "Normal",
                        1 => "Soft",
                        2 => "Hard" ),

0x000c => array(        'Name' => "Contrast",
                        'Type' => "Lookup",
                        0 => "Normal",
                        1 => "Low",
                        2 => "High" ),

0x000d => array(        'Name' => "Saturation",
                        'Type' => "Lookup",
                        0 => "Normal",
                        1 => "Low",
                        2 => "High" ),

0x0014 => array(        'Name' => "ISO Speed",
                        'Type' => "Lookup",
                        10 => "100",
                        16 => "200",
                        100 => "100",
                        200 => "200" ),

0x0017 => array(        'Name' => "Colour",
                        'Type' => "Lookup",
                        1 => "Normal",
                        2 => "Black & White",
                        3 => "Sepia" ),

0x0e00 => array(        'Name' => "Print Image Matching Info",
                        'Type' => "PIM" ),

0x1000 => array(        'Name' => "Time Zone",
                        'Type' => "String" ),

0x1001 => array(        'Name' => "Daylight Savings",
                        'Type' => "String" ),





);

/******************************************************************************
* End of Global Variable:     IFD_Tag_Definitions, Pentax
******************************************************************************/



?>
