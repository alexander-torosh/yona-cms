<?php

/******************************************************************************
*
* Filename:     kyocera.php
*
* Description:  Kyocera Makernote Parser
*               Provides functions to decode an Kyocera EXIF makernote and to interpret
*               the resulting array into html. Includes Kyocera's Contax brand
*
*               Kyocera Makernote Format:
*
*               Field           Size            Description
*               ----------------------------------------------------------------
*               Header          22 Bytes        "KYOCERA            \x00\x00\x00"
*               IFD Data        Variable        NON-Standard IFD Data using Kyocera Tags
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


// Add the parser and interpreter functions to the list of Makernote parsers and interpreters.

$GLOBALS['Makernote_Function_Array']['Read_Makernote_Tag'][] = "get_Kyocera_Makernote";
$GLOBALS['Makernote_Function_Array']['get_Makernote_Text_Value'][] = "get_Kyocera_Text_Value";
$GLOBALS['Makernote_Function_Array']['Interpret_Makernote_to_HTML'][] = "get_Kyocera_Makernote_Html";




/******************************************************************************
*
* Function:     get_Kyocera_Makernote
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

function get_Kyocera_Makernote( $Makernote_Tag, $EXIF_Array, $filehnd, $Make_Field )
{

        // Check if the Make Field contains the word Contax or Kyocera
        if ( ( stristr( $Make_Field, "Contax" ) === FALSE ) &&
             ( stristr( $Make_Field, "Kyocera" ) === FALSE ) )
        {
                // Kyocera or Contax not found in maker field - abort
                return FALSE;
        }


        // Check if the header exists at the start of the Makernote
        if ( substr( $Makernote_Tag['Data'], 0, 22 ) != "KYOCERA            \x00\x00\x00" )
        {
                // This isn't a Kyocera Makernote, abort
                return FALSE ;
        }


        // Seek to the start of the IFD
        fseek($filehnd, $Makernote_Tag['Tiff Offset'] + $Makernote_Tag['Offset'] + 22 );

        // Read the IFD(s) into an array
        $Makernote_Tag['Decoded Data'] = read_Multiple_IFDs( $filehnd, $Makernote_Tag['Tiff Offset'], $Makernote_Tag['ByteAlign'], "Kyocera", True, False );

        // Save some information into the Tag element to aid interpretation
        $Makernote_Tag['Decoded'] = TRUE;
        $Makernote_Tag['Makernote Type'] = "Kyocera";
        $Makernote_Tag['Makernote Tags'] = "Kyocera";


        // Return the new tag
        return $Makernote_Tag;
}

/******************************************************************************
* End of Function:     get_Kyocera_Makernote
******************************************************************************/






/******************************************************************************
*
* Function:     get_Kyocera_Text_Value
*
* Description:  Provides a text value for any tag marked as special for makernotes
*               that this script can decode. Returns false if this is not a makernote
*               that can be processed with this script
*
* Parameters:   Exif_Tag - the element of an the Makernote array containing the
*                          tag in question, as returned from get_Kyocera_Makernote
*               Tag_Definitions_Name - The name of the Tag Definitions group
*                                      within the global array IFD_Tag_Definitions
*
*
* Returns:      output - the text value for the tag
*               FALSE - If this script could not decode the makernote, or if
*                       an error occured in decoding
*
******************************************************************************/

function get_Kyocera_Text_Value( $Exif_Tag, $Tag_Definitions_Name )
{
        // Check that this tag uses Kyocera tags, otherwise it can't be interpreted here
        if ( $Tag_Definitions_Name == "Kyocera" )
        {
                // No Special Kyocera tags so far
                return FALSE;
        }

        return FALSE;

}

/******************************************************************************
* End of Function:     get_Kyocera_Text_Value
******************************************************************************/








/******************************************************************************
*
* Function:     get_Kyocera_Makernote_Html
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

function get_Kyocera_Makernote_Html( $Makernote_tag, $filename )
{

        // Check that this is a Kyocera Makernote, otherwise it can't be interpreted here
        if ( $Makernote_tag['Makernote Type'] != "Kyocera" )
        {
                // Not a Kyocera Makernote - cannot interpret it - abort
                return False;
        }

        // Interpret the IFD and return the HTML
        return interpret_IFD( $Makernote_tag['Decoded Data'][0], $filename );

}

/******************************************************************************
* End of Function:     get_Kyocera_Makernote_Html
******************************************************************************/








/******************************************************************************
* Global Variable:      IFD_Tag_Definitions, Kyocera
*
* Contents:     This global variable provides definitions of the known Kyocera
*               Makernote tags, indexed by their tag number.
*
******************************************************************************/

$GLOBALS[ "IFD_Tag_Definitions" ]["Kyocera"] = array(

1 => array(             'Name' => "Kyocera Proprietory Format Thumbnail",
                        'Type' => "Unknown" ),

0x0E00 => array(        'Name' => "Print Image Matching Info",
                        'Type' => "PIM" ),

);

/******************************************************************************
* End of Global Variable:     IFD_Tag_Definitions, Kyocera
******************************************************************************/








?>
