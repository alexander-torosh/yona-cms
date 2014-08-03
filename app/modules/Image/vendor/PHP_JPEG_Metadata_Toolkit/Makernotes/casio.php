<?php

/******************************************************************************
*
* Filename:     casio.php
*
* Description:  Casio Makernote Parser
*               Provides functions to decode an Casio EXIF makernote and to interpret
*               the resulting array into html.
*
*               Casio Makernote Format:
*
*               Type 1:
*
*               Field           Size            Description
*               ----------------------------------------------------------------
*               IFD Data        Variable        Standard IFD Data using Casio Type 1 Tags and Motorola Byte Alignment
*               ----------------------------------------------------------------
*
*               Type 2:
*
*               Field           Size            Description
*               ----------------------------------------------------------------
*               Header          6 Bytes         "QVC\x00\x00\x00"
*               IFD Data        Variable        Standard IFD Data using Casio Type 2 Tags and Motorola Byte Alignment
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
* Changes:      1.00 -> 1.11 : changed get_Casio_Makernote_Html to allow thumbnail links to work when
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

$GLOBALS['Makernote_Function_Array']['Read_Makernote_Tag'][] = "get_Casio_Makernote";
$GLOBALS['Makernote_Function_Array']['get_Makernote_Text_Value'][] = "get_Casio_Text_Value";
$GLOBALS['Makernote_Function_Array']['Interpret_Makernote_to_HTML'][] = "get_Casio_Makernote_Html";

include_once dirname(__FILE__) .'/../pjmt_utils.php';          // Change: as of version 1.11 - added to allow directory portability


/******************************************************************************
*
* Function:     get_Casio_Makernote
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

function get_Casio_Makernote( $Makernote_Tag, $EXIF_Array, $filehnd, $Make_Field )
{

        // Check if the Make Field contains the word Casio
        if ( stristr( $Make_Field, "Casio" ) === FALSE )
        {
                return FALSE;
        }


        if ( substr( $Makernote_Tag['Data'],0 , 6 ) == "QVC\x00\x00\x00" )
        {

                // Seek to the start of the IFD
                fseek($filehnd, $Makernote_Tag['Tiff Offset'] + $Makernote_Tag['Offset'] + 6 );

                $Makernote_Tag['ByteAlign'] = "MM";

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
                // Seek to the start of the IFD
                fseek($filehnd, $Makernote_Tag['Tiff Offset'] + $Makernote_Tag['Offset'] + 0 );

                $Makernote_Tag['ByteAlign'] = "MM";

                // Read the IFD(s) into an array
                $Makernote_Tag['Decoded Data'] = read_Multiple_IFDs( $filehnd, $Makernote_Tag['Tiff Offset'], $Makernote_Tag['ByteAlign'], "Casio Type 1" );

                // Save some information into the Tag element to aid interpretation
                $Makernote_Tag['Decoded'] = TRUE;
                $Makernote_Tag['Makernote Type'] = "Casio Type 1";
                $Makernote_Tag['Makernote Tags'] = "Casio Type 1";

                // Return the new tag
                return $Makernote_Tag;
        }
}

/******************************************************************************
* End of Function:     get_Casio_Makernote
******************************************************************************/




/******************************************************************************
*
* Function:     get_Casio_Text_Value
*
* Description:  Provides a text value for any tag marked as special for makernotes
*               that this script can decode. Returns false if this is not a makernote
*               that can be processed with this script
*
* Parameters:   Exif_Tag - the element of an the Makernote array containing the
*                          tag in question, as returned from get_Casio_Makernote
*               Tag_Definitions_Name - The name of the Tag Definitions group
*                                      within the global array IFD_Tag_Definitions
*
*
* Returns:      output - the text value for the tag
*               FALSE - If this script could not decode the makernote, or if
*                       an error occured in decoding
*
******************************************************************************/

