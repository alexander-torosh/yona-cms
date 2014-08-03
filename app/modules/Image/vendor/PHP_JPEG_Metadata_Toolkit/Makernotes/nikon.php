<?php

/******************************************************************************
*
* Filename:     Nikon.php
*
* Description:  Nikon Makernote Parser
*               Provides functions to decode an Nikon EXIF makernote and to interpret
*               the resulting array into html.
*
*               Nikon Makernote Format:
*
*               Type 1
*
*               Field           Size            Description
*               ----------------------------------------------------------------
*               Header          8 Bytes         "Nikon\x00\x01\x00"
*               IFD Data        Variable        Standard IFD Data using Nikon Type 1 Tags
*               ----------------------------------------------------------------
*
*               Type 2
*
*               Field           Size            Description
*               ----------------------------------------------------------------
*               IFD Data        Variable        Standard IFD Data using Nikon Type 3 Tags
*               ----------------------------------------------------------------
*
*               Type 3
*
*               Field           Size            Description
*               ----------------------------------------------------------------
*               Header          10 Bytes         "Nikon\x00\x02\x10\x00\x00"
*                                               or
*                                               "Nikon\x00\x02\x00\x00\x00"
*               TIFF Data       Variable        TIFF header, with associated
*                                               Standard IFD Data using, Nikon
*                                               Type 3 Tags. Offsets are from
*                                               this second tiff header
*               ----------------------------------------------------------------
*
*               // Note: The Nikon Coolpix 775 uses the Fujifilm makernote format
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

$GLOBALS['Makernote_Function_Array']['Read_Makernote_Tag'][] = "get_Nikon_Makernote";
$GLOBALS['Makernote_Function_Array']['get_Makernote_Text_Value'][] = "get_Nikon_Text_Value";
$GLOBALS['Makernote_Function_Array']['Interpret_Makernote_to_HTML'][] = "get_Nikon_Makernote_Html";




/******************************************************************************
*
* Function:     get_Nikon_Makernote
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

function get_Nikon_Makernote( $Makernote_Tag, $EXIF_Array, $filehnd, $Make_Field )
{
        // Check if the Make Field contains the word Nikon
        if ( stristr( $Make_Field, "Nikon" ) === FALSE )
        {
                // Nikon not found in maker field - abort
                return FALSE;
        }


        // Check if the header exists at the start of the Makernote
        if ( substr( $Makernote_Tag['Data'],0 , 8 ) == "Nikon\x00\x01\x00" )
        {
                // Nikon Type 1 Makernote
                
                // Seek to the start of the IFD
                fseek($filehnd, $Makernote_Tag['Tiff Offset'] + $Makernote_Tag['Offset'] + 8 );

                // Read the IFD(s) into an array
                $Makernote_Tag['Decoded Data'] = read_Multiple_IFDs( $filehnd, $Makernote_Tag['Tiff Offset'], $Makernote_Tag['ByteAlign'], "Nikon Type 1" );

                // Save some information into the Tag element to aid interpretation
                $Makernote_Tag['Decoded'] = TRUE;
                $Makernote_Tag['Makernote Type'] = "Nikon Type 1";
                $Makernote_Tag['Makernote Tags'] = "Nikon Type 1";


                // Return the new tag
                return $Makernote_Tag;
        

        }
        else if ( ( substr( $Makernote_Tag['Data'],0 , 10 ) == "Nikon\x00\x02\x10\x00\x00" ) ||
                  ( substr( $Makernote_Tag['Data'],0 , 10 ) == "Nikon\x00\x02\x00\x00\x00" ) )
        {
                // Nikon Type 3 Makernote

                // Seek to the start of the IFD
                fseek($filehnd, $Makernote_Tag['Tiff Offset'] + $Makernote_Tag['Offset'] + 10 );

                // Read the TIFF header and IFD(s) into an array
                $Makernote_Tag['Decoded Data'] = process_TIFF_Header( $filehnd, "Nikon Type 3" );

                // Save some information into the Tag element to aid interpretation
                $Makernote_Tag['Makernote Type'] = "Nikon Type 3";
                $Makernote_Tag['Makernote Tags'] = "Nikon Type 3";
                $Makernote_Tag['Decoded'] = TRUE;
                

                // Return the new tag
                return $Makernote_Tag;
        }
        else if ( substr( $Makernote_Tag['Data'],0 , 8 ) == "FUJIFILM" )
        {
                // Fuji Makernote - used by Nikon Coolpix 775
                // Let the Fujifilm library handle it
                return False;
        }
        else
        {
                // No header - Nikon Type 2

                // Seek to the start of the IFD
                fseek($filehnd, $Makernote_Tag['Tiff Offset'] + $Makernote_Tag['Offset'] + 0 );

                // Read the IFD(s) into an array
                $Makernote_Tag['Decoded Data'] = read_Multiple_IFDs( $filehnd, $Makernote_Tag['Tiff Offset'], $Makernote_Tag['ByteAlign'], "Nikon Type 3" );

                // Save some information into the Tag element to aid interpretation
                $Makernote_Tag['Decoded'] = TRUE;
                $Makernote_Tag['Makernote Type'] = "Nikon Type 2";
                $Makernote_Tag['Makernote Tags'] = "Nikon Type 3";


                // Return the new tag
                return $Makernote_Tag;
        }


        // Shouldn't get here
        return FALSE;
}

/******************************************************************************
* End of Function:     get_Nikon_Makernote
******************************************************************************/











