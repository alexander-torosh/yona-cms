<?php

/******************************************************************************
*
* Filename:     olympus.php
*
* Description:  Olympus Makernote Parser
*               Provides functions to decode an Olympus EXIF makernote and to interpret
*               the resulting array into html.
*
*               Olympus Makernote Format:
*
*               Field           Size            Description
*               ----------------------------------------------------------------
*               Header          7 Bytes         "OLYMP\x00\x01" or "OLYMP\x00\x02"
*               Unknown         1 Bytes         Unknown
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
* Revision:     1.11
*
* Changes:      1.00 -> 1.11 : changed get_Olympus_Makernote_Html to allow thumbnail links to work when
*                              toolkit is portable across directories
*
* URL:          http://electronics.ozhiker.com
*
* Copyright:    Copyright Evan Hunter 2004
*               This file may be used freely for non-commercial purposes.For
*               commercial uses please contact the author: evan@ozhiker.com
*
******************************************************************************/



// Add the parser and interpreter functions to the list of Makernote parsers and interpreters.

$GLOBALS['Makernote_Function_Array']['Read_Makernote_Tag'][] = "get_Olympus_Makernote";
$GLOBALS['Makernote_Function_Array']['get_Makernote_Text_Value'][] = "get_Olympus_Text_Value";
$GLOBALS['Makernote_Function_Array']['Interpret_Makernote_to_HTML'][] = "get_Olympus_Makernote_Html";




include_once dirname(__FILE__) .'/../pjmt_utils.php';          // Change: as of version 1.11 - added to allow directory portability




/******************************************************************************
*
* Function:     get_Olympus_Makernote
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

function get_Olympus_Makernote( $Makernote_Tag, $EXIF_Array, $filehnd, $Make_Field )
{

        // Check if the Make Field contains the word Olympus
        if ( stristr( $Make_Field, "Olympus" ) === FALSE )
        {
                return FALSE;
        }

        // Check if the header exists at the start of the Makernote
        if ( ( substr( $Makernote_Tag['Data'], 0, 7 ) != "OLYMP\x00\x01" ) &&
             ( substr( $Makernote_Tag['Data'], 0, 7 ) != "OLYMP\x00\x02" ) )
        {
                // This isn't a Olympus Makernote, abort
                return FALSE ;
        }



        // Seek to the start of the IFD
        fseek($filehnd, $Makernote_Tag['Tiff Offset'] + $Makernote_Tag['Offset'] + 8 );

        // Read the IFD(s) into an array
        $Makernote_Tag['Decoded Data'] = read_Multiple_IFDs( $filehnd, $Makernote_Tag['Tiff Offset'], $Makernote_Tag['ByteAlign'], "Olympus" );

        // Save some information into the Tag element to aid interpretation
        $Makernote_Tag['Decoded'] = TRUE;
        $Makernote_Tag['Makernote Type'] = "Olympus";
        $Makernote_Tag['Makernote Tags'] = "Olympus";


        // Return the new tag
        return $Makernote_Tag;
}

/******************************************************************************
* End of Function:     get_Olympus_Makernote
******************************************************************************/







/******************************************************************************
*
* Function:     get_Olympus_Text_Value
*
* Description:  Provides a text value for any tag marked as special for makernotes
*               that this script can decode. Returns false if this is not a makernote
*               that can be processed with this script
*
* Parameters:   Exif_Tag - the element of an the Makernote array containing the
*                          tag in question, as returned from get_Olympus_Makernote
*               Tag_Definitions_Name - The name of the Tag Definitions group
*                                      within the global array IFD_Tag_Definitions
*
*
* Returns:      output - the text value for the tag
*               FALSE - If this script could not decode the makernote, or if
*                       an error occured in decoding
*
******************************************************************************/

function get_Olympus_Text_Value( $Exif_Tag, $Tag_Definitions_Name )
{
        // Check that this tag uses the Olympus tags, otherwise it can't be decoded here
        if ( $Tag_Definitions_Name !== "Olympus" )
        {
                // Not an Olympus tag - can't decode it
                return FALSE;
        }


        // Process the tag acording to it's tag number, to produce a text value
        if ( $Exif_Tag['Tag Number'] == 0x200 )
        {
                // Special Mode Tag

                // Add info from the first value to the output string
                switch ( $Exif_Tag['Data'][0] )
                {
                        case 0: $outputstr = "Normal\n";
                                break;
                        case 2: $outputstr = "Fast\n";
                                break;
                        case 3: $outputstr = "Panorama\n";
                                break;
                        default: $outputstr = "Unknown Mode ( " . $Exif_Tag['Data'][0] . " )\n";
                                        break;
                }

                // Add info from the second value to the output string
                $outputstr .= "Sequence Number: " . $Exif_Tag['Data'][1] . "\n";

                // Add info from the third value to the output string
                switch ( $Exif_Tag['Data'][2] )
                {
                        case 0: // Do nothing
                                break;
                        case 1: $outputstr .= "Panorama Direction: Left to Right\n";
                                break;
                        case 2: $outputstr .= "Panorama Direction: Right to Left\n";
                                break;
                        case 3: $outputstr .= "Panorama Direction: Bottom to Top\n";
                                break;
                        case 4: $outputstr .= "Panorama Direction: Top to Bottom\n";
                                break;
                        default: $outputstr .= "Unknown Panorama Direction\n";
                                        break;
                }

                // Return the output string
                return $outputstr;
        }
        else
        {
                // Unknown special tag - can't process it here
                return FALSE;
        }

        // Unknown special tag - can't process it here
        return FALSE;

}

