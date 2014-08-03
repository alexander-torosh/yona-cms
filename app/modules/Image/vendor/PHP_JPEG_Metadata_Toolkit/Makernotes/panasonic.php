<?php

/******************************************************************************
*
* Filename:     panasonic.php
*
* Description:  Panasonic Makernote Parser
*               Provides functions to decode a Panasonic EXIF makernote and to interpret
*               the resulting array into html.
*
*               Panasonic Makernote Format:
*
*               Type 1  - IFD form
*
*               Field           Size            Description
*               ----------------------------------------------------------------
*               Header          12 Bytes        "Panasonic\x00\x00\x00"
*               IFD Data        Variable        NON-Standard IFD Data using Panasonic Tags
*                                               There is no Next-IFD pointer after the IFD
*               ----------------------------------------------------------------
*
*               Type 2  - Blank (Header only)
*
*               Field           Size            Description
*               ----------------------------------------------------------------
*               Header          4 Bytes         "MKED"
*               Junk            1 or 2 bytes    Blank or Junk data
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

$GLOBALS['Makernote_Function_Array']['Read_Makernote_Tag'][] = "get_Panasonic_Makernote";
$GLOBALS['Makernote_Function_Array']['get_Makernote_Text_Value'][] = "get_Panasonic_Text_Value";
$GLOBALS['Makernote_Function_Array']['Interpret_Makernote_to_HTML'][] = "get_Panasonic_Makernote_Html";




/******************************************************************************
*
* Function:     get_Panasonic_Makernote
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

function get_Panasonic_Makernote( $Makernote_Tag, $EXIF_Array, $filehnd, $Make_Field )
{
        // Check if the Make Field contains the word Panasonic
        if ( stristr( $Make_Field, "Panasonic" ) === FALSE )
        {
                // No Panasonic in the maker - abort
                return FALSE;
        }
        

        // Check if the header exists at the start of the Makernote
        if ( substr( $Makernote_Tag['Data'], 0, 4 ) == "MKED" )
        {
                // Panasonic Type 2 - Empty Makernote
                // No Makernote Data
                $Makernote_Tag['Makernote Type'] = "Panasonic Empty Makernote";
                $Makernote_Tag['Makernote Tags'] = "-";
                $Makernote_Tag['Decoded'] = TRUE;
                
                // Return the new tag
                return $Makernote_Tag;
        }
        else if ( substr( $Makernote_Tag['Data'], 0, 12 ) == "Panasonic\x00\x00\x00" )
        {
                // Panasonic Type 1 - IFD Makernote

                // Seek to the start of the IFD
                fseek($filehnd, $Makernote_Tag['Tiff Offset'] + $Makernote_Tag['Offset'] + 12 );

                // Read the IFD(s) into an array
                $Makernote_Tag['Decoded Data'] = read_Multiple_IFDs( $filehnd, $Makernote_Tag['Tiff Offset'], $Makernote_Tag['ByteAlign'], "Panasonic", FALSE, FALSE );

                // Save some information into the Tag element to aid interpretation
                $Makernote_Tag['Decoded'] = TRUE;
                $Makernote_Tag['Makernote Type'] = "Panasonic";
                $Makernote_Tag['Makernote Tags'] = "Panasonic";

                // Return the new tag
                return $Makernote_Tag;
        }
        else
        {
                // Unknown Header
                return FALSE;
        }

        // Shouldn't get here
        return FALSE;
}

/******************************************************************************
* End of Function:     get_Panasonic_Makernote
******************************************************************************/










/******************************************************************************
*
* Function:     get_Panasonic_Text_Value
*
* Description:  Provides a text value for any tag marked as special for makernotes
*               that this script can decode. Returns false if this is not a makernote
*               that can be processed with this script
*
* Parameters:   Exif_Tag - the element of an the Makernote array containing the
*                          tag in question, as returned from get_Panasonic_Makernote
*               Tag_Definitions_Name - The name of the Tag Definitions group
*                                      within the global array IFD_Tag_Definitions
*
*
* Returns:      output - the text value for the tag
*               FALSE - If this script could not decode the makernote, or if
*                       an error occured in decoding
*
******************************************************************************/

function get_Panasonic_Text_Value( $Exif_Tag, $Tag_Definitions_Name )
{

        // Check that this tag uses the Olympus tags, otherwise it can't be decoded here
        if ( $Tag_Definitions_Name == "Panasonic" )
        {
                // No Special Tags yet
                return FALSE;
        }
        
        return FALSE;
}

/******************************************************************************
* End of Function:     get_Panasonic_Text_Value
******************************************************************************/










/******************************************************************************
*
* Function:     get_Panasonic_Makernote_Html
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

function get_Panasonic_Makernote_Html( $Makernote_tag, $filename )
{
        if ( $Makernote_tag['Makernote Type'] == "Panasonic" )
        {
                return interpret_IFD( $Makernote_tag['Decoded Data'][0], $filename );
        }
        else if ( $Makernote_tag['Makernote Type'] == "Panasonic Empty Makernote" )
        {
                // Do Nothing
                return "";
        }
        else
        {
                // Unknown Makernote Type
                return FALSE;
        }

        // Shouldn't get here
        return FALSE;
}

/******************************************************************************
* End of Function:     get_Panasonic_Makernote_Html
******************************************************************************/














/******************************************************************************
* Global Variable:      IFD_Tag_Definitions, Panasonic
*
* Contents:     This global variable provides definitions of the known Panasonic
*               Makernote tags, indexed by their tag number.
*
******************************************************************************/

$GLOBALS[ "IFD_Tag_Definitions" ]["Panasonic"] = array(

0x01 => array(  'Name' => "Quality Mode",
                'Type' => "Numeric" ),

0x02 => array(  'Name' => "Version",
                'Type' => "String" ),

0x1c => array(  'Name' => "Macro Mode",
                'Type' => "Lookup",
                1 => "On",
                2 => "Off" ),

0x1f => array(  'Name' => "Record Mode",
                'Type' => "Lookup",
                1 => "Normal",
                2 => "Portrait",
                9 => "Macro" ),

0xE00 => array( 'Name' => "Print Image Matching Info",
                'Type' => "PIM" ),

);

/******************************************************************************
* End of Global Variable:     IFD_Tag_Definitions, Panasonic
******************************************************************************/





?>