/******************************************************************************
*
* Function:     get_Nikon_Text_Value
*
* Description:  Provides a text value for any tag marked as special for makernotes
*               that this script can decode. Returns false if this is not a makernote
*               that can be processed with this script
*
* Parameters:   Exif_Tag - the element of an the Makernote array containing the
*                          tag in question, as returned from get_Nikon_Makernote
*               Tag_Definitions_Name - The name of the Tag Definitions group
*                                      within the global array IFD_Tag_Definitions
*
*
* Returns:      output - the text value for the tag
*               FALSE - If this script could not decode the makernote, or if
*                       an error occured in decoding
*
******************************************************************************/

function get_Nikon_Text_Value( $Exif_Tag, $Tag_Definitions_Name )
{

        // Check that this tag uses the Nikon tags, otherwise it can't be interpreted here
        // And check which variety of tags
        if ( $Tag_Definitions_Name == "Nikon Type 1" )
        {
                // No special tags for Nikon type 1 so far
                return FALSE;
        }
        else if ( $Tag_Definitions_Name == "Nikon Type 3" )
        {
                // Nikon Type 3 special tag
                
                // Process tag according to it's tag number
                
                if ( $Exif_Tag['Tag Number'] == 1)      // Nikon Makernote Version - some are binary, some are text
                {
                        return "\"" .HTML_UTF8_Escape( $Exif_Tag['Data'] ) . "\" (" . bin2hex( $Exif_Tag['Data'] ) . " hex)";
                }

                else if ( ( $Exif_Tag['Tag Number'] == 2  ) ||   // ISO Speed Used
                          ( $Exif_Tag['Tag Number'] == 19 ) )    // ISO Speed Requested
                {
                        // ISO speed settings - should be the second of two values
                        if ( count( $Exif_Tag['Data'] ) == 2 )
                        {
                                // There are two values - display the second
                                return $Exif_Tag['Data'][1] . " " . $Exif_Tag['Units'];
                        }
                        else
                        {
                                // There is not two values - display generic version of values
                                return get_IFD_value_as_text( $Exif_Tag['Data'] )  . " " . $Exif_Tag['Units'];
                        }
                }
                else if ( $Exif_Tag['Tag Number'] == 137  )     // Bracketing & Shooting Mode
                {
                        // Add shooting mode to output from first two bits
                        switch ( $Exif_Tag['Data'][0] & 0x03 )
                        {
                                case 0x00:
                                        $outputstr = "Shooting Mode: Single Frame\n";
                                        break;
                                case 0x01:
                                        $outputstr = "Shooting Mode: Continuous\n";
                                        break;
                                case 0x02:
                                        $outputstr = "Shooting Mode: Self Timer\n";
                                        break;
                                case 0x03:
                                        $outputstr = "Shooting Mode: Remote??\n";
                                        break;
                                default:
                                        $outputstr = "Shooting Mode: Unknown\n";
                                        break;
                        }

                        // Add flash bracketing to output from fifth bit
                        if ( ( $Exif_Tag['Data'][0] & 0x10 ) == 0x10 )
                        {
                                $outputstr .= "AE/Flash Bracketing On\n";
                        }
                        else
                        {
                                $outputstr .= "AE/Flash Bracketing Off\n";
                        }
                        
                        // Add white balance bracketing to output from seventh bit
                        if ( ( $Exif_Tag['Data'][0] & 0x40 ) == 0x40 )
                        {
                                $outputstr .= "White Balance Bracketing On\n";
                        }
                        else
                        {
                                $outputstr .= "White Balance Bracketing Off\n";
                        }
                        
                        // Return the output
                        return $outputstr;

                }
                else if ( $Exif_Tag['Tag Number'] == 136  )     // Auto Focus Area
                {
                        // Create a string to receive the output
                        $outputstr = "";
                        
                        // If all zeros, this could be manual focus
                        if ( $Exif_Tag['Data'] == "\x00\x00\x00\x00" )
                        {
                                $outputstr .= "Manual Focus, or\n";
                        }

                        // Add AF mode according to the first byte
                        switch ( ord($Exif_Tag['Data']{0}) )
                        {
                                case 0x00:
                                        $outputstr .= "Auto Focus Mode: Single Area\n";
                                        break;
                                case 0x01:
                                        $outputstr .= "Auto Focus Mode: Dynamic Area\n";
                                        break;
                                case 0x02:
                                        $outputstr .= "Auto Focus Mode: Closest Subject\n";
                                        break;
                                default:
                                        $outputstr .= "Auto Focus Mode: Unknown AF Mode\n";
                                        break;
                        }

                        // Add AF area according to second byte
                        switch ( ord($Exif_Tag['Data']{1}) )
                        {
                                case 0x00:
                                        $outputstr .= "Auto Focus Area Selected: Centre\n";
                                        break;
                                case 0x01:
                                        $outputstr .= "Auto Focus Area Selected: Top\n";
                                        break;
                                case 0x02:
                                        $outputstr .= "Auto Focus Area Selected: Bottom\n";
                                        break;
                                case 0x03:
                                        $outputstr .= "Auto Focus Area Selected: Left\n";
                                        break;
                                case 0x04:
                                        $outputstr .= "Auto Focus Area Selected: Right\n";
                                        break;
                        }
                        
                        // Add properly focused areas to output according to byte 3 bits
                        
                        $outputstr .= "Properly Focused Area(s): ";
                        if ( ord($Exif_Tag['Data']{3}) == 0x00 )
                        {
                                $outputstr .= "None";
                        }
                        if ( ( ord($Exif_Tag['Data']{3}) & 0x01 ) == 0x01 )
                        {
                                $outputstr .= "Centre ";
                        }
                        if ( ( ord($Exif_Tag['Data']{3}) & 0x02 ) == 0x02 )
                        {
                                $outputstr .= "Top ";
                        }
                        if ( ( ord($Exif_Tag['Data']{3}) & 0x04 ) == 0x04 )
                        {
                                $outputstr .= "Bottom ";
                        }
                        if ( ( ord($Exif_Tag['Data']{3}) & 0x08 ) == 0x08 )
                        {
                                $outputstr .= "Left ";
                        }
                        if ( ( ord($Exif_Tag['Data']{3}) & 0x10 ) == 0x10 )
                        {
                                $outputstr .= "Right ";
                        }
                        $outputstr .= "\n";

                        // return the string
                        return $outputstr;
                }
                else
                {
                        // Unknown special tag
                        return FALSE;
                }
        }

        
        return FALSE;
}

