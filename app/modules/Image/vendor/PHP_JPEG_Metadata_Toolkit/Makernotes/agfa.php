<?php

/******************************************************************************
*
* Filename:     agfa.php
*
* Description:  Agfa Makernote Parser
*               Provides functions to decode an Agfa EXIF makernote and to interpret
*               the resulting array into html.
*
*               Agfa Makernote Format:
*
*               Field           Size            Description
*               ----------------------------------------------------------------
*               Header          8 Bytes         "AGFA \x00\x01"
*               IFD Data        Variable        Standard IFD Data using Olympus Tags
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


// Agfa makernote uses Olympus tags - ensure they are included

include_once 'olympus.php';



// Add the Parser function to the list of Makernote Parsers. (Interpreter Functions are supplied by the Olympus script)

$GLOBALS['Makernote_Function_Array']['Read_Makernote_Tag'][] = "get_Agfa_Makernote";





/******************************************************************************
*
* Function:     get_Agfa_Makernote
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

function get_Agfa_Makernote( $Makernote_Tag, $EXIF_Array, $filehnd, $Make_Field )
{
        // Check if the Make Field contains the word Agfa
        if ( stristr( $Make_Field, "Agfa" ) === FALSE )
        {
                // The Make Field doesnt contain the word Agfa
                return FALSE;
        }

        // Check if the header exists at the start of the Makernote
        if ( substr( $Makernote_Tag['Data'], 0, 7 ) != "AGFA \x00\x01" )
        {
                // This isn't a Agfa Makernote, abort
                return FALSE ;
        }

        // Seek to the start of the IFD
        fseek($filehnd, $Makernote_Tag['Tiff Offset'] + $Makernote_Tag['Offset'] + 8 );

        // Read the IFD(s) into an array
        $Makernote_Tag['Decoded Data'] = read_Multiple_IFDs( $filehnd, $Makernote_Tag['Tiff Offset'], $Makernote_Tag['ByteAlign'], "Olympus" );

        // Save some information into the Tag element to aid interpretation
        $Makernote_Tag['Decoded'] = TRUE;
        $Makernote_Tag['Makernote Type'] = "Agfa";
        $Makernote_Tag['Makernote Tags'] = "Olympus";

        // Return the new tag
        return $Makernote_Tag;

}

/******************************************************************************
* End of Function:     get_Agfa_Makernote
******************************************************************************/



?>
