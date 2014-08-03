<?php

/******************************************************************************
*
* Filename:     ricoh.php
*
* Description:  Ricoh Makernote Parser
*               Provides functions to decode an Ricoh EXIF makernote and to interpret
*               the resulting array into html.
*
*               Ricoh Makernote Format:
*
*               Type 1 - Text Makernote
*
*               Field           Size            Description
*               ----------------------------------------------------------------
*               Text            Variable        Text, beginning with "Rv" or "Rev"
*               ---------------------------------------------------------------
*
*
*               Type 2 - Empty Makernote
*
*               Field           Size            Description
*               ----------------------------------------------------------------
*               Blank           Variable        Blank field filled with 0x00 characters
*               ---------------------------------------------------------------*
*
*
*               Type 3 - IFD Makernote
*
*               Field           Size            Description
*               ----------------------------------------------------------------
*               Header          5 Bytes        "Ricoh" or "RICOH"
*               Unknown         1 Byte          Unknown field
*               Zeros           2 Bytes         Two 0x00 characters
*               IFD Data        Variable        Standard IFD Data using Ricoh Tags
*                                               with Motorola byte alignment
*               ---------------------------------------------------------------
*
*               Within Makernote Type 3, Tag 0x2001 is the Ricoh Camera Info Sub-IFD
*               It has the following format:
*
*               Field           Size            Description
*               ----------------------------------------------------------------
*               Header          19 Bytes        "[Ricoh Camera Info]"
*               Unknown         1 Byte          Unknown field
*               IFD Data        Variable        NON-Standard IFD Data using Ricoh
*                                               Sub-IFD Tags with Motorola byte alignment
*                                               and has No final Next-IFD pointer
*               ---------------------------------------------------------------
*
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

$GLOBALS['Makernote_Function_Array']['Read_Makernote_Tag'][] = "get_Ricoh_Makernote";
$GLOBALS['Makernote_Function_Array']['get_Makernote_Text_Value'][] = "get_Ricoh_Text_Value";
$GLOBALS['Makernote_Function_Array']['Interpret_Makernote_to_HTML'][] = "get_Ricoh_Makernote_Html";




/******************************************************************************
*
* Function:     get_Ricoh_Makernote
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

function get_Ricoh_Makernote( $Makernote_Tag, $EXIF_Array, $filehnd, $Make_Field )
{
        // Check if the Make Field contains the word Ricoh
        if ( stristr( $Make_Field, "Ricoh" ) === FALSE )
        {
                // Ricoh not in the Maker field - abort
                return FALSE;
        }


        // Check if the Text Makernote header exists at the start of the Makernote
        if ( ( substr( $Makernote_Tag['Data'], 0, 2 ) === "Rv" ) ||
             ( substr( $Makernote_Tag['Data'], 0, 3 ) === "Rev" ) )
        {
                // This is a text makernote - Label it as such
                $Makernote_Tag['Makernote Type'] = "Ricoh Text";
                $Makernote_Tag['Makernote Tags'] = "None";
                $Makernote_Tag['Decoded'] = TRUE;

                // Return the new Makernote tag
                return $Makernote_Tag;

        }
        // Check if the Empty Makernote header exists at the start of the Makernote
        else if ( $Makernote_Tag['Data'] === str_repeat ( "\x00", strlen( $Makernote_Tag['Data'] )) )
        {
                // This is an Empty Makernote - Label it as such
                $Makernote_Tag['Makernote Type'] = "Ricoh Empty Makernote";
                $Makernote_Tag['Makernote Tags'] = "None";
                $Makernote_Tag['Decoded'] = TRUE;

                // Return the new Makernote tag
                return $Makernote_Tag;

        }
        // Check if the IFD Makernote header exists at the start of the Makernote
        else if ( ( substr( $Makernote_Tag['Data'], 0, 5 ) === "RICOH" ) ||
                  ( substr( $Makernote_Tag['Data'], 0, 5 ) === "Ricoh" ) )
        {
                //This is an IFD Makernote

                // Seek to the start of the IFD
                fseek($filehnd, $Makernote_Tag['Tiff Offset'] + $Makernote_Tag['Offset'] + 8 );

                // Ricoh Makernote always uses Motorola Byte Alignment
                $Makernote_Tag['ByteAlign'] = "MM";

                // Read the IFD(s) into an array
                $Makernote_Tag['Decoded Data'] = read_Multiple_IFDs( $filehnd, $Makernote_Tag['Tiff Offset'], $Makernote_Tag['ByteAlign'], "Ricoh" );

                // Save some information into the Tag element to aid interpretation
                $Makernote_Tag['Decoded'] = TRUE;
                $Makernote_Tag['Makernote Type'] = "Ricoh";
                $Makernote_Tag['Makernote Tags'] = "Ricoh";

                // Ricoh Makernotes can have a tag 0x2001 which is a Sub-IFD
                // Check if the tag exists
                if  ( ( $Makernote_Tag['Decoded Data'][0] !== FALSE ) &&
                      ( array_key_exists( 0x2001, $Makernote_Tag['Decoded Data'][0] ) ) )
                {
                        // Ricoh Sub-IFD tag exists - Process it

                        // Grab the Sub-IFD tag for easier processing
                        $SubIFD_Tag = &$Makernote_Tag['Decoded Data'][0][0x2001];

                        // Check if the Sub-IFD starts with the correct header
                        if ( substr( $SubIFD_Tag['Data'], 0, 19 ) === "[Ricoh Camera Info]" )
                        {
                                // Correct Header found

                                // Seek to the start of the Sub-IFD
                                fseek($filehnd, $Makernote_Tag['Tiff Offset'] + $SubIFD_Tag['Offset'] + 20 );

                                // Ricoh Makernote Sub-IFD always uses Motorola Byte Alignment
                                $SubIFD_Tag['ByteAlign'] = "MM";


                                // Read the IFD(s) into an array
                                $SubIFD_Tag['Data'] = read_Multiple_IFDs( $filehnd, $Makernote_Tag['Tiff Offset'], $SubIFD_Tag['ByteAlign'], "RicohSubIFD", False, False );

                                // Save some information into the Tag element to aid interpretation
                                $SubIFD_Tag['Decoded'] = TRUE;
                                $SubIFD_Tag['Makernote Type'] = "Ricoh";
                                $SubIFD_Tag['Makernote Tags'] = "RicohSubIFD";

                                // Change the tag type to a Sub-IFD so it is handled automatically for interpretation
                                $SubIFD_Tag['Type'] = "SubIFD";
                                $SubIFD_Tag['Tags Name'] = "RicohSubIFD";

                        }
                        else
                        {
                                // Couldn't find header of Sub-IFD - Probably corrupt
                                $SubIFD_Tag['Type'] = "String";
                                $SubIFD_Tag['Text Value'] = "Corrupted Ricoh Sub IFD";
                        }

                }

                // Return the new makernote tag
                return $Makernote_Tag;
        }
        else
        {
                // Unrecognised header for makernote - abort
                return FALSE;
        }

        // Shouldn't get here
        return False;
}

/******************************************************************************
* End of Function:     get_Ricoh_Makernote
******************************************************************************/