/******************************************************************************
* End of Function:     get_Nikon_Text_Value
******************************************************************************/




/******************************************************************************
*
* Function:     get_Nikon_Makernote_Html
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

function get_Nikon_Makernote_Html( $Makernote_tag, $filename )
{

        // Check that this is a Nikon Makernote, otherwise it can't be interpreted here
        if ( ( $Makernote_tag['Makernote Type'] != "Nikon Type 1" ) &&
             ( $Makernote_tag['Makernote Type'] != "Nikon Type 2" ) &&
             ( $Makernote_tag['Makernote Type'] != "Nikon Type 3" ) )
        {
                // Not a Nikon Makernote - cannot interpret it - abort
                return FALSE;;
        }

        // Interpret the IFD and return the HTML
        return interpret_IFD( $Makernote_tag['Decoded Data'][0], $filename );
}

/******************************************************************************
* End of Function:     get_Nikon_Makernote_Html
******************************************************************************/














/******************************************************************************
* Global Variable:      IFD_Tag_Definitions, Nikon Type 1
*
* Contents:     This global variable provides definitions of the known Nikon Type 1
*               Makernote tags, indexed by their tag number.
*
******************************************************************************/

$GLOBALS[ "IFD_Tag_Definitions" ]["Nikon Type 1"] = array(


3 => array(     'Name' => "Quality",
                'Description' => "1: VGA Basic, 2: VGA Normal, 3: VGA Fine, 4: SXGA Basic, 5: SXGA Normal, 6: SXGA Fine",
                'Type' => "Lookup",
                1 => "VGA (640x480) Basic",
                2 => "VGA (640x480) Normal",
                3 => "VGA (640x480) Fine",
                4 => "SXGA (1280x960) Basic",
                5 => "SXGA (1280x960) Normal",
                6 => "SXGA (1280x960) Fine",
                7 => "Unknown, Possibly XGA (1024x768) Basic",
                8 => "Unknown, Possibly XGA (1024x768) Basic",
                9 => "Unknown, Possibly XGA (1024x768) Basic",
                10 => "UXGA (1600x1200) Basic",
                11 => "UXGA (1600x1200) Normal",
                12 => "UXGA (1600x1200) Fine" ),

4 => array(     'Name' => "Colour Mode",
                'Description' => "1: Colour, 2: Monochrome.",
                'Type' => "Lookup",
                1 => "Colour",
                2 => "Monochrome" ),

5 => array(     'Name' => "Image Adjustment",
                'Description' => "0: Normal, 1: Bright+, 2: Bright-, 3: Contrast+, 4: Contrast-.",
                'Type' => "Lookup",
                0 => "Normal",
                1 => "Bright+",
                2 => "Bright-",
                3 => "Contrast+",
                4 => "Contrast-" ),

6 => array(     'Name' => "CCD Sensitivity",
                'Description' => "0: ISO80, 2: ISO160, 4: ISO320, 5: ISO100",
                'Type' => "Lookup",
                0 => "ISO 80",
                2 => "ISO 160",
                4 => "ISO 320",
                5 => "ISO 100" ),

7 => array(     'Name' => "White Balance",
                'Description' => "0: Auto, 1: Preset, 2: Daylight, 3: Incandescense, 4: Fluorescence, 5: Cloudy, 6: SpeedLight",
                'Type' => "Lookup",
                0 => "Auto",
                1 => "Preset",
                2 => "Daylight",
                3 => "Incandescense",
                4 => "Flourescence",
                5 => "Cloudy",
                6 => "Speedlight" ),

8 => array(     'Name' => "Focus",
                'Description' => "If infinite focus, value is '1/0'.",
                'Type' => "Numeric" ),

10 => array(    'Name' => "Digital Zoom",
                'Description' => "'160/100' means 1.6x digital zoom, '0/100' means no digital zoom (optical zoom only).",
                'Type' => "Numeric" ),

11 => array(    'Name' => "Converter",
                'Description' => "If Fisheye Converter is used, value is 1",
                'Type' => "Lookup",
                0 => "No Converter Used",
                1 => "Fish-eye Converter Used" )

);