/******************************************************************************
* End of Function:     get_Olympus_Text_Value
******************************************************************************/




/******************************************************************************
*
* Function:     get_Olympus_Makernote_Html
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

function get_Olympus_Makernote_Html( $Makernote_tag, $filename )
{

        // Check that this tag uses the Olympus tags, otherwise it can't be interpreted here
        if ( $Makernote_tag['Makernote Tags'] != "Olympus" )
        {
                // Not Olympus tags - can't interpret with this function
                return FALSE;
        }

        // Check if the Decoded data is valid
        if ( $Makernote_tag['Decoded Data'][0] === FALSE )
        {
                // Decoded data is not valid - can't interpret with this function
                return FALSE;
        }

        // Minolta Thumbnail 1
        if ( ( array_key_exists( 0x0088, $Makernote_tag['Decoded Data'][0] ) ) &&
             ( $Makernote_tag['Makernote Tags'] == "Olympus" ) )
        {
                // Change: as of version 1.11 - Changed to make thumbnail link portable across directories
                // Build the path of the thumbnail script and its filename parameter to put in a url
                $link_str = get_relative_path( dirname(__FILE__)  . "/../get_minolta_thumb.php" , getcwd ( ) );
                $link_str .= "?filename=";
                $link_str .= get_relative_path( $filename, dirname(__FILE__) ."/.." );

                // Add thumbnail link to html
                $Makernote_tag['Decoded Data'][0][0x0088]['Text Value'] = "<a class=\"EXIF_Minolta_Thumb_Link\" href=\"$link_str\" ><img class=\"EXIF_Minolta_Thumb\" src=\"$link_str\"></a>";

                $Makernote_tag['Decoded Data'][0][0x0088]['Type'] = "String";
        }
        // Minolta Thumbnail 2
        if ( ( array_key_exists( 0x0081, $Makernote_tag['Decoded Data'][0] ) ) &&
             ( $Makernote_tag['Makernote Tags'] == "Olympus" ) )
        {
                // Change: as of version 1.11 - Changed to make thumbnail link portable across directories
                // Build the path of the thumbnail script and its filename parameter to put in a url
                $link_str = get_relative_path( dirname(__FILE__) . " /../get_minolta_thumb.php" , getcwd ( ) );
                $link_str .= "?filename=";
                $link_str .= get_relative_path( $filename, dirname(__FILE__) ."/.." );

                // Add thumbnail link to html
                $Makernote_tag['Decoded Data'][0][0x0081]['Text Value'] = "<a class=\"EXIF_Minolta_Thumb_Link\" href=\"$link_str\" ><img class=\"EXIF_Minolta_Thumb\" src=\"$link_str\"></a>";
                $Makernote_tag['Decoded Data'][0][0x0081]['Type'] = "String";
        }

        // Interpret the IFD and return the HTML
        return interpret_IFD( $Makernote_tag['Decoded Data'][0], $filename );

}

/******************************************************************************
* End of Function:     get_Olympus_Makernote_Html
******************************************************************************/












/******************************************************************************
* Global Variable:      IFD_Tag_Definitions, Olympus
*
* Contents:     This global variable provides definitions of the known Olympus
*               Makernote tags, indexed by their tag number.
*               It also includes Minolta and Agfa tags, as they use many of the
*               same tags
*
******************************************************************************/