/******************************************************************************
*
* Function:     get_Ricoh_Makernote_Html
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

function get_Ricoh_Makernote_Html( $Makernote_tag, $filename )
{

        // Check if this makernote is Ricoh IFD type
        if ( $Makernote_tag['Makernote Type'] == "Ricoh" )
        {
                // This is a Ricoh IFD makernote - interpret it
                return  interpret_IFD( $Makernote_tag['Decoded Data'][0], $filename );
        }
        // Check if this makernote is Ricoh Text type
        else if ( $Makernote_tag['Makernote Type'] == "Ricoh Text" )
        {
                // This is a Ricoh text makernote
                //  Construct the start of enclosing html for the text
                $output_str = "<table  class=\"EXIF_Table\"border=1><tr class=\"EXIF_Table_Row\"><td class=\"EXIF_Value_Cell\">";

                // Replace the semicolon dividers with line break html tags
                $output_str .= str_replace ( ";", "<BR>\n", $Makernote_tag['Data'] );

                // Close the html
                $output_str .= "</td></tr></table>";

                // Return the html
                return  $output_str;
        }
        // Check if this makernote is a Ricoh Empty makernote
        else if ( $Makernote_tag['Makernote Type'] == "Ricoh Empty Makernote" )
        {
                // Do Nothing
                return "";
        }
        else
        {
                // Don't recognise the Makernote type - not a Ricoh makernote
                return FALSE;
        }

        // shouldn't get here
        return FALSE;
}

/******************************************************************************
* End of Function:     get_Ricoh_Makernote_Html
******************************************************************************/








/******************************************************************************
*
* Function:     get_Ricoh_Text_Value
*
* Description:  Provides a text value for any tag marked as special for makernotes
*               that this script can decode. Returns false if this is not a makernote
*               that can be processed with this script
*
* Parameters:   Exif_Tag - the element of an the Makernote array containing the
*                          tag in question, as returned from get_Ricoh_Makernote
*               Tag_Definitions_Name - The name of the Tag Definitions group
*                                      within the global array IFD_Tag_Definitions
*
*
* Returns:      output - the text value for the tag
*               FALSE - If this script could not decode the makernote, or if
*                       an error occured in decoding
*
******************************************************************************/

function get_Ricoh_Text_Value( $Exif_Tag, $Tag_Definitions_Name )
{

        // Check that this tag uses the Ricoh tags, otherwise it can't be decoded here
        if ( $Tag_Definitions_Name == "Ricoh" )
        {

                // Process the tag acording to it's tag number, to produce a text value
                if ( $Exif_Tag['Tag Number'] == 0x0002 ) // Version tag
                {
                        $tmp = implode ( "\x00", $Exif_Tag['Data']);
                        return "\"" .HTML_UTF8_Escape( $tmp ) . "\" (" . bin2hex( $tmp ) . " hex)";
                }

        }

        // Unknown tag or tag definitions
        return FALSE;

}

/******************************************************************************
* End of Function:     get_Ricoh_Text_Value
******************************************************************************/











/******************************************************************************
* Global Variable:      IFD_Tag_Definitions, Ricoh
*
* Contents:     This global variable provides definitions of the known Ricoh
*               Makernote tags, indexed by their tag number.
*
******************************************************************************/

$GLOBALS[ "IFD_Tag_Definitions" ]["Ricoh"] = array(


0x0001 => array(        'Name' => "Makernote Data Type",
                        'Type' => "String" ),

0x0002 => array(        'Name' => "Version",
                        'Type' => "Special" ),

0x0e00 => array(        'Name' => "Print Image Matching Info",
                        'Type' => "PIM" ),

0x2001 => array(        'Name' => "Ricoh Camera Info Makernote Sub-IFD",
                        'Type' => "Special" ),

);

/******************************************************************************
* End of Global Variable:     IFD_Tag_Definitions, Ricoh
******************************************************************************/








/******************************************************************************
* Global Variable:      IFD_Tag_Definitions, RicohSubIFD
*
* Contents:     This global variable provides definitions of the known Ricoh
*               Camera Info Sub-IFD Makernote tags, indexed by their tag number.
*
******************************************************************************/

$GLOBALS[ "IFD_Tag_Definitions" ]["RicohSubIFD"] = array(

);

/******************************************************************************
* End of Global Variable:     IFD_Tag_Definitions, RicohSubIFD
******************************************************************************/






?>