/******************************************************************************
* End of Global Variable:     IFD_Tag_Definitions, Nikon Type 1
******************************************************************************/





/******************************************************************************
* Global Variable:      IFD_Tag_Definitions, Nikon Type 3
*
* Contents:     This global variable provides definitions of the known Nikon Type 3
*               Makernote tags, indexed by their tag number.
*
******************************************************************************/

$GLOBALS[ "IFD_Tag_Definitions" ]["Nikon Type 3"] = array(


1 => array(     'Name' => "Nikon Makernote Version",
                'Type' => "Special" ),

2 => array(     'Name' => "ISO Speed Used",
                'Type' => "Special" ),

3 => array(     'Name' => "Colour Mode",
                'Type' => "String" ),

4 => array(     'Name' => "Quality",
                'Type' => "String" ),

5 => array(     'Name' => "White Balance",
                'Type' => "String" ),

6 => array(     'Name' => "Sharpening",
                'Type' => "String" ),

7 => array(     'Name' => "Focus Mode",
                'Type' => "String" ),

8 => array(     'Name' => "Flash Setting",
                'Type' => "String" ),

9 => array(     'Name' => "Auto Flash Mode",
                'Type' => "String"  ),

11 => array(    'Name' => "White Balance Bias Value",
                'Type' => "Numeric",
                'Units' => "(Units Approx: 100 Mired per increment)" ),

12 => array(    'Name' => "White Balance Red, Blue Coefficients?",
                'Type' => "Numeric"  ),

15 => array(    'Name' => "ISO Selection?",
                'Type' => "String" ),


18 => array(    'Name' => "Flash Compensation",
                'Type' => "Lookup",
                0x06 => "+1.0 EV",
                0x04 => "+0.7 EV",
                0x03 => "+0.5 EV",
                0x02 => "+0.3 EV",
                0x00 => "0.0 EV",
                0xfe => "-0.3 EV",
                0xfd => "-0.5 EV",
                0xfc => "-0.7 EV",
                0xfa => "-1.0 EV",
                0xf8 => "-1.3 EV",
                0xf7 => "-1.5 EV",
                0xf6 => "-1.7 EV",
                0xf4 => "-2.0 EV",
                0xf2 => "-2.3 EV",
                0xf1 => "-2.5 EV",
                0xf0 => "-2.7 EV",
                0xee => "-3.0 EV" ),

19 => array(    'Name' => "ISO Speed Requested",
                'Type' => "Special",
                'Units' => "(May be different to Speed Used when Auto ISO is on)" ),


22 => array(    'Name' => "Photo corner coordinates",
                'Type' => "Numeric",
                'Units' => "Pixels"  ),

24 => array(    'Name' => "Flash Bracket Compensation Applied",
                'Type' => "Lookup",
                0x06 => "+1.0 EV",
                0x04 => "+0.7 EV",
                0x03 => "+0.5 EV",
                0x02 => "+0.3 EV",
                0x00 => "0.0 EV",
                0xfe => "-0.3 EV",
                0xfd => "-0.5 EV",
                0xfc => "-0.7 EV",
                0xfa => "-1.0 EV",
                0xf8 => "-1.3 EV",
                0xf7 => "-1.5 EV",
                0xf6 => "-1.7 EV",
                0xf4 => "-2.0 EV",
                0xf2 => "-2.3 EV",
                0xf1 => "-2.5 EV",
                0xf0 => "-2.7 EV",
                0xee => "-3.0 EV" ),

25 => array(    'Name' => "AE Bracket Compensation Applied",
                'Type' => "Numeric",
                'Units' => "EV" ),

128 => array(   'Name' => "Image Adjustment?",
                'Type' => "String" ),

129 => array(   'Name' => "Tone Compensation (Contrast)",
                'Type' => "String" ),

130 => array(   'Name' => "Auxiliary Lens (Adapter)",
                'Type' => "String" ),

131 => array(   'Name' => "Lens Type?",
                'Type' => "Lookup",
                6 => "Nikon D series Lens",
                14 => "Nikon G series Lens" ),

132 => array(   'Name' => "Lens Min/Max Focal Length, Min/Max Aperture",
                'Type' => "Numeric",
                'Units' => " mm, mm, F#, F#" ),

133 => array(   'Name' => "Manual Focus Distance?",
                'Type' => "Numeric"),

134 => array(   'Name' => "Digital Zoom Factor?",
                'Type' => "Numeric" ),

135 => array(   'Name' => "Flash Used",
                'Type' => "Lookup",
                0 => "Flash Not Used",
                9 => "Flash Fired" ),

136 => array(   'Name' => "Auto Focus Area",
                'Description' => "byte 1 : AF Mode: 00 = single area, 01 = Dynamic Area, 02 = Closest Subject\n
                                  byte 2 : AF Area Selected : 00 = Centre, 01 = Top, 02 = Bottom, 03 = Left, 04 = Right\n
                                  byte 3 : Unknown, always zero\n
                                  byte 4 : Properly focused Area(s) : bit 0 = Centre, bit 1 = Top, bit 2 = Bottom, bit 3 = Left, bit 4 = Right",
                'Type' => "Special" ),

137 => array(   'Name' => "Bracketing & Shooting Mode",
                'Description' => "bit 0&1 (0 = single frame, 1 = continuous,2=timer, 3=remote timer? 4 = remote?\n
                                  bit 4, Bracketing on or off\n
                                  bit 6, white Balance Bracketing on",
                'Type' => "Special" ),

141 => array(   'Name' => "Colour Mode",
                'Description' =>"1a = Portrait sRGB, 2 = Adobe RGB, 3a = Landscape sRGB",
                'Type' => "String" ),

143 => array(   'Name' => "Scene Mode?",
                'Type' => "Numeric" ),

144 => array(   'Name' => "Lighting Type",
                'Type' => "String" ),

146 => array(   'Name' => "Hue Adjustment",
                'Type' => "Numeric",
                'Units' => "Degrees" ),

148 => array(   'Name' => "Saturation?",
                'Type' => "Lookup",
                -3 => "Black and White",
                -2 => "-2",
                -1 => "-1",
                0 =>  "Normal",
                1 =>  "+1",
                2 =>  "+2" ),

149 => array(   'Name' => "Noise Reduction",
                'Type' => "String" ),

167 => array(   'Name' => "Total Number of Shutter Releases for Camera",
                'Type' => "Numeric",
                'Units' => "Shutter Releases" ),

169 => array(   'Name' => "Image optimisation",
                'Type' => "String" ),

170 => array(   'Name' => "Saturation",
                'Type' => "String" ),

171 => array(   'Name' => "Digital Vari-Program",
                'Type' => "String" )


// Tags that exist but are unknown: 10, 13, 14, 16, 17, 23, 24, 138, 139, 145,
// 151, 152, 160, 162 163, 165, 166, 168


);

/******************************************************************************
* End of Global Variable:     IFD_Tag_Definitions, Nikon Type 3
******************************************************************************/



?>