$GLOBALS[ "IFD_Tag_Definitions" ]["Olympus"] = array(

0x0000 => array( 'Name' => "Makernote Version",           // Minolta
                 'Type' => "String" ),

0x0001 => array( 'Name' => "Camera Settings",           // Minolta
                 'Type' => "Special" ),

0x0003 => array( 'Name' => "Camera Settings",           // Minolta
                 'Type' => "Special" ),

0x0040 => array( 'Name' => "Compressed Image Size",           // Minolta
                 'Type' => "Numeric",
                 'Units' => "Bytes" ),

0x0081 => array( 'Name' => "Minolta Thumbnail",           // Minolta
                 'Type' => "Special" ),

0x0088 => array( 'Name' => "Minolta Thumbnail",           // Minolta
                 'Type' => "Special" ),

0x0089 => array( 'Name' => "Minolta Thumbnail Length",           // Minolta
                 'Type' => "Numeric",
                 'Units' => "bytes" ),

0x0101 => array( 'Name' => "Colour Mode",           // Minolta
                 'Type' => "Lookup",
                 0 => "Natural Colour",
                 1 => "Black & White",
                 2 => "Vivid colour",
                 3 => "Solarization",
                 4 => "AdobeRGB" ),

0x0102 => array( 'Name' => "Image Quality",           // Minolta
                 'Type' => "Lookup",
                 0 => "Raw",
                 1 => "Super Fine",
                 2 => "Fine",
                 3 => "Standard",
                 4 => "Extra Fine" ),

0x0103 => array( 'Name' => "Image Quality?",           // Minolta
                 'Type' => "Lookup",
                 0 => "Raw",
                 1 => "Super Fine",
                 2 => "Fine",
                 3 => "Standard",
                 4 => "Extra Fine" ),



0x0200 => array( 'Name' => "Special Mode",
                'Type' => "Special" ),


0x0201 => array( 'Name' => "JPEG Quality",
                 'Type' => "Lookup",
                 1 => "Standard Quality",
                 2 => "High Quality",
                 3 => "Super High Quality" ),

0x0202 => array( 'Name' => "Macro",
                 'Type' => "Lookup",
                 0 => "Normal (Not Macro)",
                 1 => "Macro" ),

0x0204 => array( 'Name' => "Digital Zoom",
                 'Type' => "Numeric",
                 'Units' => " x Digital Zoom, (0 or 1 = normal)" ),

0x0207 => array( 'Name' => "Firmware Version",
                'Type' => "String" ),


0x0208 => array( 'Name' => "Picture Info Data",
                'Type' => "String" ),

0x0209 => array( 'Name' => "Camera ID",
                'Type' => "String" ),


0x020B => array( 'Name' => "Image Width",        // Epson Tag
                'Type' => "Pixels" ),

0x020C => array( 'Name' => "Image Height",        // Epson Tag
                'Type' => "Pixels" ),

0x020D => array( 'Name' => "Original Manufacturer Model?",        // Epson Tag
                'Type' => "String" ),

0x0E00 => array( 'Name'=> "Print Image Matching Info",     // Minolta Tag
                 'Type' => "PIM" ),

0x1004 => array( 'Name' => "Flash Mode",
                 'Type' => "Numeric" ),

0x1006 => array( 'Name' => "Bracket",
                 'Type' => "Numeric" ),

0x100B => array( 'Name' => "Focus Mode",
                 'Type' => "Numeric" ),

0x100C => array( 'Name' => "Focus Distance",
                 'Type' => "Numeric" ),

0x100D => array( 'Name' => "Zoom",
                 'Type' => "Numeric" ),

0x100E => array( 'Name' => "Macro Focus",
                 'Type' => "Numeric" ),

0x100F => array( 'Name' => "Sharpness",
                 'Type' => "Numeric" ),

0x1011 => array( 'Name' => "Colour Matrix",
                 'Type' => "Numeric" ),

0x1012 => array( 'Name' => "Black Level",
                 'Type' => "Numeric" ),

0x1015 => array( 'Name' => "White Balance",
                 'Type' => "Numeric" ),

0x1017 => array( 'Name' => "Red Bias",
                 'Type' => "Numeric" ),

0x1018 => array( 'Name' => "Blue Bias",
                 'Type' => "Numeric" ),

0x101A => array( 'Name' => "Serial Number",
                 'Type' => "Numeric" ),

0x1023 => array( 'Name' => "Flash Bias",
                 'Type' => "Numeric" ),

0x1029 => array( 'Name' => "Contrast",
                 'Type' => "Numeric" ),

0x102A => array( 'Name' => "Sharpness Factor",
                 'Type' => "Numeric" ),

0x102B => array( 'Name' => "Colour Control",
                 'Type' => "Numeric" ),

0x102C => array( 'Name' => "Valid Bits",
                 'Type' => "Numeric" ),

0x102D => array( 'Name' => "Coring Filter",
                 'Type' => "Numeric" ),

0x102E => array( 'Name' => "Final Width",
                 'Type' => "Numeric" ),

0x102F => array( 'Name' => "Final Height",
                 'Type' => "Numeric" ),

0x1034 => array( 'Name' => "Compression Ratio",
                 'Type' => "Numeric" ),



);


/******************************************************************************
* End of Global Variable:     IFD_Tag_Definitions, Olympus
******************************************************************************/





?>