function get_Casio_Text_Value( $Exif_Tag, $Tag_Definitions_Name )
{

        // Check that this tag uses the Casio tag definitions, otherwise it can't be decoded here
        if ( $Tag_Definitions_Name == "Casio Type 2" )
        {
                // Tag Uses Casio Type 2 Tag definitions
                // Process the tag according to it's tag number
                if ( $Exif_Tag['Tag Number'] == 0x001D )
                {
                        return  $Exif_Tag['Data'][0]/10 . $Exif_Tag['Units'];
                }
                else
                {
                        return FALSE;
                }
        }
        else if ( $Tag_Definitions_Name == "Casio Type 1" )
        {
                // Tag Uses Casio Type 1 Tags
                return FALSE;
        }
        else
        {
                // Tag does NOT use Casio Tag definitions
                return FALSE;
        }

        // Shouldn't get here
        return FALSE;

}

/******************************************************************************
* End of Function:     get_Casio_Text_Value
******************************************************************************/







/******************************************************************************
*
* Function:     get_Casio_Makernote_Html
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

function get_Casio_Makernote_Html( $Makernote_tag, $filename )
{
        // Check that this tag uses the Casio tags, otherwise it can't be interpreted here
        if ( ( $Makernote_tag['Makernote Type'] != "Casio Type 1" ) &&
             ( $Makernote_tag['Makernote Type'] != "Casio Type 2" ) )
        {
                // Not Casio tags - can't interpret with this function
                return FALSE;
        }

        // Casio Thumbnail (Tag 4)
        if ( ( array_key_exists( 4, $Makernote_tag['Decoded Data'][0] ) ) &&
             ( $Makernote_tag['Makernote Tags'] == "Casio Type 2" ) )
        {
                // Change: as of version 1.11 - Changed to make thumbnail link portable across directories
                // Build the path of the thumbnail script and its filename parameter to put in a url
                $link_str = get_relative_path( dirname(__FILE__) . "/get_casio_thumb.php" , getcwd ( ) );
                $link_str .= "?filename=";
                $link_str .= get_relative_path( $filename, dirname(__FILE__) );

                // Add thumbnail link to html
                $Makernote_tag['Decoded Data'][0][4]['Text Value'] = "<a class=\"EXIF_Casio_Thumb_Link\" href=\"$link_str\"><img class=\"EXIF_Casio_Thumb\" src=\"$link_str\"></a></td></tr>\n";
                $Makernote_tag['Decoded Data'][0][4]['Type'] = "String";
        }


        // Casio Thumbnail (Tag 8192)
        if ( ( array_key_exists( 8192, $Makernote_tag['Decoded Data'][0] ) ) &&
             ( $Makernote_tag['Makernote Tags'] == "Casio Type 2" ) )
        {
                // Change: as of version 1.11 - Changed to make thumbnail link portable across directories
                // Build the path of the thumbnail script and its filename parameter to put in a url
                $link_str = get_relative_path( dirname(__FILE__) . "/.." . "/get_casio_thumb.php" , getcwd ( ) );
                $link_str .= "?filename=";
                $link_str .= get_relative_path( $filename, dirname(__FILE__) . "/.." );

                // Add thumbnail link to html
                $Makernote_tag['Decoded Data'][0][8192]['Text Value'] = "<a class=\"EXIF_Casio_Thumb_Link\" href=\"$link_str\"><img class=\"EXIF_Casio_Thumb\" src=\"$link_str\"></a></td></tr>\n";
                $Makernote_tag['Decoded Data'][0][8192]['Type'] = "String";
        }


        // Check if there are two thumbnail offset tags
        if ( ( array_key_exists( 4, $Makernote_tag['Decoded Data'][0] ) ) &&
             ( array_key_exists( 8192, $Makernote_tag['Decoded Data'][0] ) ) )
        {
                // There are two copies of the thumbnail offset - Remove one
                array_splice( $Makernote_tag['Decoded Data'][0], 4, 1);
        }


        // Interpret the IFD and return the html
        return interpret_IFD( $Makernote_tag['Decoded Data'][0], $filename );

}

/******************************************************************************
* End of Function:     get_Casio_Makernote_Html
******************************************************************************/






