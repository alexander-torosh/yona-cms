<?php

/******************************************************************************
*
* Filename:     sony.php
*
* Description:  Sony Makernote Parser
*               Provides functions to decode an Sony EXIF makernote and to interpret
*               the resulting array into html.
*
*               Sony Makernote Format:
*
*               Field           Size            Description
*               ----------------------------------------------------------------
*               Header          12 Bytes        "SONY CAM \x00\x00\x00"
*                                               or
*                                               "SONY DSC \x00\x00\x00"
*               IFD Data        Variable        NON-Standard IFD Data using Sony Tags
*                                               IFD has no Next-IFD pointer at end of IFD,
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

$GLOBALS['Makernote_Function_Array']['Read_Makernote_Tag'][] = "get_Sony_Makernote";
$GLOBALS['Makernote_Function_Array']['get_Makernote_Text_Value'][] = "get_Sony_Text_Value";
$GLOBALS['Makernote_Function_Array']['Interpret_Makernote_to_HTML'][] = "get_Sony_Makernote_Html";





/******************************************************************************
*
* Function:     get_Sony_Makernote
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

function get_Sony_Makernote( $Makernote_Tag, $EXIF_Array, $filehnd, $Make_Field )
{

        // Check if the Make Field contains the word Sony
        if ( stristr( $Make_Field, "Sony" ) === FALSE )
        {
                // This isn't a Sony makernote
                return FALSE;
        }
                
        // Check if the header exists at the start of the Makernote
        if ( ( substr( $Makernote_Tag['Data'], 0, 12 ) != "SONY CAM \x00\x00\x00" ) &&
             ( substr( $Makernote_Tag['Data'], 0, 12 ) != "SONY DSC \x00\x00\x00" ) )
        {
                // This isn't a Sony Makernote, abort
                return FALSE ;
        }

        // Seek to the start of the IFD
        fseek($filehnd, $Makernote_Tag['Tiff Offset'] + $Makernote_Tag['Offset'] + 12 );

        // Read the IFD(s) into an array


        $Makernote_Tag['Decoded Data'] = read_Multiple_IFDs( $filehnd, $Makernote_Tag['Tiff Offset'], $Makernote_Tag['ByteAlign'], "Sony", FALSE, FALSE );

        // Save some information into the Tag element to aid interpretation
        $Makernote_Tag['Decoded'] = TRUE;
        $Makernote_Tag['Makernote Type'] = "Sony";
        $Makernote_Tag['Makernote Tags'] = "sony";


        // Return the new tag
        return $Makernote_Tag;


}

/******************************************************************************
* End of Function:     get_Sony_Makernote
******************************************************************************/






/******************************************************************************
*
* Function:     get_Sony_Text_Value
*
* Description:  Provides a text value for any tag marked as special for makernotes
*               that this script can decode. Returns false if this is not a makernote
*               that can be processed with this script
*
* Parameters:   Exif_Tag - the element of an the Makernote array containing the
*                          tag in question, as returned from get_Sony_Makernote
*               Tag_Definitions_Name - The name of the Tag Definitions group
*                                      within the global array IFD_Tag_Definitions
*
*
* Returns:      output - the text value for the tag
*               FALSE - If this script could not decode the makernote, or if
*                       an error occured in decoding
*
******************************************************************************/

function get_Sony_Text_Value( $Exif_Tag, $Tag_Definitions_Name )
{
        // Check that this tag uses the Sony tags, otherwise it can't be decoded here
        if ( $Tag_Definitions_Name == "Sony" )
        {
                // No Special Tags yet
                return FALSE;
        }

        return FALSE;
}

/******************************************************************************
* End of Function:     get_Sony_Text_Value
******************************************************************************/






/******************************************************************************
*
* Function:     get_Sony_Makernote_Html
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

function get_Sony_Makernote_Html( $Makernote_tag, $filename )
{

        // Check that this tag uses the Sony tags, otherwise it can't be interpreted here
        if ( $Makernote_tag['Makernote Type'] != "Sony" )
        {
                // Not Sony tags - can't interpret with this function
                return FALSE;
        }

        // Interpret the IFD and return the HTML
        return interpret_IFD( $Makernote_tag['Decoded Data'][0], $filename );

}

/******************************************************************************
* End of Function:     get_Sony_Makernote_Html
******************************************************************************/










/******************************************************************************
* Global Variable:      IFD_Tag_Definitions, Sony
*
* Contents:     This global variable provides definitions of the known Sony
*               Makernote tags, indexed by their tag number.
*
******************************************************************************/

$GLOBALS[ "IFD_Tag_Definitions" ]["Sony"] = array(

0xE00 => array( 'Name' => "Print Image Matching Info",
                'Type' => "PIM" ),

);

/******************************************************************************
* End of Global Variable:     IFD_Tag_Definitions, Sony
******************************************************************************/











?>