/******************************************************************************
* Global Variable:      IFD_Tag_Definitions, Casio Type 1
*
* Contents:     This global variable provides definitions of the known Casio Type 1
*               Makernote tags, indexed by their tag number.
*
******************************************************************************/


$GLOBALS[ "IFD_Tag_Definitions" ]["Casio Type 1"] = array(

1 => array(     'Name' => "Recording Mode",
                'Type' => "Lookup",
                1 => "Single Shutter",
                2 => "Panorama",
                3 => "Night Scene",
                4 => "Portrait",
                5 => "Landscape" ),

2 => array(     'Name' => "Quality",
                'Type' => "Lookup",
                1 => "Economy",
                2 => "Normal",
                3 => "Fine" ),

3 => array(     'Name' => "Focusing Mode",
                'Type' => "Lookup",
                2 => "Macro",
                3 => "Auto Focus",
                4 => "Manual Focus",
                5 => "Infinity" ),

4 => array(     'Name' => "Flash Mode",
                'Type' => "Lookup",
                1 => "Auto",
                2 => "On",
                3 => "Off",
                4 => "Off" ),

5 => array(     'Name' => "Flash Intensity",
                'Type' => "Lookup",
                11 => "Weak",
                13 => "Normal",
                15 => "Strong" ),

6 => array(     'Name' => "Object Distance",
                'Type' => "Numeric",
                'Units' => "mm" ),

7 => array(     'Name' => "White Balance",
                'Type' => "Lookup",
                1 => "Auto",
                2 => "Tungsten",
                3 => "Daylight",
                4 => "Flourescent",
                5 => "Shade",
                129 => "Manual" ),

10 => array(    'Name' => "Digital Zoom",
                'Type' => "Lookup",
                0x10000 => "Off",
                0x10001 => "2x Digital Zoom",
                0x20000 => "2x Digital Zoom",
                0x40000 => "4x Digital Zoom" ),

11 => array(    'Name' => "Sharpness",
                'Type' => "Lookup",
                0 => "Normal",
                1 => "Soft",
                2 => "Hard" ),

12 => array(    'Name' => "Contrast",
                'Type' => "Lookup",
                0 => "Normal",
                1 => "Low",
                2 => "High" ),

13 => array(    'Name' => "Saturation",
                'Type' => "Lookup",
                0 => "Normal",
                1 => "Low",
                2 => "High" ),

20 => array(    'Name' => "CCD Sensitivity",
                'Type' => "Lookup",
                64 => "Normal",
                125 => "+1.0",
                250 => "+2.0",
                244 => "+3.0",
                80 => "Normal (ISO 80 equivalent)",
                100 => "High" ),

);

/******************************************************************************
* End of Global Variable:     IFD_Tag_Definitions, Casio Type 1
******************************************************************************/






/******************************************************************************
* Global Variable:      IFD_Tag_Definitions, Casio Type 2
*
* Contents:     This global variable provides definitions of the known Casio Type 2
*               Makernote tags, indexed by their tag number.
*
******************************************************************************/

$GLOBALS[ "IFD_Tag_Definitions" ]["Casio Type 2"] = array(

0x0002 => array(        'Name' => "Preview Thumbnail Dimensions",
                        'Type' => "Numeric",
                        'Units' => "(x,y pixels)" ),

0x0003 => array(        'Name' => "Preview Thumbnail Size",
                        'Type' => "Numeric",
                        'Units' => "bytes" ),

0x0004 => array(        'Name' => "Preview Thumbnail",    // thumbnail offset
                        'Type' => "Numeric" ),


0x0008 => array(        'Name' => "Quality Mode",
                        'Type' => "Lookup",
                        1 => "Fine",
                        2 => "Super Fine" ),

0x0009 => array(        'Name' => "Image Size",
                        'Type' => "Lookup",
                        20 => "2288 x 1712 pixels",
                        36 => "3008 x 2008 pixels",
                        5 => "2048 x 1536 pixels",
                        4 => "1600 x 1200 pixels",
                        21 => "2592 x 1944 pixels",
                        0 => "640 x 480 pixels",
                        22 => "2304 x 1728 pixels" ),

0x000D => array(        'Name' => "Focus Mode",
                        'Type' => "Lookup",
                        0 => "Normal",
                        1 => "Macro" ),


0x0014 => array(        'Name' => "Iso Sensitivity",
                        'Type' => "Lookup",
                        3 => "50",
                        4 => "64",
                        6 => "100",
                        9 => "200" ),


0x0019 => array(        'Name' => "White Balance",
                        'Type' => "Lookup",
                        0 => "Auto",
                        1 => "Daylight",
                        2 => "Shade",
                        3 => "Tungsten",
                        4 => "Fluorescent",
                        5 => "Manual" ),

0x001D => array(        'Name' => "Focal Length",
                        'Type' => "Special",
                        'Units' => "mm" ),

0x001F => array(        'Name' => "Saturation",
                        'Type' => "Lookup",
                        0 => "-1",
                        1 => "Normal",
                        2 => "+1", ),

0x0020 => array(        'Name' => "Contrast",
                        'Type' => "Lookup",
                        0 => "-1",
                        1 => "Normal",
                        2 => "+1", ),

0x0021 => array(        'Name' => "Sharpness",
                        'Type' => "Lookup",
                        0 => "-1",
                        1 => "Normal",
                        2 => "+1", ),


0x0e00 => array(        'Name' => "Print Image Matching Info",
                        'Type' => "PIM" ),


0x2000 => array(        'Name' => "Casio Preview Thumbnail",    // thumbnail offset
                        'Type' => "String" ),



0x2011 => array(        'Name' => "White Balance Bias",
                        'Type' => "Numeric" ),


0x2012 => array(        'Name' => "White Balance",
                        'Type' => "Lookup",
                        12 => "Flash",
                        0 =>  "Manual",
                        1 => "Auto?",
                        4 => "Flash?", ),

0x2022 => array(        'Name' => "Object Distance",
                        'Type' => "Numeric",
                        'Units' => "mm" ),


0x2034 => array(        'Name' => "Flash Distance",
                        'Type' => "Numeric",
                        'Units' => "   (0=Off)" ),

0x3000 => array(        'Name' => "Record Mode",
                        'Type' => "Lookup",
                        2 => "Normal Mode" ),

0x3001 => array(        'Name' => "Self Timer?",
                        'Type' => "Lookup",
                        1 => "Off?" ),


0x3002 => array(        'Name' => "Quality",
                        'Type' => "Lookup",
                        3 => "Fine" ),

0x3003 => array(        'Name' => "Focus Mode",
                        'Type' => "Lookup",
                        6 => "Multi-Area Auto Focus",
                        1 => "Fixation" ),


0x3006 => array(        'Name' => "Time Zone",
                        'Type' => "String" ),


0x3007 => array(        'Name' => "Bestshot Mode",
                        'Type' => "Lookup",
                        0 => "Off",
                        1 => "On?" ),


0x3014 => array(        'Name' => "CCD ISO Sensitivity",
                        'Type' => "Numeric" ),



0x3015 => array(        'Name' => "Colour Mode",
                        'Type' => "Lookup",
                        0 => "Off" ),


0x3016 => array(        'Name' => "Enhancement",
                        'Type' => "Lookup",
                        0 => "Off" ),

0x3017 => array(        'Name' => "Filter",
                        'Type' => "Lookup",
                        0 => "Off" ),


);

/******************************************************************************
* End of Global Variable:     IFD_Tag_Definitions, Casio Type 2
******************************************************************************/


















?>